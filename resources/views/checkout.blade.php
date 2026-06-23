<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PrimeStage — Premium Concert Experience</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body data-user-logged-in="{{ auth()->check() ? 'true' : 'false' }}">
    <!-- ====== CHECKOUT PAGE ====== -->
    <form id="checkout-form" action="{{ route('simpanTransaksi') }}" method="POST">
        @csrf
        <input type="hidden" name="ticket_id" id="ticket_id" value="{{ $ticket?->id }}">
        <input type="hidden" name="quantity" id="quantity" value="{{ $qty ?? 1 }}">
        @php
        $ticketPrice = $ticket?->price ?? 0;
        $checkoutTitle = $ticket?->konser
            ? ($ticket->konser->name ?? 'Concert Title')
            : 'Concert Title';

        if ($ticket?->konser) {
            $dateStr = $ticket->konser->date;
            $formattedDate = date('d F Y', strtotime($dateStr));
            $checkoutMeta = $formattedDate . ' · ' . ($ticket->konser->venue ?? 'Venue');
        } else {
            $checkoutMeta = 'Date · Venue';
        }
        $checkoutCat = $ticket ? $ticket->name . ' × ' . ($qty ?? 1) : '';
        $checkoutImg = $ticket?->konser?->image ? '/storage/' . $ticket->konser->image : '';
        $checkoutTotal = $ticketPrice * ($qty ?? 1) + 10000;
        @endphp
        <div class="page active" id="page-checkout">
            <div class="checkout-wrap">
                <div class="checkout-form">
                    <h2>CHECKOUT</h2>
                    <div class="checkout-section">
                        <h3><span>1</span> Data Diri</h3>
                        <div class="form-row">
                            <div class="form-group"><label class="form-label">Nama Depan</label><input
                                    class="form-input" type="text" placeholder="John" name="nama_depan"></div>
                            <div class="form-group"><label class="form-label">Nama Belakang</label><input
                                    class="form-input" type="text" placeholder="Doe" name="nama_belakang"></div>
                        </div>
                        <div class="form-group"><label class="form-label">Email</label><input class="form-input"
                                type="email" placeholder="john@example.com" name="email"></div>
                        <div class="form-group"><label class="form-label">Nomor HP</label><input class="form-input"
                                type="tel" placeholder="+62 812 3456 7890" name="nomor_hp"></div>
                        <div class="form-group"><label class="form-label">NIK (Opsional)</label><input
                                class="form-input" type="text" placeholder="3273xxxxxxxx" name="nik"></div>
                    </div>
                    <div class="checkout-section">
                        <h3><span>2</span> Metode Pembayaran</h3>
                        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;">
                            <label style="cursor:pointer;">
                                <input type="radio" name="metode_pembayaran" value="bca" style="display:none;"
                                    onclick="selectPayment(this)">
                                <div class="ticket-category" id="pay-bca">
                                    <div>
                                        <div class="ticket-cat-name">Bank BCA</div>
                                        <div class="ticket-cat-stock" style="color:var(--gray);">Transfer VA</div>
                                    </div>
                                    <i class="fas fa-university" style="color:var(--gray);"></i>
                                </div>
                            </label>
                            <label style="cursor:pointer;">
                                <input type="radio" name="metode_pembayaran" value="gopay" style="display:none;"
                                    onclick="selectPayment(this)">
                                <div class="ticket-category" id="pay-gopay">
                                    <div>
                                        <div class="ticket-cat-name">GoPay</div>
                                        <div class="ticket-cat-stock" style="color:var(--gray);">E-Wallet</div>
                                    </div>
                                    <i class="fas fa-wallet" style="color:var(--gray);"></i>
                                </div>
                            </label>
                            <label style="cursor:pointer;">
                                <input type="radio" name="metode_pembayaran" value="qris" style="display:none;"
                                    onclick="selectPayment(this)">
                                <div class="ticket-category" id="pay-qris">
                                    <div>
                                        <div class="ticket-cat-name">QRIS</div>
                                        <div class="ticket-cat-stock" style="color:var(--gray);">Scan & Pay</div>
                                    </div>
                                    <i class="fas fa-qrcode" style="color:var(--gray);"></i>
                                </div>
                            </label>
                        </div>
                    </div>
                    <label class="form-checkbox" style="margin-bottom:20px;">
                        <input type="checkbox" id="agree-tos"> Saya setuju dengan Syarat &amp; Ketentuan PrimeStage
                    </label>
                    <button class="btn-book-now" type="button" onclick="submitOrder()" style="max-width:400px;"><i
                            class="fas fa-lock"></i>&nbsp; KONFIRMASI PEMBELIAN</button>
                    <a class="btn-batal" type="button" href="/dashboard" style="max-width:400px;"><i
                            class="fas fa-times"></i>&nbsp; KEMBALI</a>
                </div>
                <div>
                    <div class="order-summary-box">
                        <h3 style="font-family:var(--font-head);font-size:22px;letter-spacing:2px;margin-bottom:20px;">
                            RINGKASAN PESANAN</h3>
                        <div class="order-concert-card">
                            <img class="order-concert-img" id="checkout-img" src="{{ $checkoutImg }}"
                                alt="">
                            <div class="order-concert-info">
                                <h4 id="checkout-title">{{ $checkoutTitle }}</h4>
                                <p id="checkout-meta">{{ $checkoutMeta }}</p>
                                <p id="checkout-cat" style="color:var(--red);font-weight:700;margin-top:4px;">
                                    {{ $checkoutCat }}</p>
                            </div>
                        </div>
                        <div class="summary-row"><span class="summary-label">Harga Satuan</span><span
                                id="co-unit">Rp {{ number_format($ticketPrice, 0, ',', '.') }}</span></div>
                        <div class="summary-row"><span class="summary-label">Jumlah</span><span
                                id="co-qty">{{ $qty ?? 1 }}</span></div>
                        <div class="summary-row"><span class="summary-label">Subtotal</span><span id="co-sub">Rp
                                {{ number_format($ticketPrice * ($qty ?? 1), 0, ',', '.') }}</span></div>
                        <div class="summary-row"><span class="summary-label">Biaya Admin</span><span>Rp 10.000</span>
                        </div>
                        <div class="summary-row" id="co-discount-row" style="display:none;"><span
                                class="summary-label">Diskon</span><span id="co-disc" style="color:#22c55e;">-Rp
                                0</span></div>
                        <div class="summary-row total"><span class="summary-label">TOTAL</span><span
                                class="summary-value" id="co-total">Rp
                                {{ number_format($checkoutTotal, 0, ',', '.') }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Toast Notification Container -->
    <div class="toast" id="toast-container"></div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="js/script.js"></script>
</body>

</html>
