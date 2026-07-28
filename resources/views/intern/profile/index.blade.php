@extends('layouts.app')

@section('title', 'Profil')

@section('content')
<div class="space-y-4">

    <!-- Header Card / Header Biodata -->
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 text-center relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-16 bg-gradient-to-r from-blue-600 to-indigo-700"></div>
        
        <div class="relative pt-4">
            <!-- Foto Avatar / Initials -->
            <div class="w-20 h-20 mx-auto rounded-full bg-white p-1 shadow-md mb-3">
                <div class="w-full h-full rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-2xl font-black border border-blue-200">
                    {{ strtoupper(substr(auth()->user()->name ?? 'M', 0, 1)) }}
                </div>
            </div>

            <!-- Nama & Instansi -->
            <h2 class="text-base font-bold text-slate-800">{{ auth()->user()->name ?? 'Anak Magang' }}</h2>
            <p class="text-xs text-slate-500 font-medium">{{ auth()->user()->email ?? 'email@example.com' }}</p>
            
            <div class="mt-3 inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-[11px] font-semibold border border-blue-100">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span>Bapenda NTB — Peserta Magang</span>
            </div>
        </div>
    </div>

    <!-- Card Informasi Detail Biodata -->
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 space-y-3">
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Informasi Akademik & Diri</h3>

        <div class="space-y-2.5 text-xs">
            <!-- NIM / NISN -->
            <div class="flex items-center justify-between py-1.5 border-b border-slate-100">
                <span class="text-slate-500">NIM / NISN</span>
                <span class="font-bold text-slate-800">{{ auth()->user()->nim_nisn ?? '-' }}</span>
            </div>

            <!-- Asal Instansi / Universitas -->
            <div class="flex items-center justify-between py-1.5 border-b border-slate-100">
                <span class="text-slate-500">Asal Sekolah/Kampus</span>
                <span class="font-bold text-slate-800">{{ auth()->user()->institution ?? '-' }}</span>
            </div>

            <!-- Program Studi / Jurusan -->
            <div class="flex items-center justify-between py-1.5 border-b border-slate-100">
                <span class="text-slate-500">Jurusan / Prodi</span>
                <span class="font-bold text-slate-800">{{ auth()->user()->department ?? 'Teknik Informatika' }}</span>
            </div>

            <!-- Tanggal Mulai Magang -->
            <div class="flex items-center justify-between py-1.5">
                <span class="text-slate-500">Status Akun</span>
                <span class="px-2 py-0.5 text-[10px] bg-emerald-100 text-emerald-700 font-bold rounded-md">Aktif</span>
            </div>
        </div>
    </div>

    <!-- Form Ubah Password -->
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 space-y-3">
        <div class="flex items-center space-x-2">
            <div class="p-1.5 bg-amber-100 text-amber-600 rounded-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Keamanan & Password</h3>
        </div>

        <form action="{{ route('intern.profile.update_password') }}" method="POST" class="space-y-3 pt-1">
            @csrf
            @method('PUT')

            <!-- Password Saat Ini -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Password Saat Ini</label>
                <input type="password" name="current_password" required
                       class="w-full text-xs border border-slate-200 rounded-xl p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="••••••••">
                @error('current_password')
                    <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password Baru -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Password Baru</label>
                <input type="password" name="password" required
                       class="w-full text-xs border border-slate-200 rounded-xl p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Minimal 8 karakter">
                @error('password')
                    <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Konfirmasi Password Baru -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" required
                       class="w-full text-xs border border-slate-200 rounded-xl p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Ulangi password baru">
            </div>

            <button type="submit" 
                    class="w-full py-2.5 bg-slate-800 hover:bg-slate-900 active:scale-95 text-white text-xs font-bold rounded-xl transition-all shadow-md">
                Perbarui Password
            </button>
        </form>
    </div>

    <!-- Tombol Logout -->
    <div class="pt-2">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" 
                    class="w-full py-3 bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold rounded-2xl border border-rose-200/80 text-xs flex items-center justify-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span>Keluar dari Akun (Logout)</span>
            </button>
        </form>
    </div>

</div>
@endsection