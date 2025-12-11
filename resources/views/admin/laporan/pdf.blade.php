<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pembelian</title>
    <style>
        /* Gunakan font yang kompatibel dengan DomPDF (DejaVu Sans disarankan) */
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111827; }
        .container { width: 100%; margin: 0 auto; padding: 8px 12px; }
        header { text-align: center; margin-bottom: 8px; }
        h3 { margin: 0; font-size: 16px; }
        p.meta { margin: 4px 0 10px 0; font-size: 11px; color: #374151; }

        table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        th, td { border: 1px solid #cbd5e1; padding: 6px 8px; text-align: center; vertical-align: middle; }
        thead th { background: #f1f5f9; font-weight: 600; }
        tbody td { font-size: 11px; }

        .text-right { text-align: right; }
        .total-row td { background: #f8fafc; font-weight: 700; }
        .footer { margin-top: 10px; font-size: 11px; text-align: center; color: #6b7280; }

        /* responsif sederhana (untuk preview browser) */
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
<div class="container">
    <header>
        <h3>Laporan Pembelian - Toko Satriyo</h3>

        @if(!empty($tahun) || !empty($bulan) || !empty($tanggal))
            <p class="meta">
                @if(!empty($tanggal))
                    Periode: {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}
                @else
                    Periode:
                    @if(!empty($bulan)) {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} @endif
                    @if(!empty($tahun)) {{ $tahun }} @endif
                @endif
            </p>
        @else
            <p class="meta">Periode: Hari Ini ({{ \Carbon\Carbon::today()->translatedFormat('d F Y') }})</p>
        @endif
    </header>

    <table>
        <thead>
            <tr>
                <th style="width:5%;">No</th>
                <th style="width:28%;">Nama Barang</th>
                <th style="width:10%;">Jumlah</th>
                <th style="width:17%;">Harga Satuan</th>
                <th style="width:15%;">Total</th>
                <th style="width:12%;">Metode Pembayaran</th>
                <th style="width:13%;">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($laporan as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td style="text-align:left; padding-left:8px;">{{ $item->nama_barang }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td class="text-right">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                    <td>{{ $item->metode_pembayaran ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_pembelian)->translatedFormat('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="padding:12px; font-style:italic; color:#6b7280;">
                        Tidak ada data pembelian untuk periode ini.
                    </td>
                </tr>
            @endforelse

            @if($laporan->count() > 0)
            <tr class="total-row">
                <td colspan="4" class="text-right">Total Pendapatan</td>
                <td colspan="3" class="text-right">Rp {{ number_format($total, 0, ',', '.') }}</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        Dicetak otomatis dari sistem kasir <strong>Toko Satriyo</strong>
    </div>
</div>
</body>
</html>
