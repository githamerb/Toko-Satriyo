@extends('layouts.admin')

@section('title','Tambah Barang - Toko Satriyo')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold text-dark-blue"><i class="fas fa-plus-circle me-2"></i> Tambah Barang Baru</h1>
        <a href="{{ route('admin.barang.index') }}" class="btn btn-outline-secondary-custom btn-sm fw-semibold">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="card shadow-lg border-0 rounded-4 p-lg-4">
        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger-custom mb-4">
                    <h6 class="fw-bold"><i class="fas fa-exclamation-triangle me-1"></i> Terjadi Kesalahan Input:</h6>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.barang.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label for="nama_barang" class="form-label fw-semibold text-dark-blue">Nama Barang <span class="text-danger">*</span></label>
                    <input type="text" name="nama_barang" id="nama_barang" class="form-control form-control-lg @error('nama_barang') is-invalid @enderror" value="{{ old('nama_barang') }}" required placeholder="Masukkan nama barang">
                    @error('nama_barang')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                {{-- ✅ FIELD BARU: KATEGORI --}}
                <div class="mb-4">
                    <label for="kategori" class="form-label fw-semibold text-dark-blue">Kategori <span class="text-danger">*</span></label>
                    <select name="kategori" id="kategori" class="form-select form-control-lg @error('kategori') is-invalid @enderror" required>
                        <option value="">-- Pilih Kategori --</option>
                        @php
                            // Daftar kategori harus sama dengan yang digunakan di tampilan Kasir
                            $kategoriList = ['Minuman', 'Rokok', 'Snack', 'Pembersih', 'Sembako', 'Lain-lain']; 
                        @endphp
                        @foreach($kategoriList as $kategori)
                            <option value="{{ $kategori }}" {{ old('kategori') == $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
                        @endforeach
                    </select>
                    @error('kategori')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                {{-- _________________________________ --}}

                <div class="mb-4">
                    <label for="harga" class="form-label fw-semibold text-dark-blue">Harga (Rp) <span class="text-danger">*</span></label>
                    <input type="number" name="harga" id="harga" class="form-control form-control-lg @error('harga') is-invalid @enderror" value="{{ old('harga') }}" required placeholder="Contoh: 15000">
                    @error('harga')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="stok" class="form-label fw-semibold text-dark-blue">Stok <span class="text-danger">*</span></label>
                    <input type="number" name="stok" id="stok" class="form-control form-control-lg @error('stok') is-invalid @enderror" value="{{ old('stok') }}" required placeholder="Jumlah stok awal">
                    @error('stok')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="gambar" class="form-label fw-semibold text-dark-blue">Gambar Produk (Opsional)</label>
                    <input type="file" name="gambar" id="gambar" class="form-control @error('gambar') is-invalid @enderror">
                    <div class="form-text">Maksimal ukuran file 2MB. Format: jpg, jpeg, png.</div>
                    @error('gambar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end pt-3">
                    <button type="submit" class="btn btn-primary-custom btn-lg fw-semibold me-2">
                        <i class="fas fa-check-circle me-1"></i> Simpan Barang
                    </button>
                    <a href="{{ route('admin.barang.index') }}" class="btn btn-secondary-custom btn-lg">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
/* 🎨 PALET WARNA CUSTOM */
:root {
    --primary-dark: #3674B5; /* Biru Gelap - Utama */
    --primary-medium: #578FCA; /* Biru Sedang - Sekunder/Aksen */
    --dark-blue-text: #213d5a; /* Teks gelap */
    --light-blue-bg: #eef4f9;
}

/* 🌈 Global Styling */
body {
    background-color: #f8f9fa;
}

.text-dark-blue {
    color: var(--dark-blue-text) !important;
}

/* 📝 Form Card */
.card {
    border-radius: 16px; /* Sudut membulat */
}

/* 🏷️ Input Fields */
.form-control-lg, .form-select.form-control-lg {
    border-radius: 10px;
    padding: 1rem 1rem;
    border: 1px solid #ced4da;
    transition: all 0.2s;
}

.form-control-lg:focus, .form-select.form-control-lg:focus {
    border-color: var(--primary-medium);
    box-shadow: 0 0 0 0.25rem rgba(54, 116, 181, 0.25);
}

.form-label {
    font-size: 0.95rem;
}

/* 🚨 Error Alert (Jika ada validasi) */
.alert-danger-custom {
    background-color: #f8d7da;
    color: #721c24;
    border-color: #f5c6cb;
    border-radius: 10px;
}
.alert-danger-custom h6 {
    color: #721c24;
}

/* uttons Aksi */
.btn-primary-custom {
    background-color: var(--primary-dark) !important;
    border-color: var(--primary-dark) !important;
    color: white !important;
    border-radius: 10px;
    padding: 10px 25px;
    transition: all 0.3s;
}
.btn-primary-custom:hover {
    background-color: var(--primary-medium) !important;
    border-color: var(--primary-medium) !important;
    box-shadow: 0 4px 10px rgba(54, 116, 181, 0.3) !important;
}

.btn-secondary-custom {
    background-color: #adb5bd !important;
    border-color: #adb5bd !important;
    color: white !important;
    border-radius: 10px;
    padding: 10px 25px;
    transition: all 0.3s;
}
.btn-secondary-custom:hover {
    background-color: #6c757d !important;
    border-color: #6c757d !important;
}

.btn-outline-secondary-custom {
    color: var(--primary-dark) !important;
    border-color: var(--primary-dark) !important;
    border-radius: 50px;
    transition: all 0.2s;
}
.btn-outline-secondary-custom:hover {
    background-color: var(--light-blue-bg) !important;
    color: var(--primary-dark) !important;
}
</style>
@endsection