@extends('layouts.app')

@section('title', 'Riwayat Hari Kerja')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800 uppercase">Riwayat Hari Kerja</h1>
            <p class="text-sm text-slate-500">Catatan presensi Anda bulan ini.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3 w-full md:w-auto justify-between md:justify-end">
            <!-- Tombol Cetak PDF Presensi Magang -->
            <a href="{{ route('intern.attendance.pdf') }}" target="_blank" 
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-bold rounded-2xl shadow-md shadow-blue-200 transition-all text-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Cetak Riwayat Presensi</span>
            </a>

            <!-- Month Navigation -->
            <div class="flex items-center gap-3 bg-slate-50 p-1.5 rounded-xl border border-slate-100">
                @php
                    $prevMonthNum = $month - 1 > 0 ? $month - 1 : 12;
                    $prevYearNum = $month - 1 > 0 ? $year : $year - 1;
                    $prevMonthDate = \Carbon\Carbon::createFromDate($prevYearNum, $prevMonthNum, 1);
                @endphp
                
                @if($prevMonthDate->gte($startDate))
                    <a href="{{ route('intern.attendance.index', ['month' => str_pad($prevMonthNum, 2, '0', STR_PAD_LEFT), 'year' => $prevYearNum]) }}" class="p-2 text-slate-500 hover:text-blue-600 hover:bg-white rounded-lg transition-all shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                @else
                    <div class="p-2 text-slate-300 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </div>
                @endif
                <span class="font-bold text-slate-800 min-w-[130px] text-center text-xs sm:text-sm uppercase tracking-wide">
                    Bulan {{ \Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y') }}
                </span>
                <a href="{{ route('intern.attendance.index', ['month' => str_pad($month + 1 > 12 ? 1 : $month + 1, 2, '0', STR_PAD_LEFT), 'year' => $month + 1 > 12 ? $year + 1 : $year]) }}" class="p-2 text-slate-500 hover:text-blue-600 hover:bg-white rounded-lg transition-all shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Attendance Cards -->
    <div class="space-y-4">
        @forelse($attendances as $attendance)
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:shadow-md transition-shadow relative overflow-hidden">
                <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $attendance->time_out ? 'bg-emerald-500' : 'bg-amber-500' }}"></div>
                
                <div class="flex items-center gap-4 pl-3 w-full sm:w-auto">
                    <div class="w-10 h-10 rounded-full {{ $attendance->time_out ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }} flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($attendance->time_out)
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            @endif
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-lg">{{ \Carbon\Carbon::parse($attendance->date)->translatedFormat('d F Y') }}</h3>
                    </div>
                </div>
                
                <div class="flex items-center justify-between sm:justify-end gap-6 md:gap-12 pr-2 w-full sm:w-auto pl-3 sm:pl-0">
                    <div class="text-center">
                        <p class="text-xs font-semibold text-slate-500 mb-1">Masuk</p>
                        <p class="font-bold text-slate-800 text-lg">
                            {{ \Carbon\Carbon::parse($attendance->time_in)->format('g:i A') }}
                        </p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs font-semibold text-slate-500 mb-1">Jam Pulang</p>
                        <p class="font-bold text-slate-800 text-lg {{ !$attendance->time_out ? 'text-slate-300' : '' }}">
                            {{ $attendance->time_out ? \Carbon\Carbon::parse($attendance->time_out)->format('g:i A') : '--:-- --' }}
                        </p>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl p-12 text-center shadow-sm border border-slate-100">
                <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-4 text-slate-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="font-bold text-slate-700 text-lg mb-1">Belum Ada Riwayat</h3>
                <p class="text-slate-500 text-sm">Tidak ada catatan presensi pada bulan ini.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
