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

    public function test_late_permission_approval_creates_hadir_attendance()
    {
        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

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

        $permission = Permission::create([
            'user_id' => $user->id,
            'date' => '2026-07-28',
            'reason_option' => 'Terlambat / Di luar Radius Kantor',
            'custom_reason' => 'Macet jalanan',
            'status' => 'Pending',
        ]);

        $this->actingAs($admin);

        // Approve permission
        $response = $this->post(route('admin.permission.update', $permission->id), [
            'status' => 'Approved',
        ]);

        $response->assertRedirect();
        
        // Assert permission status is Approved
        $this->assertDatabaseHas('permissions', [
            'id' => $permission->id,
            'status' => 'Approved',
        ]);

        // Assert Attendance record is automatically created with status Hadir
        $this->assertDatabaseHas('attendances', [
            'user_id' => $user->id,
            'date' => '2026-07-28',
            'status' => 'Hadir',
        ]);
    }
}
