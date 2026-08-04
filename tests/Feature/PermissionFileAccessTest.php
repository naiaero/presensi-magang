<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PermissionFileAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_intern_can_upload_permission_file_and_file_is_stored()
    {
        Storage::fake('public');

        $intern = User::forceCreate([
            'name' => 'Peserta Tes File',
            'email' => 'filetest@example.com',
            'password' => Hash::make('password'),
            'role' => 'intern',
            'start_date' => '2026-05-01',
            'end_date' => '2026-08-31',
        ]);

        $this->actingAs($intern);

        $file = UploadedFile::fake()->create('surat_izin.pdf', 100, 'application/pdf');

        $response = $this->post(route('intern.permission.store'), [
            'date' => '2026-08-10',
            'reason_option' => 'Sakit',
            'proof_file' => $file,
        ]);

        $response->assertRedirect(route('intern.dashboard'));

        $permission = Permission::where('user_id', $intern->id)->first();
        $this->assertNotNull($permission);
        $this->assertNotNull($permission->proof_file);

        Storage::disk('public')->assertExists($permission->proof_file);
    }

    public function test_authenticated_admin_can_view_permission_file_via_route()
    {
        $admin = User::forceCreate([
            'name' => 'Admin File Tes',
            'email' => 'adminfile@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $filename = '123456_file.pdf';
        $directory = storage_path('app/public/permissions');
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
        file_put_contents($directory . '/' . $filename, 'fake pdf content');

        $this->actingAs($admin);

        $response = $this->get(route('permission.file', $filename));

        $response->assertStatus(200);

        @unlink($directory . '/' . $filename);
    }

    public function test_missing_permission_file_redirects_with_error_message()
    {
        $admin = User::forceCreate([
            'name' => 'Admin File Tes 2',
            'email' => 'adminfile2@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        $response = $this->get(route('permission.file', 'non_existent_file_999.pdf'));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'File lampiran tidak ditemukan pada server.');
    }
}
