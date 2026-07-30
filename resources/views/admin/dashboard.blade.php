@extends('layouts.app')
@section('title', 'Beranda')

@section('content')
<div class="mb-8 p-6 bg-gradient-to-br from-blue-700 to-indigo-800 rounded-3xl shadow-xl shadow-blue-900/20 text-white relative overflow-hidden">
    <!-- Decorative elements -->
    <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-white/10 blur-2xl"></div>
    <div class="absolute bottom-0 left-0 -ml-8 -mb-8 w-40 h-40 rounded-full bg-blue-500/20 blur-3xl"></div>
    
    <div class="relative z-10">
        <h1 class="text-3xl font-black mb-2 tracking-tight">Manajemen Presensi Magang</h1>
        <p class="text-blue-100/80 font-medium max-w-xl">Kelola data peserta magang, pantau aktivitas presensi, dan tinjau permintaan izin dengan mudah dan efisien.</p>
    </div>
</div>

<!-- Tabs -->
<div class="mb-8">
    <nav class="flex space-x-2 p-1.5 bg-slate-100 rounded-2xl w-full md:w-max mx-auto md:mx-0 shadow-inner" aria-label="Tabs" id="admin-tabs">
        <button onclick="switchTab('users')" class="tab-btn active-tab bg-white text-blue-700 shadow-sm rounded-xl py-2.5 px-6 font-bold text-sm transition-all duration-300 w-1/2 md:w-auto" id="tab-users">
            <div class="flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Daftar Pengguna
            </div>
        </button>
        <button onclick="switchTab('permissions')" class="tab-btn inactive-tab text-slate-500 hover:text-slate-800 hover:bg-slate-200/50 rounded-xl py-2.5 px-6 font-semibold text-sm transition-all duration-300 w-1/2 md:w-auto" id="tab-permissions">
            <div class="flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Permintaan Izin
            </div>
        </button>
    </nav>
</div>

<!-- Tab Content: Users -->
<div id="content-users" class="tab-content block">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Daftar User</h2>
                <p class="text-sm text-slate-500">Kelola data pengguna dan lihat kalender presensi.</p>
            </div>
            <button onclick="openModal('modalTambahUser')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-semibold shadow-md shadow-blue-500/20 transition-all transform hover:-translate-y-0.5">
                + Tambah Pengguna
            </button>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($users as $user)
            <div class="p-4 hover:bg-slate-50 transition-colors group flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-50 last:border-0">
                <div class="flex items-center gap-4 cursor-pointer flex-1" onclick="openCalendarModal('{{ $user->id }}', '{{ addslashes($user->name) }}', '{{ addslashes($user->institution) }}', '{{ \Carbon\Carbon::parse($user->start_date)->format('d M Y') }}', '{{ \Carbon\Carbon::parse($user->end_date)->format('d M Y') }}', '{{ addslashes($user->major) }}')">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold shrink-0">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-800 group-hover:text-blue-600 transition-colors flex items-center gap-2">
                            {{ $user->name }}
                            @if($user->end_date && \Carbon\Carbon::today()->toDateString() > $user->end_date)
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-rose-100 text-rose-700">Nonaktif</span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-700">Aktif</span>
                            @endif
                        </h3>
                        <p class="text-xs text-slate-500">{{ $user->institution }} {{ $user->major ? ' - ' . $user->major : '' }} &middot; {{ \Carbon\Carbon::parse($user->start_date)->format('d M Y') }} s.d {{ \Carbon\Carbon::parse($user->end_date)->format('d M Y') }}</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2 shrink-0 w-full md:w-auto mt-2 md:mt-0">
                    <button type="button" onclick="openEditUserModal('{{ $user->id }}', '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}', '{{ addslashes($user->institution) }}', '{{ addslashes($user->major) }}', '{{ $user->start_date }}', '{{ $user->end_date }}')" class="px-3 py-1.5 bg-slate-100 text-slate-600 hover:bg-blue-50 hover:text-blue-600 rounded-lg text-xs font-semibold transition-colors">Edit</button>
                    <button type="button" onclick="openResetPasswordModal('{{ $user->id }}', '{{ addslashes($user->name) }}')" class="px-3 py-1.5 bg-slate-100 text-slate-600 hover:bg-rose-50 hover:text-rose-600 rounded-lg text-xs font-semibold transition-colors">Reset Password</button>
                    <button type="button" onclick="openCalendarModal('{{ $user->id }}', '{{ addslashes($user->name) }}', '{{ addslashes($user->institution) }}', '{{ \Carbon\Carbon::parse($user->start_date)->format('d M Y') }}', '{{ \Carbon\Carbon::parse($user->end_date)->format('d M Y') }}', '{{ addslashes($user->major) }}')" class="px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-xs font-semibold transition-colors flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Kalender
                    </button>
                </div>
            </div>
            @empty
            <div class="p-8 text-center text-slate-500">
                <p>Belum ada pengguna terdaftar.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Tab Content: Permissions -->
