<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    // Koordinat Pusat Kantor Bapenda NTB (Mataram)
    private float $officeLat = -8.583333; 
    private float $officeLng = 116.116667;
    private int $maxRadiusMeters = 100; // Radius toleransi presensi (100 meter)
    private string $lateLimitTime = '07:30:00'; // Batas jam masuk tepat waktu

    /**
     * Halaman Utama Dashboard Anak Magang
     */
    public function dashboard()
    {
        $userId = Auth::id();
        $today = Carbon::today()->toDateString();

        // Ambil data presensi hari ini
        $todayAttendance = Attendance::where('user_id', $userId)
            ->where('date', $today)
            ->first();

        // Ambil 5 riwayat presensi terakhir
        $recentAttendances = Attendance::where('user_id', $userId)
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();

        return view('intern.dashboard', compact('todayAttendance', 'recentAttendances'));
    }

    /**
     * Halaman Scan / Pengambilan Geolocation untuk Presensi
     */
    public function scanView()
    {
        $userId = Auth::id();
        $today = Carbon::today()->toDateString();

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
        $request->validate([
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $user = Auth::user();
        $now = Carbon::now('Asia/Makassar'); // WITA (UTC+8)
        $today = $now->toDateString();
        $currentTime = $now->toTimeString();

        // 1. Cek apakah sudah pernah presensi masuk hari ini
        $existingAttendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if ($existingAttendance && $existingAttendance->time_in) {
            return redirect()->route('intern.dashboard')
                ->with('error', 'Anda sudah melakukan presensi masuk hari ini.');
        }

        // 2. Cek Batas Waktu Masuk (Maksimal 07:30 WITA)
        if ($currentTime > $this->lateLimitTime) {
            return redirect()->route('intern.permission.create')
                ->with('error', 'Waktu presensi masuk telah melewati batas 07:30 WITA. Silakan ajukan Form Izin/Terlambat.');
        }

        // 3. Validasi Geofencing (Radius dari Kantor Bapenda NTB)
        $distance = $this->calculateHaversineDistance(
            $request->latitude,
            $request->longitude,
            $this->officeLat,
            $this->officeLng
        );

        if ($distance > $this->maxRadiusMeters) {
            return redirect()->route('intern.permission.create')
                ->with('error', 'Anda berada di luar radius Kantor Bapenda NTB (' . round($distance) . ' meter). Silakan lakukan presensi di dalam kantor atau ajukan Izin.');
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
            ->with('success', 'Presensi masuk berhasil dicatat pada jam ' . $currentTime . ' WITA.');
    }

    /**
     * Proses Presensi Pulang (Check-Out)
     */
    public function storeOut(Request $request)
    {
        $now = Carbon::now('Asia/Makassar');
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

        $user = Auth::user();
        $now = Carbon::now('Asia/Makassar');
        $today = $now->toDateString();
        $currentTime = $now->toTimeString();

        // 1. Cari data presensi hari ini
        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if (!$attendance || !$attendance->time_in) {
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
            ->with('success', 'Presensi pulang berhasil dicatat pada jam ' . $currentTime . ' WITA.');
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
}