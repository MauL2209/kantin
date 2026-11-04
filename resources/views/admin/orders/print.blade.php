<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pesanan #{{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 13px;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 20px;
        }
        .ticket {
            max-width: 320px;
            margin: auto;
            border: 1px dashed #000;
            padding: 15px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .divider { border-top: 1px dashed #000; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th, td { padding: 4px 0; }
        @media print {
            body { padding: 0; }
            .ticket { border: none; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="no-print text-center" style="margin-bottom: 15px;">
        <button onclick="window.print()" style="padding: 8px 16px; cursor: pointer; font-weight: bold;">
            Cetak Struk Sekarang
        </button>
    </div>

    <div class="ticket">
        <div class="text-center">
            <h2 style="margin: 0; font-size: 16px;">KANTIN SEKOLAH</h2>
            <h3 style="margin: 2px 0 6px; font-size: 18px;">[MELATIAREA]</h3>
            <p style="margin: 0; font-size: 11px;">SMK NEGERI MELATIAREA</p>
            <p style="margin: 2px 0 0; font-size: 10px;">TIKET PESANAN KANTIN</p>
        </div>

        <div class="divider"></div>

        <div>
            <div>NO: <strong>{{ $order->order_number }}</strong></div>
            <div>TGL: {{ $order->created_at->format('d/m/Y H:i') }}</div>
            <div>PEMESAN: {{ $order->customer_name }}</div>
            <div>KELAS: {{ $order->customer_class }}</div>
            <div>LOKASI: {{ $order->customer_room }}</div>
            <div>STATUS: {{ strtoupper($order->status) }}</div>
        </div>

        <div class="divider"></div>

        <table>
            <thead>
                <tr>
                    <th align="left">Menu</th>
                    <th align="center">Qty</th>
                    <th align="right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderDetails as $detail)
                    <tr>
                        <td colspan="3" style="padding-top: 6px; font-weight: bold;">
                            {{ $detail->product->name ?? 'Menu' }}
                            <br><small>[{{ $detail->product->stand->name ?? 'Stand' }}]</small>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 11px;">@ Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                        <td align="center">{{ $detail->quantity }}x</td>
                        <td align="right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="divider"></div>

        <table>
            <tr>
                <td><strong>TOTAL</strong></td>
                <td align="right"><strong>{{ $order->formatted_total }}</strong></td>
            </tr>
            <tr>
                <td>METODE BAYAR</td>
                <td align="right">{{ strtoupper($order->payment_method) }}</td>
            </tr>
        </table>

        @if($order->customer_note)
            <div class="divider"></div>
            <div style="font-size: 11px;">
                <strong>Catatan:</strong> {{ $order->customer_note }}
            </div>
        @endif

        <div class="divider"></div>

        <div class="text-center" style="font-size: 11px;">
            <p style="margin: 4px 0;">Terima Kasih!</p>
            <p style="margin: 0;">Tunjukkan struk ini saat mengambil di stand.</p>
        </div>
    </div>

</body>
</html>

