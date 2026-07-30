@extends('layouts.app')

@section('title', 'Presensi')

@push('styles')
<!-- Leaflet CSS untuk Tampilan Peta Lokasi -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('content')
<div class="space-y-4">

    <!-- Header Card -->
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex items-center justify-between">
        <div>
            <h2 class="text-base font-bold text-slate-800">Verifikasi Lokasi</h2>
            <p class="text-xs text-slate-500">Pastikan GPS perangkat Anda aktif</p>
        </div>
        <a href="{{ route('intern.dashboard') }}" class="p-2 text-slate-400 hover:text-slate-600 rounded-lg bg-slate-50">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </a>
    </div>

    <!-- Container Peta & Status GPS -->
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 space-y-3">
        
        <!-- Peta Interaktif -->
        <div id="map" class="w-full h-52 rounded-xl bg-slate-100 border border-slate-200 z-10"></div>

        <!-- Status Lokasi & Koordinat -->
        <div class="bg-slate-50 rounded-xl p-3 space-y-2 border border-slate-100 text-xs">
            <div class="flex items-center justify-between">
                <span class="text-slate-500">Status GPS:</span>
                <span id="gps-status" class="font-semibold text-amber-600 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Mencari Lokasi...
                </span>
            </div>
            
            <div class="grid grid-cols-2 gap-2 pt-1 border-t border-slate-200/60 font-mono text-[11px]">
                <div>
                    <span class="text-slate-400 block text-[10px]">LATITUDE</span>
                    <span id="display-lat" class="font-bold text-slate-700">-</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[10px]">LONGITUDE</span>
                    <span id="display-lng" class="font-bold text-slate-700">-</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Submit Presensi -->
    @php
        $isAlreadyCheckedIn = isset($todayAttendance) && $todayAttendance->time_in;
        $isAlreadyCheckedOut = isset($todayAttendance) && $todayAttendance->time_out;
        $actionRoute = $isAlreadyCheckedIn ? route('intern.attendance.store_out') : route('intern.attendance.store_in');
    @endphp

    <form id="attendance-form" action="{{ $actionRoute }}" method="POST" class="space-y-3">
        @csrf
        
        <!-- Input Hidden Koordinat -->
        <input type="hidden" name="latitude" id="input-lat">
        <input type="hidden" name="longitude" id="input-lng">

        @if(!$isAlreadyCheckedIn)
            <!-- Tombol Presensi Masuk -->
            <button type="submit" id="btn-submit" disabled
                class="w-full py-3.5 px-4 bg-blue-600 hover:bg-blue-700 disabled:bg-slate-300 disabled:cursor-not-allowed text-white font-bold rounded-2xl shadow-lg shadow-blue-200 transition-all text-sm flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
                <span>Kirim Presensi Masuk</span>
            </button>
        @elseif(!$isAlreadyCheckedOut)

            <!-- Tombol Presensi Pulang -->
            <button type="{{ (isset($isEarlyCheckout) && $isEarlyCheckout) ? 'button' : 'submit' }}" 
                id="btn-submit" 
                {{ (isset($isEarlyCheckout) && $isEarlyCheckout) ? 'onclick=showEarlyLeaveModal()' : '' }}
                disabled
                class="w-full py-3.5 px-4 bg-emerald-600 hover:bg-emerald-700 disabled:bg-slate-300 disabled:cursor-not-allowed text-white font-bold rounded-2xl shadow-lg shadow-emerald-200 transition-all text-sm flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span>Kirim Presensi Pulang</span>
            </button>

            @if(isset($isEarlyCheckout) && $isEarlyCheckout)
                @push('modals')
                <!-- Modal Alasan Pulang Cepat -->
                <div id="early-leave-modal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm items-center justify-center p-4">
                    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden transform transition-all">
                        <div class="bg-rose-50 border-b border-rose-100 p-4 flex items-start gap-3 text-rose-700">
                            <div class="w-10 h-10 rounded-full bg-rose-200/50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-sm">Konfirmasi Pulang Awal</h3>
                                <p class="text-xs text-rose-600/80 mt-1">Anda melakukan presensi pulang sebelum pukul {{ substr($limitTime, 0, 5) }} WITA.</p>
                            </div>
                        </div>
                        <div class="p-4 space-y-3">
                            <label for="early_leave_reason" class="block text-xs font-semibold text-slate-700">Alasan Pulang <span class="text-rose-500">*</span></label>
                            <textarea name="early_leave_reason" id="early_leave_reason" rows="3" form="attendance-form"
                                class="w-full text-sm border-slate-200 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 rounded-xl shadow-sm placeholder-slate-400 p-3" 
                                placeholder="Jelaskan alasan Anda pulang lebih awal..." {{ (isset($isEarlyCheckout) && $isEarlyCheckout) ? 'required' : '' }}></textarea>
                            
                            @error('early_leave_reason')
                                <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="p-4 bg-slate-50 border-t border-slate-100 flex gap-2 justify-end">
                            <button type="button" onclick="hideEarlyLeaveModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200 bg-slate-100 rounded-xl transition-all">
                                Batal
                            </button>
                            <button type="submit" form="attendance-form" class="px-4 py-2 text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-md transition-all">
                                Konfirmasi & Pulang
                            </button>
                        </div>
                    </div>
                </div>

                <script>
                    function showEarlyLeaveModal() {
                        const modal = document.getElementById('early-leave-modal');
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                    }
                    function hideEarlyLeaveModal() {
                        const modal = document.getElementById('early-leave-modal');
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                    }
                </script>
                @endpush
            @endif
        @else
            <!-- Sudah Presensi Masuk & Pulang -->
            <div class="p-3.5 bg-slate-100 text-slate-600 text-center text-xs font-semibold rounded-2xl border border-slate-200">
                Anda telah menyelesaikan presensi masuk & pulang hari ini.
            </div>
        @endif
    </form>

    <!-- Tombol Alternatif Izin -->
    <div class="text-center pt-2">
        <a href="{{ route('intern.permission.create') }}" class="text-xs text-blue-600 hover:underline font-medium">
            Terlambat atau berada di luar kantor? Ajukan Izin di sini
        </a>
    </div>

