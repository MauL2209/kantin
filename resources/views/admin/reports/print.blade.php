<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan Kantin Sekolah MELATIAREA</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            background: #fff;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header h2 { margin: 4px 0; font-size: 14px; font-weight: normal; }
        .header p { margin: 0; font-size: 11px; color: #666; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-size: 11px;
            text-transform: uppercase;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .summary-box {
            display: flex;
            justify-content: space-between;
            margin: 15px 0;
            padding: 10px;
            background: #fafafa;
            border: 1px solid #eee;
        }
        .signatures {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        .sig-block {
            text-align: center;
            width: 200px;
        }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 8px 16px; background: #059669; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-weight: bold;">
            Cetak Laporan / Simpan PDF
        </button>
    </div>

    <div class="header">
        <h1>Kantin Sekolah [MELATIAREA]</h1>
        <h2>SMK NEGERI MELATIAREA</h2>
        <p>Laporan Rekapitulasi Penjualan & Transaksi 8 Stand Jurusan</p>
        <p style="margin-top: 5px; font-size: 10px;">Periode Laporan: {{ ucfirst(str_replace('_', ' ', $filter)) }} (Dicetak pada: {{ date('d F Y H:i') }} WIB)</p>
    </div>

    <div class="summary-box">
        <div>Total Transaksi: <strong>{{ $totalOrders }} Pesanan</strong></div>
        <div>Total Omset Penjualan: <strong style="font-size: 14px; color: #047857;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</strong></div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No. Pesanan</th>
                <th>Tanggal</th>
                <th>Nama Pemesan</th>
                <th>Kelas</th>
                <th>Metode Bayar</th>
                <th>Status</th>
                <th class="text-right">Total (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $idx => $order)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td style="font-family: monospace; font-weight: bold;">{{ $order->order_number }}</td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td>{{ $order->customer_class }}</td>
                    <td>{{ strtoupper($order->payment_method) }}</td>
                    <td>{{ $order->status }}</td>
                    <td class="text-right" style="font-family: monospace;">{{ number_format($order->total_price, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data transaksi.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="font-weight: bold; background: #f9f9f9;">
                <td colspan="7" class="text-right">TOTAL PENDAPATAN:</td>
                <td class="text-right" style="font-family: monospace; color: #047857; font-size: 13px;">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="signatures">
        <div class="sig-block">
            <p>Mengetahui,<br>Kepala Sekolah SMK Melatiarea</p>
            <br><br><br>
            <p><strong>( ......................................... )</strong><br>NIP. -</p>
        </div>
        <div class="sig-block">
            <p>Pengelola Kantin Sekolah,<br>Koordinator 8 Stand Jurusan</p>
            <br><br><br>
            <p><strong>( Administrator Kantin )</strong><br>NIP. -</p>
        </div>
    </div>

</body>
</html>

