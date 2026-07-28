<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi Presensi</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center py-5" style="min-height: 100vh;">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-4 p-sm-5">
                        
                        <div class="text-center mb-4">
                            <h3 class="fw-bold text-primary">Portal Admin Presensi</h3>
                            <p class="text-muted small">Silakan masuk menggunakan akun admin</p>
                        </div>

                        <!-- Form Login -->
                        <form method="POST" action="{{ url('/login') }}">
                            @csrf

                            <!-- Pesan Error Gagal Login -->
                            @if($errors->any())
                                <div class="alert alert-danger py-2 small">
                                    {{ $errors->first() }}
                                </div>
                            @endif

                            <div class="mb-3">
                                <label for="email" class="form-label">Username</label>
                                <input type="text" class="form-control" id="email" name="email" required placeholder="admin">
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required placeholder="admin">
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary py-2 fw-bold">Masuk</button>
                            </div>
                        </form>

                    </div>
                </div>
                <div class="text-center mt-3 text-muted small">
                    &copy; 2026 Aplikasi Presensi Magang
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>