<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test que la page d'accueil se charge correctement.
     */
    public function test_home_page_loads_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('home');
    }

    /**
     * Test que la page d'accueil contient les éléments essentiels.
     */
    public function test_home_page_contains_essential_content(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('CarryWay');
        $response->assertSee('Envoyez ou transportez des colis facilement entre particuliers au Maroc');
        $response->assertSee('Comment ça marche');
        $response->assertSee('Pourquoi choisir CarryWay');
    }

    /**
     * Test que les boutons d'action sont présents.
     */
    public function test_home_page_contains_action_buttons(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Publier une annonce de livraison');
        $response->assertSee('Proposer un trajet');
    }
}
