<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk #{{ $order->id }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            width: 80mm;
            margin: 0;
            padding: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .bold {
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 3px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin:0;">E-KANTIN SEKOLAH</h2>
        <p style="margin:5px 0;">Struk Pembelian</p>
    </div>

    <div>
        <table>
            <tr>
                <td>No. Order</td>
                <td>: #{{ $order->id }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>: {{ $order->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td>Pembeli</td>
                <td>: {{ $order->user->name }}</td>
            </tr>
            <tr>
                <td>Metode</td>
                <td>: {{ ucfirst($order->payment_method) }}</td>
            </tr>
        </table>
    </div>

    <div class="divider"></div>

    <table>
        @foreach($order->items as $item)
        <tr>
            <td colspan="2">{{ $item->product->name }}</td>
        </tr>
        <tr>
            <td>{{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }}</td>
            <td class="text-right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </table>

    <div class="divider"></div>

    <table>
        <tr class="bold">
            <td>Total</td>
            <td class="text-right">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
        </tr>
        @if($order->payment_method === 'balance')
        <tr>
            <td>Saldo Awal</td>
            <td class="text-right">Rp {{ number_format($order->user->balance + $order->total_amount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Saldo Akhir</td>
            <td class="text-right">Rp {{ number_format($order->user->balance, 0, ',', '.') }}</td>
        </tr>
        @endif
    </table>

    <div class="divider"></div>

    <div class="text-center">
        <p>Terima kasih atas kunjungan Anda</p>
        <p>Simpan struk ini sebagai bukti pembayaran</p>
    </div>
</body>
</html> 