<div id="content-permissions" class="tab-content hidden">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h2 class="text-lg font-bold text-slate-800">Permintaan Izin</h2>
            <p class="text-sm text-slate-500">Terima atau tolak permintaan izin dari pengguna.</p>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($permissions as $perm)
            <div class="p-6">
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="font-bold text-slate-800">{{ $perm->user->name ?? 'Unknown' }}</h3>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-blue-100 text-blue-700">Menunggu</span>
                        </div>
                        <p class="text-sm font-medium text-slate-700 mb-1">Tanggal Izin: {{ \Carbon\Carbon::parse($perm->date)->format('d F Y') }}</p>
                        <div class="text-sm text-slate-600 bg-slate-50 p-3 rounded-lg border border-slate-100 mt-2 space-y-2">
                            <div>
                                <span class="font-semibold text-slate-700">Alasan:</span> 
                                {{ $perm->reason_option }} 
                                @if($perm->custom_reason)
                                    <span class="text-slate-500 italic">({{ $perm->custom_reason }})</span>
                                @endif
                            </div>
                            
                            @if($perm->proof_file)
                            <div>
                                <span class="font-semibold text-slate-700">Lampiran:</span>
                                <a href="{{ asset('storage/' . $perm->proof_file) }}" target="_blank" class="text-blue-600 hover:text-blue-700 hover:underline inline-flex items-center gap-1 font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                    Lihat File Lampiran
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="flex gap-2 shrink-0">
                        <button type="button" onclick="openConfirmPermissionModal('{{ $perm->id }}', 'Approved', '{{ addslashes($perm->user->name) }}')" class="px-4 py-2 bg-emerald-50 text-emerald-600 border border-emerald-200 hover:bg-emerald-100 font-semibold text-sm rounded-xl transition-colors">Terima</button>
                        <button type="button" onclick="openConfirmPermissionModal('{{ $perm->id }}', 'Rejected', '{{ addslashes($perm->user->name) }}')" class="px-4 py-2 bg-rose-50 text-rose-600 border border-rose-200 hover:bg-rose-100 font-semibold text-sm rounded-xl transition-colors">Tolak</button>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-8 text-center text-slate-500">
                <p>Tidak ada permintaan izin saat ini.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@push('modals')

<!-- Modal Tambah User -->
<div id="modalTambahUser" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex min-h-screen w-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-slate-100">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-bold leading-6 text-slate-900" id="modal-title">Tambah Pengguna Baru</h3>
                    <button type="button" onclick="closeModal('modalTambahUser')" class="text-slate-400 hover:text-slate-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
            
            <form id="formTambahUser" method="POST" action="{{ route('admin.user.store') }}">
                @csrf
                <div class="px-4 py-5 sm:p-6 space-y-4">
                    
                    @if ($errors->any())
                    <div class="bg-rose-50 border border-rose-200 text-rose-700 rounded-xl p-4 text-sm mb-4">
                        <p class="font-bold mb-2">Validasi Gagal:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Password</label>
                        <input type="password" name="password" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Asal Instansi</label>
                        <input type="text" name="institution" value="{{ old('institution') }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Jurusan/Program Studi</label>
                        <input type="text" name="major" value="{{ old('major') }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm outline-none">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Masuk</label>
                            <input type="date" name="start_date" value="{{ old('start_date') }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Selesai Magang</label>
                            <input type="date" name="end_date" value="{{ old('end_date') }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm outline-none">
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-100">
                    <button type="submit" class="inline-flex w-full justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto transition-colors">Tambahkan Pengguna</button>
                    <button type="button" onclick="closeModal('modalTambahUser')" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto transition-colors">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit User -->
