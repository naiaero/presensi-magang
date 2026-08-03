<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Carbon\Carbon;

class CalendarTest extends TestCase
{
    use RefreshDatabase;

    public function test_intern_calendar_shows_correct_month_and_restricts_past_months()
    {
        $user = User::forceCreate([
            'name' => 'Intern Test',
            'email' => 'intern@test.com',
            'password' => Hash::make('password'),
            'role' => 'intern',
            'institution' => 'Universitas Mataram',
            'major' => 'Teknik Informatika',
            'start_date' => '2026-05-01',
            'duration' => '3 Bulan',
            'created_at' => Carbon::parse('2026-05-15 10:00:00'),
            'updated_at' => Carbon::parse('2026-05-15 10:00:00'),
        ]);

        $this->actingAs($user);

        // Access calendar for April 2026 (prior to account creation in May 2026)
        $response = $this->get(route('intern.attendance.index', ['month' => '04', 'year' => '2026']));
        
        $response->assertStatus(200);
        // Should override to May 2026 (05)
        $response->assertViewHas('month', '05');
        $response->assertViewHas('year', '2026');
    }

    public function test_permission_submission_is_auto_approved_and_creates_hadir_attendance()
    {
        $user = User::create([
            'name' => 'Intern Test',
            'email' => 'intern2@test.com',
            'password' => Hash::make('password'),
            'role' => 'intern',
            'institution' => 'Universitas Mataram',
            'major' => 'Teknik Informatika',
            'start_date' => '2026-05-01',
            'duration' => '3 Bulan',
        ]);

        $this->actingAs($user);

        $response = $this->post(route('intern.permission.store'), [
            'date' => '2026-07-28',
            'reason_option' => 'Terlambat / Di luar Radius Kantor',
            'custom_reason' => 'Macet jalanan',
        ]);

        $response->assertRedirect(route('intern.dashboard'));

        $permission = Permission::where('user_id', $user->id)
            ->where('date', '2026-07-28')
            ->first();

        $this->assertNotNull($permission);
        $this->assertSame('Approved', $permission->status);

        $this->assertDatabaseHas('attendances', [
            'user_id' => $user->id,
            'date' => '2026-07-28',
            'status' => 'Hadir',
        ]);
    }
}
