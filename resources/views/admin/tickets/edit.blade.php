<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Tiket - PrimeStage Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/css1.css">
</head>
<body>
    <div class="admin-page">
        <div class="admin-header">
            <a href="/admin"><i class="fas fa-arrow-left"></i> Kembali</a>
            <h1 class="admin-title">EDIT TIKET</h1>
        </div>

        <form class="form-container" method="POST" action="{{ route('admin.tickets.update', $ticket->id) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Konser</label>
                <select name="konser_id" class="form-input" required>
                    <option value="">Pilih Konser</option>
                    @foreach($konsers as $konser)
                        <option value="{{ $konser->id }}" {{ old('konser_id', $ticket->konser_id) == $konser->id ? 'selected' : '' }}>
                            {{ $konser->artist?->name ?? 'N/A' }} — {{ $konser->title }}
                        </option>
                    @endforeach
                </select>
                @error('konser_id') <span style="color: var(--red); font-size: 12px;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Nama Kategori Tiket</label>
                <input type="text" name="name" class="form-input" placeholder="VVIP / VIP / Festival..." value="{{ old('name', $ticket->name) }}" required>
                @error('name') <span style="color: var(--red); font-size: 12px;">{{ $message }}</span> @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Harga (Rp)</label>
                    <input type="text" name="price" id="price" class="form-input" placeholder="750000"
                           value="{{ old('price', $ticket->price) ? number_format(old('price', $ticket->price), 0, ',', '.') : '' }}" required oninput="formatRupiah(this)">
                    @error('price') <span style="color: var(--red); font-size: 12px;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Stok</label>
                    <input type="number" name="stock" class="form-input" placeholder="500" value="{{ old('stock', $ticket->stock) }}" min="1" required>
                    @error('stock') <span style="color: var(--red); font-size: 12px;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi Area</label>
                <input type="text" name="description" class="form-input" placeholder="Deskripsi area tiket..." value="{{ old('description', $ticket->description) }}">
                @error('description') <span style="color: var(--red); font-size: 12px;">{{ $message }}</span> @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Harga Promo (Rp)</label>
                    <input type="text" name="promo_price" id="promo_price" class="form-input" placeholder="600000"
                           value="{{ old('promo_price', $ticket->promo_price) ? number_format(old('promo_price', $ticket->promo_price), 0, ',', '.') : '' }}" oninput="formatRupiah(this)">
                    @error('promo_price') <span style="color: var(--red); font-size: 12px;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Promo Berlaku s/d</label>
                    <input type="date" name="promo_valid_until" class="form-input" value="{{ old('promo_valid_until', $ticket->promo_valid_until?->format('Y-m-d')) }}">
                    @error('promo_valid_until') <span style="color: var(--red); font-size: 12px;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Batas Pembelian per User</label>
                <input type="number" name="max_purchase" class="form-input" placeholder="4" value="{{ old('max_purchase', $ticket->max_purchase) }}" min="1" max="100" required>
                @error('max_purchase') <span style="color: var(--red); font-size: 12px;">{{ $message }}</span> @enderror
            </div>

            <div class="btn-group">
                <button type="submit" id="submitBtn" class="btn-submit text-dark"><i class="fas fa-save"></i>&nbsp; SIMPAN TIKET</button>
                <a href="/admin" class="btn-back"><i class="fas fa-times"></i>&nbsp; BATAL</a>
            </div>
        </form>
    </div>

    <script>
        // Fungsi untuk membuat format otomatis titik ribuan saat diketik
        function formatRupiah(element) {
            let value = element.value.replace(/[^,\d]/g, '').toString();
            let split = value.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            element.value = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        }

        // Membersihkan titik (.) sebelum data dikirim ke Controller Laravel
        document.querySelector('form').addEventListener('submit', function() {
            let priceInput = document.getElementById('price');
            let promoPriceInput = document.getElementById('promo_price');

            // Hapus semua titik agar dibaca angka murni oleh Laravel
            priceInput.value = priceInput.value.replace(/\./g, '');
            if(promoPriceInput.value) {
                promoPriceInput.value = promoPriceInput.value.replace(/\./g, '');
            }
        });
    </script>
