<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class AttendanceController extends Controller
{
    // Koordinat Pusat Kantor Bapenda NTB (Mataram)
    private float $officeLat = -8.583333; 
    private float $officeLng = 116.116667;
    private int $maxRadiusMeters = 3000; // Diperbesar menjadi 3000 meter untuk toleransi akurasi GPS laptop/desktop
    private string $lateLimitTime = '07:30:00'; // Batas jam masuk tepat waktu

    /**
     * Halaman Utama Dashboard Anak Magang
     */
    public function dashboard()
    {
        $userId = Auth::id();
        $today = Carbon::now('Asia/Makassar')->toDateString();

        // Ambil data presensi hari ini
        $todayAttendance = Attendance::where('user_id', $userId)
            ->where('date', $today)
            ->first();

        // Ambil 5 riwayat presensi terakhir
        $recentAttendances = Attendance::where('user_id', $userId)
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();

        // Ambil data pengajuan izin hari ini (jika ada)
        $todayPermission = \App\Models\Permission::where('user_id', $userId)
            ->where('date', $today)
            ->first();

        $toastPermission = null;
        if ($todayPermission) {
            $hasShownToast = session()->get('permission_toast_shown', false);

            if (!$hasShownToast) {
                $toastPermission = $todayPermission;
                session()->put('permission_toast_shown', true);
            }
        }

        return view('intern.dashboard', compact('todayAttendance', 'recentAttendances', 'todayPermission', 'toastPermission'));
    }

    /**
     * Halaman Kalender Kehadiran
     */
    public function calendar(Request $request)
    {
        $user = Auth::user();
        
        $month = $request->query('month', Carbon::now()->format('m'));
        $year = $request->query('year', Carbon::now()->format('Y'));
        
        $startDate = Carbon::parse($user->created_at ?? $user->start_date)->startOfMonth();
        $currentRequestedDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();

        if ($currentRequestedDate->lt($startDate)) {
            $currentRequestedDate = $startDate;
            $month = $startDate->format('m');
            $year = $startDate->format('Y');
        }
        
        $currentMonth = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);
        
        // Fetch attendances for current month
        $attendances = Attendance::where('user_id', $user->id)
            ->where('date', 'like', $currentMonth . '%')
            ->get();
            
        // Fetch permissions for current month
        $permissions = \App\Models\Permission::where('user_id', $user->id)
            ->where('date', 'like', $currentMonth . '%')
            ->get();

        $userCreatedAt = Carbon::parse($user->created_at ?? $user->start_date)->toDateString();

        return view('intern.attendance.calendar', compact('attendances', 'permissions', 'month', 'year', 'startDate', 'userCreatedAt'));
    }

    /**
     * Halaman Riwayat Presensi (History)
     */
    public function history(Request $request)
    {
        $user = Auth::user();
        
        $month = $request->query('month', Carbon::now()->format('m'));
        $year = $request->query('year', Carbon::now()->format('Y'));
        
        $startDate = Carbon::parse($user->created_at ?? $user->start_date)->startOfMonth();
        $currentRequestedDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();

        if ($currentRequestedDate->lt($startDate)) {
            $currentRequestedDate = $startDate;
            $month = $startDate->format('m');
            $year = $startDate->format('Y');
        }
        
        $currentMonth = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);
        
        // Fetch attendances for current month
        $attendances = Attendance::where('user_id', $user->id)
            ->where('date', 'like', $currentMonth . '%')
            ->orderBy('date', 'desc')
            ->get();
            
        return view('intern.attendance.history', compact('attendances', 'month', 'year', 'startDate'));
    }

    /**
     * Halaman Scan / Pengambilan Geolocation untuk Presensi
     */
    public function scanView()
    {
        $user = Auth::user();
        $today = Carbon::now('Asia/Makassar')->toDateString();

        if ($user->end_date && Carbon::parse($today)->greaterThan(Carbon::parse($user->end_date))) {
            return redirect()->route('intern.dashboard')
                ->with('error', 'Masa magang Anda telah selesai pada tanggal ' . Carbon::parse($user->end_date)->translatedFormat('d F Y') . '. Anda tidak dapat lagi melakukan presensi.');
        }

        $userId = $user->id;

        $todayAttendance = Attendance::where('user_id', $userId)
            ->where('date', $today)
            ->first();

        $now = Carbon::now('Asia/Makassar');
        $isFriday = $now->isFriday();
        $limitTime = $isFriday ? '17:00:00' : '16:00:00';
        $isEarlyCheckout = $now->toTimeString() < $limitTime;

        return view('intern.attendance.scan', compact('todayAttendance', 'isEarlyCheckout', 'limitTime'));
    }

    /**
     * Proses Presensi Masuk (Check-In)
     */
    public function storeIn(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now('Asia/Makassar'); // WITA (UTC+8)
        $today = $now->toDateString();

        if ($user->end_date && Carbon::parse($today)->greaterThan(Carbon::parse($user->end_date))) {
            return redirect()->route('intern.dashboard')
                ->with('error', 'Masa magang Anda telah selesai pada tanggal ' . Carbon::parse($user->end_date)->translatedFormat('d F Y') . '. Anda tidak dapat lagi melakukan presensi.');
        }

        $request->validate([
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $currentTime = $now->toTimeString();

        // 1. Cek apakah sudah pernah presensi atau sudah ada record kehadiran hari ini
        $existingAttendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if ($existingAttendance) {
            if ($existingAttendance->time_in) {
                return redirect()->route('intern.dashboard')
                    ->with('error', 'Anda sudah melakukan presensi masuk hari ini.');
            } elseif ($existingAttendance->status === 'Hadir') {
                return redirect()->route('intern.dashboard')
                    ->with('error', 'Kehadiran Anda hari ini sudah dicatat melalui persetujuan izin Admin. Silakan langsung melakukan presensi pulang saat waktunya.');
            } else {
                return redirect()->route('intern.dashboard')
                    ->with('error', 'Presensi tidak dapat dilakukan karena status Anda hari ini tercatat sebagai: ' . $existingAttendance->status);
            }
        }

        // 2. Validasi Geofencing (Radius dari Kantor Bapenda NTB)
        $distance = $this->calculateHaversineDistance(
            $request->latitude,
            $request->longitude,
            $this->officeLat,
            $this->officeLng
        );

        if ($distance > $this->maxRadiusMeters) {
            return redirect()->route('intern.permission.create', ['type' => 'tidak_hadir'])
                ->with('error', 'Anda berada di luar radius Kantor Bapenda NTB (' . round($distance) . ' meter). Silakan lakukan presensi di dalam kantor atau ajukan Pengajuan Izin Tidak Hadir.');
        }

        // 3. Cek Batas Waktu Masuk (Maksimal 07:30 WITA)
        if ($currentTime > $this->lateLimitTime) {
            return redirect()->route('intern.permission.create', ['type' => 'telat'])
                ->with('error', 'Waktu presensi masuk telah melewati batas 07:30 WITA. Silakan ajukan Form Keterangan Terlambat.');
        }

        // 4. Simpan Data Presensi Masuk
        Attendance::create([
            'user_id'      => $user->id,
            'date'         => $today,
            'time_in'      => $currentTime,
            'latitude_in'  => $request->latitude,
            'longitude_in' => $request->longitude,
            'status'       => 'Hadir',
        ]);

        return redirect()->route('intern.dashboard')
            ->with('success', 'Presensi masuk berhasil dilakukan');
    }

    /**
     * Proses Presensi Pulang (Check-Out)
     */
    public function storeOut(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now('Asia/Makassar');
        $today = $now->toDateString();

        if ($user->end_date && Carbon::parse($today)->greaterThan(Carbon::parse($user->end_date))) {
            return redirect()->route('intern.dashboard')
                ->with('error', 'Masa magang Anda telah selesai pada tanggal ' . Carbon::parse($user->end_date)->translatedFormat('d F Y') . '. Anda tidak dapat lagi melakukan presensi.');
        }

        $currentTime = $now->toTimeString();
        $isFriday = $now->isFriday();
        $limitTime = $isFriday ? '17:00:00' : '16:00:00';
        $isEarlyCheckout = $currentTime < $limitTime;

        $rules = [
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ];

        if ($isEarlyCheckout) {
            $rules['early_leave_reason'] = 'required|string|max:500';
        }

        $request->validate($rules, [
            'early_leave_reason.required' => 'Karena Anda pulang sebelum pukul ' . substr($limitTime, 0, 5) . ' WITA, mohon isi alasan Anda.',
        ]);

        $currentTime = $now->toTimeString();

        // 1. Cari data presensi hari ini
        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if (!$attendance || (!$attendance->time_in && $attendance->status !== 'Hadir')) {
            return redirect()->route('intern.dashboard')
                ->with('error', 'Anda belum melakukan presensi masuk hari ini.');
        }

        if ($attendance->time_out) {
            return redirect()->route('intern.dashboard')
                ->with('error', 'Anda sudah melakukan presensi pulang hari ini.');
        }

        // 2. Update jam pulang dan lokasi
        $attendance->update([
            'time_out'      => $currentTime,
            'latitude_out'  => $request->latitude,
            'longitude_out' => $request->longitude,
            'early_leave_reason' => $isEarlyCheckout ? $request->early_leave_reason : null,
        ]);

        return redirect()->route('intern.dashboard')
            ->with('success', 'Presensi pulang berhasil dilakukan');
    }

    /**
     * Rumus Haversine untuk menghitung jarak 2 titik koordinat (dalam Meter)
     */
    private function calculateHaversineDistance($lat1, $lon1, $lat2, $lon2): float
    {
        $earthRadius = 6371000; // Radius Bumi dalam Meter

        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo   = deg2rad($lat2);
        $lonTo   = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                 cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $earthRadius * $angle;
    }

    /**
     * Cetak File PDF Riwayat Presensi Magang
     */
    public function exportPdf()
    {
        $user = Auth::user();

        // Ambil seluruh data presensi user selama magang berlangsung
        $attendances = Attendance::where('user_id', $user->id)
            ->orderBy('date', 'asc')
            ->get();

        $pdf = app('dompdf.wrapper')->loadView('intern.attendance.pdf', compact('user', 'attendances'));
        $pdf->setPaper('a4', 'portrait');

        $filename = 'Riwayat_Presensi_' . \Illuminate\Support\Str::slug($user->name) . '.pdf';

        return $pdf->stream($filename);
    }
}