<div id="modalEditUser" class="fixed inset-0 z-[60] hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex min-h-screen w-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-slate-100">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-bold leading-6 text-slate-900">Edit Pengguna</h3>
                    <button type="button" onclick="closeModal('modalEditUser')" class="text-slate-400 hover:text-slate-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
            
            <form id="formEditUser" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="px-4 py-5 sm:p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" id="edit_name" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Email</label>
                        <input type="email" name="email" id="edit_email" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Asal Instansi</label>
                        <input type="text" name="institution" id="edit_institution" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Jurusan / Program Studi</label>
                        <input type="text" name="major" id="edit_major" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm outline-none">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Masuk</label>
                            <input type="date" name="start_date" id="edit_start_date" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Selesai Magang</label>
                            <input type="date" name="end_date" id="edit_end_date" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm outline-none">
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-100">
                    <button type="submit" class="inline-flex w-full justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto transition-colors">Simpan Perubahan</button>
                    <button type="button" onclick="closeModal('modalEditUser')" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto transition-colors">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modalKalenderUser" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex min-h-screen w-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl">
            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-xl font-bold text-slate-900" id="judulKalender">Kalender Absensi</h3>
                    <p class="text-sm text-slate-500 mt-1" id="infoDetailUser">Detail instansi</p>
                </div>
                <div class="flex items-center gap-3 bg-slate-50 p-2 rounded-xl border border-slate-200">
                    <button type="button" id="prevAdminMonthBtn" onclick="changeAdminMonth(-1)" class="p-1 text-slate-500 hover:text-blue-600 rounded transition-colors disabled:opacity-30 disabled:cursor-not-allowed">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    <span id="labelKalenderBulan" class="font-bold text-slate-800 text-sm min-w-[120px] text-center">
                        Memuat...
                    </span>
                    <button type="button" id="nextAdminMonthBtn" onclick="changeAdminMonth(1)" class="p-1 text-slate-500 hover:text-blue-600 rounded transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                    <button type="button" onclick="closeModal('modalKalenderUser')" class="ml-2 text-slate-400 hover:text-slate-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
            
            <div class="px-4 py-5 sm:p-6">
                <!-- Legends -->
                <div class="flex flex-wrap gap-3 mb-6">
                    <span class="flex items-center gap-2 text-xs font-semibold px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-100 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Hadir
                    </span>
                    <span class="flex items-center gap-2 text-xs font-semibold px-3 py-1.5 rounded-xl bg-amber-50 text-amber-700 border border-amber-100 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span> Izin
                    </span>
                    <span class="flex items-center gap-2 text-xs font-semibold px-3 py-1.5 rounded-xl bg-rose-50 text-rose-700 border border-rose-100 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span> Alpa
                    </span>
                </div>

                <div class="calendar-grid mb-3 text-center text-sm font-bold text-slate-500 uppercase tracking-wider">
                    <div>Min</div><div>Sen</div><div>Sel</div><div>Rabu</div><div>Kam</div><div>Jum</div><div>Sab</div>
                </div>
                
                <div id="gridKalenderBulan" class="calendar-grid text-sm">
                    <!-- Dimuat via AJAX -->
                </div>
            </div>
            <div class="bg-slate-50 px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-100">
                <button type="button" onclick="closeModal('modalKalenderUser')" class="inline-flex w-full justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:w-auto transition-colors">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endpush

