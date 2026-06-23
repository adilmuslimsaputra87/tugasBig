<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tambah Artis - PrimeStage Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/css1.css">


</head>
<body>
    <div class="admin-page">
        <div class="admin-header">
            <a href="/admin"><i class="fas fa-arrow-left"></i> Kembali</a>
            <h1 class="admin-title">TAMBAH ARTIS</h1>
        </div>

        <form class="form-container" method="POST" action="/simpanArtis" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label">Nama Artis/Band</label>
                <input type="text" name="name" class="form-input" placeholder="Nama artis..." value="{{ old('name') }}" required>
                @error('name') <span style="color: var(--red); font-size: 12px;">{{ $message }}</span> @enderror
            </div>

            <div class="form-row">
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
                        <option value="Hip-Hop" {{ old('genre') == 'Hip-Hop' ? 'selected' : '' }}>Hip-Hop</option>
                    </select>
                    @error('genre') <span style="color: var(--red); font-size: 12px;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Asal Negara</label>
                    <select name="country" class="form-input" required>
                        <option value="">Pilih Negara</option>
                        <option value="indonesia" {{ old('country') == 'indonesia' ? 'selected' : '' }}>🇮🇩 Indonesia</option>
                        <option value="internasional" {{ old('country') == 'internasional' ? 'selected' : '' }}>🌍 Internasional</option>
                    </select>
                    @error('country') <span style="color: var(--red); font-size: 12px;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Foto Artis</label>
                <input type="file" name="image" class="form-input">
                @error('image') <span style="color: var(--red); font-size: 12px;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group form-row-full">
                <label class="form-label">Bio Singkat</label>
                <textarea name="bio" class="form-input form-textarea" placeholder="Ceritakan tentang artis...">{{ old('bio') }}</textarea>
                @error('bio') <span style="color: var(--red); font-size: 12px;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Instagram</label>
                <input type="text" name="instagram" class="form-input" placeholder="@username" value="{{ old('instagram') }}">
                @error('instagram') <span style="color: var(--red); font-size: 12px;">{{ $message }}</span> @enderror
            </div>

            <div class="btn-group">
                <button type="submit" class="btn-submit"><i class="fas fa-save"></i>&nbsp; SIMPAN ARTIS</button>
                <a href="/admin" class="btn-back"><i class="fas fa-times"></i>&nbsp; BATAL</a>
            </div>
        </form>
    </div>
</body>
</html>
