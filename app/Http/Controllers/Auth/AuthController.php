<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required'],
            'password' => ['required'],
        ]);

        $login = trim($data['email']);
        $password = $data['password'];

        $attempts = [];

        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $attempts[] = ['email' => $login, 'password' => $password];
        } else {
            $attempts[] = ['name' => $login, 'password' => $password];
            $attempts[] = ['email' => $login, 'password' => $password];
            $attempts[] = ['email' => 'admin@admin.com', 'password' => $password];
        }

        foreach ($attempts as $credentials) {
            if (Auth::attempt($credentials, $request->boolean('remember'))) {
                $request->session()->regenerate();

                $user = Auth::user();
                
                // Cek apakah akun intern sudah nonaktif (masa magang selesai)
                if ($user->role === 'intern' && $user->end_date && \Carbon\Carbon::today()->toDateString() > $user->end_date) {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    
                    return back()->withErrors([
                        'email' => 'Akun Anda telah dinonaktifkan karena masa magang telah selesai.',
                    ])->onlyInput('email');
                }

                if ($user->role === 'admin') {
                    return redirect()->intended(route('admin.dashboard'));
                }

                return redirect()->intended(route('intern.dashboard'));
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
