<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminPdfExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_export_intern_attendance_pdf()
    {
        $admin = User::forceCreate([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $intern = User::forceCreate([
            'name' => 'Peserta Intern',
            'email' => 'intern@test.com',
            'password' => Hash::make('password'),
            'role' => 'intern',
            'institution' => 'Universitas Mataram',
            'major' => 'Teknik Informatika',
            'start_date' => '2026-05-01',
            'end_date' => '2026-08-01',
        ]);

        Attendance::create([
            'user_id' => $intern->id,
            'date' => '2026-05-15',
            'time_in' => '07:15:00',
            'time_out' => '16:00:00',
            'status' => 'Hadir',
        ]);

        $this->actingAs($admin);

        $response = $this->get(route('admin.user.pdf', $intern->id));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_intern_cannot_export_other_user_pdf_via_admin_route()
    {
        $intern = User::forceCreate([
            'name' => 'Peserta Intern 1',
            'email' => 'intern1@test.com',
            'password' => Hash::make('password'),
            'role' => 'intern',
            'start_date' => '2026-05-01',
            'end_date' => '2026-08-01',
        ]);

        $otherIntern = User::forceCreate([
            'name' => 'Peserta Intern 2',
            'email' => 'intern2@test.com',
            'password' => Hash::make('password'),
            'role' => 'intern',
            'start_date' => '2026-05-01',
            'end_date' => '2026-08-01',
        ]);

        $this->actingAs($intern);

        $response = $this->get(route('admin.user.pdf', $otherIntern->id));

        // Blocked by CheckAdminRole middleware
        $response->assertStatus(403);
    }
}
