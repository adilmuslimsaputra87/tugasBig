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

    <!-- ====== NAVBAR ====== -->
    <nav id="navbar">
        <div class="nav-logo" onclick="navigate('home')">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/08/Netflix_2015_logo.svg/1024px-Netflix_2015_logo.svg.png"
                alt="logo" style="display:none;">
            <div
                style="width:36px;height:36px;border-radius:8px;background:#E50914;display:flex;align-items:center;justify-content:center;font-family:'Bebas Neue',sans-serif;font-size:22px;color:white;letter-spacing:1px;">
                P</div>
            <span class="nav-logo-text">PRIME<span>STAGE</span></span>
        </div>
        <div class="nav-menu">
            <span class="nav-link active" onclick="navigate('home')" id="nl-home">Home</span>
            <span class="nav-link" onclick="navigate('concerts')" id="nl-concerts">Concerts</span>
            <span class="nav-link" onclick="navigate('artists')" id="nl-artists">Artists</span>
            <span class="nav-link" onclick="navigate('gallery')" id="nl-gallery">Gallery</span>
            <a class="nav-link" href="/admin" id="nl-admin" style="color:#E50914;">Admin</a>
        </div>
        <div class="nav-right">
            <div class="nav-search-wrap">
                <i class="fas fa-search nav-search-icon"></i>
                <input type="text" class="nav-search" placeholder="Cari konser...">
            </div>

            @guest
                <div id="nav-auth-btns">
                    <button class="btn-login" onclick="openModal('login')" style="margin-right:8px;">Masuk</button>
                    <button class="btn-signup" onclick="openModal('register')">Daftar</button>
                </div>
            @endguest

            @auth
                <div id="nav-user">
                    <div class="nav-avatar">
                        <span id="nav-avatar-initial">
                            {{ strtoupper(Str::substr(Auth::user()->first_name, 0, 1) . Str::substr(Auth::user()->last_name, 0, 1)) }}
                        </span>
                        <div class="nav-dropdown">
                            <div class="nav-dd-item" onclick="navigate('profile')"><i class="fas fa-user"></i> Profil Saya
                            </div>
                            <div class="nav-dd-item" onclick="navigate('history')"><i class="fas fa-ticket-alt"></i> Tiket
                                Saya</div>
                            @if (auth()->user()->role === 'admin')
                                <div class="nav-dd-item" onclick="navigate('admin')"><i class="fas fa-shield-alt"></i> Admin
                                    Panel</div>
                            @endif
                            <div class="nav-dd-item" style="border-top:1px solid var(--border);margin-top:4px;"
                                onclick="logout()"><i class="fas fa-sign-out-alt"></i> Keluar</div>
                        </div>
                    </div>
                </div>
            @endauth

        </div>
    </nav>

    <!-- ====== HOME PAGE ====== -->
    <div class="page active" id="page-home">
        <!-- Hero -->
        <section class="hero">
            <div class="hero-bg"></div>
            <div class="hero-particles" id="hero-particles"></div>
            <div class="hero-grid"></div>
            <div class="hero-content">
                <div class="hero-badge"><i class="fas fa-circle"></i> LIVE CONCERTS 2025</div>
                <h1 class="hero-title">
                    EXPERIENCE<br>
                    <span class="line2">THE STAGE</span>
                    LIVE
                </h1>
                <p class="hero-subtitle">Platform tiket konser premium Indonesia. Dapatkan akses eksklusif ke konser
                    band lokal hingga artis internasional terbaik.</p>
                <div class="hero-cta">
                    <button class="btn-primary" onclick="navigate('concerts')"><i class="fas fa-ticket-alt"></i> Beli
                        Tiket Sekarang</button>
                    <button class="btn-outline" onclick="openVideoModal()"><i class="fas fa-play"></i> Tonton
                        Trailer</button>
                </div>
                <div class="hero-stats">
                    <div class="stat">
                        <div class="stat-num">{{ count($konsers) ?? 0 }} +</div>
                        <div class="stat-label">Konser Live</div>
                    </div>
                    <div class="stat">
                        <div class="stat-num">{{ count($transaksi) ?? 0 }} +</div>
                        <div class="stat-label">Tiket Terjual</div>
                    </div>
                    <div class="stat">
                        <div class="stat-num">{{ count($artists) ?? 0 }} +</div>
                        <div class="stat-label">Artis</div>
                    </div>
                </div>
            </div>
            <div class="hero-featured" id="hero-featured"></div>
            <div class="hero-scroll-indicator">
                <span>SCROLL</span>
                <i class="fas fa-chevron-down"></i>
            </div>
        </section>

        <!-- Featured Banner -->
        <div class="featured-banner" data-aos onclick="openConcertDetail(0)">
            <img class="featured-banner-img"
                src="https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=1200&q=80" alt="">
            <div class="featured-banner-bg"></div>
            <div class="featured-banner-content">
                <div class="featured-label"><i class="fas fa-star"></i> &nbsp;Featured Concert</div>
                <h2 class="featured-title">COLDPLAY<br>MUSIC OF THE SPHERES</h2>
                <p class="featured-desc">Konser spektakuler dari salah satu band terbesar dunia dengan visual show yang
                    luar biasa dan pengalaman live yang tak terlupakan.</p>
                <div class="featured-meta">
                    <div class="featured-meta-item"><i class="fas fa-calendar"></i> 15 Agustus 2025</div>
                    <div class="featured-meta-item"><i class="fas fa-map-marker-alt"></i> GBK Jakarta</div>
                    <div class="featured-meta-item"><i class="fas fa-clock"></i> 19.00 WIB</div>
                </div>
                <button class="btn-primary" style="font-size:13px;padding:11px 28px;"><i
                        class="fas fa-ticket-alt"></i> Beli Tiket</button>
            </div>
        </div>

        <!-- Upcoming Concerts -->
        <section class="section">
            <div class="section-header" data-aos>
                <div>
                    <div class="section-tag">On Sale Now</div>
                    <h2 class="section-title">UPCOMING <span>CONCERTS</span></h2>
                </div>
                <span class="section-link" onclick="navigate('concerts')">Lihat Semua <i
                        class="fas fa-arrow-right"></i></span>
            </div>
            <div class="concerts-grid" id="home-concerts-grid"></div>
        </section>

        <!-- Artists -->
        <section class="section" style="padding-top:0;">
            <div class="section-header" data-aos>
                <div>
                    <div class="section-tag">Top Performers</div>
                    <h2 class="section-title">FEATURED <span>ARTISTS</span></h2>
                </div>
                <span class="section-link" onclick="navigate('artists')">Lihat Semua <i
                        class="fas fa-arrow-right"></i></span>
            </div>
            <div class="artists-scroll" id="home-artists"></div>
        </section>

        <!-- Video Section -->
        <section class="video-section">
            <div class="section-header" data-aos>
                <div>
                    <div class="section-tag">Watch Now</div>
                    <h2 class="section-title">CONCERT <span>TRAILERS</span></h2>
                </div>
            </div>
            <div class="video-grid" id="video-grid"></div>
        </section>

        <!-- Footer -->
        <footer>
            <div class="footer-grid">
                <div>
                    <div class="footer-logo-text">PRIME<span style="color:var(--red);">STAGE</span></div>
                    <p class="footer-desc">Platform tiket konser premium terpercaya di Indonesia. Rasakan pengalaman
                        live music terbaik bersama kami.</p>
                    <div class="footer-social">
                        <div class="social-btn"><i class="fab fa-instagram"></i></div>
                        <div class="social-btn"><i class="fab fa-twitter"></i></div>
                        <div class="social-btn"><i class="fab fa-youtube"></i></div>
                        <div class="social-btn"><i class="fab fa-tiktok"></i></div>
                    </div>
                </div>
                <div>
                    <div class="footer-heading">Konser</div>
                    <span class="footer-link">Semua Konser</span>
                    <span class="footer-link">Konser Mendatang</span>
                    <span class="footer-link">Festival</span>
                    <span class="footer-link">Internasional</span>
                </div>
                <div>
                    <div class="footer-heading">Informasi</div>
                    <span class="footer-link">Tentang Kami</span>
                    <span class="footer-link">Cara Beli Tiket</span>
                    <span class="footer-link">FAQ</span>
                    <span class="footer-link">Hubungi Kami</span>
                </div>
                <div>
                    <div class="footer-heading">Legal</div>
                    <span class="footer-link">Syarat & Ketentuan</span>
                    <span class="footer-link">Kebijakan Privasi</span>
                    <span class="footer-link">Refund Policy</span>
                </div>
            </div>
            <div class="footer-bottom">
                <span>© 2025 PrimeStage. Hak cipta dilindungi.</span>
                <span>Made with <i class="fas fa-heart" style="color:var(--red);"></i> in Indonesia</span>
            </div>
        </footer>
    </div>

    <!-- ====== CONCERTS PAGE ====== -->
    <div class="page" id="page-concerts">
        <div class="page-header">
            <div class="section-tag">Semua Konser</div>
            <h1 class="page-title">KONSER & <span style="color:var(--red);">EVENTS</span></h1>
            <p class="page-subtitle">Temukan konser favorit kamu dan dapatkan tiketnya sekarang</p>
        </div>
        <div class="filter-bar">
            <div class="filter-search">
                <i class="fas fa-search"></i>
                <input type="text" id="concert-search" placeholder="Cari artis atau konser..."
                    oninput="filterConcerts()">
            </div>
            <select class="filter-select" id="filter-genre" onchange="filterConcerts()">
                <option value="">Semua Genre</option>
                <option>Rock</option>
                <option>Pop</option>
                <option>R&B</option>
                <option>Metal</option>
                <option>Jazz</option>
                <option>Indie</option>
            </select>
            <select class="filter-select" id="filter-city" onchange="filterConcerts()">
                <option value="">Semua Kota</option>
                <option>Jakarta</option>
                <option>Bandung</option>
                <option>Surabaya</option>
                <option>Yogyakarta</option>
                <option>Bali</option>
            </select>
            <select class="filter-select" id="filter-sort" onchange="filterConcerts()">
                <option value="date">Tanggal Terdekat</option>
                <option value="price-asc">Harga: Terendah</option>
                <option value="price-desc">Harga: Tertinggi</option>
            </select>
            <button class="filter-btn active" onclick="filterConcerts('all')"><i class="fas fa-th"></i>
                Semua</button>
            <button class="filter-btn" onclick="filterConcerts('indonesia')"><i class="fas fa-flag"></i>
                Lokal</button>
            <button class="filter-btn" onclick="filterConcerts('internasional')"><i class="fas fa-globe"></i>
                Internasional</button>
        </div>
        <div class="concerts-page-grid" id="concerts-page-grid"></div>
    </div>

    <!-- ====== CONCERT DETAIL PAGE ====== -->
    <div class="page" id="page-detail">
        <div class="detail-hero">
            <div class="detail-hero-bg"><img id="detail-bg-img" src="" alt=""></div>
            <div class="detail-hero-overlay"></div>
            <div class="detail-hero-content">
                <div class="detail-genre-badge" id="detail-badge">ROCK</div>
                <h1 class="detail-title" id="detail-title">CONCERT TITLE</h1>
                <div class="detail-artist" id="detail-artist">
                    <i class="fas fa-music"></i><span>Artist Name</span>
                </div>
                <div class="detail-meta-row">
                    <div class="detail-meta-item"><i class="fas fa-calendar"></i><span id="detail-date">Date</span>
                    </div>
                    <div class="detail-meta-item"><i class="fas fa-map-marker-alt"></i><span
                            id="detail-venue">Venue</span></div>
                    <div class="detail-meta-item"><i class="fas fa-clock"></i><span id="detail-time">19.00 WIB</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="detail-body">
            <div class="detail-desc">
                <h3>TENTANG KONSER</h3>
                <p id="detail-description">Loading...</p>
                <h3>LINE-UP ARTIS</h3>
                <div id="detail-lineup"></div>
                <h3>GALERI</h3>
                <div class="gallery-grid" id="detail-gallery"></div>
                <h3>VIDEO TRAILER</h3>
                <div class="video-item" style="margin-top:16px;" onclick="openVideoModal()">
                    <img id="detail-trailer-thumb" src="" alt="">
                    <div class="video-play-btn"><i class="fas fa-play"></i></div>
                    <div class="video-item-info">
                        <div class="video-item-title" id="detail-trailer-title">Tonton Trailer</div>
                    </div>
                </div>
            </div>
            <div class="ticket-sidebar">
                <div class="ticket-box">
                    <h3>PILIH TIKET</h3>
                    <div id="ticket-categories"></div>
                    <div class="qty-control" style="margin-top:20px;">
                        <span style="font-size:13px;color:var(--gray);flex:1;">Jumlah Tiket</span>
                        <button class="qty-btn" onclick="changeQty(-1)">−</button>
                        <div class="qty-display" id="qty-display">1</div>
                        <button class="qty-btn" onclick="changeQty(1)">+</button>
                    </div>
                    <div class="booking-summary">
                        <div class="summary-row">
                            <span class="summary-label">Harga Tiket</span>
                            <span class="summary-value" id="s-price">Rp 0</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Qty</span>
                            <span class="summary-value" id="s-qty">x1</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Biaya Admin</span>
                            <span class="summary-value">Rp 10.000</span>
                        </div>
                        <div class="summary-row total">
                            <span class="summary-label">TOTAL</span>
                            <span class="summary-value" id="s-total">Rp 0</span>
                        </div>
                    </div>
                    <button class="btn-book-now" onclick="goCheckout()"><i class="fas fa-ticket-alt"></i>&nbsp; BELI
                        TIKET</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ====== ARTISTS PAGE ====== -->
    <div class="page" id="page-artists">
        <div class="page-header">
            <div class="section-tag">Semua Artis</div>
            <h1 class="page-title">FEATURED <span style="color:var(--red);">ARTISTS</span></h1>
            <p class="page-subtitle">Band dan artis terbaik yang akan tampil di PrimeStage</p>
        </div>
        <div class="filter-bar">
            <div class="filter-search">
                <i class="fas fa-search"></i>
                <input type="text" id="artist-search" placeholder="Cari artis..." oninput="filterArtists()">
            </div>
            <button class="filter-btn active" onclick="setArtistFilter('all',this)">Semua</button>
            <button class="filter-btn" onclick="setArtistFilter('indonesia',this)">Indonesia</button>
            <button class="filter-btn" onclick="setArtistFilter('international',this)">International</button>
        </div>
        <div class="artists-grid" id="artists-grid"></div>
    </div>

    <!-- ====== GALLERY PAGE ====== -->
    <div class="page" id="page-gallery">
        <div class="page-header">
            <div class="section-tag">Galeri</div>
            <h1 class="page-title">PHOTO <span style="color:var(--red);">GALLERY</span></h1>
            <p class="page-subtitle">Momen terbaik dari konser-konser spektakuler</p>
        </div>
        <div style="padding:32px 5%;columns:4 200px;gap:16px;" id="gallery-grid">
        </div>
    </div>

    <!-- ====== PROFILE PAGE ====== -->
    <div class="page" id="page-profile">
        <div class="profile-wrap">
            <div class="profile-header">
                <div class="profile-avatar-lg" id="profile-avatar-text">A</div>
                <div>
                    <div class="profile-name" id="profile-name">Adil Muslim</div>
                    <div class="profile-email" id="profile-email">adil@example.com</div>
                    <div class="profile-badges">
                        <span class="profile-badge">MEMBER</span>
                        <span class="profile-badge">VERIFIED</span>
                    </div>
                </div>
                <button class="btn-outline" style="margin-left:auto;font-size:13px;"
                    onclick="openModal('editProfile')"><i class="fas fa-edit"></i>&nbsp; Edit Profil</button>
            </div>
            <div class="profile-tabs">
                <div class="profile-tab active" onclick="switchProfileTab('orders',this)">Riwayat Pembelian</div>
                <div class="profile-tab" onclick="switchProfileTab('settings',this)">Pengaturan Akun</div>
            </div>
            <div id="profile-tab-orders">
                <div class="history-item" onclick="navigate('checkout')">
                    <img class="history-img"
                        src="https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=200&q=80" alt="">
                    <div>
                        <div class="history-title">Coldplay — Music of the Spheres</div>
                        <div class="history-meta"><i class="fas fa-calendar" style="margin-right:6px;"></i>15 Agustus
                            2025 · GBK Jakarta</div>
                        <div class="history-meta" style="margin-top:4px;"><i class="fas fa-ticket-alt"
                                style="margin-right:6px;"></i>2x Festival — Rp 1.500.000/tiket</div>
                    </div>
                    <div class="history-total">
                        <div class="history-amount">Rp 3.010.000</div>
                        <div><span class="status-badge status-success">SUKSES</span></div>
                    </div>
                </div>
                <div class="history-item">
                    <img class="history-img"
                        src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=200&q=80" alt="">
                    <div>
                        <div class="history-title">NOAH — Dunia Batas World Tour</div>
                        <div class="history-meta"><i class="fas fa-calendar" style="margin-right:6px;"></i>20
                            September 2025 · Trans Studio Bandung</div>
                        <div class="history-meta" style="margin-top:4px;"><i class="fas fa-ticket-alt"
                                style="margin-right:6px;"></i>1x VIP — Rp 500.000/tiket</div>
                    </div>
                    <div class="history-total">
                        <div class="history-amount">Rp 510.000</div>
                        <div><span class="status-badge status-pending">PENDING</span></div>
                    </div>
                </div>
            </div>
            <div id="profile-tab-settings" style="display:none;">
                <div class="ticket-box">
                    <h3>PENGATURAN AKUN</h3>
                    <div class="form-group"><label class="form-label">Nama Lengkap</label><input class="form-input"
                            type="text" value="Adil Muslim Saputra"></div>
                    <div class="form-group"><label class="form-label">Email</label><input class="form-input"
                            type="email" value="adil@example.com"></div>
                    <div class="form-group"><label class="form-label">Nomor HP</label><input class="form-input"
                            type="tel" value="+62 812 3456 7890"></div>
                    <div class="form-group"><label class="form-label">Password Baru</label><input class="form-input"
                            type="password" placeholder="••••••••"></div>
                    <button class="btn-book-now" onclick="showToast('success','Profil berhasil diperbarui!')"
                        style="max-width:200px;">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ====== MODALS ====== -->
    <!-- Auth Modal -->
    <div class="modal-overlay" id="modal-auth">
        <div class="modal">
            <button class="modal-close" onclick="closeModal('auth')"><i class="fas fa-times"></i></button>
            <div class="modal-title">PRIMESTAGE</div>
            <div class="modal-subtitle">Platform tiket konser premium</div>
            <div class="modal-tabs">
                @if (auth()->check())
                    <div class="modal-tab active" id="tab-profile" onclick="switchAuthTab('profile')">Profil</div>
                    <div class="modal-tab" id="tab-logout" onclick="logout()">Keluar</div>
                @else
                    <div class="modal-tab active" id="tab-login" onclick="switchAuthTab('login')">Masuk</div>
                    <div class="modal-tab" id="tab-register" onclick="switchAuthTab('register')">Daftar</div>
                @endif
            </div>
            <!-- Login Form -->
            <form action="{{ route('login') }}" method="post">
                @csrf
                <div id="auth-login-form">
                    <div class="form-social">
                        <button class="btn-social" type="button" onclick="loginWithGoogle()"><i
                                class="fab fa-google" style="color:#ea4335;"></i> Google</button>
                        <button class="btn-social" type="button" onclick="loginWithFacebook()"><i
                                class="fab fa-facebook" style="color:#1877f2;"></i> Facebook</button>
                    </div>
                    <div class="form-divider"><span>atau dengan email</span></div>
                    <div class="form-group"><label class="form-label">Email</label><input class="form-input"
                            type="email" id="login-email" name="email" placeholder="email@example.com"></div>
                    <div class="form-group"><label class="form-label">Password</label><input class="form-input"
                            type="password" id="login-password" name="password" placeholder="••••••••"></div>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
                        <span style="font-size:12px;color:var(--red);cursor:pointer;">Lupa password?</span>
                    </div>
                    <button class="btn-submit" type="submit">MASUK</button>
                </div>
            </form>
            <!-- Register Form -->
            <form action="{{ route('register') }}" method="post">
                @csrf
                <div id="auth-register-form" style="display:none;">
                    <div class="form-row">
                        <div class="form-group"><label class="form-label">Nama Depan</label><input class="form-input"
                                type="text" id="reg-firstname" placeholder="John"></div>
                        <div class="form-group"><label class="form-label">Nama Belakang</label><input
                                class="form-input" type="text" id="reg-lastname" placeholder="Doe"></div>
                    </div>
                    <div class="form-group"><label class="form-label">Email</label><input class="form-input"
                            type="email" id="reg-email" placeholder="email@example.com"></div>
                    <div class="form-group"><label class="form-label">Nomor HP</label><input class="form-input"
                            type="tel" placeholder="+62 812 3456 7890"></div>
                    <div class="form-group"><label class="form-label">Tanggal Lahir</label><input class="form-input"
                            type="date" id="reg-dob"></div>
                    <div class="form-group"><label class="form-label">Password</label><input class="form-input"
                            type="password" id="reg-password" placeholder="Min. 8 karakter"></div>
                    <div class="form-group"><label class="form-label">Konfirmasi Password</label><input
                            class="form-input" type="password" placeholder="Ulangi password"></div>
                    <label class="form-checkbox" style="margin-bottom:16px;"><input type="checkbox" required> Saya
                        setuju dengan <span style="color:var(--red);">Syarat &amp; Ketentuan</span></label>
                    <button class="btn-submit" type="submit">BUAT AKUN</button>
                </div>
        </div>
    </div>

    <!-- CRUD Modal -->
    <div class="modal-overlay" id="modal-crud">
        <div class="modal crud-modal" style="max-width:600px;max-height:85vh;overflow-y:auto;">
            <button class="modal-close" onclick="closeModal('crud')"><i class="fas fa-times"></i></button>
            <div class="modal-title" id="crud-modal-title">TAMBAH DATA</div>
            <div id="crud-modal-body"></div>
        </div>
    </div>

    <!-- Video Modal -->
    <div class="modal-overlay" id="modal-video">
        <div class="modal" style="max-width:800px;padding:16px;">
            <button class="modal-close" onclick="closeModal('video')" style="z-index:10;"><i
                    class="fas fa-times"></i></button>
            <div
                style="background:#000;border-radius:var(--radius);overflow:hidden;aspect-ratio:16/9;display:flex;align-items:center;justify-content:center;">
                <iframe id="yt-iframe" width="100%" height="100%" src="" frameborder="0"
                    allow="autoplay;encrypted-media" allowfullscreen style="aspect-ratio:16/9;"></iframe>
            </div>
            <div style="padding:12px 4px 4px;font-family:var(--font-head);font-size:20px;letter-spacing:2px;"
                id="video-modal-title"></div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast" id="toast-container"></div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="js/script.js"></script>
</body>

</html>