@endsection

@push('scripts')
<script>
    // Tab Switching Logic
    function switchTab(tabId) {
        document.querySelectorAll('.tab-content').forEach(el => {
            el.classList.add('hidden');
            el.classList.remove('block');
        });
        document.querySelectorAll('.tab-btn').forEach(el => {
            el.classList.remove('active-tab', 'bg-white', 'text-blue-700', 'shadow-sm');
            el.classList.add('inactive-tab', 'text-slate-500');
        });

        document.getElementById('content-' + tabId).classList.remove('hidden');
        document.getElementById('content-' + tabId).classList.add('block', 'animate-fade-in');
        
        const activeBtn = document.getElementById('tab-' + tabId);
        activeBtn.classList.remove('inactive-tab', 'text-slate-500');
        activeBtn.classList.add('active-tab', 'bg-white', 'text-blue-700', 'shadow-sm');
    }

    // Modal Logic
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    // Auto-open add user modal on errors
    @if ($errors->any())
        openModal('modalTambahUser');
    @endif

    // Calendar Modal Logic
    let currentAdminUserId = null;
    let currentAdminMonth = null;
    let currentAdminYear = null;

    function openEditUserModal(id, name, email, institution, major, startDate, endDate) {
        const form = document.getElementById('formEditUser');
        form.action = `{{ url('/admin/user') }}/${id}`;
        
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_institution').value = institution;
        document.getElementById('edit_major').value = major;
        document.getElementById('edit_start_date').value = startDate;
        document.getElementById('edit_end_date').value = endDate;
        
        openModal('modalEditUser');
    }

    function openCalendarModal(userId, name, institution, startDate, endDateStr, major) {
        document.getElementById('judulKalender').innerText = name;
        const majorText = major ? ' - ' + major : '';
        document.getElementById('infoDetailUser').innerText = `${institution}${majorText} • Periode: ${startDate} s.d ${endDateStr}`;
        
        currentAdminUserId = userId;
        currentAdminMonth = null;
        currentAdminYear = null;

        openModal('modalKalenderUser');
        loadAdminCalendar(userId);
    }

    function changeAdminMonth(direction) {
        if (!currentAdminUserId || !currentAdminMonth || !currentAdminYear) return;
        
        let newMonth = currentAdminMonth + direction;
        let newYear = currentAdminYear;
        
        if (newMonth < 1) {
            newMonth = 12;
            newYear -= 1;
        } else if (newMonth > 12) {
            newMonth = 1;
            newYear += 1;
        }

        loadAdminCalendar(currentAdminUserId, newMonth, newYear);
    }

    function loadAdminCalendar(userId, month = null, year = null) {
        const grid = document.getElementById('gridKalenderBulan');
        grid.innerHTML = '<div class="col-span-7 text-center py-8 text-slate-500">Memuat data...</div>';

        let url = `{{ url('/admin/user') }}/${userId}/calendar`;
        if (month && year) {
            url += `?month=${month}&year=${year}`;
        }

        fetch(url)
            .then(response => response.json())
            .then(data => {
                currentAdminMonth = data.month;
                currentAdminYear = data.year;

                document.getElementById('labelKalenderBulan').innerText = data.monthName;

                // Cek batas navigasi bulan lalu
                const prevMonthNum = currentAdminMonth - 1 > 0 ? currentAdminMonth - 1 : 12;
                const prevYearNum = currentAdminMonth - 1 > 0 ? currentAdminYear : currentAdminYear - 1;
                const prevStr = `${prevYearNum}-${String(prevMonthNum).padStart(2, '0')}`;
                
                const prevBtn = document.getElementById('prevAdminMonthBtn');
                if (prevStr < data.startDate) {
                    prevBtn.disabled = true;
                } else {
                    prevBtn.disabled = false;
                }

                renderCalendar(data.attendances, data.permissions, data.year, data.month, data.userCreatedAt);
            })
            .catch(error => {
                grid.innerHTML = '<div class="col-span-7 text-center py-8 text-rose-500">Gagal memuat kalender</div>';
            });
    }

    function renderCalendar(attendances, permissions, year, month, userCreatedAt) {
        const grid = document.getElementById('gridKalenderBulan');
        grid.innerHTML = '';
        
        const today = new Date();
        const daysInMonth = new Date(year, month, 0).getDate();
        const firstDayIndex = new Date(year, month - 1, 1).getDay();
        
        for(let i = 0; i < firstDayIndex; i++) {
            grid.innerHTML += `<div class="calendar-day" style="background: transparent; border: none;"></div>`;
        }
        
        for(let day = 1; day <= daysInMonth; day++) {
            const dateStr = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            let att = attendances.find(a => a.date === dateStr);
            let perm = permissions.find(p => p.date === dateStr);
            
            const cellDateZero = new Date(year, month - 1, day).setHours(0,0,0,0);
            const todayZero = new Date(today.getFullYear(), today.getMonth(), today.getDate()).setHours(0,0,0,0);
            const cellDateObj = new Date(year, month - 1, day);
            const isWeekend = cellDateObj.getDay() === 0 || cellDateObj.getDay() === 6;
            
            const isPast = cellDateZero < todayZero;
            const isToday = cellDateZero === todayZero;
            const isBeforeAccount = userCreatedAt && dateStr < userCreatedAt;
            
            const isApprovedLate = (perm && perm.status === 'Approved' && perm.reason_option === 'Terlambat / Di luar Radius Kantor');
            const isApprovedIzin = (perm && perm.status === 'Approved' && perm.reason_option !== 'Terlambat / Di luar Radius Kantor');

            const isHadir = att || isApprovedLate;
            const isIzin = !isHadir && isApprovedIzin;
            const isAlpa = !isHadir && !isIzin && !isBeforeAccount && ((perm && perm.status === 'Rejected') || (isPast && !isWeekend));

            let todayStyle = isToday ? "border: 4px solid #2563eb; font-weight: bold; position: relative; z-index: 10;" : "";
            let bgStyle = `background: #fff; border: ${isToday ? '4px solid #2563eb' : '1px solid #e5e7eb'}; color: #374151; ${todayStyle}`;
            let indicator = "";

            if (isHadir) {
                bgStyle = `background-color: #ecfdf5; color: #065f46; border: ${isToday ? '4px solid #2563eb' : '1px solid #a7f3d0'}; ${todayStyle}`;
                indicator = "<div style='font-size: 10px; margin-top: 4px; font-weight: 600; color: #047857;'>Hadir</div>";
            } else if (isIzin) {
                bgStyle = `background-color: #fffbeb; color: #92400e; border: ${isToday ? '4px solid #2563eb' : '1px solid #fde68a'}; ${todayStyle}`;
                indicator = "<div style='font-size: 10px; margin-top: 4px; font-weight: 600; color: #b45309;'>Izin</div>";
            } else if (isAlpa) {
                bgStyle = `background-color: #fff1f2; color: #9f1239; border: ${isToday ? '4px solid #2563eb' : '1px solid #fecdd3'}; ${todayStyle}`;
                indicator = "<div style='font-size: 10px; margin-top: 4px; font-weight: 600; color: #be123c;'>Alpa</div>";
            }
            
            grid.innerHTML += `<div class="calendar-day flex flex-col items-center justify-center p-2 rounded-xl transition-all" style="${bgStyle}">
                <span style="font-weight: bold; font-size: 1.125rem;">${day}</span>
                ${indicator}
            </div>`;
        }
    }
