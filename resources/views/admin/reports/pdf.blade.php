<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #1a1a1a; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 24px; color: #1a1a1a; }
        .header p { margin: 5px 0 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; font-weight: bold; color: #1a1a1a; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .total-row { background-color: #fdfaf6; }
    </style>
</head>
<body>
    <div class="header">
        <h1>GARAGE COFFEE</h1>
        <p>Laporan Penjualan: {{ $startDate ?? 'Awal' }} s/d {{ $endDate ?? 'Akhir' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No Pesanan</th>
                <th>Pelanggan</th>
                <th>Tanggal & Waktu</th>
                <th>Sumber</th>
                <th>Metode</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>{{ $order['order_number'] }}</td>
                <td>{{ $order['customer_name'] }}</td>
                <td>{{ \Carbon\Carbon::parse($order['created_at'])->format('d/m/Y H:i') }}</td>
                <td>{{ $order['type'] }}</td>
                <td>{{ strtoupper($order['payment_method']) }}</td>
                <td class="text-right">Rp {{ number_format($order['total_amount'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="5" class="text-right font-bold">TOTAL PENDAPATAN</td>
                <td class="text-right font-bold">Rp {{ number_format($orders->sum('total_amount'), 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
    
    <div style="margin-top: 30px; text-align: right; font-size: 10px; color: #999;">
        <p>Dicetak pada: {{ now()->format('d M Y H:i:s') }}</p>
    </div>
</body>
</html>
