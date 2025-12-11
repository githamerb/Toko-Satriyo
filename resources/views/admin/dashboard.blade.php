@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-5">
        <h1 class="h3 fw-bold text-gray-800">Ringkasan Operasional Kasir</h1>
        <p class="text-muted mb-0">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-xl-4 col-md-6">
            <div class="card stat-card card-primary border-0 shadow-lg">
                <div class="card-body py-4">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <i class="fas fa-coins fa-3x text-primary-light"></i>
                        </div>
                        <div class="col">
                            <h6 class="text-muted mb-1 text-uppercase">Pendapatan Hari Ini</h6>
                            <h3 class="fw-bold mb-0">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card stat-card card-success border-0 shadow-lg">
                <div class="card-body py-4">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <i class="fas fa-receipt fa-3x text-success-light"></i>
                        </div>
                        <div class="col">
                            <h6 class="text-muted mb-1 text-uppercase">Transaksi Hari Ini</h6>
                            <h3 class="fw-bold mb-0">{{ $totalTransaksiHariIni }} Transaksi</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-12">
            <div class="card stat-card card-info border-0 shadow-lg">
                <div class="card-body py-4">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <i class="fas fa-box-open fa-3x text-info-light"></i>
                        </div>
                        <div class="col">
                            <h6 class="text-muted mb-1 text-uppercase">Total Barang Aktif</h6>
                            <h3 class="fw-bold mb-0">{{ $totalBarang }} Item</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white pt-4 pb-2 border-0">
                    <h5 class="fw-bold text-gray-700 mb-0">Penjualan Mingguan</h5>
                    <p class="small text-muted mb-0">Ringkasan 7 hari terakhir</p>
                </div>
                <div class="card-body">
                    <canvas id="chartMingguan" height="150"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white pt-4 pb-2 border-0">
                    <h5 class="fw-bold text-gray-700 mb-0">Penjualan Bulanan</h5>
                    <p class="small text-muted mb-0">Tinjauan tren bulan ini</p>
                </div>
                <div class="card-body">
                    <canvas id="chartBulanan" height="150"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white pt-4 pb-2 border-0">
                    <h5 class="fw-bold text-gray-700 mb-0">Barang Terlaris (Top 5)</h5>
                    <p class="small text-muted mb-0">Berdasarkan kuantitas penjualan</p>
                </div>
                <div class="card-body">
                    <canvas id="topBarangChart" height="180"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 border-start border-danger border-5 h-100">
                <div class="card-header bg-white pt-4 pb-2 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-danger mb-0">Peringatan Stok Rendah</h5>
                    <i class="fas fa-exclamation-triangle text-danger fa-lg"></i>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($stokRendah as $barang)
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-4">
                                <span><i class="fas fa-cubes me-2 text-warning"></i> {{ $barang->nama_barang }}</span>
                                <span class="badge bg-danger rounded-pill fw-bold">{{ $barang->stok }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-muted text-center py-4">
                                <i class="fas fa-check-circle me-1 text-success"></i> Semua stok dalam kondisi aman.
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-lg rounded-4">
        <div class="card-header bg-white pt-4 pb-2 border-0 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-gray-700 mb-0">Transaksi Terbaru</h5>
            <small class="text-muted">5 transaksi terakhir</small>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-borderless align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4">Tanggal & Waktu</th>
                        <th>ID Transaksi</th>
                        <th class="text-end px-4">Total Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksiTerbaru as $trx)
                        <tr>
                            <td class="px-4">{{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y H:i') }}</td>
                            <td><span class="fw-semibold text-primary">#{{ $trx->id }}</span></td>
                            <td class="text-end fw-bold px-4">Rp {{ number_format($trx->$kolomTotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">Belum ada transaksi yang tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Pengaturan global Chart.js
    Chart.defaults.font.family = 'system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif';
    Chart.defaults.color = '#6b7280'; // abu-abu gelap

    // 📅 Mingguan
    new Chart(document.getElementById('chartMingguan'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($labelsMingguan) !!},
            datasets: [{
                label: 'Total Penjualan (Rp)',
                data: {!! json_encode($dataMingguan) !!},
                backgroundColor: 'rgba(59,130,246,0.8)', // Biru lebih solid
                borderColor: '#2563eb',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true } },
            plugins: { legend: { display: false } }
        }
    });

    // 📈 Bulanan
    new Chart(document.getElementById('chartBulanan'), {
        type: 'line',
        data: {
            labels: {!! json_encode($labelsBulanan) !!},
            datasets: [{
                label: 'Total Penjualan (Rp)',
                data: {!! json_encode($dataBulanan) !!},
                borderColor: '#10b981',
                backgroundColor: 'rgba(16,185,129,0.2)', // Hijau lebih transparan
                tension: 0.4, // Kurva lebih halus
                fill: true,
                pointRadius: 4,
                pointBackgroundColor: '#10b981'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true } },
            plugins: { legend: { display: false } }
        }
    });

    // 🔝 Barang Terlaris
    new Chart(document.getElementById('topBarangChart'), {
        type: 'doughnut', // Mengubah pie chart ke doughnut chart
        data: {
            labels: {!! json_encode($topBarangLabels) !!},
            datasets: [{
                data: {!! json_encode($topBarangTotals) !!},
                backgroundColor: ['#4C8DFD','#1EC5B0','#F59E0B','#FF5858','#A755F7'], // Palet warna yang lebih modern
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'right', align: 'start' }
            }
        }
    });
});
</script>

<style>
/* 🌐 IMPOR FONT JIKA DIPERLUKAN (Misal: Google Font 'Poppins') */
/* @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');
body { font-family: 'Poppins', sans-serif; } */
body {
    background-color: #f8f9fa; /* Latar belakang body lebih terang */
}

/* 🎨 PALET WARNA BARU */
:root {
    --color-primary: #4C8DFD; /* Biru Baru */
    --color-primary-light: #7EADFF;
    --color-success: #1EC5B0; /* Hijau Mint */
    --color-success-light: #6EF4E0;
    --color-info: #FFC107; /* Kuning/Warning */
    --color-info-light: #FFDA6D;
    --color-danger: #FF5858; /* Merah */
    --color-gray-800: #343a40;
}

/* 🔹 STATISTIK UTAMA CARD (Modern) */
.stat-card {
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    border-radius: 16px;
    border-left: 5px solid transparent !important;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
}

/* Spesifik Card Color */
.card-primary { border-left-color: var(--color-primary) !important; }
.card-primary .text-primary-light { color: var(--color-primary-light) !important; }
.card-success { border-left-color: var(--color-success) !important; }
.card-success .text-success-light { color: var(--color-success-light) !important; }
.card-info { border-left-color: var(--color-info) !important; }
.card-info .text-info-light { color: var(--color-info-light) !important; }


/* 📊 GRAFIK & UMUM CARD */
.card {
    border-radius: 16px; /* Sudut lebih membulat */
}

/* 🧾 TRANSAKSI TERBARU */
.table th, .table td {
    vertical-align: middle;
    /* Tambahkan sedikit padding untuk keterbacaan */
    padding-top: 12px;
    padding-bottom: 12px;
}
.table-striped > tbody > tr:nth-of-type(odd) {
    background-color: #f1f5f9; /* Sedikit shading untuk baris ganjil */
}
.table-hover tbody tr:hover {
    background-color: #e2e8f0;
}
</style>
@endsection
