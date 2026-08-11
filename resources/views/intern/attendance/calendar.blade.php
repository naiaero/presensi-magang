@extends('layouts.app')

@section('title', 'Riwayat Kehadiran')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800">Riwayat Kehadiran</h1>
            <p class="text-sm text-slate-500">Lihat riwayat kehadiran Anda pada bulan ini.</p>
        </div>
        <div class="flex items-center gap-3 bg-slate-50 p-2 rounded-xl">
            @php
                $prevMonthNum = $month - 1 > 0 ? $month - 1 : 12;
                $prevYearNum = $month - 1 > 0 ? $year : $year - 1;
                $prevMonthDate = \Carbon\Carbon::createFromDate($prevYearNum, $prevMonthNum, 1);
            @endphp
            
            @if($prevMonthDate->gte($startDate))
                <a href="{{ route('intern.attendance.index', ['month' => str_pad($prevMonthNum, 2, '0', STR_PAD_LEFT), 'year' => $prevYearNum]) }}" class="p-2 text-slate-500 hover:text-blue-600 hover:bg-white rounded-lg transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
            @else
                <div class="p-2 text-slate-300 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </div>
            @endif
            <span class="font-bold text-slate-800 min-w-[100px] text-center">
                {{ \Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y') }}
            </span>
            <a href="{{ route('intern.attendance.index', ['month' => str_pad($month + 1 > 12 ? 1 : $month + 1, 2, '0', STR_PAD_LEFT), 'year' => $month + 1 > 12 ? $year + 1 : $year]) }}" class="p-2 text-slate-500 hover:text-blue-600 hover:bg-white rounded-lg transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
    </div>

    <!-- Legends -->
    <div class="flex flex-wrap gap-3">
        <span class="flex items-center gap-2 text-xs font-semibold px-4 py-2 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-100 shadow-sm">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Hadir
        </span>
        <span class="flex items-center gap-2 text-xs font-semibold px-4 py-2 rounded-xl bg-amber-50 text-amber-700 border border-amber-100 shadow-sm">
            <span class="w-2 h-2 rounded-full bg-amber-500"></span> Izin
        </span>
        <span class="flex items-center gap-2 text-xs font-semibold px-4 py-2 rounded-xl bg-rose-50 text-rose-700 border border-rose-100 shadow-sm">
            <span class="w-2 h-2 rounded-full bg-rose-500"></span> Alpa
        </span>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 overflow-hidden">
        <div class="calendar-grid mb-4 text-center text-sm font-bold text-slate-400 uppercase tracking-wider">
            <div>Min</div><div>Sen</div><div>Sel</div><div>Rabu</div><div>Kam</div><div>Jum</div><div>Sab</div>
        </div>
        
        <div class="calendar-grid text-sm" id="calendar-grid">
            @php
                $daysInMonth = \Carbon\Carbon::createFromDate($year, $month, 1)->daysInMonth;
                $firstDayIndex = \Carbon\Carbon::createFromDate($year, $month, 1)->dayOfWeek;
            @endphp
            
            @for ($i = 0; $i < $firstDayIndex; $i++)
                <div class="min-h-[80px] rounded-xl"></div>
            @endfor
            
            @for ($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $dateStr = sprintf('%s-%02d-%02d', $year, $month, $day);
                    $att = $attendances->firstWhere('date', $dateStr);
                    $perm = $permissions->firstWhere('date', $dateStr);
                    $isPast = \Carbon\Carbon::parse($dateStr, 'Asia/Makassar')->startOfDay()->lt(\Carbon\Carbon::now('Asia/Makassar')->startOfDay());
                    $isWeekend = \Carbon\Carbon::parse($dateStr, 'Asia/Makassar')->isWeekend();
                    $isToday = \Carbon\Carbon::parse($dateStr, 'Asia/Makassar')->isToday();
                    
                    $isBeforeAccount = isset($userCreatedAt) && $dateStr < $userCreatedAt;
                    $isApprovedLate = ($perm && $perm->status === 'Approved' && $perm->reason_option === 'Terlambat / Di luar Radius Kantor');
                    $isApprovedIzin = ($perm && $perm->status === 'Approved' && $perm->reason_option !== 'Terlambat / Di luar Radius Kantor');
                    
                    $isHadir = $att || $isApprovedLate;
                    $isIzin = !$isHadir && $isApprovedIzin;
                    $isAlpa = !$isHadir && !$isIzin && !$isBeforeAccount && (($perm && $perm->status === 'Rejected') || ($isPast && !$isWeekend));
                    
                    $todayStroke = $isToday ? ' border-4 border-blue-600 relative z-10' : '';
                    
                    $bgClass = 'bg-white border-slate-200 text-slate-700' . $todayStroke;
                    $indicator = '';
                    
                    if ($isHadir) {
                        $bgClass = 'bg-emerald-50 border-emerald-300 text-emerald-800' . $todayStroke;
                        $indicator = '<div class="text-[10px] mt-1 font-semibold text-emerald-700">Hadir</div>';
                    } elseif ($isIzin) {
                        $bgClass = 'bg-amber-50 border-amber-300 text-amber-800' . $todayStroke;
                        $indicator = '<div class="text-[10px] mt-1 font-semibold text-amber-700">Izin</div>';
                    } elseif ($isAlpa) {
                        $bgClass = 'bg-rose-50 border-rose-300 text-rose-800' . $todayStroke;
                        $indicator = '<div class="text-[10px] mt-1 font-semibold text-rose-700">Alpa</div>';
                    }
                @endphp
                
                <div class="min-h-[80px] p-2 flex flex-col items-center justify-center border rounded-xl transition-all hover:shadow-md {{ $bgClass }}">
                    <span class="font-bold text-lg">{{ $day }}</span>
                    {!! $indicator !!}
                </div>
            @endfor
            
            @php
                $totalCells = $firstDayIndex + $daysInMonth;
                $remainingCells = (7 - ($totalCells % 7)) % 7;
            @endphp
            
            @for ($i = 0; $i < $remainingCells; $i++)
                <div class="min-h-[80px] rounded-xl border border-transparent"></div>
            @endfor
        </div>
    </div>
</div>

<style>
    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
        gap: 0.5rem;
    }
</style>
@endsection
