<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KasirController extends Controller
{
    // 📦 Halaman utama kasir
    public function index()
    {
        $barang = DB::table('barang')->get();
        return view('kasir.index', compact('barang'));
    }

    // ➕ Tambah ke keranjang
    public function tambah(Request $request)
    {
        $barang = DB::table('barang')->where('id', $request->barang_id)->first();
        if (!$barang) return response()->json(['error' => 'Barang tidak ditemukan!']);

        $userId = auth()->id() ?? null;

        // Cari apakah barang sudah ada di keranjang
        $cek = DB::table('pembelian')
            ->whereNull('metode_pembayaran')
            ->where('barang_id', $barang->id)
            ->first();

        if ($cek) {
            DB::table('pembelian')->where('id', $cek->id)->update([
                'jumlah' => $cek->jumlah + $request->jumlah,
                'total' => ($cek->jumlah + $request->jumlah) * $barang->harga,
                'updated_at' => now(),
            ]);
        } else {
            DB::table('pembelian')->insert([
                'user_id' => $userId,
                'barang_id' => $barang->id,
                'jumlah' => $request->jumlah,
                'harga' => $barang->harga,
                'total' => $barang->harga * $request->jumlah,
                'created_at' => now(),
                'updated_at' => now(),
                'metode_pembayaran' => null,
                'tanggal_pembelian' => null,
                'kode_transaksi' => null,
            ]);
        }

        return response()->json(['success' => 'Barang berhasil ditambahkan ke keranjang!']);
    }

    // 🛒 Ambil isi keranjang
    public function keranjang()
    {
        $keranjang = DB::table('pembelian')
            ->join('barang', 'pembelian.barang_id', '=', 'barang.id')
            ->select('pembelian.*', 'barang.nama_barang', 'barang.gambar')
            ->whereNull('pembelian.metode_pembayaran')
            ->get();

        return response()->json($keranjang);
    }

    // 🔁 Update jumlah
    public function updateJumlah(Request $request)
    {
        $pembelian = DB::table('pembelian')->where('id', $request->id)->first();
        if (!$pembelian) return response()->json(['error' => 'Data tidak ditemukan!']);

        $newJumlah = $request->aksi === 'tambah'
            ? $pembelian->jumlah + 1
            : max(1, $pembelian->jumlah - 1);

        DB::table('pembelian')->where('id', $request->id)->update([
            'jumlah' => $newJumlah,
            'total' => $newJumlah * $pembelian->harga,
            'updated_at' => now(),
        ]);

        return response()->json(['success' => 'Jumlah barang diperbarui!']);
    }

    // ❌ Hapus item
    public function hapus(Request $request)
    {
        $deleted = DB::table('pembelian')->where('id', $request->id)->delete();
        return $deleted
            ? response()->json(['success' => 'Barang dihapus dari keranjang!'])
            : response()->json(['error' => 'Gagal menghapus barang!']);
    }

    // 💳 Checkout dan bayar
    public function checkout(Request $request)
    {
        $keranjang = DB::table('pembelian')->whereNull('metode_pembayaran')->get();
        if ($keranjang->isEmpty()) return response()->json(['error' => 'Keranjang kosong!']);

        $kodeTransaksi = 'TRX' . now()->format('YmdHis');

        foreach ($keranjang as $item) {
            // Kurangi stok barang
            DB::table('barang')->where('id', $item->barang_id)->decrement('stok', $item->jumlah);

            // Update pembelian
            DB::table('pembelian')->where('id', $item->id)->update([
                'metode_pembayaran' => $request->metode_pembayaran ?? 'Tunai',
                'tanggal_pembelian' => now(),
                'kode_transaksi' => $kodeTransaksi,
                'updated_at' => now(),
            ]);
        }

        return response()->json([
            'success' => 'Pembayaran berhasil!',
            'kode_transaksi' => $kodeTransaksi,
        ]);
    }

    // 🧾 Cetak struk per transaksi
    public function struk(Request $request)
    {
        $kode = $request->kode;

        $struk = DB::table('pembelian')
            ->join('barang', 'pembelian.barang_id', '=', 'barang.id')
            ->select('pembelian.*', 'barang.nama_barang')
            ->where('kode_transaksi', $kode)
            ->get();

        if ($struk->isEmpty()) abort(404, 'Transaksi tidak ditemukan.');

        $total = $struk->sum('total');
        $metode = $struk->first()->metode_pembayaran;
        $tanggal = Carbon::parse($struk->first()->tanggal_pembelian)->format('d/m/Y H:i:s');

        return view('kasir.struk', compact('struk', 'total', 'metode', 'tanggal', 'kode'));
    }
}
