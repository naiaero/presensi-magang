<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="icon" href="{{ asset('images/Logo.png') }}" type="image/png">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Outfit', sans-serif; }
        /* Make the page background white and the panel solid white */
        .glass-panel {
            background: #ffffff;
            border: 1px solid rgba(2,6,23,0.06);
        }
        .dark .glass-panel {
            background: #0b1220;
            border: 1px solid rgba(255, 255, 255, 0.04);
        }
        .animated-bg {
            background: linear-gradient(-45deg, #1e3a8a, #3b82f6, #0ea5e9, #1d4ed8);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }
        .logo-frame {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 110px;
            height: 110px;
            margin: 0 auto 1rem;
        }
        .logo-image {
            max-width: 150%;
            max-height: 150%;
            object-fit: contain;
        }
        .dark .animated-bg {
            background: linear-gradient(-45deg, #020617, #0f172a, #1e3a8a, #172554);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
    </style>
</head>
    <body class="antialiased bg-white text-slate-800 dark:text-slate-200 min-h-screen flex items-center justify-center p-6 selection:bg-cyan-500 selection:text-white">

    <div class="w-full max-w-md relative z-10">
        <div class="glass-panel rounded-3xl p-8 relative overflow-hidden shadow-lg shadow-slate-200">
            <div class="text-center mb-8">
                <div class="logo-frame">
                    <img src="{{ asset('images/Logo.png') }}" alt="Logo Presensi" class="logo-image">
                </div>
                <h2 class="text-2xl font-bold text-slate-800">Login</h2>
                <p class="text-slate-500 text-sm mt-2">Presensi Magang Bapenda NTB</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-4" onsubmit="sessionStorage.setItem('session_active', '1');">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                    <input id="email" type="text" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                        class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent transition-all">
                    @error('email')
                        <p class="text-red-300 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent transition-all">
                </div>

                <button type="submit" class="w-full py-2.5 mt-4 bg-blue-600 text-white font-semibold rounded-lg shadow hover:shadow-md hover:bg-blue-700 transition-all">
                    Login
                </button>
            </form>
            <div class="mt-6 pt-5 border-t border-slate-100 text-center">
                <a href="{{ route('monitoring') }}" class="inline-flex items-center justify-center gap-2 text-xs font-semibold text-slate-600 hover:text-slate-800 bg-slate-50 hover:bg-slate-100 px-4 py-2 rounded-xl transition-all border border-slate-100">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Monitoring Presensi</span>
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
