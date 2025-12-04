<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Toko Satriyo</title>

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

        .register-card {
            backdrop-filter: blur(12px);
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 1.5rem;
            box-shadow: 0 8px 30px rgba(0,0,0,0.3);
            border: 1px solid rgba(255, 255, 255, 0.5);
            transition: all 0.3s ease;
        }

        .btn-ts-primary {
            background-color: var(--ts-primary) !important;
            color: white !important;
        }
        .btn-ts-primary:hover {
            background-color: var(--ts-gradient-start) !important;
            transform: translateY(-1px);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">

                <div class="register-card p-4 p-md-5 mt-4">

                    <div class="text-center mb-5">
                        <i class="fas fa-user-plus fa-3x text-ts-primary mb-3"></i>
                        <h3 class="fw-bolder text-gray-800 mb-1">Buat Akun Baru</h3>
                        <p class="text-muted fw-semibold mb-0">Untuk akses admin / kasir</p>
                    </div>

                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('register.process') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" name="name" class="form-control" placeholder="Nama lengkap"
                                       value="{{ old('name') }}" required>
                            </div>
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="email" class="form-control" placeholder="email@satriyo.com"
                                       value="{{ old('email') }}" required>
                            </div>
                            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Role</label>
                            <select name="role" class="form-select" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="admin">Admin</option>
                                <option value="kasir">Kasir</option>
                            </select>
                            @error('role') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" class="form-control"
                                       placeholder="Minimal 6 karakter" required>
                            </div>
                            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <button class="btn btn-ts-primary w-100 py-2 fw-bold rounded-pill">
                            <i class="fas fa-save me-1"></i> Buat Akun
                        </button>
                    </form>
                    <p class="text-center text-sm text-gray-600 mt-6">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:underline">Login di sini</a>
            </p>

                    

                </div>

            </div>
        </div>
    </div>

</body>
</html>
