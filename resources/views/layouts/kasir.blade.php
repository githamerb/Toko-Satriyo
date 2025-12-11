<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kasir - Toko Satriyo')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- 🎨 CSS KUSTOM KASIR --}}
    <style>
        :root {
            --kasir-primary: #3674B5; /* Biru Gelap yang diminta */
            --kasir-accent: #1EC5B0; /* Hijau Mint untuk aksi/success */
            --kasir-bg: #f8f9fa; /* Latar Belakang Body */
            --kasir-text: #213d5a; /* Teks Gelap */
        }
        
        body {
            background-color: var(--kasir-bg) !important;
        }

        /* Navbar Styling */
        .navbar-kasir {
            background-color: var(--kasir-primary) !important;
        }

        /* Tombol Aksi */
        .btn-kasir-primary {
            background-color: var(--kasir-primary) !important;
            border-color: var(--kasir-primary) !important;
            color: white !important;
        }
        .btn-kasir-primary:hover {
            background-color: #2a5a8f !important; /* Biru sedikit lebih gelap */
            border-color: #2a5a8f !important;
        }

        .text-kasir-primary {
            color: var(--kasir-primary) !important;
        }
    </style>
    @yield('head')
</head>
<body>

    <nav class="navbar navbar-kasir shadow mb-4">
        <div class="container d-flex justify-content-between align-items-center py-2">
            <div class="d-flex align-items-center">
                <i class="fas fa-cash-register text-white fs-4 me-3"></i>
                <span class="navbar-brand text-white mb-0 h1 fw-bold">Kasir Toko Satriyo</span>
            </div>
            
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
            <button class="btn btn-light fw-semibold"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt me-1"></i> Logout
            </button>
        </div>
    </nav>

    <div class="container-fluid pt-3">
        @yield('content')
    </div>

    {{-- Font Awesome untuk ikon --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
