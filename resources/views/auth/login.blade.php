<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass-panel {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .dark .glass-panel {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.05);
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
<body class="antialiased animated-bg text-slate-800 dark:text-slate-200 min-h-screen flex items-center justify-center p-6 selection:bg-cyan-500 selection:text-white">

    <div class="w-full max-w-md relative z-10">
        <div class="glass-panel rounded-3xl p-8 relative overflow-hidden shadow-2xl shadow-blue-900/40">
            <div class="text-center mb-8">
                <div class="logo-frame">
                    <img src="{{ asset('images/Logo.png') }}" alt="Logo Presensi" class="logo-image">
                </div>
                <h2 class="text-2xl font-bold text-white">Login</h2>
                <p class="text-blue-200 text-sm mt-2">Presensi Magang Bapenda NTB</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-4" onsubmit="sessionStorage.setItem('session_active', '1');">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-semibold text-blue-100 mb-2">Email</label>
                    <input id="email" type="text" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                        class="w-full px-4 py-2.5 bg-white/10 border border-white/20 rounded-lg text-white placeholder-blue-200/50 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent transition-all backdrop-blur-sm">
                    @error('email')
                        <p class="text-red-300 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-blue-100 mb-2">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="w-full px-4 py-2.5 bg-white/10 border border-white/20 rounded-lg text-white placeholder-blue-200/50 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent transition-all backdrop-blur-sm">
                </div>

                <button type="submit" class="w-full py-2.5 mt-4 bg-white text-blue-700 font-semibold rounded-lg shadow-lg hover:shadow-xl hover:bg-blue-50 transition-all">
                    Login
                </button>
            </form>
        </div>
    </div>
</body>
</html>
