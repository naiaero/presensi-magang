<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use App\Models\Permission;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    /**
     * Menampilkan Halaman Dashboard Pemantauan Presensi Publik
     */
    public function index()
    {
        $data = $this->getMonitoringData();

        return view('monitoring.index', $data);
    }

    /**
     * Endpoint API JSON untuk Auto-Refresh Real-Time
     */
    public function data()
    {
        $data = $this->getMonitoringData();

        return response()->json($data);
    }

    /**
     * Helper untuk mengolah data kehadiran dan tanpa keterangan hari ini
     */
    private function getMonitoringData(): array
    {
        $now = Carbon::now('Asia/Makassar');
        $today = $now->toDateString();
        $formattedDate = $now->translatedFormat('d M Y');
        $fullDate = $now->translatedFormat('l, d F Y');

        // Ambil semua peserta magang yang aktif pada hari ini
        $interns = User::where('role', 'intern')
            ->where(function ($query) use ($today) {
                $query->whereNull('start_date')
                      ->orWhere('start_date', '<=', $today);
            })
            ->where(function ($query) use ($today) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>=', $today);
            })
            ->orderBy('name', 'asc')
            ->get();

        // Ambil presensi hari ini
        $attendances = Attendance::where('date', $today)
            ->get()
            ->keyBy('user_id');

        // Ambil permohonan izin hari ini
        $permissions = Permission::where('date', $today)
            ->get()
            ->keyBy('user_id');

        $presentList = [];
        $permissionList = [];
        $absentList = [];

        $totalHadir = 0;
        $totalSakit = 0;
        $totalIzin = 0;

        foreach ($interns as $user) {
            $attendance = $attendances->get($user->id);
            $permission = $permissions->get($user->id);

            $timeIn = '-';
            $timeOut = '-';
            $status = '-';
            $statusType = '';

            // HADIR: ada record absensi (termasuk terlambat)
            if ($attendance) {
                $timeIn = $attendance->time_in ? Carbon::parse($attendance->time_in)->format('H:i:s') : '-';
                $timeOut = $attendance->time_out ? Carbon::parse($attendance->time_out)->format('H:i:s') : '-';

                if ($attendance->status === 'Telat') {
                    $status = 'Terlambat';
                    $statusType = 'terlambat';
                } else {
                    $status = 'Masuk';
                    $statusType = 'masuk';
                }

                $totalHadir++;

                $presentList[] = [
                    'id'           => $user->id,
                    'name'         => $user->name,
                    'institution'  => $user->institution ?: '-',
                    'major'        => $user->major ?: '',
                    'email'        => $user->email,
                    'phone_number' => $user->phone_number ?: '-',
                    'time_in'      => $timeIn,
                    'time_out'     => $timeOut,
                    'status'       => $status,
                    'status_type'  => $statusType,
                ];
            }
            // IZIN/SAKIT: tidak ada absensi, tapi ada form izin (apapun alasannya)
            elseif ($permission) {
                $reason = strtolower(trim((string) ($permission->reason_option ?? '')));

                if (strpos($reason, 'sakit') !== false) {
                    $status = 'Sakit';
                    $statusType = 'sakit';
                    $totalSakit++;
                } else {
                    $status = 'Izin';
                    $statusType = 'izin';
                    $totalIzin++;
                }

                $permissionList[] = [
                    'id'           => $user->id,
                    'name'         => $user->name,
                    'institution'  => $user->institution ?: '-',
                    'major'        => $user->major ?: '',
                    'email'        => $user->email,
                    'phone_number' => $user->phone_number ?: '-',
                    'time_in'      => $timeIn,
                    'time_out'     => $timeOut,
                    'status'       => $status,
                    'status_type'  => $statusType,
                ];
            }
            // ALPA: tidak ada absensi dan tidak ada izin
            else {
                $absentList[] = [
                    'id'           => $user->id,
                    'name'         => $user->name,
                    'institution'  => $user->institution ?: '-',
                    'major'        => $user->major ?: '',
                    'email'        => $user->email,
                    'phone_number' => $user->phone_number ?: '-',
                ];
            }
        }

        return [
            'formattedDate' => $formattedDate,
            'fullDate' => $fullDate,
            'currentTime' => $now->format('H:i:s'),
            'totalInterns' => count($interns),
            'totalPresent' => count($presentList),
            'totalAbsent' => count($absentList),
            'totalHadir' => $totalHadir,
            'totalSakit' => $totalSakit,
            'totalIzin' => $totalIzin,
            'presentList' => $presentList,
            'permissionList' => $permissionList,
            'absentList' => $absentList,
            'lastUpdated' => $now->format('H:i:s') . ' WITA',
        ];
    }
}
