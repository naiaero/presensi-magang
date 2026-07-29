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
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'institution' => 'required|string|max:255',
            'start_date' => 'required|date',
            'duration' => 'required|string|max:255',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'institution' => $request->institution,
            'start_date' => $request->start_date,
            'duration' => $request->duration,
        ]);

        return redirect()->back()->with('success', 'User berhasil ditambahkan.');
    }

    public function updatePermission(Request $request, $id)
    {

        $request->validate([
            'status' => 'required|in:Approved,Rejected',
        ]);

        $permission = Permission::findOrFail($id);
        $permission->status = $request->status;
        $permission->save();

        return redirect()->back()->with('success', 'Status izin diperbarui.');
    }

    public function getUserCalendar($id)
    {

        $user = User::findOrFail($id);
        
        $currentMonth = Carbon::now()->format('Y-m');
        
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
            'permissions' => $permissions
        ]);
    }
}
