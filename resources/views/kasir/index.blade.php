@extends('layouts.kasir')

@section('title', 'Kasir - Toko Satriyo')

@section('content')
<div class="container-fluid mt-3 mb-5">

    {{-- Notifikasi - Menggunakan warna aksen yang lebih halus --}}
    <div id="notif" class="alert alert-notif text-center fw-semibold d-none shadow-sm rounded-pill py-2 fixed-top mx-auto mt-2" style="z-index: 1050; width: 300px;"></div>

    <div class="row g-3">
        {{-- 🛍️ Daftar Barang & Kategori --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg h-100 card-katalog">
                
                {{-- Header Card dengan Search Bar --}}
                <div class="card-header bg-kasir-light-blue text-dark d-flex justify-content-between align-items-center sticky-top header-barang-kasir py-2">
                    <h6 class="mb-0 fw-bold text-kasir-primary"><i class="fas fa-search me-2"></i> Cari Produk:</h6>
                    <input type="text" id="searchBarang" class="form-control form-control-sm w-75 input-search-kasir" placeholder="Ketik nama produk atau scan barcode...">
                </div>

                <div class="card-body bg-light p-0">
                    
                    {{-- Navigasi Kategori --}}
                    <div class="category-tabs px-3 pt-2 pb-1 bg-white border-bottom shadow-sm sticky-top" style="top: 0;">
                        <ul class="nav nav-pills overflow-auto flex-nowrap" id="kategoriTabs">
                            <li class="nav-item me-2">
                                <a class="nav-link tab-kategori active" href="#" data-kategori="all">SEMUA</a>
                            </li>
                            @php
                                $kategoriList = ['Minuman', 'Rokok', 'Snack', 'Pembersih', 'Sembako'];
                            @endphp
                            @foreach($kategoriList as $kategori)
                            <li class="nav-item me-2">
                                <a class="nav-link tab-kategori text-nowrap" href="#" data-kategori="{{ strtolower($kategori) }}">{{ strtoupper($kategori) }}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Konten Barang --}}
                    <div class="body-scroll p-3" style="max-height: 75vh; overflow-y: auto;">
                        <div class="row g-2" id="daftarBarang">
                            @foreach($barang as $item)
                            <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 barang-card" 
                                 data-nama="{{ strtolower($item->nama_barang) }}"
                                 data-kategori="{{ strtolower($item->kategori ?? 'uncategorized') }}">
                                <div class="card border-0 shadow-sm hover-card h-100 text-center">
                                    <div class="position-relative">
                                        <img src="{{ asset($item->gambar ?? 'https://via.placeholder.com/200x160?text=No+Image') }}" 
                                             class="card-img-top barang-gambar barang-img-fix" 
                                             data-id="{{ $item->id }}" 
                                             style="cursor:pointer;">
                                        <span class="badge bg-kasir-primary position-absolute top-0 end-0 m-2 shadow-sm fs-6 fw-bold">
                                            Rp {{ number_format($item->harga,0,',','.') }}
                                        </span>
                                    </div>
                                    <div class="card-body p-2 d-flex flex-column justify-content-between">
                                        <h6 class="fw-bolder text-kasir-text mb-1 text-truncate" title="{{ $item->nama_barang }}">{{ $item->nama_barang }}</h6> 
                                        <small class="text-muted">Stok: <span class="fw-bold text-{{ $item->stok < 10 ? 'danger' : 'success' }}">{{ $item->stok }}</span></small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            
                            <div class="col-12 text-center text-muted mt-5 d-none" id="noResults">
                                <i class="fas fa-box-open fa-3x mb-3"></i>
                                <p>Tidak ada produk yang ditemukan di kategori ini.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 🛒 Keranjang Belanja --}}
        <div class="col-lg-4">
            <div class="position-sticky" style="top: 10px;">
                <div class="card border-0 shadow-lg card-keranjang">
                    
                    {{-- ✅ PERUBAHAN: Header Keranjang menjadi Putih dengan Teks Biru --}}
                    <div class="card-header bg-white text-kasir-primary d-flex justify-content-between align-items-center fw-bold py-3 border-bottom-0">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-shopping-cart me-2"></i> Keranjang Belanja</h5>
                        <span id="cartCount" class="badge bg-kasir-primary text-white px-3 py-2 shadow-sm fw-bolder fs-6">0 Item</span>
                    </div>
                    
                    <div class="card-body bg-white p-3">
                        <div class="table-responsive" style="max-height: 50vh; overflow-y: auto;">
                            <table class="table table-sm table-borderless align-middle text-center mb-0" id="keranjangTable">
                                <thead class="table-keranjang-header text-dark sticky-top">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Jml</th>
                                        <th>Total</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <div id="keranjang-empty" class="text-center text-muted py-5" style="display:none;">
                                <i class="fas fa-box-open fa-2x mb-2"></i>
                                <p>Keranjang kosong. Klik barang untuk menambahkan.</p>
                            </div>
                        </div>

                        {{-- ✅ PERUBAHAN: Grand Total menjadi abu-abu muda dan teks total lebih besar --}}
                        <div class="d-flex justify-content-between align-items-center mt-3 p-3 bg-light rounded-3 shadow-sm border border-2">
                            <span class="fw-bolder fs-4 text-kasir-text">GRAND TOTAL:</span>
                            <span id="total-harga" class="text-kasir-primary fw-bolder fs-2">Rp 0</span>
                        </div>

                        <div class="text-center mt-4">
                            <button id="checkoutBtn" class="btn btn-lg btn-kasir-accent text-white fw-bolder shadow-lg rounded-3 w-100 py-3" style="display:none; font-size: 1.1rem;">
                                <i class="fas fa-cash-register me-2"></i> PROSES PEMBAYARAN
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Pembayaran --}}
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-kasir-primary text-white">
                <h5 class="modal-title fw-bold"><i class="fas fa-wallet me-2"></i> Pilih Metode Pembayaran</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="btn-group w-100 mb-3" role="group">
                    <input type="radio" class="btn-check" name="metode" id="tunai" value="Tunai" checked>
                    <label class="btn btn-outline-kasir-primary rounded-start-3 px-4 py-3 fw-semibold" for="tunai">💵 Tunai</label>

                    <input type="radio" class="btn-check" name="metode" id="qris" value="QRIS">
                    <label class="btn btn-outline-kasir-primary rounded-end-3 px-4 py-3 fw-semibold" for="qris">📱 QRIS</label>
                </div>
                <p class="text-muted">Pastikan metode pembayaran sesuai pelanggan.</p>
            </div>
            <div class="modal-footer border-0 d-flex justify-content-center">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="confirmPayment" class="btn btn-kasir-primary rounded-pill px-4 fw-bold">
                    <i class="fas fa-check-circle me-1"></i> Konfirmasi
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function(){

    function showNotif(msg){
        $('#notif').text(msg).removeClass('d-none');
        setTimeout(()=> $('#notif').addClass('d-none'), 2000);
    }

    function loadKeranjang(){
        $.get("{{ route('kasir.keranjang') }}", function(res){
            let tbody = '';
            let totalBelanja = 0;
            const item_count = res.length;

            if (item_count === 0) {
                $('#keranjang-empty').show();
                $('#checkoutBtn').fadeOut();
            } else {
                $('#keranjang-empty').hide();
                $('#checkoutBtn').fadeIn();
            }

            res.forEach((item,index)=>{
                totalBelanja += parseFloat(item.total);
                tbody += `<tr>
                    <td>${index+1}</td>
                    <td><span class="text-kasir-text fw-semibold">${item.nama_barang}</span></td>
                    <td>
                        <div class="d-flex justify-content-center align-items-center gap-1">
                            <button class="btn btn-xs btn-outline-danger kurang-btn" data-id="${item.id}"><i class="fas fa-minus"></i></button>
                            <span class="fw-bolder" style="width:20px;">${item.jumlah}</span>
                            <button class="btn btn-xs btn-outline-kasir-primary tambah-btn-keranjang" data-id="${item.id}"><i class="fas fa-plus"></i></button>
                        </div>
                    </td>
                    <td class="fw-bold text-success">Rp ${Number(item.total).toLocaleString('id-ID')}</td>
                    <td><button class="btn btn-sm btn-danger hapus-btn p-1" data-id="${item.id}" title="Hapus"><i class="fas fa-trash-alt fa-sm"></i></button></td>
                </tr>`;
            });

            $('#keranjangTable tbody').html(tbody);
            $('#total-harga').text('Rp ' + totalBelanja.toLocaleString('id-ID'));
            $('#cartCount').text(item_count + (item_count > 1 ? ' Items' : ' Item'));
        });
    }

    // KLIK KATEGORI (NEW)
    $('.tab-kategori').on('click', function(e) {
        e.preventDefault();
        $('.tab-kategori').removeClass('active');
        $(this).addClass('active');

        const selectedKategori = $(this).data('kategori');
        let visibleCount = 0;

        $('.barang-card').each(function() {
            const cardKategori = $(this).data('kategori');
            
            if (selectedKategori === 'all' || cardKategori === selectedKategori) {
                $(this).show();
                visibleCount++;
            } else {
                $(this).hide();
            }
        });

        // Tampilkan pesan jika tidak ada barang di kategori tersebut
        if (visibleCount === 0) {
            $('#noResults').removeClass('d-none');
        } else {
            $('#noResults').addClass('d-none');
        }
        
        // Kosongkan dan fokuskan search bar saat kategori diklik
        $('#searchBarang').val('').focus();
    });

    // klik tambah barang
    $(document).on('click', '.barang-gambar', function(){
        const id = $(this).data('id');
        $.post("{{ route('kasir.tambah') }}", {
            _token: "{{ csrf_token() }}", barang_id: id, jumlah: 1
        }, res => { res.success ? (showNotif(res.success), loadKeranjang()) : alert(res.error); });
    });

    // update jumlah
    $(document).on('click', '.tambah-btn-keranjang', function(){
        $.post("{{ route('kasir.updateJumlah') }}", {
            _token: "{{ csrf_token() }}", id: $(this).data('id'), aksi: 'tambah'
        }, res => { res.success ? loadKeranjang() : alert(res.error); });
    });

    $(document).on('click', '.kurang-btn', function(){
        $.post("{{ route('kasir.updateJumlah') }}", {
            _token: "{{ csrf_token() }}", id: $(this).data('id'), aksi: 'kurang'
        }, res => { res.success ? loadKeranjang() : alert(res.error); });
    });

    // hapus
    $(document).on('click', '.hapus-btn', function(){
        if(confirm('Hapus barang ini dari keranjang?'))
        $.post("{{ route('kasir.hapus') }}", {
            _token: "{{ csrf_token() }}", id: $(this).data('id')
        }, res => { res.success ? loadKeranjang() : alert(res.error); });
    });

    // pembayaran
    $('#checkoutBtn').click(()=> $('#paymentModal').modal('show'));
    $('#confirmPayment').click(function(){
        let metode = $('input[name="metode"]:checked').val();
        $('#paymentModal').modal('hide');
        $.post("{{ route('kasir.checkout') }}", {
            _token: "{{ csrf_token() }}", metode_pembayaran: metode
        }, res => {
            if(res.success){
                showNotif(res.success);
                loadKeranjang();
                // Membuka struk di tab baru
                window.open("{{ route('kasir.struk') }}?kode=" + res.kode_transaksi, "_blank");
            } else alert(res.error);
        });
    });

    // search (disesuaikan agar tetap berfungsi meskipun ada kategori filter)
    $('#searchBarang').on('keyup', function(){
        let val = $(this).val().toLowerCase();
        let visibleCount = 0;
        
        // Pastikan filter kategori aktif saat melakukan pencarian
        const activeKategori = $('#kategoriTabs .active').data('kategori');

        $('.barang-card').each(function(){
            const cardKategori = $(this).data('kategori');
            const cardNama = $(this).data('nama');
            
            const matchesSearch = cardNama.includes(val);
            const matchesCategory = (activeKategori === 'all' || cardKategori === activeKategori);

            if (matchesSearch && matchesCategory) {
                $(this).show();
                visibleCount++;
            } else {
                $(this).hide();
            }
        });

        // Tampilkan pesan jika tidak ada barang yang ditemukan
        if (visibleCount === 0) {
            $('#noResults').removeClass('d-none');
        } else {
            $('#noResults').addClass('d-none');
        }
    });

    loadKeranjang();
});
</script>

