@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-12 gap-5 md:gap-8">

    <!-- Kolom Utama Kiri (Berisi Profil & Status) -->
    <div class="md:col-span-7 lg:col-span-8 space-y-5 md:space-y-8">
        
        <!-- Card Ringkasan Profil & Status -->
        <div class="bg-blue-700 text-white rounded-2xl p-5 shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-xs text-blue-200">Selamat Datang,</p>
                    <h2 class="text-xl md:text-2xl font-bold truncate">{{ auth()->user()->name ?? 'Salsabila Nailafahdi' }}</h2>
                    <p class="text-xs md:text-sm text-blue-100/80">{{ auth()->user()->institution ?? 'Universitas Mataram' }}</p>
                </div>
                <div class="w-14 h-14 md:w-16 md:h-16 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center font-bold text-2xl md:text-3xl border border-white/30">
                    {{ strtoupper(substr(auth()->user()->name ?? 'S', 0, 1)) }}
                </div>
            </div>

            <!-- Real-time Digital Clock -->
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 flex items-center justify-between border border-white/10">
                <div>
                    <p class="text-[10px] md:text-xs uppercase tracking-wider text-blue-200">Waktu Lokal (Wita)</p>
                    <p id="live-clock" class="text-2xl md:text-3xl font-extrabold tracking-tight">00:00:00 Wita</p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] md:text-xs text-blue-200">Tanggal</p>
                    <p class="text-sm md:text-base font-semibold">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Status Presensi Hari Ini -->
        <div class="bg-white rounded-2xl p-5 md:p-6 shadow-sm border border-slate-100 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-xs md:text-sm font-bold uppercase tracking-wider text-slate-500">Status Presensi Hari Ini</h3>
                
                {{-- Status Badge Dinamis --}}
                @if(isset($todayAttendance))
                    @if($todayAttendance->status == 'Hadir')
                        <span class="px-3 py-1.5 text-xs font-semibold bg-emerald-100 text-emerald-700 rounded-full shadow-sm">Hadir</span>
                    @elseif($todayAttendance->status == 'Telat')
                        <span class="px-3 py-1.5 text-xs font-semibold bg-amber-100 text-amber-700 rounded-full shadow-sm">Terlambat</span>
                    @elseif($todayAttendance->status == 'Izin')
                        <span class="px-3 py-1.5 text-xs font-semibold bg-blue-100 text-blue-700 rounded-full shadow-sm">Izin</span>
                    @endif
                @else
                    <span class="px-3 py-1.5 text-xs font-semibold bg-slate-100 text-slate-600 rounded-full shadow-sm">Belum Absen</span>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-4 pt-2">
                <!-- Jam Masuk -->
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 flex items-center space-x-3 md:space-x-4">
                    <div class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium">Jam Masuk</p>
                        <p class="text-lg md:text-xl font-bold text-slate-800">
                            {{ $todayAttendance->time_in ?? '--:--' }}
                        </p>
                        <p class="text-[10px] text-slate-400">Maks. 07:30 WITA</p>
                    </div>
                </div>

                <!-- Jam Pulang -->
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 flex items-center space-x-3 md:space-x-4">
                    <div class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium">Jam Pulang</p>
                        <p class="text-lg md:text-xl font-bold text-slate-800">
                            {{ $todayAttendance->time_out ?? '--:--' }}
                        </p>
                        <p class="text-[10px] text-slate-400">Presensi Keluar</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Samping Kanan (Aksi Cepat & Info) -->
    <div class="md:col-span-5 lg:col-span-4 space-y-5 md:space-y-6">
        
        <!-- Info Radius Geofencing -->
        <div class="bg-amber-50 border border-amber-200/80 rounded-2xl p-4 flex items-start space-x-3 shadow-sm">
            <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div class="text-xs text-amber-800">
                <p class="font-bold md:text-sm">Ketentuan Area Presensi</p>
                <p class="text-[11px] md:text-xs mt-1 text-amber-700 leading-relaxed">
                    Presensi masuk wajib dilakukan di dalam radius Kantor Bapenda NTB sebelum 07:30 Wita. Jika berada di luar lokasi atau terlambat, silakan ajukan perizinan.
                </p>
            </div>
        </div>

        @php
            $isExpired = auth()->user()->end_date && \Carbon\Carbon::today()->toDateString() > auth()->user()->end_date;
        @endphp

        @if($isExpired)
            <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4 flex items-start space-x-3 shadow-sm">
                <div class="w-8 h-8 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div class="text-xs text-rose-800">
                    <p class="font-bold md:text-sm">Masa Magang Telah Selesai</p>
                    <p class="text-[11px] md:text-xs mt-1 text-rose-700 leading-relaxed">
                        Masa magang Anda telah berakhir pada tanggal <strong>{{ \Carbon\Carbon::parse(auth()->user()->end_date)->translatedFormat('d F Y') }}</strong>. Status akun Anda saat ini <strong>Tidak Aktif</strong> dan Anda tidak dapat lagi melakukan presensi.
                    </p>
                </div>
            </div>
        @endif

        <!-- Tombol Aksi Cepat (Quick Actions) -->
        <div class="space-y-3">
            <h3 class="text-xs md:text-sm font-bold uppercase tracking-wider text-slate-500">Menu Aksi Cepat</h3>

            <div class="grid grid-cols-2 gap-3 md:gap-4">
                <!-- Tombol Absen Masuk / Pulang -->
                @if(!$isExpired)
                    <a href="{{ route('intern.attendance.scan') }}" 
                       class="flex flex-col items-center justify-center p-5 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white rounded-2xl shadow-md shadow-blue-200 transition-all text-center">
                        <svg class="w-8 h-8 md:w-10 md:h-10 mb-2 md:mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-xs md:text-sm font-bold">Presensi Cepat</span>
                        <span class="text-[10px] md:text-xs text-blue-100 font-light mt-0.5">Masuk / Pulang</span>
                    </a>
                @else
                    <div class="flex flex-col items-center justify-center p-5 bg-slate-300 text-slate-500 rounded-2xl cursor-not-allowed text-center opacity-70">
                        <svg class="w-8 h-8 md:w-10 md:h-10 mb-2 md:mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-xs md:text-sm font-bold">Presensi Tidak Aktif</span>
                        <span class="text-[10px] md:text-xs text-slate-500 font-light mt-0.5">Magang Selesai</span>
                    </div>
                @endif

                <!-- Tombol Form Izin -->
                <a href="{{ route('intern.permission.create') }}" 
                   class="flex flex-col items-center justify-center p-5 bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white rounded-2xl shadow-md shadow-emerald-200 transition-all text-center">
                    <svg class="w-8 h-8 md:w-10 md:h-10 mb-2 md:mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="text-xs md:text-sm font-bold">Pengajuan Izin</span>
                    <span class="text-[10px] md:text-xs text-emerald-100 font-light mt-0.5">Sakit / Luar Radius</span>
                </a>
            </div>
        </div>

        <!-- Riwayat Singkat Presensi Terakhir -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 space-y-3">
            <div class="flex items-center justify-between">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Riwayat Terakhir</h3>
                <a href="#" class="text-xs text-blue-600 font-semibold hover:underline">Lihat Semua</a>
            </div>

            <div class="divide-y divide-slate-100 text-xs md:text-sm">
                @forelse($recentAttendances ?? [] as $history)
                    <div class="py-3 flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-slate-800">{{ \Carbon\Carbon::parse($history->date)->translatedFormat('l, d M Y') }}</p>
                            <p class="text-[10px] md:text-xs text-slate-400 mt-0.5">Masuk: <span class="font-medium text-slate-600">{{ $history->time_in ?? '-' }}</span> | Pulang: <span class="font-medium text-slate-600">{{ $history->time_out ?? '-' }}</span></p>
                        </div>
                        <div>
                            <span class="px-2.5 py-1 text-[10px] md:text-xs font-bold rounded-md 
                                {{ $history->status == 'Hadir' ? 'bg-emerald-50 text-emerald-700' : ($history->status == 'Telat' ? 'bg-amber-50 text-amber-700' : 'bg-blue-50 text-blue-700') }}">
                                {{ $history->status }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="py-6 text-center text-slate-400 text-xs md:text-sm italic">
                        Belum ada riwayat presensi.
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    // Script Jam Real-Time Waktu Lokal
    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        
        document.getElementById('live-clock').textContent = `${hours}:${minutes}:${seconds} WITA`;
    }

    setInterval(updateClock, 1000);
    updateClock();
</script>
@endpush