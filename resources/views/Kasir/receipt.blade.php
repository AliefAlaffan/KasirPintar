@php $store = \App\Models\StoreSetting::current(); @endphp
<!DOCTYPE html>
<html>
<head>
    <title>Struk {{ $transaction->invoice_number }}</title>
    <style>
        body { font-family: monospace; font-size: 12px; width: 280px; margin: 0 auto; padding: 10px; }
        .center { text-align: center; }
        .line { border-top: 1px dashed #000; margin: 6px 0; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 2px 0; }
        .right { text-align: right; }
        @media print { @page { margin: 0; } }
    </style>
</head>
<body onload="window.print()">
    <div class="center">
        <strong>{{ strtoupper($store->store_name) }}</strong><br>
        @if ($store->address)
            {{ $store->address }}<br>
        @endif
        @if ($store->phone)
            {{ $store->phone }}<br>
        @endif
        {{ $transaction->created_at->format('d/m/Y H:i') }}<br>
        No: {{ $transaction->invoice_number }}
    </div>
    <div class="line"></div>
    <table>
        @foreach ($transaction->details as $detail)
            <tr><td colspan="2">{{ $detail->product_name }}</td></tr>
            <tr>
                <td>{{ $detail->quantity }} x {{ number_format($detail->price, 0, ',', '.') }}</td>
                <td class="right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </table>
    <div class="line"></div>
    <table>
        <tr><td>Subtotal</td><td class="right">{{ number_format($transaction->subtotal, 0, ',', '.') }}</td></tr>
        <tr><td>Diskon</td><td class="right">{{ number_format($transaction->discount, 0, ',', '.') }}</td></tr>
        @if ($transaction->tax > 0)
            <tr><td>Pajak</td><td class="right">{{ number_format($transaction->tax, 0, ',', '.') }}</td></tr>
        @endif
        <tr><td><strong>Total</strong></td><td class="right"><strong>{{ number_format($transaction->total, 0, ',', '.') }}</strong></td></tr>
        <tr><td>Bayar</td><td class="right">{{ number_format($transaction->paid_amount, 0, ',', '.') }}</td></tr>
        <tr><td>Kembali</td><td class="right">{{ number_format($transaction->change_amount, 0, ',', '.') }}</td></tr>
    </table>
    <div class="line"></div>
    <div class="center">{{ $store->receipt_footer }}</div>
</body>
</html>