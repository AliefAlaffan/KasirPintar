<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        h2 { margin-bottom: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background: #f3f4f6; }
        .summary { margin-top: 10px; }
        .right { text-align: right; }
    </style>
</head>
<body>
    <h2>Laporan Penjualan — KasirPintar</h2>
    <p>Periode: {{ \Carbon\Carbon::parse($from)->format('d M Y') }} — {{ \Carbon\Carbon::parse($to)->format('d M Y') }}</p>

    <div class="summary">
        <strong>Total Pendapatan:</strong> Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}<br>
        <strong>Total Laba:</strong> Rp {{ number_format($summary['total_profit'], 0, ',', '.') }}<br>
        <strong>Jumlah Transaksi:</strong> {{ $summary['total_transactions'] }}
    </div>

    <h3>Produk Terlaris</h3>
    <table>
        <thead>
            <tr><th>Produk</th><th>Terjual</th><th class="right">Pendapatan</th></tr>
        </thead>
        <tbody>
            @foreach ($bestSelling as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->total_sold }}</td>
                    <td class="right">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Detail Transaksi</h3>
    <table>
        <thead>
            <tr><th>Invoice</th><th>Tanggal</th><th>Kasir</th><th class="right">Total</th></tr>
        </thead>
        <tbody>
            @foreach ($transactions as $trx)
                <tr>
                    <td>{{ $trx->invoice_number }}</td>
                    <td>{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $trx->user->name }}</td>
                    <td class="right">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>