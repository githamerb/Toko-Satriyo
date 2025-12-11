@extends('layouts.admin')

@section('title', 'Laporan Pembelian')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-start align-items-center mb-4">
        <h1 class="h3 fw-bold text-dark-blue"><i class="fas fa-file-invoice-dollar me-2"></i> Laporan Pembelian Barang</h1>
    </div>

    <div class="card shadow-lg border-0 rounded-4">
        
        <div class="card-header bg-primary-custom text-white d-flex justify-content-between align-items-center rounded-top-4 py-3">
            <h5 class="mb-0 fw-bold">Detail Pembelian</h5>
            <span class="badge bg-success-custom fs-6 fw-semibold py-2 px-3">
                Total Biaya: Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}
            </span>
        </div>

        <div class="card-body p-4 bg-white">
            
            <form method="GET" action="{{ route('admin.laporan.index') }}" class="row g-3 mb-4 p-3 border rounded-3 bg-light align-items-end">
                
                <div class="col-md-3 col-lg-3">
                    <label class="form-label fw-semibold text-dark-blue small mb-1">Filter Tanggal:</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ $tanggal ?? '' }}">
                </div>

                {{-- Tambahkan filter bulan/tahun jika Anda memilikinya di controller --}}
                {{--
                <div class="col-md-3 col-lg-2">
                    <label class="form-label fw-semibold text-dark-blue small mb-1">Bulan:</label>
                    <input type="month" name="bulan" class="form-control" value="{{ $bulan ?? '' }}">
                </div>
                --}}

                <div class="col-md-3 col-lg-2">
                    <button type="submit" class="btn btn-primary-custom fw-semibold w-100 shadow-sm">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                </div>

                <div class="col-md-3 col-lg-2">
                    <a href="{{ route('admin.laporan.index') }}" class="btn btn-secondary-custom fw-semibold w-100 shadow-sm">
                        <i class="fas fa-sync-alt me-1"></i> Reset
                    </a>
                </div>

                <div class="col-md-3 col-lg-3">
                    <a href="{{ route('admin.laporan.pdf', [
                            'tanggal' => $tanggal ?? '',
                            'bulan' => $bulan ?? '',
                            'tahun' => $tahun ?? ''
                        ]) }}" target="_blank" class="btn btn-danger-custom fw-semibold w-100 shadow-sm">
                        <i class="fas fa-file-pdf me-1"></i> Cetak PDF
                    </a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    
                    {{-- Header Tabel - Biru Gelap --}}
                    <thead class="table-header-custom text-white text-center">
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Harga Satuan</th>
                            <th>Total Biaya</th>
                            <th>Metode Bayar</th>
                            <th>Tanggal Pembelian</th>
                        </tr>
                    </thead>
                    
                    <tbody class="text-center">
                        @forelse ($laporan as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="fw-semibold text-dark-blue">{{ $item->nama_barang }}</td>
                                <td>{{ $item->jumlah }}</td>
                                <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td class="fw-bold text-danger">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge {{ $item->metode_pembayaran == 'Tunai' ? 'bg-info text-dark' : 'bg-secondary' }}">
                                        {{ $item->metode_pembayaran }}
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_pembelian)->translatedFormat('d F Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-muted text-center py-4">
                                    <i class="fas fa-info-circle me-1"></i> Tidak ada data pembelian untuk periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{{-- CSS Kustom Tambahan untuk Laporan --}}
<style>
/* CATATAN: CSS KUSTOM INI HARUS DIPINDAHKAN KE layouts/admin.blade.php
    AGAR WARNA BERUBAH DI SEMUA HALAMAN. 
    Saya hanya menyertakan beberapa kelas yang mungkin belum ada:
*/
:root {
    --color-primary: #3674B5; /* Biru Baru */
    --color-success: #1EC5B0; /* Hijau Mint */
    --color-secondary: #adb5bd; /* Abu-abu */
    --color-danger: #FF5858; /* Merah */
}

.bg-primary-custom {
    background-color: var(--color-primary) !important;
}
.bg-success-custom {
    background-color: var(--color-success) !important;
}

.btn-primary-custom {
    background-color: var(--color-primary) !important;
    border-color: var(--color-primary) !important;
}
.btn-primary-custom:hover {
    background-color: #3f7de0 !important;
    border-color: #3f7de0 !important;
}

.btn-secondary-custom {
    background-color: var(--color-secondary) !important;
    border-color: var(--color-secondary) !important;
}

.btn-danger-custom {
    background-color: var(--color-danger) !important;
    border-color: var(--color-danger) !important;
}

.table-header-custom {
    background-color: #1A3E6F; /* Biru Sidebar */
}
</style>
@endsection
