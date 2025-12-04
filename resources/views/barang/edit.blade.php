@extends('layouts.admin')

@section('title','Edit Barang - Toko Satriyo')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold text-dark-blue"><i class="fas fa-edit me-2"></i> Edit Data Barang</h1>
        <a href="{{ route('admin.barang.index') }}" class="btn btn-outline-secondary-custom btn-sm fw-semibold">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="card shadow-lg border-0 rounded-4 p-lg-4">
        <div class="card-body">
            <form action="{{ route('admin.barang.update', $barang->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="nama_barang" class="form-label fw-semibold text-dark-blue">Nama Barang <span class="text-danger">*</span></label>
                    <input type="text" name="nama_barang" id="nama_barang" class="form-control form-control-lg @error('nama_barang') is-invalid @enderror" value="{{ old('nama_barang', $barang->nama_barang) }}" required placeholder="Masukkan nama barang">
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
                            // Daftar kategori harus sama dengan yang digunakan di Kasir/Create
                            $kategoriList = ['Minuman', 'Rokok', 'Snack', 'Pembersih', 'Sembako', 'Lain-lain']; 
                        @endphp
                        @foreach($kategoriList as $kategori)
                            {{-- Memastikan kategori saat ini terpilih atau menggunakan old() saat validasi gagal --}}
                            <option value="{{ $kategori }}" 
                                {{ old('kategori', $barang->kategori) == $kategori ? 'selected' : '' }}>
                                {{ $kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                {{-- _________________________________ --}}

                <div class="mb-4">
                    <label for="harga" class="form-label fw-semibold text-dark-blue">Harga (Rp) <span class="text-danger">*</span></label>
                    <input type="number" name="harga" id="harga" class="form-control form-control-lg @error('harga') is-invalid @enderror" value="{{ old('harga', $barang->harga) }}" required placeholder="Contoh: 15000">
                    @error('harga')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="stok" class="form-label fw-semibold text-dark-blue">Stok <span class="text-danger">*</span></label>
                    <input type="number" name="stok" id="stok" class="form-control form-control-lg @error('stok') is-invalid @enderror" value="{{ old('stok', $barang->stok) }}" required placeholder="Jumlah stok saat ini">
                    @error('stok')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="gambar" class="form-label fw-semibold text-dark-blue">Ganti Gambar Produk</label>
                    
                    @if($barang->gambar)
                        <div class="mb-2 p-3 border rounded-3 bg-light d-inline-block">
                            <p class="small text-muted mb-1">Gambar Saat Ini:</p>
                            <img src="{{ asset($barang->gambar) }}" alt="Gambar Produk Saat Ini" width="120" height="120" class="rounded-3 shadow-sm object-fit-cover current-image-preview">
                        </div>
                    @else
                        <div class="alert alert-warning-custom p-2 small">
                            <i class="fas fa-exclamation-circle me-1"></i> Belum ada gambar produk.
                        </div>
                    @endif

                    <input type="file" name="gambar" id="gambar" class="form-control @error('gambar') is-invalid @enderror">
                    <div class="form-text">Biarkan kosong jika tidak ingin mengubah gambar. Max 2MB.</div>
                    @error('gambar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end pt-3">
                    <button type="submit" class="btn btn-primary-custom btn-lg fw-semibold me-2">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
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
/* 🎨 PALET WARNA CUSTOM (Sama seperti halaman Data Barang) */
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

/* 🖼️ Gambar Preview */
.current-image-preview {
    object-fit: cover;
    border: 3px solid var(--primary-medium);
}

.alert-warning-custom {
    background-color: #fff3cd;
    color: #856404;
    border: 1px solid #ffeeba;
    border-radius: 8px;
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