<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Presensi Magang - Bapenda NTB</title>
    
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
        @keyframes shine {
            100% { left: 125%; }
        }
        .animate-shine {
            animation: shine 1.5s ease-in-out;
        }
    </style>
</head>
<body class="antialiased animated-bg text-slate-800 dark:text-slate-200 min-h-screen flex flex-col justify-between selection:bg-cyan-500 selection:text-white">

    <!-- Header -->
    <header class="w-full p-6 lg:px-12 flex justify-between items-center z-10">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-md border border-white/30 text-white font-bold text-xl shadow-lg">
                B
            </div>
            <div>
                <h1 class="text-white font-bold text-lg leading-tight tracking-tight">Bapenda NTB</h1>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 flex items-center justify-center p-6 z-10">
        <div class="max-w-5xl w-full grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            
            <!-- Hero Text -->
            <div class="space-y-6 text-center lg:text-left">
                <h2 class="text-5xl lg:text-7xl font-extrabold text-white tracking-tight leading-[1.1]">
                    Presensi Magang
                </h2>
                
                <div class="flex flex-col sm:flex-row items-center gap-4 justify-center lg:justify-start pt-4">
                    @if(Route::has('login'))
                        @auth
                            <a href="{{ route('intern.dashboard') }}" class="w-full sm:w-auto px-8 py-4 bg-white text-blue-700 font-bold rounded-2xl shadow-xl shadow-blue-900/20 hover:scale-105 hover:shadow-2xl hover:shadow-blue-900/40 transition-all flex items-center justify-center gap-2">
                                <span>Buka Dashboard</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 bg-white text-blue-700 font-bold rounded-2xl shadow-xl shadow-blue-900/20 hover:scale-105 hover:shadow-2xl hover:shadow-blue-900/40 transition-all flex items-center justify-center gap-2">
                                <span>Masuk ke Akun</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                            </a>
                        @endauth
                    @else 
                        <a href="{{ route('intern.dashboard') }}" class="w-full sm:w-auto px-8 py-4 bg-white text-blue-700 font-bold rounded-2xl shadow-xl shadow-blue-900/20 hover:scale-105 hover:shadow-2xl hover:shadow-blue-900/40 transition-all flex items-center justify-center gap-2">
                            <span>Mulai Presensi</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    {{-- <footer class="w-full text-center py-6 border-t border-white/10 z-10 mt-8 bg-black/10 backdrop-blur-md">
        <p class="text-blue-100/70 text-xs font-medium tracking-wide">
            &copy; {{ date('Y') }} Badan Pendapatan Daerah Provinsi NTB. 
            <span class="block sm:inline sm:ml-1 mt-1 sm:mt-0">Dikembangkan oleh Tim Magang.</span>
        </p>
    </footer> --}}
</body>
</html>
