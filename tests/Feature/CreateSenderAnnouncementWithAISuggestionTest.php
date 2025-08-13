<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CreateSenderAnnouncementWithAISuggestionTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_flow_form_prefills_with_ai_and_submits()
    {
        // Ensure tests use array sessions and skip creating sessions table
        config()->set('session.driver', 'array');

        // Fake Gemini to return a realistic JSON value
        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [[
                    'content' => [
                        'parts' => [[ 'text' => json_encode(['declared_value_mad' => 8200]) ]]
                    ]
                ]]
            ], 200),
        ]);

        // Create schema for minimal tables needed (users) without sessions
        $this->artisan('migrate', ['--path' => 'database/migrations/0001_01_01_000000_create_users_table.php'])->run();

        // Create and login a user
        $user = User::factory()->create(['status' => 'active']);
        $this->actingAs($user);

        // Call the proxy like the front-end would
        $payload = [
            'pickup_city' => 'Fès',
            'delivery_city' => 'Tanger',
            'preferred_date' => now()->addDays(3)->toDateString(),
            'package_weight' => 0.2,
            'item_type' => 'Documents',
            'dimensions' => '20x20',
            'description' => 'un colis contient un iPhone 12 Pro',
            'additional_info' => '',
        ];
        $ai = $this->postJson(route('ai.suggest-declared-value'), $payload)
            ->assertOk()
            ->json();

        // Ensure we got the model value
        $this->assertEquals(8200, $ai['declared_value_mad']);

        // Submit the sender announcement form with the suggested value
        $submit = $this->post(route('dashboard.store-sender-announcement'), [
            'pickup_city' => 'Fès',
            'delivery_city' => 'Tanger',
            'preferred_date' => now()->addDays(3)->toDateString(),
            'package_weight' => 0.2,
            'description' => 'un colis contient un iPhone 12 Pro',
            'item_type' => 'Documents',
            'dimensions' => '20x20',
            'declared_value' => $ai['declared_value_mad'],
            'additional_info' => null,
        ]);

        $submit->assertRedirect(route('dashboard.index'));
    }
}


