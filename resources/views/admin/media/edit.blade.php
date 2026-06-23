<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tambah Media - PrimeStage Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/css1.css">
</head>
<body>
    <div class="admin-page">
        <div class="admin-header">
            <a href="/admin"><i class="fas fa-arrow-left"></i> Kembali</a>
            <h1 class="admin-title">EDIT MEDIA</h1>
        </div>

        <form class="form-container" method="POST" action="/simpanEditMedia/{{ $media->id }}" enctype="multipart/form-data">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Nama Media</label>
                    <input type="text" name="name" class="form-input" placeholder="Nama Media" value="{{ old('name', $media->name) }}" required>
                    @error('name') <span style="color: var(--red); font-size: 12px;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Lokasi</label>
                    <input type="text" name="location" class="form-input" placeholder="Lokasi Media" value="{{ old('location', $media->location) }}" required>
                    @error('location') <span style="color: var(--red); font-size: 12px;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Image</label>
                <input type="file" name="image" class="form-input" accept="image/*">
                @error('image') <span style="color: var(--red); font-size: 12px;">{{ $message }}</span> @enderror
            </div>

            <div class="btn-group">
                <button type="submit" class="btn-submit"><i class="fas fa-save"></i>&nbsp; SIMPAN MEDIA</button>
                <a href="/admin" class="btn-back"><i class="fas fa-times"></i>&nbsp; BATAL</a>
            </div>
        </form>
    </div>
</body>
</html>
