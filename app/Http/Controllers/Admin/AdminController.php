<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Permission;
use App\Models\Attendance;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {

        $users = User::where('role', 'intern')->get();
        $permissions = Permission::with('user')->where('status', 'Pending')->get();

        return view('admin.dashboard', compact('users', 'permissions'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'institution' => 'required|string|max:255',
            'major' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'duration' => 'nullable|string|max:255',
        ], [
            'end_date.after_or_equal' => 'Tanggal selesai magang harus setelah atau sama dengan tanggal mulai.',
        ]);

        try {
            $durationStr = $request->duration;
            if (!$durationStr) {
                $startFmt = Carbon::parse($request->start_date)->translatedFormat('d M Y');
                $endFmt = Carbon::parse($request->end_date)->translatedFormat('d M Y');
                $durationStr = "{$startFmt} s.d {$endFmt}";
            }

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'institution' => $request->institution,
                'major' => $request->major,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'duration' => $durationStr,
                'role' => 'intern',
            ]);

            return redirect()->back()->with('success', 'User berhasil ditambahkan. User dapat login dengan email dan password yang telah ditetapkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan user: ' . $e->getMessage())->withInput();
        }
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'institution' => 'required|string|max:255',
            'major' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'duration' => 'nullable|string|max:255',
        ], [
            'end_date.after_or_equal' => 'Tanggal selesai magang harus setelah atau sama dengan tanggal mulai.',
        ]);

        try {
            $durationStr = $request->duration;
            if (!$durationStr) {
                $startFmt = Carbon::parse($request->start_date)->translatedFormat('d M Y');
                $endFmt = Carbon::parse($request->end_date)->translatedFormat('d M Y');
                $durationStr = "{$startFmt} s.d {$endFmt}";
            }

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'institution' => $request->institution,
                'major' => $request->major,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'duration' => $durationStr,
            ]);

            return redirect()->back()->with('success', 'Data user ' . $user->name . ' berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui user: ' . $e->getMessage());
        }
    }

    public function resetPassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'password' => 'required|string|min:6',
        ], [
            'password.min' => 'Password minimal harus 6 karakter.',
        ]);

        try {
            $user->update([
                'password' => Hash::make($request->password),
            ]);

            return redirect()->back()->with('success', 'Password untuk ' . $user->name . ' berhasil di-reset.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mereset password: ' . $e->getMessage());
        }
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Akun admin tidak dapat dihapus.');
        }

        try {
            $name = $user->name;
            $user->delete();
            return redirect()->back()->with('success', 'User ' . $name . ' berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }

    public function updatePermission(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Approved,Rejected',
        ]);

        $permission = Permission::findOrFail($id);
        $permission->status = $request->status;
        $permission->save();

        if ($permission->status === 'Approved' && $permission->reason_option === 'Terlambat / Di luar Radius Kantor') {
            Attendance::updateOrCreate(
                [
                    'user_id' => $permission->user_id,
                    'date'    => $permission->date,
                ],
                [
                    'status'  => 'Hadir',
                ]
            );
        }

        return redirect()->back()->with('success', 'Status izin diperbarui.');
    }

    public function getUserCalendar(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
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
        $attendances = Attendance::where('user_id', $id)
            ->where('date', 'like', $currentMonth . '%')
            ->get();
            
        // Fetch permissions for current month
        $permissions = Permission::where('user_id', $id)
            ->where('date', 'like', $currentMonth . '%')
            ->get();

        return response()->json([
            'user' => $user,
            'attendances' => $attendances,
            'permissions' => $permissions,
            'month' => (int)$month,
            'year' => (int)$year,
            'startDate' => $startDate->format('Y-m'),
            'userCreatedAt' => Carbon::parse($user->created_at ?? $user->start_date)->toDateString(),
            'monthName' => Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y'),
        ]);
    }
}
