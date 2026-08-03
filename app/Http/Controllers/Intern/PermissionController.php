<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Permission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    /**
     * Menampilkan Halaman Form Pengajuan Izin
     */
    public function create()
    {
        $today = Carbon::now('Asia/Makassar')->toDateString();
        
        // Opsi alasan standar yang dapat dipilih anak magang
        $reasonOptions = [
            'Sakit',
            'Terlambat / Di luar Radius Kantor',
            'Urusan Kampus / Sekolah',
            'Keperluan Keluarga / Acara Penting',
            'Lainnya'
        ];

        return view('intern.permission.create', compact('today', 'reasonOptions'));
    }

    /**
     * Menyimpan Data Pengajuan Izin ke Database
     */
    public function store(Request $request)
    {
        // Validasi Input
        $request->validate([
            'date'          => 'required|date',
            'reason_option' => 'required|string',
            'custom_reason' => 'required_if:reason_option,Lainnya|nullable|string|max:500',
            'proof_file'    => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // Maksimal 2MB
        ], [
            'custom_reason.required_if' => 'Mohon isi alasan lengkap jika Anda memilih opsi "Lainnya".',
            'proof_file.mimes'          => 'Lampiran harus berupa file gambar (JPG, PNG) atau PDF.',
            'proof_file.max'            => 'Ukuran file lampiran maksimal 2 MB.',
        ]);

        $user = Auth::user();

        // Cek apakah sudah pernah mengajukan izin di tanggal yang sama
        $existingPermission = Permission::where('user_id', $user->id)
            ->where('date', $request->date)
            ->first();

        if ($existingPermission) {
            return redirect()->route('intern.dashboard')
                ->with('error', 'Anda sudah mengajukan izin pada tanggal ini.');
        }

        // Handle Upload File Bukti / Lampiran
        $filePath = null;
        if ($request->hasFile('proof_file')) {
            $file = $request->file('proof_file');
            $filename = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('permissions', $filename, 'public');
        }

        // Simpan Data Ke Database
        $permission = Permission::create([
            'user_id'       => $user->id,
            'date'          => $request->date,
            'reason_option' => $request->reason_option,
            'custom_reason' => $request->reason_option === 'Lainnya' ? $request->custom_reason : null,
            'proof_file'    => $filePath,
            'status'        => 'Approved',
        ]);

        if ($permission->reason_option === 'Terlambat / Di luar Radius Kantor') {
            $attendance = Attendance::firstOrNew([
                'user_id' => $permission->user_id,
                'date'    => $permission->date,
            ]);

            $attendance->status = 'Hadir';

            if (!$attendance->time_in) {
                $attendance->time_in = $permission->created_at->timezone('Asia/Makassar')->toTimeString();
            }

            $attendance->save();
        }

        return redirect()->route('intern.dashboard')
            ->with('success', 'Pengajuan izin berhasil dikirim dan otomatis disetujui oleh sistem.');
    }
}