</script>

<!-- Modal Konfirmasi Reset Password -->
<div id="reset-password-modal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden transform transition-all">
        <div class="bg-blue-50 border-b border-blue-100 p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-blue-200/50 flex items-center justify-center text-blue-600 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-slate-800">Reset Password</h3>
                <p class="text-xs text-blue-600">Konfirmasi tindakan</p>
            </div>
            <button type="button" onclick="closeResetPasswordModal()" class="text-slate-400 hover:text-slate-600 bg-white/50 hover:bg-white rounded-xl p-2 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-5">
            <p class="text-sm text-slate-600">Anda yakin ingin mereset password untuk <strong id="reset-user-name" class="text-slate-800"></strong> menjadi <strong class="text-slate-800">password</strong>?</p>
            
            <form id="reset-password-form" method="POST" action="">
                @csrf
                <div class="flex gap-2 justify-end mt-6">
                    <button type="button" onclick="closeResetPasswordModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200 bg-slate-100 rounded-xl transition-all">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-md transition-all">
                        Ya, Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openResetPasswordModal(id, name) {
        document.getElementById('reset-user-name').innerText = name;
        document.getElementById('reset-password-form').action = `{{ url('/admin/user') }}/${id}/reset-password`;
        const modal = document.getElementById('reset-password-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeResetPasswordModal() {
        const modal = document.getElementById('reset-password-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function openConfirmPermissionModal(id, action, name) {
        const modal = document.getElementById('confirm-permission-modal');
        const form = document.getElementById('confirm-permission-form');
        const statusInput = document.getElementById('permission-status-input');
        const title = document.getElementById('permission-modal-title');
        const text = document.getElementById('permission-modal-text');
        const submitBtn = document.getElementById('permission-submit-btn');
        const iconContainer = document.getElementById('permission-icon-container');

        form.action = `{{ url('/admin/permission') }}/${id}`;
        statusInput.value = action;
        
        if (action === 'Approved') {
            title.innerText = 'Terima Izin';
            text.innerHTML = `Anda yakin ingin <strong>menerima</strong> pengajuan izin dari <strong>${name}</strong>?`;
            
            submitBtn.innerText = 'Ya, Terima Izin';
            submitBtn.className = 'px-4 py-2 text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-md transition-all';
            
            iconContainer.className = 'w-10 h-10 rounded-full bg-emerald-200/50 flex items-center justify-center text-emerald-600 flex-shrink-0';
            iconContainer.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
        } else {
            title.innerText = 'Tolak Izin';
            text.innerHTML = `Anda yakin ingin <strong>menolak</strong> pengajuan izin dari <strong>${name}</strong>?`;
            
            submitBtn.innerText = 'Ya, Tolak Izin';
            submitBtn.className = 'px-4 py-2 text-sm font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow-md transition-all';
            
            iconContainer.className = 'w-10 h-10 rounded-full bg-rose-200/50 flex items-center justify-center text-rose-600 flex-shrink-0';
            iconContainer.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeConfirmPermissionModal() {
        const modal = document.getElementById('confirm-permission-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>

<!-- Modal Konfirmasi Permission -->
<div id="confirm-permission-modal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden transform transition-all">
        <div class="bg-slate-50 border-b border-slate-100 p-4 flex items-center gap-3">
            <div id="permission-icon-container"></div>
            <div class="flex-1">
                <h3 id="permission-modal-title" class="font-bold text-slate-800">Konfirmasi Izin</h3>
                <p class="text-xs text-slate-500">Konfirmasi tindakan</p>
            </div>
            <button type="button" onclick="closeConfirmPermissionModal()" class="text-slate-400 hover:text-slate-600 bg-white/50 hover:bg-white rounded-xl p-2 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-5">
            <p id="permission-modal-text" class="text-sm text-slate-600"></p>
            
            <form id="confirm-permission-form" method="POST" action="">
                @csrf
                <input type="hidden" name="status" id="permission-status-input" value="">
                <div class="flex gap-2 justify-end mt-6">
                    <button type="button" onclick="closeConfirmPermissionModal()" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200 bg-slate-100 rounded-xl transition-all">
                        Batal
                    </button>
                    <button id="permission-submit-btn" type="submit" class=""></button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .animate-fade-in {
        animation: fadeIn 0.3s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 8px;
    }
    
    .calendar-day {
        min-height: 80px;
        padding: 10px;
        border-radius: 12px;
        font-size: 0.85rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    .calendar-day:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
</style>
@endpush