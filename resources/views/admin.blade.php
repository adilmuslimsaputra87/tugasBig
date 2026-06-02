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

    <!-- ====== ADMIN DASHBOARD ====== -->
    <div class="page-admin" id="page-admin">
        <div class="admin-layout">
            <div class="admin-sidebar">
                <div class="admin-nav-item active" id="adm-dashboard" onclick="switchAdmin('dashboard')"><i
                        class="fas fa-chart-pie"></i> Dashboard</div>
                <div class="admin-sidebar-title">Master Data</div>
                <div class="admin-nav-item" id="adm-concerts" onclick="switchAdmin('concerts')"><i
                        class="fas fa-music"></i> Konser <span class="admin-nav-badge">{{ count($konser ?? []) }}</span>
                </div>
                <div class="admin-nav-item" id="adm-artists" onclick="switchAdmin('artists')"><i
                        class="fas fa-microphone"></i> Artis <span class="admin-nav-badge">{{ count($artists ?? []) }}
                        </span></div>
                <div class="admin-nav-item" id="adm-tickets" onclick="switchAdmin('tickets')"><i
                        class="fas fa-ticket-alt"></i> Tiket<span class="admin-nav-badge">{{ count($tickets ?? []) }}
                        </span></div>
                <div class="admin-sidebar-title">Transaksi</div>
                <div class="admin-nav-item" id="adm-transactions" onclick="switchAdmin('transactions')"><i
                        class="fas fa-receipt"></i> Transaksi <span class="admin-nav-badge"
                        style="background:#22c55e;">{{ count($transaksi ?? []) }}</span></div>
                <div class="admin-nav-item" id="adm-users" onclick="switchAdmin('users')"><i class="fas fa-users"></i>
                    Users<span class="admin-nav-badge"
                        style="background:#22c55e;">{{ count($users ?? []) }}</span></div>
                <div class="admin-sidebar-title">Media</div>
                <div class="admin-nav-item" onclick="showToast('info','Fitur upload media')"><i
                        class="fas fa-upload"></i> Upload Media</div>
                <div style="padding:20px;border-top:1px solid var(--border);margin-top:auto;">
                    <button
                        style="width:100%;background:rgba(229,9,20,0.1);border:1px solid rgba(229,9,20,0.2);color:var(--red);padding:10px;border-radius:var(--radius-sm);font-size:13px;cursor:pointer;"
                        onclick="navigate('home')"><i class="fas fa-arrow-left"></i> Kembali ke Site</button>
                </div>
            </div>
            <div class="admin-main">
                <!-- Dashboard -->
                <div id="admin-section-dashboard">
                    <div class="admin-topbar">
                        <h1>DASHBOARD</h1>
                        <div style="font-size:13px;color:var(--gray);">Selamat datang, <strong
                                style="color:var(--white);">Admin</strong> — <span id="admin-date"></span></div>
                    </div>
                    <div class="stat-cards">
                        <div class="stat-card">
                            <div class="stat-card-icon"><i class="fas fa-ticket-alt"></i></div>
                            <div class="stat-card-value">{{ count($transactions ?? []) }}</div>
                            <div class="stat-card-label">Total Tiket Terjual</div>
                            <div class="stat-card-change up"><i class="fas fa-arrow-up"></i> +14.5% dari bulan lalu
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-card-icon"><i class="fas fa-money-bill-wave"></i></div>
                            <div class="stat-card-value">Rp 5,2M</div>
                            <div class="stat-card-label">Total Pendapatan</div>
                            <div class="stat-card-change up"><i class="fas fa-arrow-up"></i> +22.3% dari bulan lalu
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-card-icon"><i class="fas fa-music"></i></div>
                            <div class="stat-card-value">{{ count($konser ?? []) }}</div>
                            <div class="stat-card-label">Konser Aktif</div>
                            <div class="stat-card-change up"><i class="fas fa-arrow-up"></i> +3 konser baru</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-card-icon"><i class="fas fa-users"></i></div>
                            <div class="stat-card-value">{{ count($users ?? []) }}</div>
                            <div class="stat-card-label">Total User</div>
                            <div class="stat-card-change up"><i class="fas fa-arrow-up"></i> +156 baru hari ini</div>
                        </div>
                    </div>
                    <div class="chart-row">
                        <div class="chart-box">
                            <h3>PENJUALAN TIKET</h3>
                            <div style="font-size:12px;color:var(--gray);margin-bottom:16px;">7 Hari Terakhir</div>
                            <div class="chart-bars" id="admin-chart"></div>
                        </div>
                        <div class="chart-box">
                            <h3>KATEGORI</h3>
                            <div style="position:relative;width:120px;height:120px;margin:0 auto 20px;">
                                <svg viewBox="0 0 42 42" style="width:100%;height:100%;transform:rotate(-90deg);">
                                    <circle cx="21" cy="21" r="15.915" fill="none"
                                        stroke="var(--bg3)" stroke-width="4" />
                                    <circle cx="21" cy="21" r="15.915" fill="none" stroke="#E50914"
                                        stroke-width="4" stroke-dasharray="40 60" stroke-dashoffset="0" />
                                    <circle cx="21" cy="21" r="15.915" fill="none" stroke="#22c55e"
                                        stroke-width="4" stroke-dasharray="25 75" stroke-dashoffset="-40" />
                                    <circle cx="21" cy="21" r="15.915" fill="none" stroke="#f59e0b"
                                        stroke-width="4" stroke-dasharray="20 80" stroke-dashoffset="-65" />
                                    <circle cx="21" cy="21" r="15.915" fill="none" stroke="#60a5fa"
                                        stroke-width="4" stroke-dasharray="15 85" stroke-dashoffset="-85" />
                                </svg>
                                <div
                                    style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;font-family:var(--font-head);font-size:18px;">
                                    100%</div>
                            </div>
                            <div class="donut-wrap">
                                <div class="donut-item"><span class="donut-label"><span class="donut-dot"
                                            style="background:#E50914;"></span>Festival</span><span
                                        class="donut-pct">40%</span></div>
                                <div class="donut-item"><span class="donut-label"><span class="donut-dot"
                                            style="background:#22c55e;"></span>VIP</span><span
                                        class="donut-pct">25%</span></div>
                                <div class="donut-item"><span class="donut-label"><span class="donut-dot"
                                            style="background:#f59e0b;"></span>Regular</span><span
                                        class="donut-pct">20%</span></div>
                                <div class="donut-item"><span class="donut-label"><span class="donut-dot"
                                            style="background:#60a5fa;"></span>VVIP</span><span
                                        class="donut-pct">15%</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="admin-table-wrap">
                        <div class="table-header">
                            <h3>TRANSAKSI TERBARU</h3>
                            <div class="table-actions">
                                <button class="btn-add" onclick="switchAdmin('transactions')"><i
                                        class="fas fa-eye"></i> Lihat Semua</button>
                            </div>
                        </div>
                        <table class="admin-table" id="recent-tx-table"></table>
                    </div>
                </div>

                <!-- Concerts CRUD -->
                <div id="admin-section-concerts" style="display:none;">
                    <div class="admin-topbar">
                        <h1>KELOLA KONSER</h1>
                        <a class="btn-add" href="api/konsers/create"><i class="fas fa-plus"></i> Tambah Konser</a>
                    </div>
                    <div class="admin-table-wrap">
                        <div class="table-header">
                            <h3>DAFTAR KONSER</h3>
                            <div class="table-actions">
                                <div class="filter-search" style="width:220px;">
                                    <i class="fas fa-search"></i>
                                    <input type="text" placeholder="Cari konser..."
                                        oninput="filterAdminTable(this,'admin-concerts-table')"
                                        style="padding:8px 12px 8px 36px;background:var(--bg3);border:1px solid var(--border);border-radius:var(--radius-sm);color:var(--white);font-size:13px;width:100%;">
                                </div>
                            </div>
                        </div>
                        <table class="admin-table" id="admin-concerts-table"></table>
                    </div>
                </div>

                <!-- Artists CRUD -->
                <div id="admin-section-artists" style="display:none;">
                    <div class="admin-topbar">
                        <h1>KELOLA ARTIS</h1>
                        <a class="btn-add" href="/api/artists/create"><i class="fas fa-plus"></i> Tambah Artis</a>
                    </div>
                    <div class="admin-table-wrap">
                        <div class="table-header">
                            <h3>DAFTAR ARTIS</h3>
                        </div>
                        <table class="admin-table" id="admin-artists-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Foto</th>
                                    <th>Nama</th>
                                    <th>Bio</th>
                                    <th>Genre</th>
                                    <th>Asal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @forelse akan otomatis melakukan perulangan, dan menjalankan @empty jika data kosong --}}
                                @forelse($artists as $index => $a)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <img class="concert-thumb"
                                                src="{{ $a->img ? asset('storage/' . $a->img) : asset('images/default-artist.jpg') }}"
                                                alt="{{ $a->name }}"
                                                style="border-radius:50%; width:40px; height:40px; object-fit:cover;">
                                        </td>
                                        <td class="td-name">{{ $a->name }}</td>
                                        <td>{{ $a->bio }}</td>
                                        <td>{{ $a->genre }}</td>
                                        <td>
                                            <span
                                                class="status-badge {{ $a->country === 'indonesia' ? 'status-on-sale' : 'status-pending' }}">
                                                {{ $a->country === 'indonesia' ? '🇮🇩 Lokal' : '🌍 International' }}
                                            </span>
                                        </td>
                                        <td>{{ $a->status }}</td>
                                        <td>
                                            <div class="td-actions">
                                                {{-- Tombol Edit --}}
                                                <a class="btn-edit" href="/api/artists/{{ $a->id }}/edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                {{-- Tombol Hapus (Menggunakan HTML Form agar aman dan mendukung Method DELETE Laravel) --}}
                                                <form action="/api/artists/{{ $a->id }}" method="POST"
                                                    onsubmit="return confirm('Apakah kamu yakin ingin menghapus artis ini?')"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-del">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    {{-- Tampilan jika di database sama sekali tidak ada data artis --}}
                                    <tr>
                                        <td colspan="7"
                                            style="text-align:center; padding: 30px; color: var(--gray);">
                                            Tidak ada data artis ditemukan di database.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tickets CRUD -->
                <div id="admin-section-tickets" style="display:none;">
                    <div class="admin-topbar">
                        <h1>KELOLA TIKET</h1>
                        <a class="btn-add" href="/api/tickets/create"><i class="fas fa-plus"></i> Tambah Kategori
                            Tiket</a>
                    </div>
                    <div class="admin-table-wrap">
                        <div class="table-header">
                            <h3>DAFTAR TIKET</h3>
                        </div>
                        <table class="admin-table" id="admin-tickets-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Kategori</th>
                                    <th>Harga</th>
                                    <th>Stok Awal</th>
                                    <th>Terjual</th>
                                    <th>Sisa</th>
                                    <th>Deskripsi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tickets as $index => $t)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="td-name">{{ $t->name }}</td>
                                        {{-- Format harga ke rupiah otomatis (contoh: Rp 2.500.000) --}}
                                        <td>Rp {{ number_format($t->price, 0, ',', '.') }}</td>

                                        {{-- Stok Awal didapat dari Sisa Stok + Terjual --}}
                                        <td>{{ $t->stock + $t->sold }}</td>

                                        {{-- Tiket Terjual --}}
                                        <td style="color:#22c55e;">{{ $t->sold }}</td>

                                        {{-- Sisa Stok Tiket --}}
                                        <td>{{ $t->stock }}</td>
                                        <td>{{ $t->description }}</td>
                                        <td>
                                            <div class="td-actions">
                                                {{-- Tombol Edit --}}
                                                <a class="btn-edit" href="/api/tickets/{{ $t->id }}/edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                {{-- Tombol Hapus Form --}}
                                                <form action="/api/tickets/{{ $t->id }}" method="POST"
                                                    onsubmit="return confirm('Apakah kamu yakin ingin menghapus kategori tiket {{ $t->name }}?')"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-del">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7"
                                            style="text-align:center; padding: 30px; color: var(--gray);">
                                            Tidak ada data tiket ditemukan di database.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Transactions -->
                <div id="admin-section-transactions" style="display:none;">
                    <div class="admin-topbar">
                        <h1>TRANSAKSI</h1>
                    </div>
                    <div class="admin-table-wrap">
                        <div class="table-header">
                            <h3>SEMUA TRANSAKSI</h3>
                        </div>
                        <table class="admin-table" id="admin-transactions-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Konser</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transaksi as $tx)
                                    <tr>
                                        <td><code style="color:var(--red);font-size:12px;">{{ $tx->id }}</code>
                                        </td>
                                        <td>{{ $tx->first_name }} {{ $tx->last_name }}</td>
                                        <td style="font-size:13px;color:var(--gray);">
                                            {{ $tx->ticket?->konser?->title ?? 'Konser Tidak Ditemukan' }}</td>
                                        <td>{{ $tx->quantity }}</td>
                                        <td><strong>Rp {{ number_format($tx->total_price, 0, ',', '.') }}</strong></td>
                                        <td><span
                                                class="status-badge {{ $tx->payment_status === 'success' ? 'status-success' : 'status-pending' }}">{{ strtoupper($tx->payment_status) }}</span>
                                        </td>
                                        <td style="font-size:13px;">
                                            {{ $tx->payment_date ? \Carbon\Carbon::parse($tx->payment_date)->translatedFormat('Y-m-d') : '-' }}
                                        </td>
                                        <td>
                                            <div class="td-actions">
                                                <button class="btn-view" title="Detail {{ $tx->id }}"><i
                                                        class="fas fa-eye"></i></button>
                                                <button class="btn-edit" title="Perbarui Status"><i
                                                        class="fas fa-check"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Users CRUD -->
                <div id="admin-section-users" style="display:none;">
                    <div class="admin-topbar">
                        <h1>KELOLA USER</h1>
                        <a class="btn-add" href="{{ route('users.create') }}"><i class="fas fa-plus"></i> Tambah
                            User</a>
                    </div>
                    <div class="admin-table-wrap">
                        <div class="table-header">
                            <h3>DAFTAR USER</h3>
                        </div>
                        <table class="admin-table" id="admin-users-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Bergabung</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $index => $u)
                                    <tr>
                                        {{-- Nomor urut (1, 2, 3, dst) --}}
                                        <td>{{ $index + 1 }}</td>

                                        {{-- Nama User --}}
                                        <td class="td-name">{{ $u->name }}</td>

                                        {{-- Email User --}}
                                        <td style="color:var(--gray); font-size:13px;">{{ $u->email }}</td>

                                        {{-- Role User (ADMIN / CUSTOMER / dll) --}}
                                        <td>
                                            <span
                                                class="status-badge {{ $u->role === 'admin' ? 'status-sold-out' : 'status-pending' }}">
                                                {{ strtoupper($u->role) }}
                                            </span>
                                        </td>

                                        {{-- Status User (ACTIVE / INACTIVE / dll) --}}
                                        <td>
                                            <span
                                                class="status-badge {{ $u->status === 'active' ? 'status-on-sale' : 'status-sold-out' }}">
                                                {{ strtoupper($u->status) }}
                                            </span>
                                        </td>

                                        {{-- Tanggal Bergabung (Format: 26 Mei 2026 atau sesuai lokalisasi) --}}
                                        <td style="font-size:13px;">
                                            {{ $u->created_at ? $u->created_at->translatedFormat('d F Y') : '-' }}
                                        </td>

                                        {{-- Tombol Aksi --}}
                                        <td>
                                            <div class="td-actions">
                                                {{-- Tombol Edit --}}
                                                <a class="btn-edit" href="/api/users/{{ $u->id }}/edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                {{-- Tombol Hapus Form --}}
                                                <form action="/api/users/{{ $u->id }}" method="POST"
                                                    onsubmit="return confirm('Apakah kamu yakin ingin menghapus dari database {{ $u->name }}?')"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-del">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7"
                                            style="text-align:center; padding: 30px; color: var(--gray);">
                                            Tidak ada data user ditemukan di database.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification Container -->
    <div class="toast" id="toast-container"></div>

    <script src="{{ asset('js/script.js') }}"></script>

</body>

</html>
