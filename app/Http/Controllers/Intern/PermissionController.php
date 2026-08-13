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
    public function create(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::now('Asia/Makassar')->toDateString();

        if ($user->end_date && Carbon::parse($today)->greaterThan(Carbon::parse($user->end_date))) {
            return redirect()->route('intern.dashboard')
                ->with('error', 'Masa magang Anda telah selesai pada tanggal ' . Carbon::parse($user->end_date)->translatedFormat('d F Y') . '. Anda tidak dapat lagi mengajukan izin.');
        }
        
        $type = $request->query('type', 'tidak_hadir');
        
        if ($type === 'telat') {
            $reasonOptions = [
                'Macet',
                'Ban Bocor / Kendaraan Rusak',
                'Cuaca Buruk / Hujan Deras',
                'Urusan Mendadak',
                'Lainnya'
            ];
        } else {
            $reasonOptions = [
                'Sakit',
                'Urusan Kampus / Sekolah',
                'Keperluan Keluarga / Acara Penting',
                'Lainnya'
            ];
        }

        return view('intern.permission.create', compact('today', 'reasonOptions', 'type'));
    }

    /**
     * Menyimpan Data Pengajuan Izin ke Database
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::now('Asia/Makassar')->toDateString();

        if ($user->end_date && Carbon::parse($today)->greaterThan(Carbon::parse($user->end_date))) {
            return redirect()->route('intern.dashboard')
                ->with('error', 'Masa magang Anda telah selesai pada tanggal ' . Carbon::parse($user->end_date)->translatedFormat('d F Y') . '. Anda tidak dapat lagi mengajukan izin.');
        }

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

        $permission = Permission::create([
            'user_id'       => $user->id,
            'date'          => $request->date,
            'reason_option' => $request->reason_option,
            'custom_reason' => $request->reason_option === 'Lainnya' ? $request->custom_reason : null,
            'proof_file'    => $filePath,
            'status'        => 'Approved',
        ]);

        $type = $request->type ?? 'tidak_hadir';

        if ($type === 'telat') {
            $attendance = Attendance::firstOrNew([
                'user_id' => $permission->user_id,
                'date'    => $permission->date,
            ]);

            $attendance->status = 'Hadir'; // Atau 'Telat' jika ingin lebih spesifik

            if (!$attendance->time_in) {
                $attendance->time_in = $permission->created_at->timezone('Asia/Makassar')->toTimeString();
            }

            $attendance->save();
        } else {
            // Izin tidak hadir, buat record attendance dengan status Izin agar terlihat di history
            $attendance = Attendance::firstOrNew([
                'user_id' => $permission->user_id,
                'date'    => $permission->date,
            ]);
            $attendance->status = 'Izin';
            $attendance->save();
        }

        return redirect()->route('intern.dashboard')
            ->with('success', $type === 'telat' ? 'Keterangan terlambat berhasil disimpan. Jangan lupa absen pulang nanti.' : 'Pengajuan izin berhasil dilakukan.');
    }

    /**
     * Tampilkan / download file lampiran bukti izin secara aman
     */
    public function showFile($filename)
    {
        $cleanFilename = basename($filename);

        $possiblePaths = [
            storage_path('app/public/permissions/' . $cleanFilename),
            storage_path('app/public/' . $filename),
            storage_path('app/permissions/' . $cleanFilename),
            public_path('storage/permissions/' . $cleanFilename),
            public_path('storage/' . $filename),
        ];

        $filePath = null;
        foreach ($possiblePaths as $path) {
            if (file_exists($path) && is_file($path)) {
                $filePath = $path;
                break;
            }
        }

        if (!$filePath) {
            return redirect()->back()->with('error', 'File lampiran tidak ditemukan pada server.');
        }

        $mimeType = mime_content_type($filePath) ?: 'application/octet-stream';

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $cleanFilename . '"',
        ]);
    }
}