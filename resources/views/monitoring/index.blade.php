<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Monitoring Presensi Peserta Magang - Bapenda NTB</title>
    <link rel="icon" href="{{ asset('images/Logo.png') }}" type="image/png">

    <!-- Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS & JS - langsung dari build agar tidak perlu Vite dev server -->
    @php
        $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
        $cssFile = asset('build/' . $manifest['resources/css/app.css']['file']);
        $jsFile = asset('build/' . $manifest['resources/js/app.js']['file']);
    @endphp
    <link rel="stylesheet" href="{{ $cssFile }}">
    <script src="{{ $jsFile }}" defer></script>

    <style>
        body {
            font-family: 'Outfit', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }
        /* Hero and stats customization to match design */
        .hero-gradient {
            background-image: linear-gradient(120deg, #0066ff 0%, #0b5ed7 50%, #0a4ad0 100%);
        }
        .hero-stat {
            border: 1px solid rgba(255,255,255,0.22);
            backdrop-filter: blur(6px);
            box-shadow: 0 6px 18px rgba(10, 46, 120, 0.12);
        }
        .login-clock {
            font-weight:600;
            color:#0f172a;
            background:#f1f5f9;
            padding:6px 10px;
            border-radius:9999px;
            box-shadow:0 4px 10px rgba(2,6,23,0.06);
            font-size:12px;
            margin-right:0.75rem;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="min-h-screen bg-[#f8fafc] text-slate-800 antialiased p-4 sm:p-6 lg:p-8 flex flex-col justify-between">

    <!-- Main Container -->
    <div class="max-w-7xl mx-auto w-full space-y-6">

        <!-- 1. Top Navbar Header (Space-Between Layout: Brand, Search, Actions) -->
        <header class="bg-white rounded-2xl px-6 py-4 shadow-sm border border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <!-- Left: Logo & Brand Info -->
            <div class="flex items-center gap-5 sm:gap-6 shrink-0">
                <div class="w-11 h-11 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center p-2 shadow-sm shrink-0">
                    <img src="{{ asset('images/Logo.png') }}" alt="Logo Bapenda NTB" class="w-full h-full object-contain">
                </div>
                <div>
                    <h1 class="text-base sm:text-lg font-bold text-slate-800 leading-tight tracking-wide">Badan Pendapatan Daerah Provinsi Nusa Tenggara Barat</h1>
                    <p class="text-xs sm:text-sm text-slate-500 font-medium tracking-wide">Jalan Majapahit No. 17 Mataram</p>
                </div>
            </div>
            
            <!-- Right: Live clock + Login Button -->
            <div class="flex items-center shrink-0 justify-end">
                <div id="live-clock" class="login-clock mr-2">--:--:-- WITA</div>
                <a href="{{ route('login') }}"
                   class="h-10 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs sm:text-sm font-semibold shadow-md shadow-blue-500/20 transition-all active:scale-95 flex items-center justify-center gap-2 shrink-0"
                   style="padding-left: 1.5rem !important; padding-right: 1.5rem !important;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    <span>Login</span>
                </a>
            </div>
        </header>

        <!-- 2. Hero Banner (Solid Single Blue Color) -->
        <div class="p-6 sm:p-7 hero-gradient rounded-3xl shadow-xl shadow-blue-600/20 text-white relative overflow-hidden flex flex-col xl:flex-row xl:items-center justify-between gap-6">
            <div class="relative z-10">
                <h2 class="text-xl sm:text-2xl font-black mb-1 tracking-tight text-white flex items-center gap-3">
                    <svg class="w-7 h-7 sm:w-8 sm:h-8 text-blue-200 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Riwayat Presensi Hari ini</span><span class="mx-1 font-normal opacity-90">-</span><span id="banner-date">{{ $formattedDate }}</span>
                </h2>
            </div>

            <!-- 5 Stats Counters (Uniform Equal Dimensions & Distinct Gap) -->
            <div class="relative z-10 grid grid-cols-2 sm:grid-cols-5 gap-3 sm:gap-4 shrink-0 w-full xl:w-auto">
                <!-- 1. Total Peserta -->
                <div class="hero-stat bg-white/10 p-3 sm:p-3.5 rounded-2xl text-center flex flex-col items-center justify-center h-20 min-w-[90px] sm:min-w-[105px]">
                    <span class="text-[10px] sm:text-[11px] text-blue-200 uppercase tracking-wider block font-bold">Total Peserta</span>
                    <span id="stat-total" class="text-lg sm:text-xl font-black text-white block mt-1 leading-none">{{ $totalInterns }}</span>
                </div>
                <!-- 2. Hadir -->
                <div class="hero-stat bg-emerald-500/20 p-3 sm:p-3.5 rounded-2xl text-center flex flex-col items-center justify-center h-20 min-w-[90px] sm:min-w-[105px]">
                    <span class="text-[10px] sm:text-[11px] text-emerald-200 uppercase tracking-wider block font-bold">Hadir</span>
                    <span id="stat-hadir" class="text-lg sm:text-xl font-black text-emerald-100 block mt-1 leading-none">{{ $totalHadir }}</span>
                </div>
                <!-- 3. Sakit -->
                <div class="hero-stat bg-amber-500/20 p-3 sm:p-3.5 rounded-2xl text-center flex flex-col items-center justify-center h-20 min-w-[90px] sm:min-w-[105px]">
                    <span class="text-[10px] sm:text-[11px] text-amber-200 uppercase tracking-wider block font-bold">Sakit</span>
                    <span id="stat-sakit" class="text-lg sm:text-xl font-black text-amber-100 block mt-1 leading-none">{{ $totalSakit }}</span>
                </div>
                <!-- 4. Izin -->
                <div class="hero-stat bg-blue-400/20 p-3 sm:p-3.5 rounded-2xl text-center flex flex-col items-center justify-center h-20 min-w-[90px] sm:min-w-[105px]">
                    <span class="text-[10px] sm:text-[11px] text-blue-200 uppercase tracking-wider block font-bold">Izin</span>
                    <span id="stat-izin" class="text-lg sm:text-xl font-black text-blue-100 block mt-1 leading-none">{{ $totalIzin }}</span>
                </div>
                <!-- 5. Tanpa Ket. -->
                <div class="hero-stat bg-rose-500/20 p-3 sm:p-3.5 rounded-2xl text-center flex flex-col items-center justify-center h-20 min-w-[90px] sm:min-w-[105px] shadow-sm col-span-2 sm:col-span-1">
                    <span class="text-[10px] sm:text-[11px] text-rose-200 uppercase tracking-wider block font-bold">Tanpa Ket.</span>
                    <span id="stat-absent" class="text-lg sm:text-xl font-black text-rose-100 block mt-1 leading-none">{{ $totalAbsent }}</span>
                </div>
            </div>
        </div>

        <!-- 3. Dual Tables Grid (Side by side on desktop, stacked on mobile) -->
        <div class="grid grid-cols-1 gap-6 items-start">

            <!-- TABEL KIRI: Peserta Hadir (Top Border Hijau) -->
            <div class="w-full bg-white rounded-2xl shadow-sm border border-slate-100 border-t-4 border-t-emerald-500 overflow-hidden flex flex-col">
                <!-- Card Header -->
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-slate-800">Hadir</h3>
                        </div>
                    </div>
                    <span id="badge-present-count" class="inline-flex items-center justify-center rounded-full text-xs sm:text-sm font-bold bg-emerald-100 text-emerald-800 whitespace-nowrap shadow-sm shrink-0"
                          style="padding-left: 1.25rem !important; padding-right: 1.25rem !important; padding-top: 0.375rem !important; padding-bottom: 0.375rem !important;">
                        {{ $totalPresent }}
                    </span>
                </div>

                <!-- Table Container -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[580px]" id="table-present">
                        <thead>
                            <tr class="bg-slate-50/80 text-slate-700 text-xs uppercase tracking-wider font-bold border-b border-slate-100">
                                <th class="py-3.5 px-4 w-12 text-center">NO.</th>
                                <th class="py-3.5 px-4">Asal Instansi</th>
                                <th class="py-3.5 px-4">Nama</th>
                                <th class="py-3.5 px-4 text-center">Jam</th>
                                <th class="py-3.5 px-4 text-center">Jam Pulang</th>
                                <th class="py-3.5 px-4 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs sm:text-sm text-slate-700" id="tbody-present">
                            @forelse($presentList as $index => $item)
                                <tr class="hover:bg-slate-50/80 transition-colors row-item" data-name="{{ strtolower($item['name']) }}" data-inst="{{ strtolower($item['institution']) }}">
                                    <td class="py-3.5 px-4 text-center font-semibold text-slate-400 row-index">{{ $index + 1 }}.</td>
                                    <td class="py-3.5 px-4 font-medium text-slate-600 text-xs sm:text-sm">{{ $item['institution'] }}</td>
                                    <td class="py-3.5 px-4 font-semibold text-slate-800">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold shrink-0 text-xs" style="width: 32px; height: 32px; min-width: 32px; min-height: 32px;">
                                                {{ strtoupper(substr($item['name'], 0, 1)) }}
                                            </div>
                                            <div>
                                                                <div class="leading-tight text-slate-900 font-bold">{{ $item['name'] }}</div>
                                                @if($item['major'])
                                                    <div class="text-[11px] text-slate-400 font-normal mt-0.5">{{ $item['major'] }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 text-center font-mono font-semibold text-slate-700 whitespace-nowrap">{{ $item['time_in'] }}</td>
                                    <td class="py-3.5 px-4 text-center font-mono text-slate-500 whitespace-nowrap">{{ $item['time_out'] }}</td>
                                    <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                        @if($item['status_type'] === 'masuk')
                                            <span class="inline-flex items-center justify-center px-3.5 py-1 rounded-full text-xs font-bold bg-[#10b981] text-white shadow-sm shadow-emerald-500/20">
                                                Masuk
                                            </span>
                                        @elseif($item['status_type'] === 'sakit')
                                            <span class="inline-flex items-center justify-center px-3.5 py-1 rounded-full text-xs font-bold bg-[#f59e0b] text-white shadow-sm shadow-amber-500/20">
                                                Sakit
                                            </span>
                                        @elseif($item['status_type'] === 'izin')
                                            <span class="inline-flex items-center justify-center px-3.5 py-1 rounded-full text-xs font-bold bg-[#3b82f6] text-white shadow-sm shadow-blue-500/20">
                                                Izin
                                            </span>
                                        @elseif($item['status_type'] === 'terlambat')
                                            <span class="inline-flex items-center justify-center px-3.5 py-1 rounded-full text-xs font-bold bg-[#f97316] text-white shadow-sm shadow-orange-500/20">
                                                Terlambat
                                            </span>
                                        @else
                                            <span class="inline-flex items-center justify-center px-3.5 py-1 rounded-full text-xs font-bold bg-slate-500 text-white shadow-sm">
                                                {{ $item['status'] }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr id="empty-present-row">
                                    <td colspan="6" class="py-4 px-4 text-center font-bold text-slate-700 text-sm">
                                        Belum ada peserta yang hadir hari ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TABEL: Izin / Sakit (mid card) -->
            <div class="w-full bg-white rounded-2xl shadow-sm border border-slate-100 border-t-4 border-t-blue-500 overflow-hidden flex flex-col">
                <!-- Card Header -->
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-slate-800">Izin / Sakit</h3>
                        </div>
                    </div>
                    <span id="badge-permission-count" class="inline-flex items-center justify-center rounded-full text-xs sm:text-sm font-bold bg-blue-100 text-blue-800 whitespace-nowrap shadow-sm shrink-0"
                          style="padding-left: 1.25rem !important; padding-right: 1.25rem !important; padding-top: 0.375rem !important; padding-bottom: 0.375rem !important;">
                        {{ count($permissionList ?? []) }}
                    </span>
                </div>

                <!-- Table Container -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[580px]" id="table-permission">
                        <thead>
                            <tr class="bg-slate-50/80 text-slate-700 text-xs uppercase tracking-wider font-bold border-b border-slate-100">
                                <th class="py-3.5 px-4 w-12 text-center">NO.</th>
                                <th class="py-3.5 px-4">Asal Instansi</th>
                                <th class="py-3.5 px-4">Nama</th>
                                <th class="py-3.5 px-4 text-center">Jam</th>
                                <th class="py-3.5 px-4 text-center">Jam Pulang</th>
                                <th class="py-3.5 px-4 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs sm:text-sm text-slate-700" id="tbody-permission">
                            @forelse($permissionList as $index => $item)
                                <tr class="hover:bg-slate-50/80 transition-colors row-item" data-name="{{ strtolower($item['name']) }}" data-inst="{{ strtolower($item['institution']) }}">
                                    <td class="py-3.5 px-4 text-center font-semibold text-slate-400 row-index">{{ $index + 1 }}.</td>
                                    <td class="py-3.5 px-4 font-medium text-slate-600 text-xs sm:text-sm">{{ $item['institution'] }}</td>
                                    <td class="py-3.5 px-4 font-semibold text-slate-800">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold shrink-0 text-xs" style="width: 32px; height: 32px; min-width: 32px; min-height: 32px;">
                                                {{ strtoupper(substr($item['name'], 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="leading-tight text-slate-900 font-bold">{{ $item['name'] }}</div>
                                                @if($item['major'])
                                                    <div class="text-[11px] text-slate-400 font-normal mt-0.5 normal-case">{{ $item['major'] }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 text-center font-mono font-semibold text-slate-700 whitespace-nowrap">{{ $item['time_in'] ?? '-' }}</td>
                                    <td class="py-3.5 px-4 text-center font-mono text-slate-500 whitespace-nowrap">{{ $item['time_out'] ?? '-' }}</td>
                                    <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                        @if($item['status_type'] === 'sakit')
                                            <span class="inline-flex items-center justify-center px-3.5 py-1 rounded-full text-xs font-bold bg-[#f59e0b] text-white shadow-sm shadow-amber-500/20">Sakit</span>
                                        @elseif($item['status_type'] === 'izin')
                                            <span class="inline-flex items-center justify-center px-3.5 py-1 rounded-full text-xs font-bold bg-[#3b82f6] text-white shadow-sm shadow-blue-500/20">Izin</span>
                                        @else
                                            <span class="inline-flex items-center justify-center px-3.5 py-1 rounded-full text-xs font-bold bg-slate-500 text-white shadow-sm">{{ $item['status'] }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr id="empty-permission-row">
                                    <td colspan="6" class="py-4 px-4 text-center font-bold text-slate-700 text-sm">Tidak ada izin/sakit hari ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TABEL KANAN: Peserta Tanpa Keterangan (Top Border Merah) -->
            <div class="w-full bg-white rounded-2xl shadow-sm border border-slate-100 border-t-4 border-t-rose-500 overflow-hidden flex flex-col">
                <!-- Card Header -->
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-rose-100 flex items-center justify-center text-rose-700 font-bold shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-slate-800">Peserta Tanpa Keterangan</h3>
                        </div>
                    </div>
                    <span id="badge-absent-count" class="inline-flex items-center justify-center rounded-full text-xs sm:text-sm font-bold bg-rose-100 text-rose-800 whitespace-nowrap shadow-sm shrink-0"
                          style="padding-left: 1.25rem !important; padding-right: 1.25rem !important; padding-top: 0.375rem !important; padding-bottom: 0.375rem !important;">
                        {{ $totalAbsent }} Peserta
                    </span>
                </div>

                <!-- Table Container -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[320px]" id="table-absent">
                        <thead>
                            <tr class="bg-slate-50/80 text-slate-700 text-xs uppercase tracking-wider font-bold border-b border-slate-100">
                                <th class="py-3.5 px-4 w-12 text-center">NO.</th>
                                <th class="py-3.5 px-4">Asal Instansi</th>
                                <th class="py-3.5 px-4">Nama Peserta</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs sm:text-sm text-slate-700" id="tbody-absent">
                            @forelse($absentList as $index => $item)
                                <tr class="hover:bg-slate-50/80 transition-colors row-item" data-name="{{ strtolower($item['name']) }}" data-inst="{{ strtolower($item['institution']) }}">
                                    <td class="py-3.5 px-4 text-center font-semibold text-slate-400 row-index">{{ $index + 1 }}.</td>
                                    <td class="py-3.5 px-4 font-medium text-slate-600 text-xs sm:text-sm">{{ $item['institution'] }}</td>
                                    <td class="py-3.5 px-4 font-semibold text-slate-800">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold shrink-0 text-xs" style="width: 32px; height: 32px; min-width: 32px; min-height: 32px;">
                                                {{ strtoupper(substr($item['name'], 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="leading-tight uppercase tracking-tight text-sm font-bold text-slate-900">{{ $item['name'] }}</div>
                                                @if($item['major'])
                                                    <div class="text-[11px] text-slate-400 font-normal mt-0.5 normal-case">{{ $item['major'] }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr id="empty-absent-row">
                                    <td colspan="3" class="py-4 px-4 text-center font-bold text-slate-700 text-sm">
                                        Semua peserta telah terdata/hadir hari ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

    <!-- Footer -->
    <footer class="mt-8 text-center text-xs text-slate-400 font-medium">
        &copy; {{ date('Y') }} Badan Pendapatan Daerah Provinsi Nusa Tenggara Barat
    </footer>

    <!-- Scripts -->
    <script>
        // 1. Live Digital Clock (WITA)
        function updateLiveClock() {
            const now = new Date();
            const options = {
                timeZone: 'Asia/Makassar',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            };
            const timeString = new Intl.DateTimeFormat('id-ID', options).format(now);
            const clockEl = document.getElementById('live-clock');
            if (clockEl) {
                clockEl.textContent = timeString + ' WITA';
            }
        }
        setInterval(updateLiveClock, 1000);
        updateLiveClock();

        // 2. Fullscreen Toggle
        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => {
                    console.log(`Error attempting to enable full-screen mode: ${err.message}`);
                });
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
        }

        // 3. Search Filter for Both Tables
        function filterTables() {
            const query = (document.getElementById('searchInput').value || '').toLowerCase().trim();
            
            // Filter Left Table
            const presentRows = document.querySelectorAll('#tbody-present .row-item');
            let visiblePresentCount = 0;
            presentRows.forEach(row => {
                const name = row.getAttribute('data-name') || '';
                const inst = row.getAttribute('data-inst') || '';
                if (name.includes(query) || inst.includes(query)) {
                    row.style.display = '';
                    visiblePresentCount++;
                    const indexEl = row.querySelector('.row-index');
                    if (indexEl) indexEl.textContent = visiblePresentCount + '.';
                } else {
                    row.style.display = 'none';
                }
            });

            // Filter Right Table
            const absentRows = document.querySelectorAll('#tbody-absent .row-item');
            let visibleAbsentCount = 0;
            absentRows.forEach(row => {
                const name = row.getAttribute('data-name') || '';
                const inst = row.getAttribute('data-inst') || '';
                if (name.includes(query) || inst.includes(query)) {
                    row.style.display = '';
                    visibleAbsentCount++;
                    const indexEl = row.querySelector('.row-index');
                    if (indexEl) indexEl.textContent = visibleAbsentCount + '.';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // 4. Auto-Refresh Data from Server via AJAX
        let isFetching = false;
        async function fetchLatestData(manual = false) {
            if (isFetching) return;
            isFetching = true;

            const refreshIcon = document.getElementById('refresh-icon');
            if (refreshIcon) refreshIcon.classList.add('animate-spin');

            try {
                const response = await fetch("{{ route('monitoring.data') }}", {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) throw new Error('Network response was not ok');

                const data = await response.json();

                // Update Header Stats
                const bannerDate = document.getElementById('banner-date');
                if (bannerDate) bannerDate.textContent = data.formattedDate;
                document.getElementById('stat-total').textContent = data.totalInterns;
                document.getElementById('stat-hadir').textContent = data.totalHadir;
                document.getElementById('stat-sakit').textContent = data.totalSakit;
                document.getElementById('stat-izin').textContent = data.totalIzin;
                document.getElementById('stat-absent').textContent = data.totalAbsent;
                document.getElementById('badge-present-count').textContent = data.totalPresent;
                document.getElementById('badge-absent-count').textContent = data.totalAbsent;
                if (document.getElementById('badge-permission-count')) document.getElementById('badge-permission-count').textContent = (data.permissionList || []).length;

                // Update Left Table (Peserta Hadir)
                const tbodyPresent = document.getElementById('tbody-present');
                if (data.presentList.length === 0) {
                    tbodyPresent.innerHTML = `
                        <tr id="empty-present-row">
                            <td colspan="6" class="py-4 px-4 text-center font-bold text-slate-700 text-sm">
                                Belum ada peserta yang hadir hari ini.
                            </td>
                        </tr>`;
                } else {
                    let htmlPresent = '';
                    data.presentList.forEach((item, idx) => {
                        let badgeHtml = '';
                        if (item.status_type === 'masuk') {
                            badgeHtml = `<span class="inline-flex items-center justify-center px-3.5 py-1 rounded-full text-xs font-bold bg-[#10b981] text-white shadow-sm shadow-emerald-500/20">Masuk</span>`;
                        } else if (item.status_type === 'sakit') {
                            badgeHtml = `<span class="inline-flex items-center justify-center px-3.5 py-1 rounded-full text-xs font-bold bg-[#f59e0b] text-white shadow-sm shadow-amber-500/20">Sakit</span>`;
                        } else if (item.status_type === 'izin') {
                            badgeHtml = `<span class="inline-flex items-center justify-center px-3.5 py-1 rounded-full text-xs font-bold bg-[#3b82f6] text-white shadow-sm shadow-blue-500/20">Izin</span>`;
                        } else if (item.status_type === 'terlambat') {
                            badgeHtml = `<span class="inline-flex items-center justify-center px-3.5 py-1 rounded-full text-xs font-bold bg-[#f97316] text-white shadow-sm shadow-orange-500/20">Terlambat</span>`;
                        } else {
                            badgeHtml = `<span class="inline-flex items-center justify-center px-3.5 py-1 rounded-full text-xs font-bold bg-slate-500 text-white shadow-sm">${item.status}</span>`;
                        }

                        let majorHtml = item.major ? `<div class="text-[11px] text-slate-400 font-normal mt-0.5">${item.major}</div>` : '';
                        let initial = item.name ? item.name.charAt(0).toUpperCase() : 'P';

                        htmlPresent += `
                            <tr class="hover:bg-slate-50/80 transition-colors row-item" data-name="${item.name.toLowerCase()}" data-inst="${item.institution.toLowerCase()}">
                                <td class="py-3.5 px-4 text-center font-semibold text-slate-400 row-index">${idx + 1}.</td>
                                <td class="py-3.5 px-4 font-medium text-slate-600 text-xs sm:text-sm">${item.institution}</td>
                                <td class="py-3.5 px-4 font-semibold text-slate-800">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold shrink-0 text-xs" style="width: 32px; height: 32px; min-width: 32px; min-height: 32px;">
                                            ${initial}
                                        </div>
                                        <div>
                                            <div class="leading-tight text-slate-900 font-bold">${item.name}</div>
                                            ${majorHtml}
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-center font-mono font-semibold text-slate-700 whitespace-nowrap">${item.time_in}</td>
                                <td class="py-3.5 px-4 text-center font-mono text-slate-500 whitespace-nowrap">${item.time_out}</td>
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">${badgeHtml}</td>
                            </tr>`;
                    });
                    tbodyPresent.innerHTML = htmlPresent;
                }

                // Update Permission Table (Izin / Sakit)
                const tbodyPermission = document.getElementById('tbody-permission');
                if (tbodyPermission) {
                    if (!data.permissionList || data.permissionList.length === 0) {
                        tbodyPermission.innerHTML = `
                        <tr id="empty-permission-row">
                            <td colspan="6" class="py-4 px-4 text-center font-bold text-slate-700 text-sm">Tidak ada izin/sakit hari ini.</td>
                        </tr>`;
                    } else {
                        let htmlPermission = '';
                        data.permissionList.forEach((item, idx) => {
                            let badgeHtml = '';
                            if (item.status_type === 'sakit') {
                                badgeHtml = `<span class="inline-flex items-center justify-center px-3.5 py-1 rounded-full text-xs font-bold bg-[#f59e0b] text-white shadow-sm shadow-amber-500/20">Sakit</span>`;
                            } else if (item.status_type === 'izin') {
                                badgeHtml = `<span class="inline-flex items-center justify-center px-3.5 py-1 rounded-full text-xs font-bold bg-[#3b82f6] text-white shadow-sm shadow-blue-500/20">Izin</span>`;
                            } else {
                                badgeHtml = `<span class="inline-flex items-center justify-center px-3.5 py-1 rounded-full text-xs font-bold bg-slate-500 text-white shadow-sm">${item.status}</span>`;
                            }

                            let majorHtml = item.major ? `<div class="text-[11px] text-slate-400 font-normal mt-0.5 normal-case">${item.major}</div>` : '';
                            let initial = item.name ? item.name.charAt(0).toUpperCase() : 'P';

                            htmlPermission += `
                            <tr class="hover:bg-slate-50/80 transition-colors row-item" data-name="${item.name.toLowerCase()}" data-inst="${item.institution.toLowerCase()}">
                                <td class="py-3.5 px-4 text-center font-semibold text-slate-400 row-index">${idx + 1}.</td>
                                <td class="py-3.5 px-4 font-medium text-slate-600 text-xs sm:text-sm">${item.institution}</td>
                                <td class="py-3.5 px-4 font-semibold text-slate-800">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold shrink-0 text-xs" style="width: 32px; height: 32px; min-width: 32px; min-height: 32px;">
                                            ${initial}
                                        </div>
                                        <div>
                                            <div class="leading-tight text-slate-900 font-bold">${item.name}</div>
                                            ${majorHtml}
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-center font-mono font-semibold text-slate-700 whitespace-nowrap">${item.time_in || '-'}</td>
                                <td class="py-3.5 px-4 text-center font-mono text-slate-500 whitespace-nowrap">${item.time_out || '-'}</td>
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">${badgeHtml}</td>
                            </tr>`;
                        });
                        tbodyPermission.innerHTML = htmlPermission;
                    }
                }

                // Update Right Table (Peserta Tanpa Keterangan)
                const tbodyAbsent = document.getElementById('tbody-absent');
                if (data.absentList.length === 0) {
                    tbodyAbsent.innerHTML = `
                        <tr id="empty-absent-row">
                            <td colspan="3" class="py-4 px-4 text-center font-bold text-slate-700 text-sm">
                                Semua peserta telah terdata/hadir hari ini.
                            </td>
                        </tr>`;
                } else {
                    let htmlAbsent = '';
                    data.absentList.forEach((item, idx) => {
                        let majorHtml = item.major ? `<div class="text-[11px] text-slate-400 font-normal mt-0.5 normal-case">${item.major}</div>` : '';
                        let initial = item.name ? item.name.charAt(0).toUpperCase() : 'P';
                        htmlAbsent += `
                            <tr class="hover:bg-slate-50/80 transition-colors row-item" data-name="${item.name.toLowerCase()}" data-inst="${item.institution.toLowerCase()}">
                                <td class="py-3.5 px-4 text-center font-semibold text-slate-400 row-index">${idx + 1}.</td>
                                <td class="py-3.5 px-4 font-medium text-slate-600 text-xs sm:text-sm">${item.institution}</td>
                                <td class="py-3.5 px-4 font-semibold text-slate-800">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold shrink-0 text-xs" style="width: 32px; height: 32px; min-width: 32px; min-height: 32px;">
                                            ${initial}
                                        </div>
                                        <div>
                                            <div class="leading-tight uppercase tracking-tight text-sm font-bold text-slate-900">${item.name}</div>
                                            ${majorHtml}
                                        </div>
                                    </div>
                                </td>
                            </tr>`;
                    });
                    tbodyAbsent.innerHTML = htmlAbsent;
                }

                // Re-apply search filter
                filterTables();

            } catch (error) {
                console.error("Gagal memperbarui data presensi:", error);
            } finally {
                isFetching = false;
                if (refreshIcon) refreshIcon.classList.remove('animate-spin');
            }
        }

        // Auto-refresh interval every 30 seconds
        setInterval(() => fetchLatestData(false), 30000);
    </script>
</body>
</html>
