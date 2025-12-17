<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /**
     * Menampilkan halaman laporan pembelian
     */
    public function index(Request $request)
    {
        $tanggal = $request->get('tanggal'); // filter perhari
        $tahun   = $request->get('tahun');   // filter tahun
        $bulan   = $request->get('bulan');   // filter bulan

        // Query dasar
        $query = DB::table('pembelian')
            ->join('barang', 'pembelian.barang_id', '=', 'barang.id')
            ->select(
                'pembelian.id',
                'barang.nama_barang',
                'pembelian.jumlah',
                'pembelian.harga',
                'pembelian.total',
                'pembelian.metode_pembayaran',
                'pembelian.tanggal_pembelian'
            );

        // Filter perhari jika tanggal dipilih
        if ($tanggal) {
            $query->whereDate('pembelian.tanggal_pembelian', $tanggal);
        }
        // Jika tidak ada filter sama sekali, tampilkan hari ini
        elseif (!$tahun && !$bulan) {
            $query->whereDate('pembelian.tanggal_pembelian', Carbon::today());
        }

        // Filter tahun
        if ($tahun) {
            $query->whereYear('pembelian.tanggal_pembelian', $tahun);
        }

        // Filter bulan
        if ($bulan) {
            $query->whereMonth('pembelian.tanggal_pembelian', $bulan);
        }

        $laporan = $query->orderByDesc('pembelian.tanggal_pembelian')->get();

        // Total pendapatan sesuai data laporan yang tampil
        $totalPendapatan = $laporan->sum('total');

        // Daftar tahun untuk dropdown filter (jika diperlukan)
        $tahunList = DB::table('pembelian')
            ->selectRaw('YEAR(tanggal_pembelian) as tahun')
            ->groupBy('tahun')
            ->orderByDesc('tahun')
            ->pluck('tahun');

        return view('admin.laporan.index', compact(
            'laporan',
            'tahunList',
            'totalPendapatan',
            'tanggal',
            'tahun',
            'bulan'
        ));
    }

    /**
     * Cetak PDF laporan pembelian
     */
    public function cetakPdf(Request $request)
    {
        $tanggal = $request->get('tanggal');
        $tahun   = $request->get('tahun');
        $bulan   = $request->get('bulan');

        $query = DB::table('pembelian')
            ->join('barang', 'pembelian.barang_id', '=', 'barang.id')
            ->select(
                'pembelian.id',
                'barang.nama_barang',
                'pembelian.jumlah',
                'pembelian.harga',
                'pembelian.total',
                'pembelian.metode_pembayaran',
                'pembelian.tanggal_pembelian'
            );

        if ($tanggal) {
            $query->whereDate('pembelian.tanggal_pembelian', $tanggal);
        } elseif (!$tahun && !$bulan) {
            $query->whereDate('pembelian.tanggal_pembelian', Carbon::today());
        }

        if ($tahun) {
            $query->whereYear('pembelian.tanggal_pembelian', $tahun);
        }

        if ($bulan) {
            $query->whereMonth('pembelian.tanggal_pembelian', $bulan);
        }

        $laporan = $query->orderByDesc('pembelian.tanggal_pembelian')->get();

        // pastikan nama variabel yang dikirim ke view sesuai dengan yang dipakai di view
        $total = $laporan->sum('total');

        $pdf = Pdf::loadView('admin.laporan.pdf', compact('laporan', 'tanggal', 'bulan', 'tahun', 'total'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('laporan-pembelian-' . now()->format('Ymd_His') . '.pdf');
    }
}
