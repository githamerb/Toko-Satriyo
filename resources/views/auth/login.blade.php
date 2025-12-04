<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Toko Satriyo</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --ts-primary: #3674B5;
            --ts-light-blue: #589ae3; 
            --ts-gradient-start: #2a5a8f; 
        }

        body {
            background: linear-gradient(135deg, var(--ts-gradient-start), var(--ts-primary));
            font-family: 'Inter', sans-serif;
        }

        .login-card {
            backdrop-filter: blur(12px);
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 1.5rem;
            box-shadow: 0 8px 30px rgba(0,0,0,0.3);
            border: 1px solid rgba(255, 255, 255, 0.5);
            transition: all 0.3s ease;
        }

        .btn-ts-primary {
            background-color: var(--ts-primary) !important;
            border-color: var(--ts-primary) !important;
            color: white !important;
            transition: all 0.2s ease;
        }
        .btn-ts-primary:hover {
            background-color: var(--ts-gradient-start) !important;
            border-color: var(--ts-gradient-start) !important;
            transform: translateY(-1px);
        }

        /* Button Outline Registrasi */
        .btn-ts-outline {
            border: 2px solid var(--ts-primary) !important;
            color: var(--ts-primary) !important;
            background-color: transparent !important;
            transition: 0.2s ease;
        }
        .btn-ts-outline:hover {
            background-color: var(--ts-primary) !important;
            color: white !important;
        }

        .form-control:focus {
            border-color: var(--ts-light-blue);
            box-shadow: 0 0 0 0.25rem rgba(54, 116, 181, 0.25);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">

                <div class="login-card p-4 p-md-5 mt-4">
                    
                    <div class="text-center mb-5">
                        <i class="fas fa-store-alt fa-3x text-ts-primary mb-3"></i>
                        <h3 class="fw-bolder text-gray-800 mb-1">Toko Satriyo</h3>
                        <p class="text-muted fw-semibold mb-0">Sistem Manajemen Kasir</p>
                    </div>

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show fw-semibold" role="alert">
                            <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show fw-semibold" role="alert">
                            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('login.process') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-gray-700">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user-circle"></i></span>
                                <input type="email" name="email" class="form-control" placeholder="user@satriyo.com" required value="{{ old('email') }}" autofocus>
                            </div>
                            @error('email')
                                <p class="text-danger text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-gray-700">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                            </div>
                            @error('password')
                                <p class="text-danger text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-ts-primary w-100 py-2 fw-bold mt-2 rounded-pill shadow">
                            <i class="fas fa-sign-in-alt me-1"></i> MASUK
                        </button>
                    </form>

                    <!-- 🟦 Tombol Register Baru -->
                    <a href="{{ route('register') }}" 
                        class="btn btn-ts-outline w-100 py-2 fw-bold mt-3 rounded-pill">
                        <i class="fas fa-user-plus me-1"></i> Daftar Akun Baru
                    </a>

                    <div class="text-center mt-5 text-sm text-gray-600">
                        <p class="mb-0">Akses hanya untuk staf berwenang.</p>
                        <p class="mt-1">© {{ date('Y') }} <strong>Toko Satriyo</strong></p>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
