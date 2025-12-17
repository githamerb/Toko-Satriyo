<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Cek kolom total di tabel pembelian
        $columns = DB::getSchemaBuilder()->getColumnListing('pembelian');
        $kolomTotal = collect(['total_harga', 'total', 'grand_total', 'jumlah_harga'])
            ->first(fn($c) => in_array($c, $columns));

        if (!$kolomTotal) {
            abort(500, "Kolom total harga tidak ditemukan di tabel pembelian.");
        }

        // 💰 Pendapatan hari ini
        $pendapatanHariIni = DB::table('pembelian')
            ->whereDate('created_at', $today)
            ->sum($kolomTotal);

        // 🧾 Total transaksi hari ini
        $totalTransaksiHariIni = DB::table('pembelian')
            ->whereDate('created_at', $today)
            ->count();

        // 📦 Total barang
        $totalBarang = DB::table('barang')->count();

        // 🔝 Barang terlaris (optional, jika tabel ada)
        $topBarangLabels = [];
        $topBarangTotals = [];
        if (DB::getSchemaBuilder()->hasTable('detail_pembelian')) {
            $topBarang = DB::table('detail_pembelian')
                ->join('barang', 'detail_pembelian.barang_id', '=', 'barang.id')
                ->select('barang.nama_barang', DB::raw('SUM(detail_pembelian.jumlah) as total_terjual'))
                ->groupBy('barang.nama_barang')
                ->orderByDesc('total_terjual')
                ->limit(5)
                ->get();

            $topBarangLabels = $topBarang->pluck('nama_barang');
            $topBarangTotals = $topBarang->pluck('total_terjual');
        }

        // ⚠️ Stok rendah
        $stokRendah = DB::table('barang')
            ->where('stok', '<=', 5)
            ->orderBy('stok', 'asc')
            ->get();

        // 📊 Penjualan 7 hari terakhir
        $mingguan = DB::table('pembelian')
            ->select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw("SUM($kolomTotal) as total")
            )
            ->where('created_at', '>=', Carbon::now()->subDays(6))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('tanggal', 'asc')
            ->get();

        $labelsMingguan = $mingguan->pluck('tanggal')->map(fn($tgl) => Carbon::parse($tgl)->translatedFormat('d M'));
        $dataMingguan = $mingguan->pluck('total');

        // 📈 Penjualan 12 bulan terakhir
        $bulanan = DB::table('pembelian')
            ->select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw("SUM($kolomTotal) as total")
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(11))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('bulan', 'asc')
            ->get();

        $labelsBulanan = $bulanan->pluck('bulan')->map(fn($b) => Carbon::create()->month($b)->translatedFormat('F'));
        $dataBulanan = $bulanan->pluck('total');

        // 🧾 Transaksi terbaru
        $transaksiTerbaru = DB::table('pembelian')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'pendapatanHariIni',
            'totalTransaksiHariIni',
            'totalBarang',
            'stokRendah',
            'labelsMingguan',
            'dataMingguan',
            'labelsBulanan',
            'dataBulanan',
            'transaksiTerbaru',
            'kolomTotal',
            'topBarangLabels',
            'topBarangTotals'
        ));
    }
}
