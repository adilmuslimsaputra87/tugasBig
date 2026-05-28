<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Artis - PrimeStage Admin</title>
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
        .form-textarea { resize: vertical; min-height: 100px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .btn-group { display: flex; gap: 12px; margin-top: 40px; }
        .btn-submit { flex: 1; background: var(--red); color: white; border: none; padding: 14px 28px; border-radius: var(--radius-sm); font-size: 14px; font-weight: 600; cursor: pointer; text-transform: uppercase; letter-spacing: 1px; }
        .btn-submit:hover { background: #c70810; }
        .btn-back { flex: 1; background: var(--bg3); color: var(--gray); border: 1px solid var(--border); padding: 14px 28px; border-radius: var(--radius-sm); font-size: 14px; font-weight: 600; cursor: pointer; text-decoration: none; text-align: center; text-transform: uppercase; letter-spacing: 1px; }
        .btn-back:hover { background: var(--border); color: var(--white); }
        .form-row-full { grid-column: 1 / -1; }
        select.form-input { cursor: pointer; }
    </style>
</head>
<body>
    <div class="admin-page">
        <div class="admin-header">
            <a href="/admin"><i class="fas fa-arrow-left"></i> Kembali</a>
            <h1 class="admin-title">EDIT ARTIS</h1>
        </div>

        <form class="form-container" method="POST" action="{{ route('artists.update', $artist->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Nama Artis/Band</label>
                <input type="text" name="name" class="form-input" placeholder="Nama artis..." value="{{ old('name', $artist->name) }}" required>
                @error('name') <span style="color: var(--red); font-size: 12px;">{{ $message }}</span> @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Genre</label>
                    <select name="genre" class="form-input">
                        <option value="">Pilih Genre</option>
                        <option value="Pop" {{ old('genre', $artist->genre) == 'Pop' ? 'selected' : '' }}>Pop</option>
                        <option value="Rock" {{ old('genre', $artist->genre) == 'Rock' ? 'selected' : '' }}>Rock</option>
                        <option value="R&B" {{ old('genre', $artist->genre) == 'R&B' ? 'selected' : '' }}>R&B</option>
                        <option value="Metal" {{ old('genre', $artist->genre) == 'Metal' ? 'selected' : '' }}>Metal</option>
                        <option value="Jazz" {{ old('genre', $artist->genre) == 'Jazz' ? 'selected' : '' }}>Jazz</option>
                        <option value="Indie" {{ old('genre', $artist->genre) == 'Indie' ? 'selected' : '' }}>Indie</option>
                        <option value="Hip-Hop" {{ old('genre', $artist->genre) == 'Hip-Hop' ? 'selected' : '' }}>Hip-Hop</option>
                    </select>
                    @error('genre') <span style="color: var(--red); font-size: 12px;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Asal Negara</label>
                    <select name="country" class="form-input" required>
                        <option value="">Pilih Negara</option>
                        <option value="indonesia" {{ old('country', $artist->country) == 'indonesia' ? 'selected' : '' }}>🇮🇩 Indonesia</option>
                        <option value="internasional" {{ old('country', $artist->country) == 'internasional' ? 'selected' : '' }}>🌍 Internasional</option>
                    </select>
                    @error('country') <span style="color: var(--red); font-size: 12px;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Foto Artis</label>
                <input type="file" name="image" class="form-input" accept="image/*">
                @if($artist->image)
                    <p style="margin-top: 8px; font-size: 12px; color: var(--gray);">Foto saat ini: {{ $artist->image }}</p>
                @endif
                @error('image') <span style="color: var(--red); font-size: 12px;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group form-row-full">
                <label class="form-label">Bio Singkat</label>
                <textarea name="bio" class="form-input form-textarea" placeholder="Ceritakan tentang artis...">{{ old('bio', $artist->bio) }}</textarea>
                @error('bio') <span style="color: var(--red); font-size: 12px;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Instagram</label>
                <input type="text" name="instagram" class="form-input" placeholder="@username" value="{{ old('instagram', $artist->instagram) }}">
                @error('instagram') <span style="color: var(--red); font-size: 12px;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-input" required>
                    <option value="active" {{ old('status', $artist->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('status', $artist->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
                @error('status') <span style="color: var(--red); font-size: 12px;">{{ $message }}</span> @enderror
            </div>

            <div class="btn-group">
                <button type="submit" class="btn-submit"><i class="fas fa-save"></i>&nbsp; PERBARUI ARTIS</button>
                <a href="/admin" class="btn-back"><i class="fas fa-times"></i>&nbsp; BATAL</a>
            </div>
        </form>
    </div>
</body>
</html>
