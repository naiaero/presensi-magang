<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthSessionTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_visiting_root_is_redirected_to_login(): void
    {
        $response = $this->get('/');
        $response->assertRedirect(route('login'));
    }

    public function test_guest_can_view_login_page(): void
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
        $response->assertSee('Login');
    }

    public function test_intern_can_login_and_is_redirected_to_intern_dashboard(): void
    {
        $user = User::forceCreate([
            'name' => 'Intern User',
            'email' => 'intern@test.com',
            'password' => Hash::make('password123'),
            'role' => 'intern',
        ]);

        $response = $this->post('/login', [
            'email' => 'intern@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('intern.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_admin_can_login_and_is_redirected_to_admin_dashboard(): void
    {
        $admin = User::forceCreate([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_authenticated_intern_visiting_root_or_login_is_redirected_to_intern_dashboard(): void
    {
        $user = User::forceCreate([
            'name' => 'Intern User',
            'email' => 'intern@test.com',
            'password' => Hash::make('password123'),
            'role' => 'intern',
        ]);

        $this->actingAs($user);

        // Visiting root '/'
        $rootResponse = $this->get('/');
        $rootResponse->assertRedirect(route('intern.dashboard'));

        // Visiting '/login'
        $loginResponse = $this->get('/login');
        $loginResponse->assertRedirect(route('intern.dashboard'));
    }

    public function test_authenticated_admin_visiting_root_or_login_is_redirected_to_admin_dashboard(): void
    {
        $admin = User::forceCreate([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        // Visiting root '/'
        $rootResponse = $this->get('/');
        $rootResponse->assertRedirect(route('admin.dashboard'));

        // Visiting '/login'
        $loginResponse = $this->get('/login');
        $loginResponse->assertRedirect(route('admin.dashboard'));
    }

    public function test_authenticated_user_posting_login_is_redirected_to_dashboard(): void
    {
        $user = User::forceCreate([
            'name' => 'Intern User',
            'email' => 'intern@test.com',
            'password' => Hash::make('password123'),
            'role' => 'intern',
        ]);

        $this->actingAs($user);

        $response = $this->post('/login', [
            'email' => 'intern@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('intern.dashboard'));
    }

    public function test_user_can_logout_and_session_is_cleared(): void
    {
        $user = User::forceCreate([
            'name' => 'Intern User',
            'email' => 'intern@test.com',
            'password' => Hash::make('password123'),
            'role' => 'intern',
        ]);

        $this->actingAs($user);

        $response = $this->post('/logout');
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
