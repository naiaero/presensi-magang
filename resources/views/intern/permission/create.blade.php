@extends('layouts.app')

@section('title', 'Pengajuan Izin')

@section('content')
<div class="space-y-4">

    <!-- Top Header Card -->
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex items-center justify-between">
        <div>
            <h2 class="text-base font-bold text-slate-800">{{ $type === 'telat' ? 'Form Keterangan Terlambat' : 'Form Pengajuan Izin' }}</h2>
            <p class="text-xs text-slate-500">{{ $type === 'telat' ? 'Isi alasan mengapa Anda terlambat hari ini' : 'Isi alasan mengapa Anda berhalangan hadir' }}</p>
        </div>
        <a href="{{ route('intern.dashboard') }}" class="p-2 text-slate-400 hover:text-slate-600 rounded-lg bg-slate-50">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </a>
    </div>

    <!-- Alert / Validation Error -->
    @if ($errors->any())
        <div class="p-3 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-xs space-y-1">
            <p class="font-bold">Terjadi Kesalahan Input:</p>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Main Card -->
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100">
        <form action="{{ route('intern.permission.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            
            <input type="hidden" name="type" value="{{ $type }}">

            <!-- Tanggal Izin -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal <span class="text-rose-500">*</span></label>
                <input type="date" name="date" value="{{ old('date', $today) }}" min="{{ $today }}"
                       class="w-full text-xs font-medium border border-slate-200 rounded-xl p-3 bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <!-- Dropdown Pilihan Alasan -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">{{ $type === 'telat' ? 'Alasan Keterlambatan' : 'Kategori Alasan Izin' }} <span class="text-rose-500">*</span></label>
                <select id="reason_option" name="reason_option" onchange="toggleCustomReason()" 
                        class="w-full text-xs border border-slate-200 rounded-xl p-3 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">-- Pilih Alasan --</option>
                    @foreach($reasonOptions as $option)
                        <option value="{{ $option }}" {{ old('reason_option') == $option ? 'selected' : '' }}>
                            {{ $option }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Input Alasan Custom (Dinamis jika memilih 'Lainnya') -->
            <div id="custom_reason_wrapper" class="hidden transition-all duration-200">
                <label class="block text-xs font-semibold text-slate-700 mb-1">Tuliskan Alasan Lengkap <span class="text-rose-500">*</span></label>
                <textarea name="custom_reason" id="custom_reason" rows="3" 
                          placeholder="Jelaskan alasan detail Anda di sini..."
                          class="w-full text-xs border border-slate-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 text-slate-800">{{ old('custom_reason') }}</textarea>
            </div>

            <!-- Upload File Bukti / Lampiran -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">
                    Upload Surat / Lampiran Bukti <span class="text-slate-400 font-normal">(Opsional)</span>
                </label>
                <div class="mt-1 flex justify-center px-4 pt-4 pb-4 border-2 border-slate-200 border-dashed rounded-xl bg-slate-50/50 hover:bg-slate-50 transition-colors">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-8 w-8 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20a4 4 0 004 4h24a4 4 0 004-4V20L28 8z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M28 8v12h12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <div class="flex text-xs text-slate-600 justify-center">
                            <label for="proof_file" class="relative cursor-pointer font-semibold text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                <span>Pilih file</span>
                                <input id="proof_file" name="proof_file" type="file" accept="image/*,.pdf" class="sr-only" onchange="previewFileName(this)">
                            </label>
                            <p class="pl-1 text-slate-500">atau drag ke sini</p>
                        </div>
                        <p class="text-[10px] text-slate-400">PNG, JPG, PDF hingga 2MB</p>
                        <p id="file-name-preview" class="text-xs font-semibold text-emerald-600 pt-1 truncate max-w-[200px] mx-auto"></p>
                    </div>
                </div>
            </div>

            <!-- Tombol Kirim -->
            <div class="pt-2">
                <button type="submit" 
                        class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-bold rounded-2xl shadow-lg shadow-blue-200 transition-all text-xs flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    <span>Kirim Pengajuan Izin</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // Fungsi untuk menampilkan/menyembunyikan form input alasan custom
    function toggleCustomReason() {
        const select = document.getElementById('reason_option');
        const customWrapper = document.getElementById('custom_reason_wrapper');
        const customInput = document.getElementById('custom_reason');

        if (select.value === 'Lainnya') {
            customWrapper.classList.remove('hidden');
            customInput.setAttribute('required', 'required');
        } else {
            customWrapper.classList.add('hidden');
            customInput.removeAttribute('required');
        }
    }

    // Tampilkan Nama File saat berhasil dipilih
    function previewFileName(input) {
        const preview = document.getElementById('file-name-preview');
        if (input.files && input.files[0]) {
            preview.textContent = 'Terpilih: ' + input.files[0].name;
        } else {
            preview.textContent = '';
        }
    }

    // Panggil saat pertama kali load halaman untuk menyesuaikan status old input
    document.addEventListener('DOMContentLoaded', function() {
        toggleCustomReason();
    });
</script>
@endpush