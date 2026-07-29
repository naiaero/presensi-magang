<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_creates_admin_user(): void
    {
        $this->artisan('db:seed', ['--class' => DatabaseSeeder::class])
            ->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'email' => 'admin@admin.com',
            'role' => 'admin',
        ]);
    }
}
