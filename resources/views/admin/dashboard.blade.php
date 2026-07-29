<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Presensi & Kalender</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            font-family: 'Outfit', sans-serif;
        }
        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(-45deg, #1e3a8a, #3b82f6, #0ea5e9, #1d4ed8);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            min-height: 100vh;
        }
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .navbar-top {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 6px 24px rgba(0,0,0,0.08);
            padding: 1rem 0;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
        }
        .admin-container {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 2rem;
            padding: 2rem 0;
        }
        .admin-sidebar {
            position: relative;
        }
        .sidebar-panel {
            glass-panel rounded-3xl p-6 position-sticky;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            padding: 1.5rem;
            top: 20px;
        }
        .sidebar-title {
            color: white;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 1rem;
            opacity: 0.8;
        }
        .sidebar-nav .nav-link {
            color: rgba(255, 255, 255, 0.8);
            border-radius: 12px;
            margin-bottom: 0.5rem;
            text-align: left;
            padding: 0.85rem 1rem;
            font-weight: 500;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid transparent;
            transition: all 0.3s ease;
        }
        .sidebar-nav .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            color: white;
        }
        .sidebar-nav .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .main-content {
            padding-right: 1rem;
        }
        .card, .card-header {
            border: none;
            border-radius: 16px;
        }
        .card {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .card-header {
            background: transparent;
            color: #212529;
            padding: 1.5rem;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        .card-body {
            padding: 1.5rem;
        }
        .card h5 {
            font-weight: 600;
            color: #1a202c;
        }
        .list-group-item {
            background: transparent;
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 12px;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
        }
        .list-group-item:hover {
            background: rgba(13, 110, 253, 0.05);
            border-color: rgba(13, 110, 253, 0.2);
        }
        .list-group-item.active {
            background: rgba(13, 110, 253, 0.1);
            border-color: #0d6efd;
        }
        .badge {
            border-radius: 12px;
            font-size: 0.75rem;
            padding: 0.5em 0.75em;
            font-weight: 600;
        }
        .status-hadir { background-color: #d4f4dd; color: #22543d; }
        .status-disetujui { background-color: #fef08a; color: #7c2d12; }
        .status-ditolak { background-color: #fecaca; color: #7f1d1d; }
        .status-proses { background-color: #bfdbfe; color: #1e3a8a; }
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 8px;
        }
        .calendar-day {
            min-height: 80px;
            border: 1px solid #e5e7eb;
            padding: 10px;
            border-radius: 12px;
            background: #fff;
            color: #374151;
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
        .modal-content {
            border: none;
            border-radius: 20px;
            background: white;
        }
        .btn-primary {
            background: linear-gradient(135deg, #1e3a8a 0%, #0d6efd 100%);
            border: none;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(13, 110, 253, 0.3);
        }
        .btn-outline-success, .btn-outline-danger {
            border-radius: 8px;
            font-weight: 600;
        }
        @media (max-width: 992px) {
            .admin-container {
                grid-template-columns: 1fr;
            }
            .sidebar-panel {
                position: static;
            }
        }
    </style>
</head>
<body>

    <!-- Header Admin -->
    <header class="navbar-top sticky-top">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #1e3a8a 0%, #0d6efd 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <span style="color: white; font-weight: 700; font-size: 18px;">A</span>
                        </div>
                        <div>
                            <h5 class="mb-0" style="color: #1a202c; font-weight: 700;">Bapenda NTB</h5>
                            <small style="color: #6b7280;">ADMIN PRESENSI MAGANG</small>
                        </div>
                    </div>
                </div>
                <div class="dropdown">
                    <button class="btn btn-sm" style="color: #1a202c; font-weight: 600; border: 1px solid #e5e7eb; border-radius: 12px; padding: 0.5rem 1rem;" type="button" id="adminAccountMenu" data-bs-toggle="dropdown" aria-expanded="false">
                        ⚙️ Akun
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminAccountMenu">
                        <li><a class="dropdown-item" href="#">Profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <div class="container-fluid px-4">
        <!-- Alert Messages -->
        @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show mt-4" role="alert" style="border-radius: 12px; background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); color: #166534;">
            <strong>✓ Sukses!</strong> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert" style="border-radius: 12px; background: rgba(220, 38, 38, 0.1); border: 1px solid rgba(220, 38, 38, 0.3); color: #991b1b;">
            <strong>✗ Error!</strong> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="admin-container">
            <!-- Sidebar Admin -->
            <aside class="admin-sidebar">
                <div class="sidebar-panel">
                    <div class="sidebar-title">Menu Admin</div>
                    <div class="nav nav-pills flex-column sidebar-nav" id="adminTab" role="tablist" aria-orientation="vertical">
                        <button class="nav-link active" id="user-tab" data-bs-toggle="tab" data-bs-target="#user-content" type="button" role="tab">
                            👥 Daftar User
                        </button>
                        <button class="nav-link" id="izin-tab" data-bs-toggle="tab" data-bs-target="#izin-content" type="button" role="tab">
                            📋 Permintaan Izin
                        </button>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="main-content">
                <div class="tab-content" id="adminTabContent">
                    
                    <!-- ================= TAB 1: USER & KALENDER ================= -->
                    <div class="tab-pane fade show active" id="user-content" role="tabpanel">
                        <div class="card mb-4">
                            <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">
                                <div>
                                    <h5 class="mb-0">👨‍💼 Daftar User Magang</h5>
                                    <p class="text-muted small mb-0">Kelola data user dan lihat kalender absensi mereka</p>
                                </div>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
                                    ➕ Tambah User
                                </button>
                            </div>
                            <div class="card-body p-0">
                                <div class="list-group list-group-flush" id="listUser">
                                    @forelse($users as $user)
                                    <button type="button" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-4" data-bs-toggle="modal" data-bs-target="#modalKalenderUser" onclick="pilihUser(this, '{{ $user->id }}', '{{ $user->name }}', '{{ $user->institution }}', '{{ \Carbon\Carbon::parse($user->start_date)->format('d M Y') }}', '{{ $user->duration }}')">
                                        <div class="text-start" style="flex: 1;">
                                            <div class="fw-600" style="color: #1a202c;">{{ $user->name }}</div>
                                            <small class="text-muted">{{ $user->institution }} · 📅 {{ \Carbon\Carbon::parse($user->start_date)->format('d M Y') }}</small>
                                        </div>
                                        <span class="badge bg-primary rounded-pill ms-2">Lihat</span>
                                    </button>
                                    @empty
                                    <div class="p-4 text-center text-muted">
                                        <p class="mb-0">Belum ada user terdaftar</p>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ================= TAB 2: VERIFIKASI IZIN ================= -->
                    <div class="tab-pane fade" id="izin-content" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">📝 Permintaan Izin</h5>
                                <p class="text-muted small mb-0">Terima atau tolak permintaan izin dari user</p>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    @forelse($permissions as $perm)
                                    <div class="list-group-item d-flex flex-column gap-3 p-4">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <div class="fw-600" style="color: #1a202c;">{{ $perm->user->name ?? 'Unknown' }}</div>
                                                <small class="text-muted">📅 {{ \Carbon\Carbon::parse($perm->date)->format('d F Y') }} · {{ $perm->reason }}</small>
                                            </div>
                                            <span class="badge status-proses">Dalam Proses</span>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <form method="POST" action="{{ route('admin.permission.update', $perm->id) }}" class="flex-grow-1">
                                                @csrf
                                                <input type="hidden" name="status" value="Approved">
                                                <button type="submit" class="btn btn-sm btn-outline-success w-100" style="border-radius: 10px; font-weight: 600;">✓ Terima</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.permission.update', $perm->id) }}" class="flex-grow-1">
                                                @csrf
                                                <input type="hidden" name="status" value="Rejected">
                                                <button type="submit" class="btn btn-sm btn-outline-danger w-100" style="border-radius: 10px; font-weight: 600;">✗ Tolak</button>
                                            </form>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="text-center text-muted p-4">
                                        <p class="mb-0">✓ Tidak ada permintaan izin yang perlu diproses</p>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>

    <!-- Modal Kalender User -->
    <div class="modal fade" id="modalKalenderUser" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="border-bottom: 1px solid rgba(0,0,0,0.05);">
                    <div>
                        <h5 class="modal-title" id="judulKalender" style="font-weight: 700; color: #1a202c;">📅 Kalender Absensi</h5>
                        <p class="text-muted small mb-0" id="infoDetailUser" style="font-size: 0.85rem;">Lihat detail kalender absensi user</p>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <button type="button" class="btn btn-sm btn-warning" id="hapusUserButton" onclick="nonaktifkanUser()" disabled style="border-radius: 10px; font-weight: 600;">⚠️ Nonaktifkan</button>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body" style="padding: 2rem;">
                    <div class="d-flex flex-wrap gap-2 mb-4 small">
                        <span class="badge status-hadir">✓ Hadir</span>
                        <span class="badge status-disetujui">✓ Izin Disetujui</span>
                        <span class="badge status-ditolak">✗ Izin Ditolak</span>
                        <span class="badge status-proses">⏳ Proses Izin</span>
                    </div>
                    <h6 class="mb-4" style="font-weight: 700;">Periode: Agustus 2026</h6>
                    <div class="calendar-grid text-center fw-bold mb-3" style="color: #6b7280; font-size: 0.85rem;">
                        <div>Min</div><div>Sen</div><div>Sel</div><div>Rabu</div><div>Kam</div><div>Jum</div><div>Sab</div>
                    </div>
                    <div class="calendar-grid" id="gridKalenderBulan">
                        <!-- Dimuat via AJAX -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah User -->
    <div class="modal fade" id="modalTambahUser" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="border-bottom: 1px solid rgba(0,0,0,0.05);">
                    <h5 class="modal-title" style="font-weight: 700; color: #1a202c;">➕ Tambah User Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding: 2rem;">
                    @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 10px; background: rgba(220, 38, 38, 0.1); border: 1px solid rgba(220, 38, 38, 0.3); color: #991b1b; margin-bottom: 1.5rem;">
                        <strong>❌ Validasi Gagal!</strong>
                        <ul class="mb-0 mt-2" style="padding-left: 1.5rem;">
                            @foreach ($errors->all() as $error)
                            <li style="font-size: 0.9rem;">{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <form id="formTambahUser" method="POST" action="{{ route('admin.user.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-600" style="color: #374151;">Nama Lengkap</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso" style="border-radius: 10px; padding: 0.75rem; border: 1px solid #e5e7eb;">
                            @error('name')
                            <small class="text-danger" style="font-size: 0.85rem;">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-600" style="color: #374151;">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required placeholder="Contoh: budi@magang.com" style="border-radius: 10px; padding: 0.75rem; border: 1px solid #e5e7eb;">
                            @error('email')
                            <small class="text-danger" style="font-size: 0.85rem;">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-600" style="color: #374151;">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required style="border-radius: 10px; padding: 0.75rem; border: 1px solid #e5e7eb;">
                            @error('password')
                            <small class="text-danger" style="font-size: 0.85rem;">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-600" style="color: #374151;">Asal Instansi</label>
                            <input type="text" class="form-control @error('institution') is-invalid @enderror" name="institution" value="{{ old('institution') }}" required placeholder="Contoh: Universitas X" style="border-radius: 10px; padding: 0.75rem; border: 1px solid #e5e7eb;">
                            @error('institution')
                            <small class="text-danger" style="font-size: 0.85rem;">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-600" style="color: #374151;">Tanggal Masuk</label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" name="start_date" value="{{ old('start_date') }}" required style="border-radius: 10px; padding: 0.75rem; border: 1px solid #e5e7eb;">
                            @error('start_date')
                            <small class="text-danger" style="font-size: 0.85rem;">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-600" style="color: #374151;">Durasi Kerja / Magang</label>
                            <input type="text" class="form-control @error('duration') is-invalid @enderror" name="duration" value="{{ old('duration') }}" required placeholder="Contoh: 3 Bulan" style="border-radius: 10px; padding: 0.75rem; border: 1px solid #e5e7eb;">
                            @error('duration')
                            <small class="text-danger" style="font-size: 0.85rem;">{{ $message }}</small>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100" style="border-radius: 12px; padding: 0.75rem; font-weight: 700;">✓ Simpan User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-open modal jika ada validation error
        @if ($errors->any())
        document.addEventListener('DOMContentLoaded', function() {
            var modal = new bootstrap.Modal(document.getElementById('modalTambahUser'));
            modal.show();
        });
        @endif

        let selectedUserElement = null;

        function pilihUser(element, userId, nama, instansi, tglMasuk, durasi) {
            selectedUserElement = element;
            document.getElementById('judulKalender').innerText = "📅 Kalender Absensi: " + nama;
            document.getElementById('infoDetailUser').innerText = `📌 ${instansi} | 📅 ${tglMasuk} | ⏱️ ${durasi}`;
            document.getElementById('hapusUserButton').disabled = false;
            
            // Fetch calendar data via AJAX
            document.getElementById('gridKalenderBulan').innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 2rem; color: #6b7280;">⏳ Memuat data...</div>';
            
            fetch(`/admin/user/${userId}/calendar`)
                .then(response => response.json())
                .then(data => {
                    renderCalendar(data.attendances, data.permissions);
                })
                .catch(error => {
                    document.getElementById('gridKalenderBulan').innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 2rem; color: #dc2626;">❌ Gagal memuat kalender</div>';
                });
        }

        function renderCalendar(attendances, permissions) {
            const grid = document.getElementById('gridKalenderBulan');
            grid.innerHTML = '';
            
            const today = new Date();
            const year = today.getFullYear();
            const month = today.getMonth();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const firstDayIndex = new Date(year, month, 1).getDay();
            
            // Add blanks for first row
            for(let i = 0; i < firstDayIndex; i++) {
                grid.innerHTML += `<div class="calendar-day" style="background: transparent; border: none;"></div>`;
            }
            
            for(let day = 1; day <= daysInMonth; day++) {
                const dateStr = `${year}-${String(month+1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                
                let att = attendances.find(a => a.date === dateStr);
                let perm = permissions.find(p => p.date === dateStr);
                
                let cssClass = "";
                
                if (perm) {
                    if (perm.status === 'Approved') cssClass = "status-disetujui";
                    else if (perm.status === 'Rejected') cssClass = "status-ditolak";
                    else cssClass = "status-proses";
                } else if (att) {
                    cssClass = "status-hadir";
                }
                
                grid.innerHTML += `<div class="calendar-day ${cssClass}">${day}</div>`;
            }
        }

        function nonaktifkanUser() {
            if (!selectedUserElement) {
                return;
            }
            selectedUserElement.classList.add('disabled');
            selectedUserElement.setAttribute('aria-disabled', 'true');
            selectedUserElement.querySelector('span.badge').textContent = '🔴 Nonaktif';
            selectedUserElement.querySelector('span.badge').className = 'badge bg-secondary';
            selectedUserElement = null;
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalKalenderUser'));
            if (modal) {
                modal.hide();
            }
        }
    </script>
</body>
</html>