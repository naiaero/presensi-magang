<?php

namespace Tests\Feature;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ExpiredInternLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_expired_intern_can_login_successfully()
    {
        $expiredDate = Carbon::yesterday()->toDateString();
        $user = User::forceCreate([
            'name' => 'Expired Intern',
            'email' => 'expired@test.com',
            'password' => Hash::make('password123'),
            'role' => 'intern',
            'institution' => 'Universitas Mataram',
            'major' => 'Teknik Informatika',
            'start_date' => '2026-01-01',
            'end_date' => $expiredDate,
        ]);

        $response = $this->post('/login', [
            'email' => 'expired@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('intern.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_expired_intern_can_view_dashboard_and_history()
    {
        $expiredDate = Carbon::yesterday()->toDateString();
        $user = User::forceCreate([
            'name' => 'Expired Intern',
            'email' => 'expired@test.com',
            'password' => Hash::make('password123'),
            'role' => 'intern',
            'start_date' => '2026-01-01',
            'end_date' => $expiredDate,
        ]);

        $this->actingAs($user);

        // Dashboard
        $dashboardResponse = $this->get(route('intern.dashboard'));
        $dashboardResponse->assertStatus(200);

        // History
        $historyResponse = $this->get(route('intern.attendance.index'));
        $historyResponse->assertStatus(200);

        // Profile
        $profileResponse = $this->get(route('intern.profile'));
        $profileResponse->assertStatus(200);
    }

    public function test_expired_intern_cannot_access_scan_or_store_attendance()
    {
        $expiredDate = Carbon::yesterday()->toDateString();
        $user = User::forceCreate([
            'name' => 'Expired Intern',
            'email' => 'expired@test.com',
            'password' => Hash::make('password123'),
            'role' => 'intern',
            'start_date' => '2026-01-01',
            'end_date' => $expiredDate,
        ]);

        $this->actingAs($user);

        // Scan view redirect back to dashboard
        $scanResponse = $this->get(route('intern.attendance.scan'));
        $scanResponse->assertRedirect(route('intern.dashboard'));
        $scanResponse->assertSessionHas('error');

        // Store In redirect back to dashboard
        $storeInResponse = $this->post(route('intern.attendance.store_in'), [
            'latitude' => -8.583333,
            'longitude' => 116.116667,
        ]);
        $storeInResponse->assertRedirect(route('intern.dashboard'));
        $storeInResponse->assertSessionHas('error');

        // Store Out redirect back to dashboard
        $storeOutResponse = $this->post(route('intern.attendance.store_out'), [
            'latitude' => -8.583333,
            'longitude' => 116.116667,
        ]);
        $storeOutResponse->assertRedirect(route('intern.dashboard'));
        $storeOutResponse->assertSessionHas('error');
    }

    public function test_expired_intern_cannot_access_or_submit_permission()
    {
        $expiredDate = Carbon::yesterday()->toDateString();
        $user = User::forceCreate([
            'name' => 'Expired Intern',
            'email' => 'expired@test.com',
            'password' => Hash::make('password123'),
            'role' => 'intern',
            'start_date' => '2026-01-01',
            'end_date' => $expiredDate,
        ]);

        $this->actingAs($user);

        // Create permission view redirect back to dashboard
        $createResponse = $this->get(route('intern.permission.create'));
        $createResponse->assertRedirect(route('intern.dashboard'));
        $createResponse->assertSessionHas('error');

        // Store permission redirect back to dashboard
        $storeResponse = $this->post(route('intern.permission.store'), [
            'date' => Carbon::today()->toDateString(),
            'reason_option' => 'Sakit',
        ]);
        $storeResponse->assertRedirect(route('intern.dashboard'));
        $storeResponse->assertSessionHas('error');
    }
}
