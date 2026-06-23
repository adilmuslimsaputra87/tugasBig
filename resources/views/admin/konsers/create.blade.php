<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tambah Konser - PrimeStage Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/css1.css">

</head>

<body>
    <div class="admin-page">
        <div class="admin-header">
            <a href="/admin"><i class="fas fa-arrow-left"></i> Kembali</a>
            <h1 class="admin-title">TAMBAH KONSER</h1>
        </div>

        <form class="form-container" method="POST" action="/simpanKonser" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label">Nama Konser/Tour</label>
                <input type="text" name="title" class="form-input" placeholder="Music of the Spheres..."
                    value="{{ old('title') }}" required>
                @error('title')
                    <span style="color: var(--red); font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Artis</label>
                    <select name="artists_id" class="form-input" required>
                        <option value="">Pilih Artis/Band</option>

                        @foreach ($artists as $artist)
                            <option value="{{ $artist->id }}"
                                {{ old('artists_id') == $artist->id ? 'selected' : '' }}>
                                {{ $artist->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('artists_id')
                        <span style="color: var(--red); font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Genre</label>
                    <select name="genre" class="form-input">
                        <option value="">Pilih Genre</option>
                        <option value="Pop" {{ old('genre') == 'Pop' ? 'selected' : '' }}>Pop</option>
                        <option value="Rock" {{ old('genre') == 'Rock' ? 'selected' : '' }}>Rock</option>
                        <option value="R&B" {{ old('genre') == 'R&B' ? 'selected' : '' }}>R&B</option>
                        <option value="Metal" {{ old('genre') == 'Metal' ? 'selected' : '' }}>Metal</option>
                        <option value="Jazz" {{ old('genre') == 'Jazz' ? 'selected' : '' }}>Jazz</option>
                        <option value="Indie" {{ old('genre') == 'Indie' ? 'selected' : '' }}>Indie</option>
                    </select>
                    @error('genre')
                        <span style="color: var(--red); font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="date" class="form-input" value="{{ old('date') }}" required>
                    @error('date')
                        <span style="color: var(--red); font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Waktu</label>
                    <input type="time" name="time" class="form-input" value="{{ old('time', '19:00') }}"
                        required>
                    @error('time')
                        <span style="color: var(--red); font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Venue</label>
                <input type="text" name="venue" class="form-input" placeholder="GBK Stadium, Jakarta"
                    value="{{ old('venue') }}" required>
                @error('venue')
                    <span style="color: var(--red); font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Jumlah Tiket</label>
                <input type="number" name="capacity" class="form-input" placeholder="1000"
                    value="{{ old('capacity') }}" min="1" required>
                @error('capacity')
                    <span style="color: var(--red); font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Kota</label>
                    <select name="city" class="form-input" required>
                        <option value="">Pilih Kota</option>
                        <option value="Jakarta" {{ old('city') == 'Jakarta' ? 'selected' : '' }}>Jakarta</option>
                        <option value="Bandung" {{ old('city') == 'Bandung' ? 'selected' : '' }}>Bandung</option>
                        <option value="Surabaya" {{ old('city') == 'Surabaya' ? 'selected' : '' }}>Surabaya</option>
                        <option value="Yogyakarta" {{ old('city') == 'Yogyakarta' ? 'selected' : '' }}>Yogyakarta
                        </option>
                        <option value="Bali" {{ old('city') == 'Bali' ? 'selected' : '' }}>Bali</option>
                    </select>
                    @error('city')
                        <span style="color: var(--red); font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Harga Mulai (Rp)</label>
                    <input type="text" name="price" id="price" class="form-input" placeholder="500.000"
                        value="{{ old('price') ? number_format(old('price'), 0, ',', '.') : '' }}" required
                        oninput="formatRupiah(this)">

                    @error('price')
                        <span style="color: var(--red); font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group form-row-full">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-input form-textarea" placeholder="Deskripsi konser...">{{ old('description') }}</textarea>
                @error('description')
                    <span style="color: var(--red); font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group form-row-full">
                <label class="form-label">Tambahkan Trailer Konser</label>
                <input type="file" name="trailer" class="form-input" placeholder="Masukkan Video" value="{{ old('trailer') }}" accept="video/*">
                @error('trailer')
                    <span style="color: var(--red); font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Poster Konser</label>
                    <input type="file" name="image" class="form-input" accept="image/*">
                    @error('image')
                        <span style="color: var(--red); font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Tipe</label>
                    <select name="type" class="form-input" required>
                        <option value="">Pilih Tipe</option>
                        <option value="lokal" {{ old('type') == 'lokal' ? 'selected' : '' }}>🇮🇩 Lokal</option>
                        <option value="internasional" {{ old('type') == 'internasional' ? 'selected' : '' }}>🌍
                            Internasional</option>
                    </select>
                    @error('type')
                        <span style="color: var(--red); font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Status</label>
                <div style="display: flex; gap: 20px; margin-top: 12px;">
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; cursor: pointer;">
                        <input type="radio" name="status" value="published"
                            {{ old('status') == 'published' ? 'checked' : '' }} style="accent-color: var(--red);">
                        <span>Published (On Sale)</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; cursor: pointer;">
                        <input type="radio" name="status" value="draft"
                            {{ old('status') == 'draft' || !old('status') ? 'checked' : '' }}
                            style="accent-color: var(--red);">
                        <span>Draft</span>
                    </label>
                </div>
                @error('status')
                    <span style="color: var(--red); font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="btn-group">
                <button type="submit" class="btn-submit text-dark"><i class="fas fa-save"></i>&nbsp; SIMPAN
                    KONSER</button>
                <a href="/admin" class="btn-back"><i class="fas fa-times"></i>&nbsp; BATAL</a>
            </div>
        </form>

        @if ($errors->any())
            <div
                style="background: rgba(229, 9, 20, 0.2); border: 1px solid var(--red); padding: 15px; margin-bottom: 20px; border-radius: var(--radius-sm);">
                <h4 style="color: var(--red); margin-bottom: 8px;">Gagal Menyimpan:</h4>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li style="color: #fff; font-size: 14px; margin-left: 20px; list-style-type: square;">
                            {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
    <script>
        function formatRupiah(element) {
            // Ambil value, hilangkan semua karakter selain angka
            let value = element.value.replace(/[^,\d]/g, '').toString();

            // Lakukan memformat angka dengan titik setiap 3 digit
            let split = value.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;

            // Masukkan kembali hasil format ke dalam input
            element.value = rupiah;
        }
    </script>
</body>

</html>
