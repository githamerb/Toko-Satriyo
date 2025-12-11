<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Toko Satriyo')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* 🎨 PALET WARNA KUSTOM BIRU */
        :root {
            --sidebar-bg: #1A3E6F; /* Biru Gelap Tua untuk Sidebar */
            --sidebar-accent: #3674B5; /* Biru Primary untuk Active/Hover */
            --link-color: #d1d5db; /* Abu-abu terang untuk link non-aktif */
            --text-white: #fff;
            --content-bg: #f4f7f9; /* Latar belakang konten */
            --navbar-bg: #fff;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--content-bg);
            /* Memastikan sidebar yang fixed tidak menghalangi scroll */
            overflow-x: hidden;
        }

        /* ----------------------- */
        /* ⚓ SIDEBAR STYLING */
        /* ----------------------- */
        .sidebar {
            width: 250px;
            background-color: var(--sidebar-bg);
            color: var(--text-white);
            transition: all 0.3s ease;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1050; /* Di atas konten */
        }
        .sidebar.collapsed {
            width: 80px;
        }

        /* Logo/Brand */
        .sidebar .logo-brand {
            padding: 1rem 0.5rem;
            color: var(--text-white);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Link Navigasi */
        .sidebar .nav-link {
            color: var(--link-color);
            text-decoration: none;
            transition: all 0.2s;
            margin-right: 0;
            border-radius: 8px; /* Sudut membulat */
            padding: 0.75rem 1rem !important; /* Padding yang lebih baik */
        }
        .sidebar .nav-link:hover {
            background-color: var(--sidebar-accent); /* Biru Primary saat hover */
            color: var(--text-white);
        }
        .sidebar .nav-link.active {
            background-color: var(--sidebar-accent) !important;
            color: var(--text-white) !important;
            font-weight: 600;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Sembunyikan Teks */
        .sidebar.collapsed .nav-text {
            display: none;
        }
        .sidebar.collapsed .nav-link {
            justify-content: center; /* Ikon di tengah */
            padding: 0.75rem 0.5rem !important;
        }
        .sidebar.collapsed .nav-link i {
            margin-right: 0 !important;
        }
        
        /* Garis Pemisah */
        .sidebar hr {
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Logout Link */
        .sidebar .nav-link.logout-link {
            color: #ff5050 !important; /* Merah untuk logout */
        }
        .sidebar .nav-link.logout-link:hover {
            background-color: #331f1f; /* Merah gelap saat hover */
            color: #fff !important;
        }

        /* ----------------------- */
        /* 📦 KONTEN UTAMA */
        /* ----------------------- */
        .content {
            margin-left: 250px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }
        .content.collapsed {
            margin-left: 80px;
        }

        /* Navbar Konten */
        .content .navbar {
            height: 65px; /* Ketinggian tetap */
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05) !important;
        }

        .toggle-btn {
            cursor: pointer;
            transition: transform 0.3s;
            color: var(--sidebar-bg) !important;
        }
    </style>
</head>
<body>

<div class="d-flex">
    <div id="sidebar" class="sidebar vh-100 p-3 d-flex flex-column position-fixed">
        <div class="d-flex align-items-center mb-4 logo-brand">
            <i class="bi bi-shop fs-3 me-2"></i>
            <h5 class="fw-bold mb-0 nav-text">Toko Satriyo</h5>
        </div>

        <ul class="nav flex-column gap-1">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link d-flex align-items-center {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 fs-5 me-3"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.barang.index') }}" class="nav-link d-flex align-items-center {{ request()->routeIs('admin.barang.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam fs-5 me-3"></i>
                    <span class="nav-text">Data Barang</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.laporan.index') }}" class="nav-link d-flex align-items-center {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-bar-graph fs-5 me-3"></i>
                    <span class="nav-text">Laporan</span>
                </a>
            </li>
            <hr class="my-3">
            <li class="nav-item mt-auto">
                <a href="{{ route('logout') }}" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                   class="nav-link d-flex align-items-center logout-link">
                    <i class="bi bi-box-arrow-right fs-5 me-3"></i>
                    <span class="nav-text">Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            </li>
        </ul>
    </div>

    <div id="content" class="content flex-grow-1">
        <nav class="navbar navbar-light bg-white shadow-sm px-4">
            <div class="d-flex align-items-center">
                <i class="bi bi-list fs-3 toggle-btn me-4" id="toggleSidebar"></i>
                <h5 class="fw-semibold mb-0 text-dark">@yield('title', 'Dashboard')</h5>
            </div>
        </nav>

        <div class="p-4 p-lg-5">
            @yield('content')
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')

<script>
    document.getElementById('toggleSidebar').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('collapsed');
        document.getElementById('content').classList.toggle('collapsed');
    });
</script>

</body>
</html>
