<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pembelian - Toko Satriyo</title>
    <style>
        body {
            font-family: monospace;
            font-size: 12px;
            width: 270px;
            margin: auto;
        }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        hr { border: 1px dashed #000; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; }
        td { vertical-align: top; }
        .total { font-weight: bold; font-size: 13px; }
    </style>
</head>
<body onload="window.print()">

    <div class="text-center">
        <img src="{{ asset('logo/logo.png') }}" width="60" height="60" alt="Logo Toko Satriyo"><br>
        <strong>Toko Satriyo</strong><br>
        Jl. Pasir Putih, Rejodadi, Dalegan<br>
        Gresik<br>
        Telp: 0857-0898-3793<br>
        ----------------------------------<br>
    </div>

    <table style="margin-bottom:5px;">
        <tr>
            <td>{{ \Carbon\Carbon::now()->format('Y-m-d') }}</td>
            <td class="text-end">{{ \Carbon\Carbon::now()->format('H:i:s') }}</td>
        </tr>
        <tr>
            <td>Kasir</td>
            <td class="text-end">{{ auth()->user()->name ?? 'Admin' }}</td>
        </tr>
        <tr>
            <td>Metode</td>
            <td class="text-end">{{ $metode ?? '-' }}</td>
        </tr>
    </table>

    <hr>

    <table>
        <tbody>
            @foreach($struk as $index => $item)
            <tr>
                <td colspan="2">{{ $index + 1 }}. {{ $item->nama_barang }}</td>
            </tr>
            <tr>
                <td>{{ $item->jumlah }} x Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                <td class="text-end">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <hr>

    <table>
        <tr>
            <td>Total QTY</td>
            <td class="text-end">{{ $struk->sum('jumlah') }}</td>
        </tr>
        <tr>
            <td>Sub Total</td>
            <td class="text-end">Rp {{ number_format($total, 0, ',', '.') }}</td>
        </tr>
        <tr class="total">
            <td>Total</td>
            <td class="text-end">Rp {{ number_format($total, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Bayar ({{ $metode ?? '-' }})</td>
            <td class="text-end">Rp {{ number_format($total, 0, ',', '.') }}</td>
        </tr>
    </table>

    <hr>

    <div class="text-center">
        Terima kasih telah berbelanja di <strong>Toko Satriyo</strong>!<br>
        <small>Link Kritik & Saran:<br>
        satriyo-shop.com/e-receipt/{{ strtoupper(substr(md5(now()), 0, 8)) }}</small>
    </div>

</body>
</html>
