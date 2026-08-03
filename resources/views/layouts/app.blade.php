<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Presensi Magang - Bapenda NTB')</title>

    <!-- Tailwind CSS (via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Heroicons CDN untuk Icon -->
    <script src="https://unpkg.com/heroicons@2.0.18/24/outline/index.js" defer></script>
    
    <style>
        /* Mencegah highlight biru saat tapping di mobile */
        * {
            -webkit-tap-highlight-color: transparent;
        }
    </style>
    @stack('styles')
</head>
<body class="h-full text-slate-800 antialiased bg-slate-50 min-h-screen flex flex-col md:flex-row relative">

    <!-- Sidebar for Desktop (md and above) -->
    <aside class="hidden md:flex flex-col w-64 lg:w-72 bg-white border-r border-slate-200 h-screen sticky top-0 shadow-lg z-40">
        <!-- Logo -->
        <div class="p-6 border-b border-slate-100 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center text-white font-bold text-xl shadow-md">
                B
            </div>
            <div>
                <h1 class="text-sm font-bold text-slate-800 leading-tight">Bapenda NTB</h1>
                <p class="text-[10px] text-slate-500 uppercase tracking-wider font-semibold">Presensi Magang</p>
            </div>
        </div>
        
        <!-- Navigation Links -->
        <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto">
            <p class="px-4 text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 mt-4">Menu Utama</p>
            
            @if(auth()->check() && auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-blue-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span class="text-sm">Beranda</span>
                </a>
                
                <p class="px-4 text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 mt-6">Akun</p>
                <a href="{{ route('intern.profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('intern.profile') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('intern.profile') ? 'text-blue-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="text-sm">Profil</span>
                </a>
            @else
                <a href="{{ route('intern.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('intern.dashboard') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('intern.dashboard') ? 'text-blue-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span class="text-sm">Beranda</span>
                </a>

                <a href="{{ route('intern.attendance.scan') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('intern.attendance.scan') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('intern.attendance.scan') ? 'text-blue-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm">Presensi</span>
                </a>

                <a href="{{ route('intern.permission.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('intern.permission.*') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('intern.permission.*') ? 'text-blue-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="text-sm">Pengajuan Izin</span>
                </a>

                <a href="{{ route('intern.attendance.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('intern.attendance.index') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('intern.attendance.index') ? 'text-blue-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-sm">Riwayat Presensi</span>
                </a>
                
                <p class="px-4 text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 mt-6">Akun</p>

                <a href="{{ route('intern.profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('intern.profile') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('intern.profile') ? 'text-blue-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="text-sm">Profil</span>
                </a>
            @endif
        </nav>

        <!-- Logout Button in Sidebar -->
        <div class="p-4 border-t border-slate-100">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 hover:text-red-700 rounded-xl transition-all font-semibold text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col min-h-screen w-full relative pb-16 md:pb-0 transition-all duration-300">
        



        <!-- Content Area -->
        <main class="flex-1 p-4 md:p-8 lg:p-10 w-full overflow-x-hidden">
            <div class="max-w-6xl mx-auto">
                {{-- Flash Messages --}}
                @if (session('success'))
                    <div class="mb-5 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm flex items-center gap-3 shadow-sm">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-5 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-sm flex items-center gap-3 shadow-sm">
                        <div class="w-8 h-8 rounded-full bg-rose-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

        <!-- Mobile Bottom Nav (Hidden on Desktop) -->
        <nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-slate-200 px-2 py-1.5 shadow-[0_-10px_20px_-10px_rgba(0,0,0,0.1)] pb-safe overflow-x-auto">
            <div class="grid {{ (auth()->check() && auth()->user()->role === 'admin') ? 'grid-cols-2' : 'grid-cols-5' }} gap-1 min-w-[320px]">
                @if(auth()->check() && auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex flex-col items-center justify-center py-2 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'text-blue-700 font-bold' : 'text-slate-400 hover:text-slate-600' }}">
                        <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span class="text-[10px]">Dashboard</span>
                    </a>
                    <a href="{{ route('intern.profile') }}" 
                       class="flex flex-col items-center justify-center py-2 rounded-xl transition-all {{ request()->routeIs('intern.profile') ? 'text-blue-700 font-bold' : 'text-slate-400 hover:text-slate-600' }}">
                        <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span class="text-[10px]">Profil</span>
                    </a>
                @else
                    <a href="{{ route('intern.dashboard') }}" 
                       class="flex flex-col items-center justify-center py-2 rounded-xl transition-all {{ request()->routeIs('intern.dashboard') ? 'text-blue-700 font-bold' : 'text-slate-400 hover:text-slate-600' }}">
                        <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span class="text-[10px]">Beranda</span>
                    </a>

                    <a href="{{ route('intern.attendance.scan') }}" 
                       class="flex flex-col items-center justify-center py-2 rounded-xl transition-all {{ request()->routeIs('intern.attendance.scan') ? 'text-blue-700 font-bold' : 'text-slate-400 hover:text-slate-600' }}">
                        <div class="relative">
                            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            @if(request()->routeIs('intern.attendance.scan'))
                                <span class="absolute -top-1 -right-1 w-2 h-2 bg-blue-600 rounded-full"></span>
                            @endif
                        </div>
                        <span class="text-[10px]">Presensi</span>
                    </a>

                    <a href="{{ route('intern.permission.create') }}" 
                       class="flex flex-col items-center justify-center py-2 rounded-xl transition-all {{ request()->routeIs('intern.permission.*') ? 'text-blue-700 font-bold' : 'text-slate-400 hover:text-slate-600' }}">
                        <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="text-[10px]">Izin</span>
                    </a>

                    <a href="{{ route('intern.attendance.index') }}" 
                       class="flex flex-col items-center justify-center py-2 rounded-xl transition-all {{ request()->routeIs('intern.attendance.index') ? 'text-blue-700 font-bold' : 'text-slate-400 hover:text-slate-600' }}">
                        <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-[10px]">Kalender</span>
                    </a>

                    <a href="{{ route('intern.profile') }}" 
                       class="flex flex-col items-center justify-center py-2 rounded-xl transition-all {{ request()->routeIs('intern.profile') ? 'text-blue-700 font-bold' : 'text-slate-400 hover:text-slate-600' }}">
                        <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span class="text-[10px]">Profil</span>
                    </a>
                @endif
            </div>
            
            <!-- Mobile Safe Area Support -->
            <style>
                .pb-safe { padding-bottom: env(safe-area-inset-bottom); }
            </style>
        </nav>
    </div>

    @stack('modals')
    @stack('scripts')
</body>
</html>