</div>
@endsection

@push('scripts')
<!-- Leaflet JS untuk Rendering Peta -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const gpsStatus = document.getElementById('gps-status');
        const displayLat = document.getElementById('display-lat');
        const displayLng = document.getElementById('display-lng');
        const inputLat = document.getElementById('input-lat');
        const inputLng = document.getElementById('input-lng');
        const btnSubmit = document.getElementById('btn-submit');

        // Initial Leaflet Map (Default Pusat NTB/Mataram)
        const defaultLat = -8.583333;
        const defaultLng = 116.116667;
        const map = L.map('map').setView([defaultLat, defaultLng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        let userMarker = null;

        // Ambil Lokasi Menggunakan HTML5 Geolocation API
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    // Update Tampilan Koordinat
                    displayLat.textContent = lat.toFixed(6);
                    displayLng.textContent = lng.toFixed(6);

                    // Set Nilai Input Hidden Form
                    inputLat.value = lat;
                    inputLng.value = lng;

                    // Update Marker & Map View
                    map.setView([lat, lng], 17);

                    if (userMarker) {
                        userMarker.setLatLng([lat, lng]);
                    } else {
                        userMarker = L.marker([lat, lng]).addTo(map)
                            .bindPopup('<b>Lokasi Anda</b>').openPopup();
                    }

                    // Tambahkan Lingkaran Radius Toleransi
                    L.circle([lat, lng], {
                        color: '#3b82f6',
                        fillColor: '#93c5fd',
                        fillOpacity: 0.3,
                        radius: position.coords.accuracy
                    }).addTo(map);

                    // Update Status Badge GPS & Aktifkan Tombol Submit
                    gpsStatus.className = 'font-semibold text-emerald-600 flex items-center gap-1';
                    gpsStatus.innerHTML = `
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Lokasi Terkunci
                    `;

                    if (btnSubmit) {
                        btnSubmit.removeAttribute('disabled');
                    }
                },
                function (error) {
                    let errorMsg = 'Gagal mengambil lokasi.';
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMsg = 'Akses lokasi ditolak oleh pengguna.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMsg = 'Informasi lokasi tidak tersedia.';
                            break;
                        case error.TIMEOUT:
                            errorMsg = 'Waktu permintaan lokasi habis.';
                            break;
                    }

                    gpsStatus.className = 'font-semibold text-rose-600';
                    gpsStatus.textContent = errorMsg;
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        } else {
            gpsStatus.className = 'font-semibold text-rose-600';
            gpsStatus.textContent = 'Browser Anda tidak mendukung Geolocation.';
        }
    });
</script>
@endpush