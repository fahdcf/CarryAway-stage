<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AISuggestionTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function it_returns_declared_value_from_gemini_proxy()
    {
        $this->withoutMiddleware(\Illuminate\Auth\Middleware\Authenticate::class);

        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [[
                    'content' => [
                        'parts' => [[ 'text' => json_encode(['declared_value_mad' => 350]) ]]
                    ]
                ]]
            ], 200)
        ]);

        $payload = [
            'pickup_city' => 'Fès',
            'delivery_city' => 'Tanger',
            'preferred_date' => now()->addDays(2)->toDateString(),
            'package_weight' => 0.5,
            'item_type' => 'Documents',
            'dimensions' => '30x30',
            'description' => 'papier de convention de stage',
            'additional_info' => '',
        ];

        $res = $this->postJson(route('ai.suggest-declared-value'), $payload);

        $res->assertOk()
            ->assertJsonStructure(['declared_value_mad'])
            ->assertJson(['declared_value_mad' => 350]);
    }

    /** @test */
    public function it_returns_422_when_no_numeric_value_is_found()
    {
        $this->withoutMiddleware(\Illuminate\Auth\Middleware\Authenticate::class);

        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [[
                    'content' => [
                        'parts' => [[ 'text' => 'no number here' ]]
                    ]
                ]]
            ], 200)
        ]);

        $res = $this->postJson(route('ai.suggest-declared-value'), []);

        $res->assertStatus(422)
            ->assertJson(['error' => 'no_value']);
    }
}


