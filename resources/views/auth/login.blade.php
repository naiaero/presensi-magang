<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Sistem Presensi Magang NTB</title>
    
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

    <div class="w-full max-w-md relative perspective-1000 z-10">
        <!-- Floating shapes behind card -->
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-cyan-400 rounded-full mix-blend-multiply filter blur-2xl opacity-60 animate-pulse"></div>
        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-blue-600 rounded-full mix-blend-multiply filter blur-2xl opacity-60 animate-pulse" style="animation-delay: 2s;"></div>

        <div class="glass-panel rounded-3xl p-8 relative overflow-hidden shadow-2xl shadow-blue-900/40">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-14 h-14 bg-white/20 rounded-2xl mb-4 border border-white/30 backdrop-blur-sm shadow-inner">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <h2 class="text-2xl font-bold text-white tracking-tight">Selamat Datang</h2>
                <p class="text-blue-200 text-sm mt-1">Masuk untuk mencatat presensi Anda hari ini</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-blue-100 uppercase tracking-wide mb-1.5 ml-1">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-blue-200/50 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent transition-all backdrop-blur-sm">
                    @error('email')
                        <p class="text-red-300 text-xs mt-1.5 font-medium ml-1 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-blue-100 uppercase tracking-wide mb-1.5 ml-1">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-blue-200/50 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent transition-all backdrop-blur-sm">
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-white/10 border-white/20 text-cyan-500 focus:ring-cyan-500 focus:ring-offset-0 bg-transparent cursor-pointer">
                        <span class="text-sm text-blue-200 group-hover:text-white transition-colors">Ingat Saya</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3.5 mt-4 bg-white text-blue-700 font-bold rounded-xl shadow-[0_0_20px_rgba(255,255,255,0.3)] hover:shadow-[0_0_25px_rgba(255,255,255,0.5)] hover:bg-blue-50 transition-all hover:-translate-y-0.5 active:translate-y-0">
                    Masuk
                </button>
            </form>
            
            <div class="mt-8 text-center border-t border-white/10 pt-4">
                <p class="text-xs text-blue-200/80">
                    Belum memiliki akun? <br>
                    <span class="text-white font-medium mt-1 inline-block">Hubungi admin untuk pendaftaran akun magang.</span>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
