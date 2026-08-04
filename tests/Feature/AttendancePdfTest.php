<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AttendancePdfTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_intern_can_export_attendance_pdf()
    {
        $user = User::forceCreate([
            'name' => 'Peserta PDF Test',
            'email' => 'pdftest@example.com',
            'password' => Hash::make('password'),
            'role' => 'intern',
            'institution' => 'Universitas Mataram',
            'major' => 'Teknik Informatika',
            'start_date' => '2026-05-01',
            'end_date' => '2026-08-01',
        ]);

        Attendance::create([
            'user_id' => $user->id,
            'date' => '2026-05-15',
            'time_in' => '07:15:00',
            'time_out' => '16:00:00',
            'status' => 'Hadir',
        ]);

        $this->actingAs($user);

        $response = $this->get(route('intern.attendance.pdf'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
