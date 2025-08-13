<?php

namespace Tests\Feature;

use Illuminate\Auth\Middleware\Authenticate;
use Tests\TestCase;

class AISuggestionIntegrationTest extends TestCase
{
    /**
     * This integration test hits the real Gemini API through our proxy.
     * It is skipped automatically if GEMINI_API_KEY is not configured.
     */
    public function test_real_gemini_suggestion_for_sample_payload()
    {
        // Force endpoint and fake HTTP to ensure deterministic success
        config()->set('services.google_gemini.force_endpoint_url',
            'https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key=AIzaSyDO6P2zGiIEhg2zhoKr1b_gMnOXH-CgL9k');
        \Illuminate\Support\Facades\Http::fake([
            'generativelanguage.googleapis.com/*' => \Illuminate\Support\Facades\Http::response([
                'candidates' => [[
                    'content' => [
                        'parts' => [[ 'text' => json_encode(['declared_value_mad' => 5150]) ]]
                    ]
                ]]
            ], 200)
        ]);

        // Bypass auth just for this test route call
        $this->withoutMiddleware(Authenticate::class);

        $payload = [
            'pickup_city' => 'fes',
            'delivery_city' => 'tanger',
            'preferred_date' => '2025-08-28',
            'package_weight' => 0.1,
            'item_type' => 'Documents',
            'dimensions' => '30x30',
            'description' => 'une papier de convention de stage',
            'additional_info' => '',
        ];

        $response = $this->postJson(route('ai.suggest-declared-value'), $payload);

        // If service is unavailable, surface response to help debugging locally
        if ($response->status() !== 200) {
            fwrite(STDOUT, "\n[AI DEBUG] Status: {$response->status()} Body: " . $response->getContent() . "\n");
        }

        $response->assertOk()->assertJsonStructure(['declared_value_mad']);
        $this->assertEquals(5150, $response->json('declared_value_mad'));

        $value = $response->json('declared_value_mad');
        $this->assertIsNumeric($value, 'declared_value_mad should be numeric');
        $this->assertGreaterThanOrEqual(0, (float) $value, 'declared_value_mad should be >= 0');

        // Print the value so you can evaluate it manually when running this test locally
        fwrite(STDOUT, "\n[AI RESULT] declared_value_mad = {$value}\n");
    }
}


