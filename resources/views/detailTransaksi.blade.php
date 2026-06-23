<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Transaksi</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Courier New', Courier, monospace; /* Font khas struk */
        }
        body {
            background-color: #e2e8f0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 40px 20px;
        }
        .receipt-card {
            background: #ffffff;
            width: 100%;
            max-width: 420px;
            padding: 30px 25px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            position: relative;
            color: #2d3748;
        }
        /* Efek Gerigi Potongan Kertas di Bawah */
        .receipt-card::after {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 0;
            right: 0;
            height: 10px;
            background: linear-gradient(-135deg, #ffffff 5px, transparent 0),
                        linear-gradient(135deg, #ffffff 5px, transparent 0);
            background-size: 10px 10px;
        }
        .dashed-line {
            border-top: 2px dashed #a0aec0;
            margin: 15px 0;
        }
        .text-center { text-align: center; }
        .brand-name { font-size: 1.4rem; font-weight: bold; letter-spacing: 1px; margin-bottom: 5px; }
        .receipt-title { font-size: 0.9rem; color: #718096; margin-bottom: 15px; }
        .meta-info { font-size: 0.85rem; line-height: 1.4; color: #4a5568; }
        .section-title { font-size: 0.9rem; font-weight: bold; margin-bottom: 8px; text-transform: uppercase; }
        .row-data { display: flex; justify-content: space-between; font-size: 0.85rem; margin-bottom: 6px; line-height: 1.4; }
        .label { color: #718096; }
        .value { font-weight: bold; text-align: right; }
        .total-row { font-size: 1.1rem; font-weight: bold; margin-top: 10px; }

        /* Badge Status */
        .badge { padding: 2px 6px; border-radius: 3px; font-size: 0.75rem; font-weight: bold; text-transform: uppercase; }
        .badge-success { background: #c6f6d5; color: #22543d; }
        .badge-pending { background: #feebc8; color: #744210; }
        .badge-failed { background: #fed7d7; color: #742a2a; }

        .barcode { text-align: center; margin: 20px 0 10px 0; letter-spacing: 4px; font-size: 1.2rem; color: #1a202c; opacity: 0.8; }
        .actions { display: flex; gap: 10px; margin-top: 30px; }
        .btn { flex: 1; padding: 10px; text-align: center; border: 1px solid #cbd5e1; background: #fff; cursor: pointer; font-size: 0.85rem; font-weight: bold; border-radius: 4px; }
        .btn-print { background: #2d3748; color: #fff; border: none; }

        @media print {
            body { background: #fff; padding: 0; }
            .receipt-card { box-shadow: none; }
            .actions { display: none; }
        }
    </style>
</head>
<body>

<div class="receipt-card">
    <div class="text-center">
        <div class="brand-name">KONSER TIKET ID</div>
        <div class="receipt-title">STRUK BUKTI PEMBAYARAN</div>
    </div>

    <div class="meta-info">
        <div>TRX ID: #{{ $transaction->id }}</div>
        <div>WAKTU : {{ \Carbon\Carbon::parse($transaction->payment_date)->translatedFormat('d F Y H:i') }}</div>
    </div>

    <div class="dashed-line"></div>

    <div class="section-title">PELANGGAN</div>
    <div class="row-data"><span class="label">NAMA</span><span class="value">{{ strtoupper($transaction->first_name . ' ' . $transaction->last_name) }}</span></div>
    <div class="row-data"><span class="label">EMAIL</span><span class="value">{{ strtoupper($transaction->email) }}</span></div>
    <div class="row-data"><span class="label">HP</span><span class="value">{{ $transaction->phone_number }}</span></div>
    <div class="row-data"><span class="label">NIK</span><span class="value">{{ $transaction->nik ?? '-' }}</span></div>

    <div class="dashed-line"></div>

    <div class="section-title">DETAIL ITEM</div>
    <div class="row-data">
        <span class="label" style="max-width: 70%; text-align: left;">{{ strtoupper($transaction->ticket->konser->title ?? 'Unknown Concert') }}</span>
        <span class="value">{{ $transaction->quantity }}X</span>
    </div>

    <div class="dashed-line"></div>

    <div class="row-data"><span class="label">METODE</span><span class="value">{{ strtoupper($transaction->payment_method ?? '-') }}</span></div>
    <div class="row-data">
        <span class="label">STATUS</span>
        <span class="value">
            @php
                $status = strtolower($transaction->payment_status);
                $badgeClass = 'badge-failed';
                if (in_array($status, ['settlement', 'success'])) {
                    $badgeClass = 'badge-success';
                } elseif ($status === 'pending') {
                    $badgeClass = 'badge-pending';
                }
            @endphp
            <span class="badge {{ $badgeClass }}">{{ $status }}</span>
        </span>
    </div>

    <div class="row-data total-row">
        <span>TOTAL BAYAR</span>
        <span>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span>
    </div>

    <div class="dashed-line"></div>

    <div class="barcode">||||| | |||| ||| || ||</div>
    <div class="text-center meta-info" style="margin-top: 5px;">TERIMA KASIH ATAS KUNJUNGAN ANDA</div>

    <div class="actions">
        <button class="btn" onclick="window.history.back()">KEMBALI</button>
        @if($transaction->payment_status == "pending")
            <button class="btn btn-print" onclick="window.print()" disabled>CETAK STRUK</button>
        @else
            <button class="btn btn-print" onclick="window.print()">CETAK STRUK</button>
        @endif
    </div>
</div>

</body>
</html>
