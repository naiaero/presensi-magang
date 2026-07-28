<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Presensi & Kalender</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
        }
        .calendar-day {
            min-height: 80px;
            border: 1px solid #e7e7e7;
            padding: 8px;
            border-radius: 8px;
            background: #fff;
            color: #2c3e50;
            font-size: 0.85rem;
        }
        .admin-sidebar {
            background: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 16px;
        }
        .sidebar-nav .nav-link {
            color: #495057;
            border-radius: 12px;
            margin-bottom: 0.5rem;
            text-align: left;
            padding: 0.85rem 1rem;
            font-weight: 500;
        }
        .sidebar-nav .nav-link.active {
            background-color: #f5f7fa;
            color: #0d6efd;
            box-shadow: inset 0 0 0 1px rgba(13, 110, 253, 0.15);
        }
        .card, .card-header {
            border: none;
            border-radius: 16px;
        }
        .card {
            background: #ffffff;
            box-shadow: 0 10px 30px rgba(0,0,0,0.04);
        }
        .card-header {
            background: transparent;
            color: #212529;
            padding: 1rem 1.25rem;
        }
        .card-body {
            padding: 1.25rem;
        }
        .table {
            margin-bottom: 0;
        }
        .table thead th {
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }
        .badge {
            border-radius: 12px;
            font-size: 0.75rem;
            padding: 0.5em 0.65em;
        }
        .status-hadir { background-color: #e9f8ef !important; border-color: #d4eed9; }
        .status-disetujui { background-color: #fff9e6 !important; border-color: #f4e6b8; }
        .status-ditolak { background-color: #fdecea !important; border-color: #f5c6cb; }
        .status-proses { background-color: #e8f4ff !important; border-color: #cadef9; }
        .navbar-light-custom {
            background: #ffffff;
            border-bottom: 1px solid #e9ecef;
            box-shadow: 0 6px 24px rgba(0,0,0,0.04);
        }
    </style>
</head>
<body class="bg-light">

    <!-- Header Admin -->
    <header class="navbar-light-custom py-3 mb-4">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="mb-1">Dashboard Admin</h4>
                    <p class="mb-0 text-secondary small">Presensi & Kalender Magang</p>
                </div>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="adminAccountMenu" data-bs-toggle="dropdown" aria-expanded="false">
                        Akun
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminAccountMenu">
                        <li><a class="dropdown-item" href="/admin/settings">Pengaturan</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="/logout">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <div class="container my-4">
        <div class="row gx-4">
            <div class="col-lg-3 mb-3">
                <div class="card shadow-sm admin-sidebar p-3 mb-4">
                    <div class="mb-3">
                        <h5 class="mb-0">Menu Admin</h5>
                    </div>
                    <div class="nav nav-pills flex-column sidebar-nav" id="adminTab" role="tablist" aria-orientation="vertical">
                        <button class="nav-link active" id="user-tab" data-bs-toggle="tab" data-bs-target="#user-content" type="button" role="tab">Daftar User</button>
                        <button class="nav-link" id="izin-tab" data-bs-toggle="tab" data-bs-target="#izin-content" type="button" role="tab">Permintaan Izin</button>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="tab-content" id="adminTabContent">
                    
                    <!-- ================= TAB 1: USER & KALENDER ================= -->
                    <div class="tab-pane fade show active" id="user-content" role="tabpanel">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-transparent d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">
                                <div>
                                    <h5 class="mb-0">Daftar User</h5>
                                    <p class="text-muted small mb-0">Klik user untuk menampilkan kalender absensi dalam popup.</p>
                                </div>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahUser">Tambah User</button>
                            </div>
                            <div class="card-body p-0">
                                <div class="list-group list-group-flush" id="listUser">
                                    <button type="button" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" data-bs-toggle="modal" data-bs-target="#modalKalenderUser" onclick="pilihUser(this, 'Ahmad Zulfikar', 'Universitas Mataram', '01 Feb 2026', '3 Bulan')">
                                        <div>
                                            <div class="fw-semibold">Ahmad Zulfikar</div>
                                            <small class="text-muted">Universitas Mataram · Masuk 01 Feb 2026</small>
                                        </div>
                                        <span class="badge bg-primary rounded-pill">Lihat</span>
                                    </button>
                                    <button type="button" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" data-bs-toggle="modal" data-bs-target="#modalKalenderUser" onclick="pilihUser(this, 'Siti Rahma', 'SMK Negeri 1 Mataram', '15 Mar 2026', '2 Bulan')">
                                        <div>
                                            <div class="fw-semibold">Siti Rahma</div>
                                            <small class="text-muted">SMK Negeri 1 Mataram · Masuk 15 Mar 2026</small>
                                        </div>
                                        <span class="badge bg-primary rounded-pill">Lihat</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ================= TAB 2: VERIFIKASI IZIN ================= -->
                    <div class="tab-pane fade" id="izin-content" role="tabpanel">
                        <div class="card shadow-sm">
                            <div class="card-header bg-transparent">
                                <h5 class="mb-0">Permintaan Izin</h5>
                                <p class="text-muted small mb-0">Terima atau tolak permintaan izin user.</p>
                            </div>
                            <div class="card-body">
                                <div class="list-group">
                                    <div class="list-group-item d-flex flex-column gap-3">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <div class="fw-semibold">Ahmad Zulfikar</div>
                                                <small class="text-muted">4 Agustus 2026 · Menghadiri acara keluarga mendadak.</small>
                                            </div>
                                            <span class="badge status-proses text-dark">Dalam Proses</span>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-sm btn-outline-success" onclick="ubahStatusIzin(this, 'Setuju')">Terima</button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="ubahStatusIzin(this, 'Tolak')">Tolak</button>
                                        </div>
                                    </div>
                                    <div class="list-group-item d-flex flex-column gap-3">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <div class="fw-semibold">Siti Rahma</div>
                                                <small class="text-muted">10 Agustus 2026 · Sakit demam (melampirkan surat dokter).</small>
                                            </div>
                                            <span class="badge status-proses text-dark">Dalam Proses</span>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-sm btn-outline-success" onclick="ubahStatusIzin(this, 'Setuju')">Terima</button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="ubahStatusIzin(this, 'Tolak')">Tolak</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal Kalender User -->
    <div class="modal fade" id="modalKalenderUser" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content rounded-4">
                <div class="modal-header border-0">
                    <div>
                        <h5 class="modal-title" id="judulKalender">Kalender Absensi</h5>
                        <p class="text-muted small mb-0" id="infoDetailUser">Pilih user untuk melihat detail kalender.</p>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <button type="button" class="btn btn-sm btn-outline-warning" id="hapusUserButton" onclick="nonaktifkanUser()" disabled>Nonaktifkan User</button>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-wrap gap-2 mb-3 small">
                        <span class="badge status-hadir text-dark border">Hadir</span>
                        <span class="badge status-disetujui text-dark border">Izin Disetujui</span>
                        <span class="badge status-ditolak text-dark border">Izin Ditolak</span>
                        <span class="badge status-proses text-dark border">Proses Izin</span>
                    </div>
                    <h6 class="mb-3">Periode: Agustus 2026</h6>
                    <div class="calendar-grid text-center fw-bold mb-2 text-secondary small">
                        <div>Min</div><div>Sen</div><div>Sel</div><div>Rabu</div><div>Kam</div><div>Jum</div><div>Sab</div>
                    </div>
                    <div class="calendar-grid" id="gridKalenderBulan">
                        <div class="calendar-day status-hadir">1</div>
                        <div class="calendar-day bg-white text-muted">2</div>
                        <div class="calendar-day status-hadir">3</div>
                        <div class="calendar-day status-disetujui">4</div>
                        <div class="calendar-day status-proses">5</div>
                        <div class="calendar-day status-ditolak">6</div>
                        <div class="calendar-day status-hadir">7</div>
                        <div class="calendar-day status-hadir">8</div>
                        <div class="calendar-day bg-white text-muted">9</div>
                        <div class="calendar-day status-hadir">10</div>
                        <div class="calendar-day status-hadir">11</div>
                        <div class="calendar-day status-hadir">12</div>
                        <div class="calendar-day status-hadir">13</div>
                        <div class="calendar-day status-hadir">14</div>
                        <div class="calendar-day status-hadir">15</div>
                        <div class="calendar-day bg-white text-muted">16</div>
                        <div class="calendar-day status-hadir">17</div>
                        <div class="calendar-day status-hadir">18</div>
                        <div class="calendar-day status-hadir">19</div>
                        <div class="calendar-day status-hadir">20</div>
                        <div class="calendar-day status-hadir">21</div>
                        <div class="calendar-day status-hadir">22</div>
                        <div class="calendar-day bg-white text-muted">23</div>
                        <div class="calendar-day status-hadir">24</div>
                        <div class="calendar-day status-hadir">25</div>
                        <div class="calendar-day status-hadir">26</div>
                        <div class="calendar-day status-hadir">27</div>
                        <div class="calendar-day status-hadir">28</div>
                        <div class="calendar-day status-hadir">29</div>
                        <div class="calendar-day bg-white text-muted">30</div>
                        <div class="calendar-day status-hadir">31</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah User -->
    <div class="modal fade" id="modalTambahUser" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah User Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formTambahUser">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" required placeholder="Contoh: Budi Santoso">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Asal Instansi</label>
                            <input type="text" class="form-control" required placeholder="Contoh: Universitas X">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Masuk</label>
                            <input type="date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Durasi Kerja / Magang</label>
                            <input type="text" class="form-control" required placeholder="Contoh: 3 Bulan">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Simpan User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let selectedUserElement = null;

        // Fungsi simulasi klik user untuk memuat kalender spesifik
        function pilihUser(element, nama, instansi, tglMasuk, durasi) {
            selectedUserElement = element;
            document.getElementById('judulKalender').innerText = "Kalender Absensi: " + nama;
            document.getElementById('infoDetailUser').innerText = `Asal Instansi: ${instansi} | Durasi: ${durasi} (Masuk: ${tglMasuk})`;
            document.getElementById('hapusUserButton').disabled = false;
        }

        function nonaktifkanUser() {
            if (!selectedUserElement) {
                return;
            }
            selectedUserElement.classList.add('disabled');
            selectedUserElement.setAttribute('aria-disabled', 'true');
            selectedUserElement.querySelector('span.badge').textContent = 'Nonaktif';
            selectedUserElement.querySelector('span.badge').className = 'badge bg-secondary';
            selectedUserElement = null;
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalKalenderUser'));
            if (modal) {
                modal.hide();
            }
        }

        // Simulasi tombol aksi verifikasi izin
        function ubahStatusIzin(button, aksi) {
            const item = button.closest('.list-group-item');
            const badge = item.querySelector('.badge');
            if (aksi === 'Setuju') {
                badge.className = 'badge bg-warning text-dark';
                badge.textContent = 'Disetujui';
            } else {
                badge.className = 'badge bg-danger';
                badge.textContent = 'Ditolak';
            }
            button.closest('.d-flex').querySelectorAll('button').forEach(btn => btn.disabled = true);
        }
    </script>
</body>
</html>