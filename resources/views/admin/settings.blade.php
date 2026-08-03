<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background:
                radial-gradient(circle at top left, rgba(59,130,246,0.18), transparent 24%),
                linear-gradient(180deg, #eef4ff 0%, #f8fafc 100%);
            font-family: Inter, "Segoe UI", Arial, sans-serif;
        }

        .admin-hero {
            background: linear-gradient(135deg, #1d4ed8 0%, #3730a3 100%);
            color: white;
        }

        .admin-card {
            max-width: 780px;
            border: 1px solid rgba(148, 163, 184, 0.22);
        }

        .form-control,
        .form-check-input {
            font-size: 1rem;
        }

        .form-label,
        .form-check-label,
        .form-text {
            font-size: 0.98rem;
        }

        .btn {
            font-size: 0.98rem;
            padding: 0.75rem 1.15rem;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="card admin-card shadow-lg mx-auto border-0 rounded-4 overflow-hidden mb-4">
            <div class="admin-hero card-body p-4 p-md-5">
                <h1 class="mb-2 fw-black fs-3 fs-md-2">Pengaturan Akun Admin</h1>
                <p class="mb-0 text-white-50 fs-6">Ubah username, password, dan informasi akun lainnya.</p>
            </div>
        </div>

        <div class="card admin-card shadow-sm mx-auto border-0 rounded-4 overflow-hidden">
            <div class="card-body p-4 p-md-5">
                @if(session('success'))
                    <div class="alert alert-success fs-6">{{ session('success') }}</div>
                @endif
                <form method="POST" action="/admin/settings">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username</label>
                        <input type="text" name="username" class="form-control form-control-lg" value="{{ $currentUsername }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password Baru</label>
                        <input type="password" name="password" class="form-control form-control-lg" placeholder="Kosongkan jika tidak diubah">
                        <div class="form-text text-secondary">Isi hanya bila ingin mengganti password.</div>
                    </div>
                    <div class="mb-4 form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="active" value="1" id="adminActive" {{ $adminActive ? 'checked' : '' }}>
                        <label class="form-check-label fw-medium" for="adminActive">Akun Admin Aktif</label>
                    </div>
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <a href="/admin/dashboard" class="btn btn-outline-secondary btn-lg">Kembali ke Dashboard</a>
                        <button type="submit" class="btn btn-primary btn-lg fw-semibold">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>