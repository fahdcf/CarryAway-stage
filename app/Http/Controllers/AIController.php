<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIController extends Controller
{
    /**
     * Suggest a declared value (MAD) using Gemini based on provided shipment details.
     */
    public function suggestDeclaredValue(Request $request)
    {
        $validated = $request->validate([
            'pickup_city' => 'nullable|string|max:100',
            'delivery_city' => 'nullable|string|max:100',
            'preferred_date' => 'nullable|date',
            'package_weight' => 'nullable|numeric|min:0',
            'item_type' => 'nullable|string|max:255',
            'dimensions' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'additional_info' => 'nullable|string|max:1000',
            'no_fallback' => 'sometimes|boolean',
        ]);

        $noFallback = $request->boolean('no_fallback');

        $apiKey = config('services.google_gemini.key')
            ?: env('GEMINI_API_KEY')
            ?: '';

        if (empty($apiKey)) {
            return response()->json([
                'error' => 'API key missing',
            ], 500);
        }

        $forced = config('services.google_gemini.force_endpoint_url');
        if ($forced) {
            $endpoint = $forced;
        } else {
            $model = config('services.google_gemini.model', 'gemini-1.5-flash');
            $endpoint = 'https://generativelanguage.googleapis.com/v1/models/' . $model . ':generateContent?key=' . urlencode($apiKey);
        }

        $prompt = $this->buildPrompt($validated);

        try {
            $debugInfo = [];
            $body = [
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [
                            [
                                'text' => $prompt
                            ]
                        ]
                    ]
                ]
            ];

            // Check if SSL verification should be disabled (for local development)
            $disableSslVerify = config('services.google_gemini.disable_ssl_verify', false);
            
            if ($disableSslVerify) {
                $debugInfo['ssl_disabled'] = 'SSL verification disabled via config';
                $response = Http::timeout(12)
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->withoutVerifying() // Disable SSL verification for local dev
                    ->post($endpoint, $body);
            } else {
                // Try with SSL verification first
                $response = Http::timeout(12)
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->post($endpoint, $body);

                // If SSL fails, try without verification (for local development)
                if (!$response->successful() && str_contains($response->body(), 'cURL error 77')) {
                    $debugInfo['ssl_fallback'] = 'SSL verification failed, trying without verification';
                    $response = Http::timeout(12)
                        ->withHeaders(['Content-Type' => 'application/json'])
                        ->withoutVerifying() // Disable SSL verification for local dev
                        ->post($endpoint, $body);
                }
            }

            if (!$response->ok()) {
                $debugInfo['attempt1_status'] = $response->status();
                $debugInfo['attempt1_body'] = mb_substr($response->body(), 0, 1000);
                Log::warning('Gemini API error', ['status' => $response->status()]);
                if ($noFallback) {
                    return response()->json(array_filter([
                        'error' => 'http_error',
                        'source' => 'none',
                        'endpoint' => config('app.debug') ? $this->redactEndpoint($endpoint) : null,
                        'debug' => $debugInfo,
                    ]), 502);
                }
            }

            $json = $response->json();
            $text = data_get($json, 'candidates.0.content.parts.0.text', '')
                ?: data_get($json, 'candidates.0.content.parts.0.inline_data.data', '')
                ?: '';
            
            // Check if we got an empty response
            if (empty($text)) {
                $debugInfo['attempt1_empty'] = 'Gemini returned empty response';
                $debugInfo['attempt1_raw'] = mb_substr(json_encode($json), 0, 1000);
            }

            $value = $this->extractDeclaredValue($text);

            // Retry once with a stricter minimal prompt if no numeric value
            if ($value === null) {
                $debugInfo['attempt1_text'] = mb_substr((string) $text, 0, 1000);
                $retryPrompt = 'You must respond with ONLY this JSON: {"declared_value_mad": <number>} where <number> is a realistic Moroccan MAD value for the item. No other text or formatting.';
                $retryBody = [
                    'contents' => [
                        [
                            'role' => 'user',
                            'parts' => [
                                [
                                    'text' => $retryPrompt . "\n\nContext (JSON): " . json_encode($validated, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                                ]
                            ]
                        ]
                    ]
                ];
                $retryRes = Http::timeout(8)
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->when($disableSslVerify, fn($http) => $http->withoutVerifying())
                    ->post($endpoint, $retryBody);
                if ($retryRes->ok()) {
                    $retryJson = $retryRes->json();
                    $retryText = data_get($retryJson, 'candidates.0.content.parts.0.text', '')
                        ?: '';
                    
                    if (empty($retryText)) {
                        $debugInfo['attempt2_empty'] = 'Retry returned empty response';
                        $debugInfo['attempt2_raw'] = mb_substr(json_encode($retryJson), 0, 1000);
                    }
                    
                    $value = $this->extractDeclaredValue($retryText);
                    $debugInfo['attempt2_status'] = $retryRes->status();
                    $debugInfo['attempt2_text'] = mb_substr((string) $retryText, 0, 1000);
                } else {
                    $debugInfo['attempt2_status'] = $retryRes->status();
                    $debugInfo['attempt2_body'] = mb_substr($retryRes->body(), 0, 1000);
                }
            }

            if ($value === null) {
                // Try stripping code fences then parse again
                if (is_string($text) && preg_match('/^```/', $text)) {
                    $text = preg_replace('/^```[a-zA-Z]*\n?|```$/m', '', $text);
                    $value = $this->extractDeclaredValue($text);
                }
            }

            if ($value === null) {
                if ($noFallback) {
                    return response()->json(array_filter([
                        'error' => 'no_value',
                        'source' => 'none',
                        'endpoint' => config('app.debug') ? $this->redactEndpoint($endpoint) : null,
                        'debug' => $debugInfo,
                    ]), 422);
                }
                // Compute a conservative fallback to avoid blocking UX
                $fallback = $this->computeFallback($validated);
                return response()->json(array_filter([
                    'declared_value_mad' => $fallback,
                    'source' => 'fallback',
                    'endpoint' => config('app.debug') ? $this->redactEndpoint($endpoint) : null,
                ]));
            }

            return response()->json(array_filter([
                'declared_value_mad' => $value,
                'source' => 'gemini',
                'endpoint' => config('app.debug') ? $this->redactEndpoint($endpoint) : null,
            ]));

        } catch (\Throwable $e) {
            Log::error('Gemini call failed', ['exception' => $e->getMessage()]);
            if ($noFallback) {
                return response()->json(array_filter([
                    'error' => 'exception',
                    'source' => 'none',
                    'endpoint' => config('app.debug') ? $this->redactEndpoint(($forced ?: $endpoint)) : null,
                    'debug' => ['exception' => $e->getMessage()],
                ]), 500);
            }
            // Do not block UX: return a conservative fallback
            $fallback = $this->computeFallback($validated);
            return response()->json(array_filter([
                'declared_value_mad' => $fallback,
                'source' => 'fallback_error',
                'endpoint' => config('app.debug') ? $this->redactEndpoint(($forced ?: $endpoint)) : null,
            ]));
        }
    }

    /**
     * Simple debug page to test AI suggestion with form-like inputs.
     */
    public function debugSender()
    {
        return view('debug.ai-sender');
    }

    private function buildPrompt(array $data): string
    {
        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return <<<PROMPT
You are a pricing expert for shipping insurance in Morocco. Estimate a realistic declared value in Moroccan Dirhams (MAD).

Shipment details: {$json}

Respond with ONLY this JSON format: {"declared_value_mad": <number>}

Where <number> is a realistic Moroccan market value based on:
- Item description (prioritize specific products like iPhone, laptop, etc.)
- Item type, weight, and dimensions
- Route (pickup_city to delivery_city)
- Moroccan market context (local prices, not international)

Example: {"declared_value_mad": 5000}

Rules: Output ONLY the JSON object. No text, explanations, or formatting.
PROMPT;
    }

    private function extractDeclaredValue(?string $text): ?float
    {
        if (!is_string($text) || $text === '') {
            return null;
        }

        // Try JSON first
        $parsed = json_decode($text, true);
        if (is_array($parsed) && array_key_exists('declared_value_mad', $parsed)) {
            $val = $parsed['declared_value_mad'];
            if (is_numeric($val)) {
                return (float)$val;
            }
        }

        // Fallback: first number found
        if (preg_match('/\d+(?:[\.,]\d+)?/', $text, $m)) {
            return (float) str_replace(',', '.', $m[0]);
        }

        return null;
    }

    /**
     * Conservative heuristic if Gemini returns no numeric value.
     */
    private function computeFallback(array $data): float
    {
        $item = strtolower((string)($data['item_type'] ?? ''));
        $desc = strtolower((string)($data['description'] ?? ''));
        $weight = (float)($data['package_weight'] ?? 0);

        // Detect electronics from description even if item_type says Documents
        $isSmartphone = (bool) preg_match('/\b(iphone|smartphone|t[eé]l[eé]phone|samsung|xiaomi|huawei|pixel|one\s*plus)\b/i', $desc);
        $isLaptop = (bool) preg_match('/\b(laptop|ordinateur|macbook|notebook|pc)\b/i', $desc);
        $isConsole = (bool) preg_match('/\b(ps5|play\s*station|xbox|nintendo)\b/i', $desc);
        $isTablet = (bool) preg_match('/\b(ipad|tablette|tablet)\b/i', $desc);

        // Specific model heuristics
        $isIphone12Pro = (bool) preg_match('/iphone\s*12\s*pro\b/i', $desc);

        // Baselines (MAD) — conservative typical values for Morocco (Maroc)
        $base = 300.0; // default catch-all
        if ($isIphone12Pro) {
            $base = 8000.0; // conservative mid for used iPhone 12 Pro
        } elseif ($isSmartphone) {
            $base = 3500.0; // generic smartphone baseline
        } elseif ($isLaptop) {
            $base = 5000.0; // generic laptop baseline
        } elseif ($isConsole) {
            $base = 3500.0; // generic console baseline
        } elseif ($isTablet) {
            $base = 2500.0; // generic tablet baseline
        } else {
            // Fall back to item_type map if description didn’t signal electronics
            $baseMap = [
                'documents' => 100.0,
                'vêtements' => 250.0,
                'vetements' => 250.0,
                'électronique' => 2000.0,
                'electronique' => 2000.0,
                'médicaments' => 300.0,
                'medicaments' => 300.0,
                'nourriture' => 200.0,
            ];
            foreach ($baseMap as $key => $val) {
                if ($item === $key) { $base = $val; break; }
            }
        }

        // Condition adjustments based on Moroccan market phrasing
        $isNew = (bool) preg_match('/\b(neuf|new|scell[eé]|emball[eé])\b/i', $desc);
        $isUsed = (bool) preg_match('/\b(occasion|used|utilis[eé])\b/i', $desc);
        if ($isNew) {
            $base *= 1.25; // +25% for new/boxed
        } elseif ($isUsed) {
            $base *= 0.85; // -15% for used/occasion
        }

        // Weight adjustment — light touch for high-value electronics
        $weightAdjPerKg = 100.0;
        if ($isSmartphone || $isLaptop || $isConsole || $isTablet || $isIphone12Pro) {
            $weightAdjPerKg = 50.0;
        } elseif ($item === 'documents') {
            $weightAdjPerKg = 200.0;
        }

        $value = $base + max(0.0, $weight) * $weightAdjPerKg;

        // Soft bounds for Morocco
        $value = max(20.0, min($value, 100000.0));

        return round($value, 2);
    }

    private function redactEndpoint(string $endpoint): string
    {
        return preg_replace('/(key=)[^&]+/i', '$1REDACTED', $endpoint) ?? $endpoint;
    }
}


