<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return Auth::user()->role === 'admin'
                ? redirect()->route('admin.dashboard')
                : redirect()->route('intern.dashboard');
        }

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
            if (Auth::attempt($credentials, false)) {
                $request->session()->regenerate();

                $user = Auth::user();
                $targetRoute = ($user->role === 'admin')
                    ? route('admin.dashboard')
                    : route('intern.dashboard');

                // Clear any stored intended URL to prevent redirect loop
                if (session()->has('url.intended')) {
                    session()->forget('url.intended');
                }

                return redirect($targetRoute);
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

        return redirect()->route('login');
    }
}
