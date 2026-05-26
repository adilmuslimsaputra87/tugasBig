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
    <style>
        body { background: var(--bg); color: var(--white); }
        .admin-page { padding: 40px 5%; }
        .admin-header { display: flex; align-items: center; gap: 20px; margin-bottom: 40px; }
        .admin-header a { color: var(--gray); text-decoration: none; font-size: 14px; }
        .admin-header a:hover { color: var(--white); }
        .admin-title { font-family: var(--font-head); font-size: 32px; letter-spacing: 2px; }
        .form-container { background: var(--bg2); border: 1px solid var(--border); border-radius: var(--radius); padding: 40px; max-width: 600px; }
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 14px; margin-bottom: 8px; color: var(--gray); text-transform: uppercase; letter-spacing: 1px; }
        .form-input { width: 100%; background: var(--bg3); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 12px; color: var(--white); font-size: 14px; }
        .form-input:focus { outline: none; border-color: var(--red); box-shadow: 0 0 0 3px rgba(229,9,20,0.1); }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .form-row-full { grid-column: 1 / -1; }
        .btn-group { display: flex; gap: 12px; margin-top: 40px; }
        .btn-submit { flex: 1; background: var(--red); color: white; border: none; padding: 14px 28px; border-radius: var(--radius-sm); font-size: 14px; font-weight: 600; cursor: pointer; text-transform: uppercase; letter-spacing: 1px; }
        .btn-submit:hover { background: #c70810; }
        .btn-back { flex: 1; background: var(--bg3); color: var(--gray); border: 1px solid var(--border); padding: 14px 28px; border-radius: var(--radius-sm); font-size: 14px; font-weight: 600; cursor: pointer; text-decoration: none; text-align: center; text-transform: uppercase; letter-spacing: 1px; }
        .btn-back:hover { background: var(--border); color: var(--white); }
        select.form-input { cursor: pointer; }
    </style>
</head>
<body>
    <div class="admin-page">
        <div class="admin-header">
            <a href="/admin/tickets"><i class="fas fa-arrow-left"></i> Kembali</a>
            <h1 class="admin-title">EDIT TIKET</h1>
        </div>

        <form class="form-container" method="POST" action="{{ route('tickets.update', $ticket->id) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Konser</label>
                <select name="konser_id" class="form-input" required>
                    <option value="">Pilih Konser</option>
                    @foreach($konsers as $konser)
                        <option value="{{ $konser->id }}" {{ old('konser_id', $ticket->konser_id) == $konser->id ? 'selected' : '' }}>
                            {{ $konser->artist }} — {{ $konser->title }}
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
                    <input type="number" name="price" class="form-input" placeholder="750000" value="{{ old('price', $ticket->price) }}" min="0" required>
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
                    <input type="number" name="promo_price" class="form-input" placeholder="600000" value="{{ old('promo_price', $ticket->promo_price) }}" min="0">
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
                <button type="submit" class="btn-submit"><i class="fas fa-save"></i>&nbsp; PERBARUI TIKET</button>
                <a href="/admin/tickets" class="btn-back"><i class="fas fa-times"></i>&nbsp; BATAL</a>
            </div>
        </form>
    </div>
</body>
</html>
