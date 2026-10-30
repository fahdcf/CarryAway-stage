<?php

namespace Tests\Feature;

use Database\Seeders\AdminUserSeeder;
use Database\Seeders\CarryWaySeeder;
use Database\Seeders\DatabaseSeeder;
use Tests\TestCase;

class DemoSeederSafetyTest extends TestCase
{
    public function test_demo_seeders_refuse_production_before_writing_data(): void
    {
        app()->detectEnvironment(fn () => 'production');
        foreach ([DatabaseSeeder::class, AdminUserSeeder::class, CarryWaySeeder::class] as $seeder) {
            try {
                app($seeder)->run();
                $this->fail($seeder . ' allowed demo seeding in production.');
            } catch (\LogicException $exception) {
                $this->assertStringContainsString('local/testing', $exception->getMessage());
            }
        }
    }

    public function test_demo_admin_requires_an_explicit_local_password(): void
    {
        config()->set('seeding.admin_password', null);
        $this->expectException(\RuntimeException::class);
        app(AdminUserSeeder::class)->run();
    }

    public function test_demo_users_require_an_explicit_local_password(): void
    {
        config()->set('seeding.demo_password', null);
        $this->expectException(\RuntimeException::class);
        app(CarryWaySeeder::class)->run();
    }
}