<style>
    /* Variabel Warna (Pastikan ini ada di layouts/kasir.blade.php) */
    :root {
        --kasir-primary: #3674B5; /* Biru Gelap yang diminta */
        --kasir-accent: #1EC5B0; /* Hijau Mint untuk aksi/success (Checkout) */
        --kasir-bg: #f8f9fa; /* Latar Belakang Body */
        --kasir-light-blue: #d0e6f9; /* ✅ Diperbaiki menjadi biru sangat muda */
        --kasir-text: #213d5a; /* Teks Gelap */
    }

    /* Kelas Konsistensi */
    .bg-kasir-primary { background-color: var(--kasir-primary) !important; }
    .text-kasir-primary { color: var(--kasir-primary) !important; }
    .btn-kasir-primary { background-color: var(--kasir-primary) !important; border-color: var(--kasir-primary) !important; color: white !important; }
    .btn-kasir-primary:hover { background-color: #2a5a8f !important; border-color: #2a5a8f !important; }
    .btn-outline-kasir-primary { border-color: var(--kasir-primary) !important; color: var(--kasir-primary) !important; }
    .btn-outline-kasir-primary:hover { background-color: var(--kasir-primary) !important; color: white !important; }
    .bg-kasir-light-blue { background-color: var(--kasir-light-blue) !important; }
    .btn-kasir-accent { background-color: var(--kasir-accent) !important; border-color: var(--kasir-accent) !important; color: white !important; }
    .btn-kasir-accent:hover { background-color: #179e8c !important; border-color: #179e8c !important; }
    .alert-notif { background-color: var(--kasir-light-blue) !important; color: var(--kasir-primary) !important; border-color: var(--kasir-primary) !important; }

    /* Navigasi Kategori (Tab) */
    .nav-pills .nav-link {
        color: var(--kasir-text);
        font-weight: 500;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        transition: all 0.2s ease;
        border: 1px solid transparent;
        margin-bottom: 0.5rem;
    }
    .nav-pills .nav-link:hover {
        background-color: var(--kasir-light-blue);
    }
    .nav-pills .nav-link.active {
        color: white !important;
        background-color: var(--kasir-primary) !important;
        box-shadow: 0 2px 5px rgba(54, 116, 181, 0.4);
    }
    .category-tabs {
        white-space: nowrap;
        overflow-x: auto;
    }
    .category-tabs::-webkit-scrollbar {
        height: 6px;
    }
    .category-tabs::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 3px;
    }

    /* Styling Tambahan */
    .barang-img-fix { height: 160px; width: 100%; object-fit: cover; border-radius: 0.25rem 0.25rem 0 0; transition: opacity 0.3s ease; }
    .hover-card { transition: all 0.3s ease; border-radius: 12px; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.05); border: 1px solid #eee; }
    .hover-card:hover { transform: translateY(-3px); box-shadow: 0 6px 15px rgba(0,0,0,0.1); border: 2px solid var(--kasir-primary) !important; }
    .barang-gambar:hover { opacity: 0.95; transform: scale(1.0); } 
    .header-barang-kasir { border-radius: 0.375rem 0.375rem 0 0; }
    .table-keranjang-header { background-color: var(--kasir-light-blue) !important; color: var(--kasir-text) !important; font-weight: 600; }
    .btn-xs { padding: 0.2rem 0.4rem; font-size: 0.75rem; line-height: 1; border-radius: 0.2rem; }
    .table-responsive::-webkit-scrollbar { width: 6px; }
    .body-scroll::-webkit-scrollbar { width: 8px; }
    .body-scroll::-webkit-scrollbar-thumb { background-color: #aaa; border-radius: 4px; }
    #keranjangTable thead.table-keranjang-header tr th { top: 0; position: sticky; background-color: var(--kasir-light-blue); z-index: 10; box-shadow: 0 2px 2px rgba(0,0,0,0.05); }
</style>
@endsection
