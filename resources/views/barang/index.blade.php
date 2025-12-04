@extends('layouts.admin')

@section('title', 'Data Barang - Toko Satriyo')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-5">
        <h1 class="h3 fw-bold text-dark-blue"><i class="fas fa-boxes me-2"></i> Data Barang Produk</h1>
        {{-- Tombol Tambah Barang --}}
        <a href="{{ route('admin.barang.create') }}" class="btn btn-primary-custom shadow-sm">
            <i class="fas fa-plus me-1"></i> Tambah Barang
        </a>
    </div>
    
    <div class="row mb-4">
        <div class="col-lg-6 col-md-8 mx-auto">
            <div class="input-group input-group-lg shadow-sm rounded-pill border-2 border-primary">
                {{-- PERBAIKAN DESAIN SEARCH BAR --}}
                <span class="input-group-text bg-white border-0 ps-3 pe-1">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input type="text" id="search" class="form-control border-0 rounded-pill ps-0" placeholder="Cari barang berdasarkan nama..." aria-label="Cari Barang">
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-lg rounded-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="barangTable">
                
                {{-- PERBAIKAN PERATAAN TEKS HEADER --}}
                <thead class="table-header-custom text-white"> 
                    <tr>
                        <th class="py-3 text-center">No</th>
                        <th class="py-3 text-start">Nama Barang</th> 
                        <th class="py-3 text-start">Kategori</th>    
                        <th class="py-3 text-center">Harga</th>
                        <th class="py-3 text-center">Stok</th>
                        <th class="py-3 text-start">Gambar</th>
                        <th class="py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                
                <tbody>
                    @forelse($barang as $item)
                        <tr data-id="{{ $item->id }}">
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="fw-semibold text-dark-blue text-start">{{ $item->nama_barang }}</td> 
                            <td class="text-start">{{ $item->kategori ?? '-' }}</td> 
                            <td class="text-center">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td class="text-center fw-bold text-{{ $item->stok < 10 ? 'danger' : 'success' }}">{{ $item->stok }}</td>
                            <td class="text-center">
                                @if($item->gambar)
                                    <img src="{{ asset($item->gambar) }}" alt="Gambar Barang" width="60" height="60" class="rounded item-thumbnail">
                                @else
                                    <i class="far fa-image text-muted"></i>
                                @endif
                            </td>
                            <td class="text-center">
                                {{-- Tombol Edit (Ikon Biru) --}}
                                <a href="{{ route('admin.barang.edit', $item->id) }}" class="btn-icon-action btn-edit me-2" title="Edit Data">
                                    <i class="fas fa-edit"></i>
                                </a>
                                {{-- Tombol Hapus (Ikon Merah) --}}
                                <button type="button" class="btn-icon-action btn-delete delete-btn" data-id="{{ $item->id }}" title="Hapus Data">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                
                                {{-- Form Hapus tersembunyi untuk SweetAlert --}}
                                <form id="delete-form-{{ $item->id }}" action="{{ route('admin.barang.destroy', $item->id) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        {{-- COLSPAN DIUBAH KE 7 --}}
                        <tr><td colspan="7" class="text-center text-muted py-4">
                            <i class="fas fa-info-circle me-1"></i> Belum ada data barang. Silakan tambahkan item baru.
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{{-- SweetAlert2 & jQuery --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<script>
$(document).ready(function() {

    // 1. INISIALISASI SweetAlert untuk sukses
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2000
        });
    @endif
    
    // 2. CONFIRM HAPUS menggunakan SweetAlert
    $('.delete-btn').on('click', function() {
        const itemId = $(this).data('id');
        
        Swal.fire({
            title: 'Yakin Ingin Menghapus?',
            text: "Data barang ini akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form hapus yang tersembunyi
                $('#delete-form-' + itemId).submit();
            }
        });
    });


    // 3. FUNGSI LIVE SEARCH DENGAN AJAX
    
    // Highlight hasil pencarian
    function highlight(text, query) {
        if (!query) return text;
        const regex = new RegExp(`(${query})`, 'gi');
        return text.replace(regex, '<span class="search-highlight">$1</span>');
    }

    // Debounce function
    function debounce(func, delay) {
        let timeout;
        return function() {
            const context = this;
            const args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), delay);
        };
    }

    // Live Search AJAX
    $('#search').on('keyup', debounce(function() {
        const query = $(this).val().trim();
        const searchInput = $('#search');
        
        // Tambahkan loading visual
        searchInput.addClass('form-control-loading'); 
        searchInput.siblings('.input-group-text').html('<i class="fas fa-spinner fa-spin text-primary"></i>');

        $.ajax({
            url: "{{ route('admin.barang.search') }}",
            type: "GET",
            data: { query: query },
            success: function(data) {
                const baseUrl = window.location.origin;
                let tbody = '';

                if(data.length > 0){
                    
                    // Urutkan (opsional): yang dimulai dari kata kunci di atas
                    if(query){
                        data.sort((a, b) => {
                            const aStarts = a.nama_barang.toLowerCase().startsWith(query.toLowerCase()) ? 0 : 1;
                            const bStarts = b.nama_barang.toLowerCase().startsWith(query.toLowerCase()) ? 0 : 1;
                            if(aStarts !== bStarts) return aStarts - bStarts;
                            return a.nama_barang.localeCompare(b.nama_barang);
                        });
                    }

                    // Render hasil pencarian
                    data.forEach((item, index) => {
                        const gambarSrc = item.gambar ? `${baseUrl}/${item.gambar}` : '';
                        const stokClass = item.stok < 10 ? 'text-danger' : 'text-success';
                        
                        tbody += `
                            <tr data-id="${item.id}">
                                <td class="text-center">${index + 1}</td>
                                <td class="fw-semibold text-dark-blue text-start">${highlight(item.nama_barang, query)}</td> 
                                <td class="text-start">${item.kategori || '-'}</td> 
                                <td>Rp ${Number(item.harga).toLocaleString('id-ID')}</td>
                                <td class="text-center fw-bold ${stokClass}">${item.stok}</td>
                                <td class="text-center">
                                    ${item.gambar ? `<img src="${gambarSrc}" width="60" height="60" class="rounded item-thumbnail">` : '<i class="far fa-image text-muted"></i>'}
                                </td>
                                <td class="text-center">
                                    <a href="${baseUrl}/admin/barang/${item.id}/edit" class="btn-icon-action btn-edit me-2" title="Edit Data">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn-icon-action btn-delete delete-btn" data-id="${item.id}" title="Hapus Data">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    <form id="delete-form-${item.id}" action="${baseUrl}/admin/barang/${item.id}" method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    // COLSPAN DIUBAH KE 7
                    tbody = `<tr><td colspan="7" class="text-center text-muted py-4"><i class="fas fa-search-minus me-1"></i> Barang tidak ditemukan.</td></tr>`;
                }

                $('#barangTable tbody').html(tbody);
            },
            error: function(err){
                console.error(err);
                $('#barangTable tbody').html(`<tr><td colspan="7" class="text-center text-danger py-4">Terjadi kesalahan saat mencari data.</td></tr>`);
            },
            complete: function() {
                // Hapus loading visual
                searchInput.removeClass('form-control-loading');
                searchInput.siblings('.input-group-text').html('<i class="fas fa-search text-muted"></i>');
                
                // Pasang kembali event handler hapus untuk tombol yang baru dimuat
                $('.delete-btn').off('click').on('click', function() {
                    const itemId = $(this).data('id');
                    Swal.fire({
                        title: 'Yakin Ingin Menghapus?',
                        text: "Data barang ini akan dihapus permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#delete-form-' + itemId).submit();
                        }
                    });
                });
            }
        });
    }, 300));
    
    {{-- ✅ CSS BARU DENGAN !important UNTUK MEMAKSA PERUBAHAN WARNA --}}
    $('head').append(`
        <style>
            /* Warna Kustom */
            :root {
                --color-tambah-btn: #3674B5; /* Hijau Tombol */
                --color-header: #3674B5;    /* Hijau Tua untuk Header */
            }

            /* 1. Warna Tombol Tambah Barang */
            .btn-primary-custom {
                background-color: var(--color-tambah-btn) !important;
                border-color: var(--color-tambah-btn) !important;
                color: white !important;
            }
            .btn-primary-custom:hover {
                background-color: var(--color-header) !important; /* Warna hover yang lebih gelap */
                border-color: var(--color-header) !important;
            }

            /* 2. Warna Tabel Header (Diberi !important) */
            .table-header-custom {
                background-color: var(--color-header) !important; 
                color: white !important; /* Memastikan teks tetap putih */
            }

            /* CSS Styling Form Pencarian */
            .input-group-lg .form-control {
                border-radius: 0 50px 50px 0 !important;
                padding-left: 1rem;
            }
            .input-group-lg .input-group-text {
                border-radius: 50px 0 0 50px !important;
                background-color: white !important;
            }
            .item-thumbnail {
                object-fit: cover;
                border: 2px solid #ddd;
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            }
            .search-highlight {
                background-color: var(--color-info-light) !important;
                padding: 1px 4px;
                border-radius: 4px;
                color: var(--text-dark-blue) !important;
            }
        </style>
    `);


});
</script>
@endsection
