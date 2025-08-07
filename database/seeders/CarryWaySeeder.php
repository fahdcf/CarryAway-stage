<?php

namespace Database\Seeders;

use App\Models\SenderAnnouncement;
use App\Models\SenderAnnouncementDetail;
use App\Models\TravelerAnnouncement;
use App\Models\TravelerAnnouncementDetail;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarryWaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some test users
        $user1 = User::create([
            'name' => 'Ahmed Benali',
            'email' => 'ahmed@example.com',
            'phone' => '0612345678',
            'password' => bcrypt('password'),
            'role' => 'user',
            'status' => 'active',
            'approved_at' => now(),
        ]);

        $user2 = User::create([
            'name' => 'Fatima Zahra',
            'email' => 'fatima@example.com',
            'phone' => '0623456789',
            'password' => bcrypt('password'),
            'role' => 'user',
            'status' => 'active',
            'approved_at' => now(),
        ]);

        $user3 = User::create([
            'name' => 'Mohammed Alami',
            'email' => 'mohammed@example.com',
            'phone' => '0634567890',
            'password' => bcrypt('password'),
            'role' => 'user',
            'status' => 'active',
            'approved_at' => now(),
        ]);

        // Create traveler announcements
        $traveler1 = TravelerAnnouncement::create([
            'user_id' => $user1->id,
            'departure_city' => 'Casablanca',
            'arrival_city' => 'Marrakech',
            'departure_date' => now()->addDays(3),
            'available_kg' => 10.5,
            'description' => 'Voyage en voiture, départ tôt le matin. Espace disponible pour colis.',
            'status' => 'active',
            'is_active' => true,
        ]);

        TravelerAnnouncementDetail::create([
            'announcement_id' => $traveler1->id,
            'transport_mode' => 'Voiture',
            'estimated_arrival_date' => now()->addDays(3),
            'price_per_kg' => 15.00,
            'extra_notes' => 'Départ à 7h du matin, retour le soir même.',
        ]);

        $traveler2 = TravelerAnnouncement::create([
            'user_id' => $user2->id,
            'departure_city' => 'Rabat',
            'arrival_city' => 'Fès',
            'departure_date' => now()->addDays(5),
            'available_kg' => 8.0,
            'description' => 'Voyage en train, confortable et sécurisé.',
            'status' => 'active',
            'is_active' => true,
        ]);

        TravelerAnnouncementDetail::create([
            'announcement_id' => $traveler2->id,
            'transport_mode' => 'Train',
            'estimated_arrival_date' => now()->addDays(5),
            'price_per_kg' => 12.50,
            'extra_notes' => 'Train climatisé, départ à 9h.',
        ]);

        // Create sender announcements
        $sender1 = SenderAnnouncement::create([
            'user_id' => $user3->id,
            'pickup_city' => 'Casablanca',
            'delivery_city' => 'Marrakech',
            'preferred_date' => now()->addDays(3),
            'package_weight' => 3.5,
            'description' => 'Colis de documents importants, besoin de transport sécurisé.',
            'status' => 'active',
            'is_active' => true,
        ]);

        SenderAnnouncementDetail::create([
            'announcement_id' => $sender1->id,
            'item_type' => 'Documents',
            'dimensions' => '30x20x5 cm',
            'declared_value' => 500.00,
            'additional_info' => 'Documents sensibles, manipulation délicate requise.',
        ]);

        $sender2 = SenderAnnouncement::create([
            'user_id' => $user1->id,
            'pickup_city' => 'Rabat',
            'delivery_city' => 'Fès',
            'preferred_date' => now()->addDays(5),
            'package_weight' => 2.0,
            'description' => 'Petit colis de vêtements, pas urgent.',
            'status' => 'active',
            'is_active' => true,
        ]);

        SenderAnnouncementDetail::create([
            'announcement_id' => $sender2->id,
            'item_type' => 'Vêtements',
            'dimensions' => '40x30x15 cm',
            'declared_value' => 150.00,
            'additional_info' => 'Vêtements pour enfants, pas fragile.',
        ]);

        $this->command->info('Test data created successfully!');
        $this->command->info('Users: ahmed@example.com, fatima@example.com, mohammed@example.com');
        $this->command->info('Password: password');
    }
}
