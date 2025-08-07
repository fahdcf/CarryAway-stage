<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserDetail;
use App\Models\TravelerAnnouncement;
use App\Models\SenderAnnouncement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test la création d'un utilisateur avec UUID.
     */
    public function test_user_creation_with_uuid(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '0612345678',
            'password' => bcrypt('password'),
        ]);

        $this->assertNotNull($user->id);
        $this->assertIsString($user->id);
        $this->assertEquals(36, strlen($user->id)); // UUID length
        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@example.com', $user->email);
    }

    /**
     * Test la relation entre User et UserDetail.
     */
    public function test_user_detail_relationship(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '0612345678',
            'password' => bcrypt('password'),
        ]);

        $userDetail = UserDetail::create([
            'user_id' => $user->id,
            'birth_date' => '1990-01-01',
            'gender' => 'male',
            'address' => '123 Test Street',
            'city' => 'Casablanca',
            'cin' => 'AB123456',
            'verified' => false,
        ]);

        $this->assertNotNull($userDetail->id);
        $this->assertEquals($user->id, $userDetail->user_id);
        $this->assertInstanceOf(User::class, $userDetail->user);
        $this->assertInstanceOf(UserDetail::class, $user->userDetail);
    }

    /**
     * Test la création d'une annonce de voyageur.
     */
    public function test_traveler_announcement_creation(): void
    {
        $user = User::create([
            'name' => 'Traveler User',
            'email' => 'traveler@example.com',
            'phone' => '0612345678',
            'password' => bcrypt('password'),
        ]);

        $announcement = TravelerAnnouncement::create([
            'user_id' => $user->id,
            'departure_city' => 'Casablanca',
            'arrival_city' => 'Rabat',
            'departure_date' => '2025-08-15',
            'available_kg' => 10.5,
            'description' => 'Voyage Casablanca-Rabat',
            'status' => 'active',
            'is_active' => true,
        ]);

        $this->assertNotNull($announcement->id);
        $this->assertEquals($user->id, $announcement->user_id);
        $this->assertEquals('Casablanca', $announcement->departure_city);
        $this->assertEquals('Rabat', $announcement->arrival_city);
        $this->assertEquals(10.5, $announcement->available_kg);
        $this->assertTrue($announcement->is_active);
        $this->assertInstanceOf(User::class, $announcement->user);
    }

    /**
     * Test la création d'une annonce d'expéditeur.
     */
    public function test_sender_announcement_creation(): void
    {
        $user = User::create([
            'name' => 'Sender User',
            'email' => 'sender@example.com',
            'phone' => '0612345678',
            'password' => bcrypt('password'),
        ]);

        $announcement = SenderAnnouncement::create([
            'user_id' => $user->id,
            'pickup_city' => 'Marrakech',
            'delivery_city' => 'Agadir',
            'preferred_date' => '2025-08-20',
            'package_weight' => 2.5,
            'description' => 'Colis fragile',
            'status' => 'pending',
            'is_active' => true,
        ]);

        $this->assertNotNull($announcement->id);
        $this->assertEquals($user->id, $announcement->user_id);
        $this->assertEquals('Marrakech', $announcement->pickup_city);
        $this->assertEquals('Agadir', $announcement->delivery_city);
        $this->assertEquals(2.5, $announcement->package_weight);
        $this->assertEquals('Colis fragile', $announcement->description);
        $this->assertTrue($announcement->is_active);
        $this->assertInstanceOf(User::class, $announcement->user);
    }
}
