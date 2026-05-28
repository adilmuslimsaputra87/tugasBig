<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit User - PrimeStage Admin</title>
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
            <a href="/admin"><i class="fas fa-arrow-left"></i> Kembali</a>
            <h1 class="admin-title">EDIT USER</h1>
        </div>

        <form class="form-container" method="POST" action="{{ route('users.update', $user->id) }}">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Nama Depan</label>
                    <input type="text" name="first_name" class="form-input" placeholder="John" value="{{ old('first_name', $user->first_name) }}" required>
                    @error('first_name') <span style="color: var(--red); font-size: 12px;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Belakang</label>
                    <input type="text" name="last_name" class="form-input" placeholder="Doe" value="{{ old('last_name', $user->last_name) }}" required>
                    @error('last_name') <span style="color: var(--red); font-size: 12px;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-input" placeholder="user@example.com" value="{{ old('email', $user->email) }}" required>
                @error('email') <span style="color: var(--red); font-size: 12px;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Nomor HP</label>
                <input type="tel" name="phone" class="form-input" placeholder="+62 812 ..." value="{{ old('phone', $user->phone) }}">
                @error('phone') <span style="color: var(--red); font-size: 12px;">{{ $message }}</span> @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="password" class="form-input" placeholder="••••••••" minlength="8">
                    <p style="font-size: 12px; color: var(--gray); margin-top: 4px;">Kosongkan jika tidak ingin mengubah password</p>
                    @error('password') <span style="color: var(--red); font-size: 12px;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-input" placeholder="••••••••" minlength="8">
                    @error('password_confirmation') <span style="color: var(--red); font-size: 12px;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-input" required>
                        <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role') <span style="color: var(--red); font-size: 12px;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-input" required>
                        <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                    @error('status') <span style="color: var(--red); font-size: 12px;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn-submit"><i class="fas fa-save"></i>&nbsp; PERBARUI USER</button>
                <a href="/admin" class="btn-back"><i class="fas fa-times"></i>&nbsp; BATAL</a>
            </div>
        </form>
    </div>
</body>
</html>
