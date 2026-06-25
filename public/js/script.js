// ====== APP STATE ======
let currentPage = "home";
let currentUser = null;
let selectedConcertId = null;
let selectedCategory = null;
let selectedQty = 1;
let selectedCategoryPrice = 0;
let selectedTicketId = null;

// Gallery images (global)
const galleryImgs = [
    "storage/",
];

// In-memory cache agar tidak fetch ulang terus-menerus
let _concertsCache = null;
let _artistsCache = null;
let _ticketCategoriesCache = null;

// CSRF token helper (Laravel)
function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content || "";
}

// ====== BASE API HELPER ======
async function apiFetch(url, options = {}) {
    const defaults = {
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken(),
        },
    };
    const response = await fetch(url, {
        ...defaults,
        ...options,
        headers: { ...defaults.headers, ...(options.headers || {}) },
    });
    if (!response.ok) {
        const err = await response.json().catch(() => ({}));
        throw new Error(err.message || `HTTP ${response.status}`);
    }
    return response.json();
}

function resolveImageSrc(src, fallback = "/images/default-poster.png") {
    // 1. Jika data kosong, gunakan gambar fallback bawaan (lokal)
    if (!src || src === "0" || typeof src !== "string") {
        return `${window.location.origin}${fallback}`;
    }

    const trimmed = src.trim();

    // 2. Jika data dari database sudah berupa URL utuh (http/https), langsung gunakan
    if (trimmed.startsWith("http://") || trimmed.startsWith("https://")) {
        return trimmed;
    }

    if (trimmed.startsWith("//")) {
        return `${window.location.protocol}${trimmed}`;
    }

    // 3. Bersihkan sisa-sisa string "/storage/" atau "storage/" lama jika masih ada di DB
    let normalized = trimmed;
    if (normalized.startsWith("/storage/")) {
        normalized = normalized.slice(9);
    } else if (normalized.startsWith("storage/")) {
        normalized = normalized.slice(8);
    }

    // 4. --- PENGALIHAN KE SUPABASE ---
    // Konfigurasi Project ID dan nama Bucket Supabase Anda
    const projectId = 'mhimeqexeizxveuckwyn';
    const bucketName = 'primestage';

    // Menghasilkan Public Object URL Supabase secara otomatis
    return `https://${projectId}.storage.supabase.co/storage/v1/object/public/${bucketName}/${normalized}`;
}
// ====== AUTH ======
function openModal(type) {
    if (type === "login" || type === "register") {
        document.getElementById("modal-auth").classList.add("show");
        switchAuthTab(type);
    } else if (type === "editProfile") {
        showToast("info", "Edit profil tersedia di tab Pengaturan Akun");
    } else if (type === "transaction") {
        document.getElementById("modal-transaction").classList.add("show");
    }
}

function closeModal(type) {
    if (type === "transaction")
        document.getElementById("modal-transaction").classList.remove("show");
    if (type === "auth")
        document.getElementById("modal-auth").classList.remove("show");
    if (type === "crud")
        document.getElementById("modal-crud").classList.remove("show");
    if (type === "video") {
        document.getElementById("modal-video").classList.remove("show");
        document.getElementById("yt-iframe").src = "";
    }
}

function switchAuthTab(tab) {
    document
        .getElementById("tab-login")
        .classList.toggle("active", tab === "login");
    document
        .getElementById("tab-register")
        .classList.toggle("active", tab === "register");
    document.getElementById("auth-login-form").style.display =
        tab === "login" ? "block" : "none";
    document.getElementById("auth-register-form").style.display =
        tab === "register" ? "block" : "none";
}

function loginUser() {
    const email = document.getElementById("login-email").value;
    const pass = document.getElementById("login-password").value;
    if (!email || !pass) {
        showToast("error", "Email dan password harus diisi");
        return;
    }

    loginUserWithAPI(email, pass)
        .then((data) => {
            const user = data.user || data;
            currentUser = {
                name: user.name || email.split("@")[0],
                email: user.email || email,
                avatar: (user.name || email).charAt(0).toUpperCase(),
            };
            document.getElementById("nav-auth-btns").style.display = "none";
            document.getElementById("nav-user").style.display = "block";
            document.getElementById("nav-avatar-initial").textContent =
                currentUser.avatar;
            closeModal("auth");
            showToast("success", "Selamat datang, " + currentUser.name + "!");
        })
        .catch((error) => {
            showToast("error", error.message || "Email atau password salah");
        });
}

function loginDemo() {
    document.getElementById("login-email").value = "adil@uhamka.ac.id";
    document.getElementById("login-password").value = "primestage123";
    loginUser();
}

function registerUserWithAPI(data) {
    return axios.post('/api/register', data)
        .then(response => response.data)
        .catch(error => {
            const errors = error.response?.data?.errors;

        if (errors) {
            const firstError = Object.values(errors)[0][0];
            throw new Error(firstError);
        }

        throw new Error(
            error.response?.data?.message || 'Gagal membuat akun'
        );
    });
}

async function registerUser(e) {
    if (e) e.preventDefault();

    const email = document.getElementById("reg-email").value;
    const first = document.getElementById("reg-firstname").value;
    const last = document.getElementById("reg-lastname").value;
    const phone = document.getElementById("reg-phone").value;
    const pass = document.getElementById("reg-password").value;
    const confirmPass = document.getElementById("reg-confirm-password").value;

    if (!email || !pass || !first || !last || !phone || !confirmPass) {
        showToast("error", "Harap isi semua field yang diperlukan");
        return;
    }

    if (pass !== confirmPass) {
        showToast("error", "Password dan konfirmasi password tidak cocok");
        return;
    }

    try {
        const result = await registerUserWithAPI({
            email: email,
            password: pass,
            first_name: first,
            last_name: last,
            phone: phone,
        });

        showToast("success", result.message || "Registrasi berhasil");
        openModal("login");

    } catch (error) {
        showToast("error", error.message);
    }
}

function logout() {
    axios
        .post("/logout")
        .then(() => {
            window.location.href = "/dashboard";
            showToast("success", "Berhasil logout");
        })
        .catch((error) => {
            console.error("Logout Gagal = ", error);
            showToast("error", "Gagal logout, Silahkan Refresh Halaman");
        });
}

// ====== API: KONSERS ======
async function loadKonsersFromAPI() {
    try {
        if (_concertsCache) return _concertsCache;
        const data = await apiFetch("/api/konsers");
        _concertsCache = data.map((k) => ({
            id: k.id,
            title: k.title,
            artist: k.artist ? k.artist.name || "Unknown Artist" : "Unknown Artist",
            genre: k.genre || "Pop",
            city: k.city,
            venue: k.venue,
            date: new Date(k.date).toLocaleDateString("id-ID", {
                year: "numeric",
                month: "long",
                day: "numeric",
            }),
            time: k.time ? k.time.substring(0, 5) + " WIB" : "19.00 WIB",
            image: k.image,
            trailer: k.trailer || "tidak ada vid",
            price: parseInt(k.price) || 0,
            type: k.type || "lokal",
            status: k.status === "published"
                ? "on-sale"
                : k.status === "sold_out"
                    ? "sold-out"
                    : k.status || "on-sale",
            desc: k.description || "",
            bg: k.type === "internasional" ? "internasional" : "indonesia",
        }));
        return _concertsCache;
    } catch (error) {
        console.error("Error loading konsers:", error);
        // Check if error is due to authentication
        const errorMsg = error.message || "";
        if (errorMsg.includes("401") || errorMsg.includes("Unauthorized")) {
            showToast("warning", "Anda harus login terlebih dahulu untuk mengakses data konser");
        } else {
            showToast("error", "Gagal memuat data konser");
        }
        return [];
    }
}

async function getKonserById(id) {
    const list = await loadKonsersFromAPI();
    return list.find((c) => c.id == id) || null;
}

async function saveKonserToAPI(isEdit = false, konserID = null) {
    const dateInput = document.getElementById("cf-date").value;
    const timeInput = document.getElementById("cf-time").value;
    const statusVal =
        document.querySelector('input[name="cf-status"]:checked')?.value ||
        "on-sale";

    const payload = {
        title: document.getElementById("cf-title").value,
        artist: document.getElementById("cf-artist").value,
        genre: document.getElementById("cf-genre").value,
        date: dateInput + " " + timeInput,
        time: timeInput,
        venue: document.getElementById("cf-venue").value,
        city: document.getElementById("cf-city").value,
        description: document.getElementById("cf-desc").value,
        price: document.getElementById("cf-price").value || 0,
        status: statusVal === "on-sale" ? "published" : statusVal,
        type: "lokal",
        capacity: 1000,
    };

    const method = isEdit ? "PUT" : "POST";
    const url = isEdit ? `/api/konsers/${konserID}` : "/api/konsers";
    const result = await apiFetch(url, {
        method,
        body: JSON.stringify(payload),
    });
    _concertsCache = null; // invalidate cache
    return result;
}

async function deleteArtistsFromAPI(konserID) {
    const result = await apiFetch(`/api/artists/${konserID}`, {
        method: "DELETE",
    });
    _artistsCache = null;
    return result;
}
async function deleteTicketFromAPI(konserID) {
    const result = await apiFetch(`/api/tickets/${konserID}`, {
        method: "DELETE",
    });
    _ticketCategoriesCache = null;
    return result;
}
async function deleteUsersFromAPI(konserID) {
    const result = await apiFetch(`/api/users/${konserID}`, {
        method: "DELETE",
    });
    _usersCache = null;
    return result;
}
async function deleteMediaFromAPI(konserID) {
    const result = await apiFetch(`/api/media/${konserID}`, {
        method: "DELETE",
    });
    _mediaCache = null;
    return result;
}
async function deleteKonserFromAPI(konserID) {
    const result = await apiFetch(`/api/konsers/${konserID}`, {
        method: "DELETE",
    });
    _concertsCache = null;
    return result;
}

// ====== CRUD FUNCTIONS: ARTISTS ======
async function saveArtistToAPI(isEdit = false, artistID = null) {
    const payload = {
        name: document.getElementById("artist-name")?.value || "",
        genre: document.getElementById("artist-genre")?.value || "Pop",
        origin: document.querySelector('input[name="artist-origin"]:checked')?.value || "indonesia",
        bio: document.getElementById("artist-bio")?.value || "",
        instagram: document.getElementById("artist-instagram")?.value || "",
    };

    const method = isEdit ? "PUT" : "POST";
    const url = isEdit ? `/api/artists/${artistID}` : "/api/artists";
    const result = await apiFetch(url, {
        method,
        body: JSON.stringify(payload),
    });
    _artistsCache = null;
    return result;
}

async function deleteArtistFromAPI(artistID) {
    const result = await apiFetch(`/api/artists/${artistID}`, {
        method: "DELETE",
    });
    _artistsCache = null;
    return result;
}

// ====== CRUD FUNCTIONS: TICKETS ======
async function saveTicketToAPI(isEdit = false, ticketID = null) {
    const payload = {
        konser_id: document.getElementById("cf-ticket-concert")?.value || "",
        name: document.getElementById("ticket-name")?.value || "",
        price: document.getElementById("ticket-price")?.value || 0,
        stock: document.getElementById("ticket-stock")?.value || 0,
        description: document.getElementById("ticket-desc")?.value || "",
    };

    const method = isEdit ? "PUT" : "POST";
    const url = isEdit ? `/api/tickets/${ticketID}` : "/api/tickets";
    const result = await apiFetch(url, {
        method,
        body: JSON.stringify(payload),
    });
    _ticketCategoriesCache = null;
    return result;
}

async function deleteTicketFromAPI(ticketID) {
    const result = await apiFetch(`/api/tickets/${ticketID}`, {
        method: "DELETE",
    });
    _ticketCategoriesCache = null;
    return result;
}

// ====== CRUD FUNCTIONS: USERS ======
async function deleteUserFromAPI(userID) {
    const result = await apiFetch(`/api/users/${userID}`, {
        method: "DELETE",
    });
    return result;
}

// ====== API: ARTISTS ======
async function loadArtistsFromAPI() {
    try {
        if (_artistsCache) return _artistsCache;
        const data = await apiFetch("/api/artists");
        _artistsCache = data.map((a) => ({
            id: a.id,
            name: a.name,
            genre: a.genre || "Pop",
            image: a.image,
            concerts: a.concerts_count || 0,
            country: a.country || "indonesia",
            bio: a.bio || "",
            instagram: a.instagram || "",
        }));
        return _artistsCache;
    } catch (error) {
        console.error("Error loading artists:", error);
        return [];
    }
}

// ====== API: TICKET CATEGORIES ======
async function loadTicketCategoriesFromAPI(konserID) {
    try {
        const data = await apiFetch(`/api/konsers/${konserID}/tickets`);
        return data.map((t) => ({
            id: t.id,
            name: t.name,
            price: parseInt(t.price) || 0,
            stock: t.stock || 0,
            desc: t.description || "",
        }));
    } catch (error) {
        console.error("Error loading ticket categories:", error);
        return [
            {
                id: 1,
                name: "FESTIVAL",
                price: 0,
                stock: 0,
                desc: "Area Festival",
            },
        ];
    }
}

// ====== API: USERS ======
async function loadUsersFromAPI() {
    try {
        const data = await apiFetch("/api/users");
        return data.map((u) => ({
            id: u.id,
            name: u.name,
            email: u.email,
            role: u.role || "user",
            status: u.status || "active",
            joined: new Date(u.created_at).toLocaleDateString("id-ID", {
                month: "short",
                year: "numeric",
            }),
        }));
    } catch (error) {
        console.error("Error loading users:", error);
        return [];
    }
}

// ====== API: Media  ======
async function loadMediaFromAPI() {
    try {
        const data = await apiFetch("/api/media");
        return data.map((m) => ({
            id: m.id,
            name: m.name,
            image: m.image,
            location: m.location || null,
        }));
    } catch (error) {
        console.error("Error loading media:", error);
        return [];
    }
}

// ====== API: TRANSACTIONS ======
async function loadTransactionsFromAPI() {
    try {
        const data = await apiFetch("/api/transactions");
        // Handle both array and object response
        const transactions = Array.isArray(data) ? data : (data.data || []);

        return transactions.map((t) => ({
            id: t.id,
            user: (t.first_name || '') + ' ' + (t.last_name || ''),
            concert: t.ticket?.konser?.title || t.ticket?.konser?.artist || 'Unknown',
            qty: t.quantity || 1,
            total: parseInt(t.total_price) || 0,
            status: t.payment_status || 'pending',
            date: new Date(t.created_at || t.date).toLocaleDateString("id-ID", {
                day: "2-digit",
                month: "short",
                year: "numeric",
            }),
        }));
    } catch (error) {
        console.error("Error loading transactions:", error);
        return [];
    }
}

/**
 * Show transaction details modal
 */


/**
 * Approve/Update transaction status
 */
async function approveTransaction(txId) {
    try {
        if (!confirm('Apakah Anda yakin ingin mengubah status transaksi menjadi CONFIRMED?')) {
            return;
        }

        const result = await apiFetch(`/api/transactions/${txId}`, {
            method: 'PUT',
            body: JSON.stringify({ payment_status: 'confirmed' })
        });

        showToast('success', '✓ Status transaksi berhasil diperbarui menjadi CONFIRMED');

        // Reload transactions table
        setTimeout(() => {
            const tx = document.getElementById("recent-tx-table");
            if (tx) {
                tx.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:20px;color:var(--gray);"><i class="fas fa-spinner fa-spin"></i></td></tr>';
                loadTransactionsFromAPI().then((transactions) => {
                    tx.innerHTML = renderTransactionsTable(transactions);
                    attachTransactionEventListeners();
                });
            }
        }, 500);
    } catch (error) {
        showToast('error', '✗ Gagal mengupdate status transaksi: ' + error.message);
        console.error('Error in approveTransaction:', error);
    }
}

/**
 * Attach event listeners to transaction buttons
 */
function attachTransactionEventListeners() {
    // Attach view button listeners (API-loaded transactions)
    const viewButtons = document.querySelectorAll('[data-action="view-transaction"]');
    viewButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const txId = this.getAttribute('data-tx-id');
            console.log('View transaction (API):', txId);
            showTransactionDetail(txId);
        });
    });

    // Attach approve button listeners (API-loaded transactions)
    const approveButtons = document.querySelectorAll('[data-action="approve-transaction"]');
    approveButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const txId = this.getAttribute('data-tx-id');
            console.log('Approve transaction (API):', txId);
            approveTransaction(txId);
        });
    });

    // Attach event listeners to Blade-rendered transaction table buttons
    const adminTxTable = document.getElementById('admin-transactions-table');
    if (adminTxTable) {
        const rows = adminTxTable.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const txId = row.querySelector('td code') ? row.querySelector('td code').textContent : null;
            const viewBtn = row.querySelector('.btn-view');
            const editBtn = row.querySelector('.btn-edit');

            if (viewBtn && txId) {
                viewBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('View transaction (Blade):', txId);
                });
            }

            if (editBtn && txId) {
                editBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Approve transaction (Blade):', txId);
                });
            }
        });
    }
}

/**
 * Render transactions table HTML
 */
function renderTransactionsTable(transactions) {
    return `<thead><tr><th>ID</th><th>User</th><th>Konser</th><th>Total</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>${transactions
        .slice(0, 5)
        .map((t) => `
    <tr>
        <td><code style="color:var(--red);font-size:12px;">${t.id}</code></td>
        <td>${t.user}</td>
        <td style="color:var(--gray);font-size:13px;">${t.concert}</td>
        <td><strong>Rp ${t.total.toLocaleString("id-ID")}</strong></td>
        <td><span class="status-badge ${t.status.toLowerCase() === 'confirmed' ? 'status-confirmed' : 'status-pending'}">${t.status.toUpperCase()}</span></td>
        <td>
            <div class="td-actions">
                <button class="btn-view" data-action="view-transaction" data-tx-id="${t.id}" title="Lihat Detail" style="cursor: pointer; border: none; background: #3b82f6; color: white; padding: 5px 8px; border-radius: 4px;">
                    <i class="fas fa-eye"></i>
                </button>
                ${t.status === 'pending' ? `<button class="btn-approve" data-action="approve-transaction" data-tx-id="${t.id}" title="Approve" style="cursor: pointer; border: none; background: #22c55e; color: white; padding: 5px 8px; border-radius: 4px; margin-left: 5px;">
                    <i class="fas fa-check"></i>
                </button>` : ''}`,
        )
    }</tbody>`;
}
// ====== API: AUTH ======
async function loginUserWithAPI(email, password) {
    return apiFetch("/api/login", {
        method: "POST",
        body: JSON.stringify({ email, password }),
    });
}

async function registerUserWithAPI(payload) {
    return apiFetch("/api/register", {
        method: "POST",
        body: JSON.stringify(payload),
    });
}

// ====== NAVIGATION ======
function navigate(page) {
    document
        .querySelectorAll(".page")
        .forEach((p) => p.classList.remove("active"));
    document
        .querySelectorAll(".nav-link")
        .forEach((l) => l.classList.remove("active"));
    const el = document.getElementById("page-" + page);
    if (el) el.classList.add("active");
    const nl = document.getElementById("nl-" + page);
    if (nl) nl.classList.add("active");
    currentPage = page;
    window.scrollTo(0, 0);
    if (page === "home") renderHomeConcerts();
    if (page === "concerts") renderConcertsPage();
    if (page === "artists") renderArtistsPage();
    if (page === "gallery") renderGallery();
    if (page === "admin") initAdmin();
}

// ====== RENDER CONCERTS ======
function concertCardHTML(c, i) {
    const isLoggedIn = document.body.getAttribute("data-user-logged-in") === "true";
    const badge =
        c.status === "sold-out"
            ? '<span class="concert-card-badge sold-out">SOLD OUT</span>'
            : '<span class="concert-card-badge">ON SALE</span>';
    const buttonText = isLoggedIn
        ? (c.status === "sold-out" ? "HABIS" : "BELI")
        : "LOGIN";
    const buttonClass = isLoggedIn
        ? (c.status === "sold-out" ? "disabled" : "")
        : "";

    return `<div class="concert-card" onclick="openConcertDetail(${c.id})"
    data-genre="${(c.tittle || '').toLowerCase()}"
    data-artist="${(c.artist || '').toLowerCase()}"
    data-city="${(c.city || '').toLowerCase()}"
    data-type="${c.type || ''}"
    data-price="${c.price || 0}"
    data-aos style="animation-delay:${i * 0.08}s">
    <div class="concert-card-img">
      <img src="${resolveImageSrc(c.image, '/images/default-poster.png')}" alt="${c.artist}" loading="lazy">
      <div class="concert-card-overlay"></div>
      ${badge}
      <div class="concert-card-wishlist" onclick="event.stopPropagation();toggleWishlist(this)"><i class="fas fa-heart"></i></div>
      <div class="concert-card-genre">${c.genre.toUpperCase()}</div>
    </div>
    <div class="concert-card-body">
      <div class="concert-card-artist">${c.artist}</div>
      <div class="concert-card-title">${c.title}</div>
      <div class="concert-card-meta">
        <div class="concert-card-meta-item"><i class="fas fa-calendar-alt"></i>${c.date}</div>
        <div class="concert-card-meta-item"><i class="fas fa-map-marker-alt"></i>${c.venue}</div>
      </div>
      <div class="concert-card-footer">
        <div>
          <div class="concert-price-label">Mulai dari</div>
          <div class="concert-price">Rp <span>${(c.price / 1000).toFixed(0)}</span>.000</div>
        </div>
        <button class="btn-book ${buttonClass}">${buttonText}</button>
      </div>
    </div>
  </div>`;
}

function renderHomeConcerts() {
    const g = document.getElementById("home-concerts-grid");
    if (g) {
        g.innerHTML =
            '<div style="grid-column:1/-1;text-align:center;padding:40px;color:var(--gray);"><i class="fas fa-spinner fa-spin" style="font-size:24px;"></i></div>';
        loadKonsersFromAPI().then((data) => {
            g.innerHTML = data
                .slice(0, 4)
                .map((c, i) => concertCardHTML(c, i))
                .join("");
            renderHeroFeatured(data);
            renderVideoSection(data);
            initAOS();
        });
    }

    const a = document.getElementById("home-artists");
    if (a) {
        loadArtistsFromAPI().then((artists) => {
            a.innerHTML = artists
                .slice(0, 8)
                .map(
                    (ar) => `
        <div class="artist-card" onclick="navigate('artists')">
          <img class="artist-card-img" src="${resolveImageSrc(ar.image, '/images/default-poster.png')}" alt="${ar.name}">
          <div class="artist-card-name">${ar.name}</div>
          <div class="artist-card-genre">${ar.genre}</div>
        </div>`,
                )
                .join("");
        });
    }
}

function renderHeroFeatured(concerts) {
    const node = document.getElementById("hero-featured");
    if (!node) return;
    const featured = concerts.slice(0, 3);
    node.innerHTML = featured
        .map(
            (c) => `
    <div class="hero-card-mini" onclick="openConcertDetail(${c.id})">
      <img src="${resolveImageSrc(c.image, '/images/default-poster.png')}" alt="${c.artist}">
      <div class="hero-card-mini-info">
        <div class="hero-card-mini-artist">${c.artist}</div>
        <div class="hero-card-mini-date"><i class="fas fa-calendar-alt" style="color:var(--red);margin-right:4px;"></i>${c.date} · ${c.city}</div>
        <div class="hero-card-mini-price">Mulai Rp ${Math.round(c.price / 1000)}.000</div>
      </div>
    </div>`,
        )
        .join("");
}

function renderVideoSection(concerts) {
    const node = document.getElementById("video-grid");
    if (!node) return;
    const videos = concerts.slice(0, 3);
    node.innerHTML = videos
        .map(
            (c) => `
    <div class="video-item" onclick="openConcertDetail(${c.id})">
      <img src="${resolveImageSrc(c.image, '/images/default-poster.png')}" alt="${c.artist} - ${c.title}">
      <div class="video-play-btn"><i class="fas fa-play"></i></div>
      <div class="video-item-info">
        <div class="video-item-title">${c.artist} — ${c.title}</div>
        <div class="video-item-dur"><i class="fas fa-map-marker-alt" style="margin-right:4px;"></i>${c.city} · ${c.date}</div>
      </div>
    </div>`,
        )
        .join("");
}

function renderConcertsPage() {
    const g = document.getElementById("concerts-page-grid");
    if (!g) return;
    g.innerHTML =
        '<div style="grid-column:1/-1;text-align:center;padding:60px;color:var(--gray);"><i class="fas fa-spinner fa-spin" style="font-size:24px;"></i></div>';
    loadKonsersFromAPI().then((data) => {
        g.innerHTML = data.map((c, i) => concertCardHTML(c, i)).join("");
        initAOS();
    });
}

// Current filtered list (async-aware)
let _currentFilteredConcerts = [];

function filterConcerts(typeFilter) {
    const search =
        (document.getElementById("concert-search") || {}).value || "";
    const genre = (document.getElementById("filter-genre") || {}).value || "";
    const city = (document.getElementById("filter-city") || {}).value || "";
    const sort = (document.getElementById("filter-sort") || {}).value || "date";
    const g = document.getElementById("concerts-page-grid");

    loadKonsersFromAPI().then((allConcerts) => {
        let filtered = allConcerts.filter((c) => {
            const matchSearch =
                !search ||
                c.artist.toLowerCase().includes(search.toUpperCase()) ||
                c.title.toLowerCase().includes(search.toLowerCase());
            const matchGenre =
                !genre || c.genre.toLowerCase().includes(genre.toLowerCase());
            const matchCity = !city || c.city === city;
            const matchType =
                !typeFilter || typeFilter === "all" || c.type === typeFilter;
            return matchSearch && matchGenre && matchCity && matchType;
        });
        if (sort === "price-asc") filtered.sort((a, b) => a.price - b.price);
        if (sort === "price-desc") filtered.sort((a, b) => b.price - a.price);
        if (g) {
            if (!filtered.length) {
                g.innerHTML =
                    '<div style="padding:60px;text-align:center;color:var(--gray);grid-column:1/-1;"><i class="fas fa-search" style="font-size:36px;margin-bottom:16px;display:block;opacity:0.3;"></i>Tidak ada konser ditemukan</div>';
            } else {
                g.innerHTML = filtered
                    .map((c, i) => concertCardHTML(c, i))
                    .join("");
            }
        }
        initAOS();
    });
}

// ====== CONCERT DETAIL ======
function openConcertDetail(id) {
    const isLoggedIn = document.body.getAttribute("data-user-logged-in") === "true";

    // 1. Cek apakah user sudah login sebelum membuka detail konser
    if (!isLoggedIn) {
        showToast('info', '🔐 Anda harus login terlebih dahulu untuk melihat detail dan membeli tiket konser.');
        setTimeout(() => {
            openModal('login');
        }, 500);
        return;
    }

    // 2. Set state ID konser terpilih dan arahkan navigasi
    selectedConcertId = id;
    navigate("detail");
    document.getElementById("nl-concerts").classList.add("active");

    // 3. Ambil data konser berdasarkan ID dari API
    getKonserById(id).then((c) => {
        if (!c) {
            showToast("error", "Konser tidak ditemukan");
            return;
        }

        // Konten utama detail konser
        document.getElementById("detail-bg-img").src = resolveImageSrc(c.image, '/images/default-poster.png');
        document.getElementById("detail-badge").textContent = c.genre.toUpperCase();
        document.getElementById("detail-title").textContent = c.title;
        document.getElementById("detail-artist").querySelector("span").textContent = c.artist;
        document.getElementById("detail-date").textContent = c.date;
        document.getElementById("detail-venue").textContent = c.venue;
        document.getElementById("detail-time").textContent = c.time;
        document.getElementById("detail-description").textContent = c.desc;

        // Render komponen lineup musisi
        document.getElementById("detail-lineup").innerHTML = [c.artist]
            .map((name, i) => `
                <div class="lineup-item">
                    <div class="lineup-num">0${i + 1}</div>
                    <div class="lineup-info">
                        <h4>${name}</h4>
                        <p>${i === 0 ? "Headliner" : "Supporting Artist"}</p>
                    </div>
                    ${i === 0 ? '<span class="status-badge status-on-sale">HEADLINER</span>' : ""}
                </div>`
            )
            .join("");

        // Konten media trailer di halaman detail
        document.getElementById("detail-gallery-img").src = resolveImageSrc(c.image, '/images/default-poster.png');
        document.getElementById("detail-trailer").src = c.trailer || '';
        document.getElementById("video-source").src = c.trailer || '';
        document.getElementById("detail-trailer-title").textContent = c.artist + " — Official Trailer";

        // 4. Load kategori tiket dari API secara dinamis
        const cats = document.getElementById("ticket-categories");
        cats.innerHTML = '<div style="text-align:center;padding:20px;color:var(--gray);"><i class="fas fa-spinner fa-spin"></i></div>';

        loadTicketCategoriesFromAPI(id).then((ticketCategories) => {
            if (!ticketCategories.length) {
                cats.innerHTML = '<div style="text-align:center;padding:20px;color:var(--gray);">Belum ada kategori tiket</div>';
                return;
            }

            cats.innerHTML = ticketCategories
                .map((t) => `
                    <div class="ticket-category" onclick="selectTicketCat(this,'${t.id}','${t.name}',${t.price})">
                        <div>
                            <div class="ticket-cat-name">${t.name}</div>
                            <div class="ticket-cat-stock" style="font-size:11px;color:var(--gray);">${t.stock} sisa</div>
                        </div>
                        <div class="ticket-cat-price">Rp ${(t.price / 1000).toFixed(0)}K</div>
                    </div>`
                )
                .join("");

            // Pilih kategori pertama secara default dan render peta kursi
            selectTicketCat(
                cats.firstElementChild,
                ticketCategories[0].id,
                ticketCategories[0].name,
                ticketCategories[0].price
            );
            renderSeatMap();
        });
    });
}

function selectTicketCat(el, id, name, price) {
    if (el) {
        document
            .querySelectorAll(".ticket-category")
            .forEach((t) => t.classList.remove("selected"));
        el.classList.add("selected");
    }
    if (id != null) selectedTicketId = id;
    if (name != null) selectedCategory = name;
    if (price != null) selectedCategoryPrice = price;
    updateBookingSummary();
}

// ====== SEAT MAP ======
function renderSeatMap() {
    // Placeholder untuk render seat map - dapat diimplementasi lebih lanjut
    console.log("Seat map rendered");
}

function changeQty(delta) {
    selectedQty = Math.max(1, Math.min(10, selectedQty + delta));
    const qtyDisplay = document.getElementById("qty-display");
    if (qtyDisplay) qtyDisplay.textContent = selectedQty;
    updateBookingSummary();
}

function updateBookingSummary() {
    const unit = Number(selectedCategoryPrice) || 0;
    const total = unit * selectedQty + 10000;
    const priceEl = document.getElementById("s-price");
    const qtyEl = document.getElementById("s-qty");
    const totalEl = document.getElementById("s-total");

    if (priceEl) priceEl.textContent = "Rp " + unit.toLocaleString("id-ID");
    if (qtyEl) qtyEl.textContent = "x" + selectedQty;
    if (totalEl) totalEl.textContent = "Rp " + total.toLocaleString("id-ID");
}

function getCheckoutDataFromURL() {
    const params = new URLSearchParams(window.location.search);

    const qty = parseInt(params.get("qty")) || 1;

    const pathParts = window.location.pathname.split("/");

    const ticketId = pathParts[2];

    return {
        ticketId,
        qty,
    };
}

function goCheckout() {
    const isLoggedIn =
        document.body.getAttribute("data-user-logged-in") === "true";

    // Cek apakah user sudah login
    if (!isLoggedIn) {
        showToast('info', '🔐 Anda harus login terlebih dahulu untuk membeli tiket. Silakan login atau buat akun baru.');
        setTimeout(() => {
            openModal('login');
        }, 500);
        return;
    }

    if(!selectedTicketId) {
        showToast('warning', '⚠️ Silakan pilih kategori tiket terlebih dahulu sebelum melanjutkan ke checkout.');
        return;
    }

    getKonserById(selectedConcertId).then((c) => {
        if (!c) return;

        const checkoutImg = document.getElementById("checkout-img");
        const checkoutTitle = document.getElementById("checkout-title");
        const checkoutMeta = document.getElementById("checkout-meta");
        const checkoutCat = document.getElementById("checkout-cat");
        const coUnit = document.getElementById("co-unit");
        const coQty = document.getElementById("co-qty");
        const coSub = document.getElementById("co-sub");
        const coTotal = document.getElementById("co-total");

        const unit = Number(selectedCategoryPrice) || 0;
        const sub = unit * selectedQty;
        const total = sub + 10000;

        if (checkoutImg) checkoutImg.src = resolveImageSrc(c.image, '/images/default-poster.png');
        if (checkoutTitle)
            checkoutTitle.textContent = c.artist + " — " + c.title;
        if (checkoutMeta) checkoutMeta.textContent = c.date + " · " + c.venue;
        if (checkoutCat)
            checkoutCat.textContent = selectedCategory
                ? selectedCategory + " × " + selectedQty
                : "";
        if (coUnit) coUnit.textContent = "Rp " + unit.toLocaleString("id-ID");
        if (coQty) coQty.textContent = selectedQty;
        if (coSub) coSub.textContent = "Rp " + sub.toLocaleString("id-ID");
        if (coTotal)
            coTotal.textContent = "Rp " + total.toLocaleString("id-ID");

        const checkoutUrl = `/checkout?ticketId=${selectedTicketId || ""}&qty=${selectedQty}`;
        if (window.location.pathname !== "/checkout") {
            window.location.href = checkoutUrl;
        } else {
            window.history.pushState({}, "", checkoutUrl);
        }
    });
}

// ====== ARTISTS PAGE ======
function renderArtistsPage() {
    filterArtists();
}

function filterArtists() {
    const search = (document.getElementById("artist-search") || {}).value || "";
    const g = document.getElementById("artists-grid");
    if (!g) return;
    loadArtistsFromAPI().then((artists) => {
        const filtered = artists.filter(
            (a) =>
                !search || a.name.toLowerCase().includes(search.toLowerCase()),
        );
                g.innerHTML = filtered
                        .map(
                                (a) => `
            <div class="artist-full-card">
                <img class="artist-full-img" src="${resolveImageSrc(a.image, '/images/default-poster.png')}" alt="${a.name}" loading="lazy">
                <div class="artist-full-name">${a.name}</div>
                <div class="artist-full-genre">${a.genre}</div>
                <div class="artist-full-concerts"><i class="fas fa-music" style="margin-right:4px;"></i>${a.concerts} konser</div>
                <button class="btn-book" style="margin-top:12px;" onclick="event.stopPropagation();navigate('concerts')">Lihat Konser</button>
            </div>`,
                        )
                        .join("");
    });
}

function setArtistFilter(val, btn) {
    document.querySelectorAll(".filter-btn").forEach((b) => {
        if (b.onclick && b.onclick.toString().includes("setArtistFilter"))
            b.classList.remove("active");
    });
    btn.classList.add("active");
    const g = document.getElementById("artists-grid");
    if (!g) return;
    loadArtistsFromAPI().then((artists) => {
        const filtered =
            val === "all" ? artists : artists.filter((a) => a.country === val);
                g.innerHTML = filtered
                        .map(
                                (a) => `
            <div class="artist-full-card">
                <img class="artist-full-img" src="${resolveImageSrc(a.image, '/images/default-poster.png')}" alt="${a.name}" loading="lazy">
                <div class="artist-full-name">${a.name}</div>
                <div class="artist-full-genre">${a.genre}</div>
                <div class="artist-full-concerts"><i class="fas fa-music" style="margin-right:4px;"></i>${a.concerts} konser</div>
                <button class="btn-book" style="margin-top:12px;" onclick="event.stopPropagation();navigate('concerts')">Lihat Konser</button>
            </div>`,
                        )
                        .join("");
    });
}

// ====== GALLERY PAGE ======
async function renderGallery() {
    const g = document.getElementById("gallery-grid");
    if (!g) return;

    // 1. Tampilkan loading spinner dulu di awal
    g.innerHTML = `
        <div style="grid-column:1/-1;text-align:center;padding:60px;color:var(--gray);">
            <i class="fas fa-spinner fa-spin" style="font-size:24px;"></i> Memuat data...
        </div>`;

    try {
        // 2. Tunggu sampai data konser berhasil diambil dari API
        const konsers = await loadKonsersFromAPI();

        // 3. Map langsung dari array konsers (tidak perlu di-map dua kali)
        g.innerHTML = konsers
            .map(
                (k) => `
        <div data-id="${k.id}" style="break-inside:avoid;margin-bottom:16px;border-radius:var(--radius);overflow:hidden;cursor:pointer;transition:var(--transition);"
             onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
          <img src="${resolveImageSrc(k.image, '/images/default-poster.png')}" style="width:100%;display:block;" loading="lazy">
        </div>`
            )
            .join("");

    } catch (error) {
        console.error("Gagal memuat galeri:", error);
        // 4. Jika error, tampilkan pesan gagal, bukan spinner loading
        g.innerHTML = `
            <div style="grid-column:1/-1;text-align:center;padding:60px;color:red;">
                Gagal memuat data konser. Silakan coba lagi nanti.
            </div>`;
    }
}

// ====== ADMIN ======
function initAdmin() {
    document.getElementById("admin-date").textContent =
        new Date().toLocaleDateString("id-ID", {
            weekday: "long",
            year: "numeric",
            month: "long",
            day: "numeric",
        });
    switchAdmin("admin");
}

function switchAdmin(section) {
    [
        "admin",
        "concerts",
        "artists",
        "tickets",
        "transactions",
        "users",
        "media",
    ].forEach((s) => {
        const el = document.getElementById("admin-section-" + s);
        const nav = document.getElementById("adm-" + s);
        if (el) el.style.display = s === section ? "block" : "none";
        if (nav) nav.classList.toggle("active", s === section);
    });
    if (section === "admin") buildAdminDashboard();
    if (section === "concerts") buildConcertsTable();
    if (section === "artists") buildArtistsTable();
    if (section === "users") buildUsersTable();
    if (section === "media") buildMediaTable();
}

function buildAdminDashboard() {
    const chart = document.getElementById("admin-chart");
    if (chart) {
        // Chart bar dari API statistik (jika ada), atau data sederhana
        apiFetch("/api/dashboard/stats")
            .then((stats) => {
                const data = stats.weekly_sales || [2, 1, 4, 1, 3, 1, 2];
                const days = ["Sen", "Sel", "Rab", "Kam", "Jum", "Sab", "Min"];
                const max = Math.max(...data) || 1;
                chart.innerHTML = data
                    .map(
                        (v, i) => `
        <div class="chart-bar-wrap">
          <div class="chart-bar" style="height:${((v / max) * 140).toFixed(0)}px;" title="${v} tiket"></div>
          <div class="chart-bar-label">${days[i]}</div>
        </div>`,
                    )
                    .join("");
            })
            .catch(() => {
                // Fallback chart kosong
                const days = ["Sen", "Sel", "Rab", "Kam", "Jum", "Sab", "Min"];
                chart.innerHTML = days
                    .map(
                        (d) => `
        <div class="chart-bar-wrap">
          <div class="chart-bar" style="height:0px;"></div>
          <div class="chart-bar-label">${d}</div>
        </div>`,
                    )
                    .join("");
            });
    }

    const tx = document.getElementById("recent-tx-table");
    if (tx) {
        tx.innerHTML =
            '<tr><td colspan="6" style="text-align:center;padding:20px;color:var(--gray);"><i class="fas fa-spinner fa-spin"></i></td></tr>';
        loadTransactionsFromAPI().then((transactions) => {
            tx.innerHTML = renderTransactionsTable(transactions);
            // Attach event listeners to buttons
            attachTransactionEventListeners();
        }).catch(error => {
            console.error('Error loading transactions:', error);
            tx.innerHTML = '<tr><td colspan="6" style="text-align:center;color:red;">Gagal memuat transaksi</td></tr>';
        });
    }

    // Load Recent Concerts for Dashboard
    const dashboardConcerts = document.getElementById("dashboard-concerts-table");
    if (dashboardConcerts) {
        dashboardConcerts.innerHTML = '<tr><td colspan="8" style="text-align:center;padding:20px;color:var(--gray);"><i class="fas fa-spinner fa-spin"></i> Memuat data...</td></tr>';
        loadKonsersFromAPI().then((konsersData) => {
            dashboardConcerts.innerHTML = `<thead><tr><th>#</th><th>Poster</th><th>Konser</th><th>Artis</th><th>Tanggal</th><th>Kota</th><th>Harga</th><th>Status</th></tr></thead>
        <tbody>${konsersData
                    .slice(0, 5)
                    .map(
                        (c, i) => `
        <tr>
            <td>${i + 1}</td>
            <td><img class="concert-thumb" src="${resolveImageSrc(c.image, '/images/default-poster.png')}" alt="" style="width:40px;height:40px;object-fit:cover;"></td>
            <td><div class="td-name">${c.title}</div></td>
            <td class="td-artist">${c.artist}</td>
            <td style="font-size:13px;">${c.date}</td>
            <td>${c.city}</td>
            <td>Rp ${(c.price / 1000).toFixed(0)}K</td>
            <td><span class="status-badge ${c.status === "on-sale" ? "status-on-sale" : "status-sold-out"}">${c.status === "on-sale" ? "ON SALE" : "SOLD OUT"}</span></td>
        </tr>`,
                    )
                    .join("")}</tbody>`;
        }).catch(() => {
            dashboardConcerts.innerHTML = '<tr><td colspan="8" style="text-align:center;color:var(--gray);">Gagal memuat data konser</td></tr>';
        });
    }

    // Load Popular Artists for Dashboard
    const dashboardArtists = document.getElementById("dashboard-artists-table");
    if (dashboardArtists) {
        dashboardArtists.innerHTML = '<tr><td colspan="4" style="text-align:center;padding:20px;color:var(--gray);"><i class="fas fa-spinner fa-spin"></i> Memuat data...</td></tr>';
        loadArtistsFromAPI().then((artistsData) => {
            dashboardArtists.innerHTML = `<thead><tr><th>#</th><th>Foto</th><th>Nama</th><th>Genre</th></tr></thead>
        <tbody>${artistsData
                    .slice(0, 5)
                    .map(
                        (a, i) => `
        <tr>
            <td>${i + 1}</td>
            <td><img class="concert-thumb" src="${resolveImageSrc(a.image, '/images/default-poster.png')}" alt="" style="border-radius:50%;width:40px;height:40px;object-fit:cover;"></td>
            <td><div class="td-name">${a.name}</div></td>
            <td>${a.genre}</td>
        </tr>`,
                    )
                    .join("")}</tbody>`;
        }).catch(() => {
            dashboardArtists.innerHTML = '<tr><td colspan="4" style="text-align:center;color:var(--gray);">Gagal memuat data artis</td></tr>';
        });
    }
}

function buildConcertsTable() {
    const t = document.getElementById("admin-concerts-tabled");
    if (!t) return;

    // Perbaikan struktur: Bungkus dengan <tbody> agar layout tabel tidak rusak saat memuat data
    t.innerHTML = '<tbody><tr><td colspan="9" style="text-align:center;padding:30px;color:var(--gray);"><i class="fas fa-spinner fa-spin"></i> Memuat data...</td></tr></tbody>';

    loadKonsersFromAPI()
        .then((konsersData) => {
            const rows = konsersData.map((c, i) => {
                
                // --- PERBAIKAN DI SINI ---
                // ID Proyek Supabase dan Nama Bucket Anda
                const projectId = 'mhimeqexeizxveuckwyn';
                const bucketName = 'primestage';
                
                // Jika data c.img dari API sudah berisi path (misal: 'posters/konser1.png')
                // Kita gabungkan langsung menjadi Public URL Supabase. Jika kosong, pakai default.
                const imgSrc = c.image 
                    ? `https://${projectId}.storage.supabase.co/storage/v1/object/public/${bucketName}/konsers/${c.image}`
                    : '/images/default-poster.png';
                // -------------------------

                return `
                <tr>
                  <td>${i + 1}</td>
                  <td></td>
                  <td><div class="td-name">${c.title}</div></td>
                  <td class="td-artist">${c.artist}</td>
                  <td style="font-size:13px;">${c.date}</td>
                  <td>${c.city}</td>
                  <td>Rp ${(c.price / 1000).toFixed(0)}rb</td>
                  <td><span class="status-badge ${c.status === "on-sale" ? "status-on-sale" : "status-sold-out"}">${c.status === "on-sale" ? "ON SALE" : "SOLD OUT"}</span></td>
                  <td><div class="td-actions">
                    <a class="btn-edit" href="/admin/konsers/${c.id}/edit"><i class="fas fa-edit"></i></a>
                    <button class="btn-del" onclick="deleteConcert(${c.id},this)"><i class="fas fa-trash"></i></button>
                  </div></td>
                </tr>`;
            }).join("");

            // Pasang struktur tabel yang valid dan rapi
            t.innerHTML = `
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Poster</th>
                        <th>Konser</th>
                        <th>Artis</th>
                        <th>Tanggal</th>
                        <th>Kota</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>${rows}</tbody>
            `;
        })
        .catch((error) => {
            const errorMsg = error.message || "";
            const message = errorMsg.includes("401") || errorMsg.includes("Unauthorized")
                ? "Anda harus login terlebih dahulu untuk mengakses data konser"
                : "Gagal memuat data konser";

            // Perbaikan struktur: Bungkus error dengan <tbody>
            t.innerHTML = `<tbody><tr><td colspan="9" style="text-align:center;color:var(--gray);">${message}</td></tr></tbody>`;
        });
}

async function buildArtistsTable() {
    const t = document.getElementById("admin-artists-table");
    if (!t) return;

    // 1. Tampilkan loading spinner
    t.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:30px;color:var(--gray);"><i class="fas fa-spinner fa-spin"></i> Memuat data...</td></tr>';

    try {
        // 2. Ambil kedua data secara paralel menggunakan Promise.all
        const [artists, konsers] = await Promise.all([
            loadArtistsFromAPI(),
            loadKonsersFromAPI()
        ]);

        // 3. Render HTML Table, looping utama dari data 'artists'
        t.innerHTML = `
            <thead>
                <tr>
                    <th>#</th>
                    <th>Foto</th>
                    <th>Nama</th>
                    <th>Genre</th>
                    <th>Konser</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                ${artists.map((a, i) => {
                    // Cari jumlah konser yang punya artists_id cocok dengan id milik artis ini
                    const jumlahKonser = konsers.filter(k => k.artists_id === a.id).length;

                    console.log(`Artis: ${a.name}, Jumlah Konser: ${jumlahKonser}`); // Debug log

                    return `
                    <tr>
                        <td>${i + 1}</td>
                        <td>
                            <img class="concert-thumb" src="${resolveImageSrc(a.image, '/images/default-poster.png')}" alt="${a.name}" style="border-radius:50%;width:40px;height:40px;object-fit:cover;">
                        </td>
                        <td><div class="td-name">${a.name}</div></td>
                        <td>${a.genre}</td>
                        <td>${jumlahKonser}</td>
                        <td>
                            <div class="td-actions">
                                <a class="btn-edit" href="/admin/artists/${a.id}/edit"><i class="fas fa-edit"></i></a>
                                <button class="btn-del" onclick="deleteArtists(${a.id},this)"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>`;
                }).join("")}
            </tbody>`;

    } catch (error) {
        console.error("Error loading artists table:", error);
        // 4. Handle error jika salah satu API gagal
        t.innerHTML = '<tr><td colspan="6" style="text-align:center;color:red;padding:30px;">Gagal memuat data artis atau konser</td></tr>';
    }
}

function buildUsersTable() {
    const t = document.getElementById("admin-users-table");
    if (!t) return;
    t.innerHTML =
        '<tr><td colspan="6" style="text-align:center;padding:30px;color:var(--gray);"><i class="fas fa-spinner fa-spin"></i> Memuat data...</td></tr>';
    loadUsersFromAPI()
        .then((users) => {
            t.innerHTML = `<thead><tr><th>#</th><th>Nama</th><th>Email</th><th>Role</th><th>Status</th><th>Aksi</th></tr></thead>
      <tbody>${users
                    .map(
                        (u, i) => `
      <tr>
        <td>${i + 1}</td>
        <td><div class="td-name">${u.name}</div></td>
        <td style="font-size:13px;color:var(--gray);">${u.email}</td>
        <td><span class="status-badge ${u.role === "admin" ? "status-on-sale" : ""}">${u.role.toUpperCase()}</span></td>
        <td><span class="status-badge ${u.status === "active" ? "status-on-sale" : "status-sold-out"}">${u.status.toUpperCase()}</span></td>
        <td><div class="td-actions">
          <button class="btn-edit" onclick="showToast('info','Edit user ${u.name}')"><i class="fas fa-edit"></i></button>
          <button class="btn-del" onclick="deleteRow(this)"><i class="fas fa-trash"></i></button>
        </div></td>
      </tr>`,
                    )
                    .join("")}</tbody>`;
        })
        .catch(() => {
            t.innerHTML =
                '<tr><td colspan="6" style="text-align:center;color:var(--gray);">Gagal memuat data user</td></tr>';
        });
}

function buildMediaTable() {
    const t = document.getElementById("admin-media-table");
    if (!t) return;

    // FIX 1: Bungkus tr dengan tbody agar struktur HTML valid saat memuat data
    t.innerHTML = '<tbody><tr><td colspan="5" style="text-align:center;padding:30px;color:var(--gray);"><i class="fas fa-spinner fa-spin"></i> Memuat data...</td></tr></tbody>';

    loadMediaFromAPI()
    .then((media) => {
        console.log("Data media dari database:", media);

        const rows = media.map((m, index) => {
            
            // --- PERBAIKAN DI SINI ---
            // ID Proyek Supabase dan Nama Bucket Anda
            const projectId = 'mhimeqexeizxveuckwyn';
            const bucketName = 'primestage';
            
            // Jika data m.image dari API terisi, arahkan ke URL Publik Supabase. 
            // Jika kosong, gunakan gambar default local asset.
            const finalImgUrl = m.image 
                ? `https://${projectId}.storage.supabase.co/storage/v1/object/public/${bucketName}/media_images/${m.image}`
                : '/storage/img/artists.jpg';
            // -------------------------

            const gambarTag = `<img src="${finalImgUrl}" alt="${m.name}" style="width:100px;height:auto;object-fit:cover;border-radius:4px;">`;

            return `
            <tr>
                <td>${index + 1}</td>
                <td><div class="td-name">${m.name}</div></td>
                <td>${gambarTag}</td>
                <td style="font-size:13px;color:var(--gray);">${m.location || '-'}</td>
                <td>
                    <div class="td-actions">
                        <button class="btn-edit" onclick="showToast('info','Edit media ${m.name}')"><i class="fas fa-edit"></i></button>
                        <button class="btn-del" onclick="deleteMedia(${m.id}, this)"><i class="fas fa-trash"></i></button>
                    </div>
                </td>
            </tr>`;
        }).join("");

        // Terapkan struktur tabel final yang kokoh
        t.innerHTML = `
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Gambar</th>
                <th>Location</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            ${rows}
        </tbody>`;
    })
    .catch((error) => {
        console.error("Error media table:", error);
        // FIX 3: Pasang pembungkus tbody untuk penanganan error layout tabel
        t.innerHTML = '<tbody><tr><td colspan="5" style="text-align:center;color:red;padding:30px;">Gagal memuat data media</td></tr></tbody>';
    });
}

function deleteConcert(id, btn) {
    const row = btn.closest("tr");
    deleteKonserFromAPI(id)
        .then(() => {
            row.style.opacity = "0";
            row.style.transition = "opacity 0.5s";
            setTimeout(() => row.remove(), 3000);
            showToast("success", "Konser berhasil dihapus");
        })
        .catch((error) => {
            showToast("error", error.message || "Gagal menghapus konser");
        });
}
function deleteArtists(id, btn) {
    const row = btn.closest("tr");
    deleteArtistsFromAPI(id)
        .then(() => {
            row.style.opacity = "0";
            row.style.transition = "opacity 0.5s";
            setTimeout(() => row.remove(), 3000);
            showToast("success", "Artis berhasil dihapus");
        })
        .catch((error) => {
            showToast("error", error.message || "Gagal menghapus artis");
        });
}

function deleteUsers(id, btn) {
    const row = btn.closest("tr");
    deleteUsersFromAPI(id)
        .then(() => {
            row.style.opacity = "0";
            row.style.transition = "opacity 0.5s";
            setTimeout(() => row.remove(), 3000);
            showToast("success", "User berhasil dihapus");
        })
        .catch((error) => {
            showToast("error", error.message || "Gagal menghapus user");
        });
}
function deleteMedia(id, btn) {
    const row = btn.closest("tr");
    deleteMediaFromAPI(id)
        .then(() => {
            row.style.opacity = "0";
            row.style.transition = "opacity 0.5s";
            setTimeout(() => row.remove(), 3000);
            showToast("success", "Media berhasil dihapus");
        })
        .catch((error) => {
            showToast("error", error.message || "Gagal menghapus media");
        });
}

function deleteRow(btn) {
    const row = btn.closest("tr");
    row.style.opacity = "0";
    row.style.transition = "opacity 0.3s";
    setTimeout(() => row.remove(), 300);
    showToast("success", "Data berhasil dihapus");
}

function filterAdminTable(input, tableId) {
    const val = input.value.toLowerCase();
    const rows = document.querySelectorAll("#" + tableId + " tbody tr");
    rows.forEach((r) => {
        r.style.display = r.textContent.toLowerCase().includes(val)
            ? ""
            : "none";
    });
}

// ====== CRUD MODALS ======
function buildCrudForms(artists) {
    return {
        concert: `
      <div class="form-group"><label class="form-label">Nama Konser/Tour</label><input class="form-input" type="text" id="cf-title" placeholder="Music of the Spheres..."></div>
      <div class="form-row">
        <div class="form-group"><label class="form-label">Artis</label>
          <select class="form-input" id="cf-artist">${artists.map((a) => `<option>${a.name}</option>`).join("")}</select>
        </div>
        <div class="form-group"><label class="form-label">Genre</label>
          <select class="form-input" id="cf-genre"><option>Pop</option><option>Rock</option><option>R&B</option><option>Metal</option><option>Jazz</option><option>Indie</option></select>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group"><label class="form-label">Tanggal</label><input class="form-input" type="date" id="cf-date"></div>
        <div class="form-group"><label class="form-label">Waktu</label><input class="form-input" type="time" id="cf-time" value="19:00"></div>
      </div>
      <div class="form-group"><label class="form-label">Venue</label><input class="form-input" type="text" id="cf-venue" placeholder="GBK Stadium, Jakarta"></div>
      <div class="form-row">
        <div class="form-group"><label class="form-label">Kota</label>
          <select class="form-input" id="cf-city"><option>Jakarta</option><option>Bandung</option><option>Surabaya</option><option>Yogyakarta</option><option>Bali</option></select>
        </div>
        <div class="form-group"><label class="form-label">Harga Mulai (Rp)</label><input class="form-input" type="number" id="cf-price" placeholder="500000" min="0"></div>
      </div>
      <div class="form-group"><label class="form-label">Deskripsi</label><textarea class="form-input" id="cf-desc" rows="3" placeholder="Deskripsi konser..."></textarea></div>
      <div class="form-row">
        <div class="form-group"><label class="form-label">Poster Konser</label><input class="form-input" type="file" id="cf-poster" accept="image/*"></div>
        <div class="form-group"><label class="form-label">Video Trailer (URL)</label><input class="form-input" type="url" id="cf-video" placeholder="https://youtube.com/..."></div>
      </div>
      <div class="form-group"><label class="form-label">Status</label>
        <div style="display:flex;gap:16px;margin-top:8px;">
          <label style="display:flex;align-items:center;gap:8px;font-size:14px;cursor:pointer;"><input type="radio" name="cf-status" value="on-sale" checked style="accent-color:var(--red);"> On Sale</label>
          <label style="display:flex;align-items:center;gap:8px;font-size:14px;cursor:pointer;"><input type="radio" name="cf-status" value="sold-out" style="accent-color:var(--red);"> Sold Out</label>
          <label style="display:flex;align-items:center;gap:8px;font-size:14px;cursor:pointer;"><input type="radio" name="cf-status" value="draft" style="accent-color:var(--red);"> Draft</label>
        </div>
      </div>
      <button class="btn-submit" onclick="saveCrudForm('concert')">SIMPAN KONSER</button>`,

        artist: `
      <div class="form-group"><label class="form-label">Nama Artis/Band</label><input class="form-input" type="text" id="artist-name" placeholder="Nama artis..."></div>
      <div class="form-row">
        <div class="form-group"><label class="form-label">Genre</label><select class="form-input" id="artist-genre"><option>Pop</option><option>Rock</option><option>R&B</option><option>Metal</option><option>Indie</option></select></div>
        <div class="form-group"><label class="form-label">Asal</label>
          <div style="display:flex;gap:16px;margin-top:8px;">
            <label style="display:flex;align-items:center;gap:8px;font-size:14px;cursor:pointer;"><input type="radio" name="artist-origin" value="indonesia" checked style="accent-color:var(--red);"> Indonesia</label>
            <label style="display:flex;align-items:center;gap:8px;font-size:14px;cursor:pointer;"><input type="radio" name="artist-origin" value="internasional" style="accent-color:var(--red);"> Internasional</label>
          </div>
        </div>
      </div>
      <div class="form-group"><label class="form-label">Foto Artis</label><input class="form-input" type="file" id="artist-image" accept="image/*"></div>
      <div class="form-group"><label class="form-label">Bio Singkat</label><textarea class="form-input" id="artist-bio" rows="3" placeholder="Ceritakan tentang artis..."></textarea></div>
      <div class="form-group"><label class="form-label">Instagram</label><input class="form-input" type="text" id="artist-instagram" placeholder="@username"></div>
      <button class="btn-submit" onclick="saveCrudForm('artist')">SIMPAN ARTIS</button>`,

        ticket: `
      <div class="form-group"><label class="form-label">Konser</label>
        <select class="form-input" id="cf-ticket-concert">
          <option value="">-- Memuat konser... --</option>
        </select>
      </div>
      <div class="form-group"><label class="form-label">Nama Kategori</label><input class="form-input" type="text" id="ticket-name" placeholder="VVIP / VIP / Festival..."></div>
      <div class="form-row">
        <div class="form-group"><label class="form-label">Harga (Rp)</label><input class="form-input" type="number" id="ticket-price" placeholder="750000" min="0"></div>
        <div class="form-group"><label class="form-label">Stok</label><input class="form-input" type="number" id="ticket-stock" placeholder="500" min="1"></div>
      </div>
      <div class="form-group"><label class="form-label">Deskripsi Area</label><input class="form-input" type="text" id="ticket-desc" placeholder="Deskripsi area tiket..."></div>
      <div class="form-group"><label class="form-label">Harga Promo <span style="color:var(--gray);font-size:11px;">(opsional)</span></label>
        <div style="display:flex;gap:8px;align-items:center;">
          <input class="form-input" type="number" id="ticket-promo-price" placeholder="600000" style="flex:1;">
          <input class="form-input" type="date" id="ticket-promo-date" style="flex:1;" placeholder="Berlaku s/d">
        </div>
      </div>
      <div class="form-group"><label class="form-label">Batas Pembelian per User</label>
        <input class="form-input" type="range" id="ticket-limit" min="1" max="10" value="4" oninput="this.nextElementSibling.textContent='Max '+this.value+' tiket'" class="price-range" style="margin-top:8px;">
        <div style="font-size:13px;color:var(--gray);margin-top:4px;">Max 4 tiket</div>
      </div>
      <button class="btn-submit" onclick="saveCrudForm('ticket')">SIMPAN TIKET</button>`,

        user: `
      <div class="form-row">
        <div class="form-group"><label class="form-label">Nama Depan</label><input class="form-input" type="text" id="user-first-name" placeholder="John"></div>
        <div class="form-group"><label class="form-label">Nama Belakang</label><input class="form-input" type="text" id="user-last-name" placeholder="Doe"></div>
      </div>
      <div class="form-group"><label class="form-label">Email</label><input class="form-input" type="email" id="user-email" placeholder="user@example.com"></div>
      <div class="form-group"><label class="form-label">Nomor HP</label><input class="form-input" type="tel" id="user-phone" placeholder="+62 812 ..."></div>
      <div class="form-group"><label class="form-label">Password</label><input class="form-input" type="password" id="user-password" placeholder="••••••••"></div>
      <div class="form-group"><label class="form-label">Role</label>
        <div style="display:flex;gap:16px;margin-top:8px;">
          <label style="display:flex;align-items:center;gap:8px;font-size:14px;cursor:pointer;"><input type="radio" name="user-role" value="user" checked style="accent-color:var(--red);"> User</label>
          <label style="display:flex;align-items:center;gap:8px;font-size:14px;cursor:pointer;"><input type="radio" name="user-role" value="admin" style="accent-color:var(--red);"> Admin</label>
        </div>
      </div>
      <div class="form-group"><label class="form-label">Status</label>
        <select class="form-input" id="user-status"><option>Active</option><option>Inactive</option><option>Banned</option></select>
      </div>
      <button class="btn-submit" onclick="saveCrudForm('user')">SIMPAN USER</button>`,
    };
}

function openCrudModal(type, data, konserID = null) {
    const titles = {
        concert: "TAMBAH KONSER",
        artist: "TAMBAH ARTIS",
        user: "TAMBAH USER",
    };
    document.getElementById("crud-modal-title").textContent = data
        ? "EDIT " + type.toUpperCase()
        : titles[type];
    document.getElementById("crud-modal-body").innerHTML =
        '<div style="text-align:center;padding:30px;color:var(--gray);"><i class="fas fa-spinner fa-spin" style="font-size:20px;"></i></div>';
    document.getElementById("modal-crud").classList.add("show");

    loadArtistsFromAPI().then((artists) => {
        const forms = buildCrudForms(artists);
        document.getElementById("crud-modal-body").innerHTML =
            forms[type] || "";

        // Prefill form jika edit
        if (data && type === "concert") {
            setTimeout(() => {
                if (document.getElementById("cf-title"))
                    document.getElementById("cf-title").value =
                        data.title || "";
                if (document.getElementById("cf-artist"))
                    document.getElementById("cf-artist").value =
                        data.artist || "";
                if (document.getElementById("cf-genre"))
                    document.getElementById("cf-genre").value =
                        data.genre || "Pop";
                if (document.getElementById("cf-venue"))
                    document.getElementById("cf-venue").value =
                        data.venue || "";
                if (document.getElementById("cf-city"))
                    document.getElementById("cf-city").value =
                        data.city || "Jakarta";
                if (document.getElementById("cf-price"))
                    document.getElementById("cf-price").value =
                        data.price || "";
                if (document.getElementById("cf-desc"))
                    document.getElementById("cf-desc").value = data.desc || "";
                // Simpan ID konser yang sedang diedit
                document.getElementById("crud-modal-body").dataset.editId =
                    konserID || "";
            }, 50);
        }

        // Populate select konser untuk form tiket
        if (type === "ticket") {
            loadKonsersFromAPI().then((konsers) => {
                const sel = document.getElementById("cf-ticket-concert");
                if (sel)
                    sel.innerHTML = konsers
                        .map(
                            (c) =>
                                `<option value="${c.id}">${c.artist} — ${c.title}</option>`,
                        )
                        .join("");
            });
        }
    });
}

function saveCrudForm(type) {
    closeModal("crud");
    if (type === "concert") {
        const editId =
            document.getElementById("crud-modal-body")?.dataset.editId;
        const isEdit = !!editId;
        saveKonserToAPI(isEdit, editId || null)
            .then(() => {
                showToast("success", "✓ Konser berhasil disimpan!");
                setTimeout(() => buildConcertsTable(), 300);
            })
            .catch((error) => {
                showToast("error", "✗ Gagal menyimpan konser: " + (error.message || "Kesalahan tidak diketahui"));
            });
    } else if (type === "artist") {
        const editId =
            document.getElementById("crud-modal-body")?.dataset.editId;
        const isEdit = !!editId;
        saveArtistToAPI(isEdit, editId || null)
            .then(() => {
                showToast("success", "✓ Artis berhasil disimpan!");
                setTimeout(() => buildArtistsTable(), 300);
            })
            .catch((error) => {
                showToast("error", "✗ Gagal menyimpan artis: " + (error.message || "Kesalahan tidak diketahui"));
            });
    } else if (type === "ticket") {
        const editId =
            document.getElementById("crud-modal-body")?.dataset.editId;
        const isEdit = !!editId;
        saveTicketToAPI(isEdit, editId || null)
            .then(() => {
                showToast("success", "✓ Tiket berhasil disimpan!");
                setTimeout(() => buildTicketsTable(), 300);
            })
            .catch((error) => {
                showToast("error", "✗ Gagal menyimpan tiket: " + (error.message || "Kesalahan tidak diketahui"));
            });
    } else if (type === "user") {
        showToast("info", "ℹ Manajemen user tersedia di halaman manajemen pengguna");
    } else {
        showToast(
            "success",
            "✓ " + type.charAt(0).toUpperCase() +
            type.slice(1) +
            " berhasil disimpan!",
        );
    }
}


// ====== ADMIN NAVBAR SEARCH ======
function handleAdminSearch(event) {
    if (event.key === 'Enter') {
        const query = document.getElementById('navbar-search').value.trim();
        if (query) {
            // Get all concerts and filter by name
            loadKonsersFromAPI().then((konsers) => {
                const results = konsers.filter(k =>
                    k.title.toLowerCase().includes(query.toLowerCase()) ||
                    k.artist.toLowerCase().includes(query.toLowerCase()) ||
                    k.city.toLowerCase().includes(query.toLowerCase())
                );
                if (results.length > 0) {
                    showToast('success', `Ditemukan ${results.length} konser`);
                    // Clear search
                    document.getElementById('navbar-search').value = '';
                } else {
                    showToast('info', 'Konser tidak ditemukan');
                }
            }).catch(() => {
                showToast('error', 'Gagal mencari konser');
            });
        }
    }
}

// ====== CHECKOUT ======
function selectPayment(input) {
    document
        .querySelectorAll('[id^="pay-"]')
        .forEach((el) => el.classList.remove("selected"));
    document.getElementById("pay-" + input.value).classList.add("selected");
}

function submitOrder() {
    const tosCheckbox = document.getElementById("agree-tos");
    if (!tosCheckbox || !tosCheckbox.checked) {
        showToast("error", "✗ Harap setujui syarat & ketentuan");
        return;
    }

    const paymentMethod = document.querySelector(
        'input[name="metode_pembayaran"]:checked',
    )?.value;
    if (!paymentMethod) {
        showToast("error", "✗ Pilih metode pembayaran");
        return;
    }

    const checkoutForm = document.getElementById("checkout-form");
    if (!checkoutForm) {
        showToast("error", "✗ Form checkout tidak ditemukan");
        return;
    }

    // Disable button and show processing notification
    const submitBtn = event.target;
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

    showToast("info", "ℹ Memproses pembayaran Anda...");

    // Submit the form
    setTimeout(() => {
        checkoutForm.submit();
    }, 1000);
}

// Tambahkan juga fungsi close ini agar saat modal ditutup, videonya berhenti berputar
function closeVideoModal() {
    const videoPlayer = document.getElementById("mp4-player");
    const modalVideo = document.getElementById("modal-video");

    if (videoPlayer && modalVideo) {
        videoPlayer.pause();
        modalVideo.classList.remove("show");
    }
}

function closeModal(type) {
    if (type === "video") {
        const modalVideo = document.getElementById("modal-video");
        const iframe = document.getElementById("yt-iframe");

        if (modalVideo) {
            modalVideo.classList.remove("show");
        }
        // WAJIB: Hapus src iframe saat modal ditutup agar video berhenti berputar di background
        if (iframe) {
            iframe.src = "";
        }
    }
    // ... baris kode closeModal untuk type lain (auth, crud, gallery) tetap biarkan seperti semula
}
// ====== WISHLIST ======
function toggleWishlist(el) {
    const icon = el.querySelector("i");
    icon.classList.toggle("far");
    icon.classList.toggle("fas");
    if (icon.classList.contains("fas")) {
        el.style.background = "var(--red)";
        el.style.color = "white";
        showToast("success", "Ditambahkan ke wishlist!");
    } else {
        el.style.background = "rgba(0,0,0,0.5)";
        el.style.color = "var(--gray)";
    }
}

// ====== DELETE FORM HANDLERS ======
function setupDeleteFormHandlers() {
    // Setup delete form handlers for artists, tickets, users, etc.
    document.addEventListener("submit", async function (e) {
        const form = e.target;

        // Only handle forms with DELETE method
        if (form.method.toUpperCase() !== "POST" || !form.querySelector('input[name="_method"][value="DELETE"]')) {
            return;
        }

        if (form.hasAttribute('data-manual') || form.getAttribute('data-manual') === 'true') {
            return; // Biarkan form submit manual & halaman berpindah/reload
        }

        e.preventDefault();

        const action = form.action;
        const entityType = detectEntityType(action);
        const entityId = extractIdFromUrl(action);

        // Show confirmation
        const entityName = detectEntityName(entityType, entityId);
        if (!confirm(`Apakah kamu yakin ingin menghapus ${entityName}?`)) {
            return;
        }

        try {
            const response = await fetch(action, {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken(),
                },
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }

            // Remove the row from table with animation
            const row = form.closest("tr");
            if (row) {
                row.style.opacity = "0";
                row.style.transition = "opacity 0.3s";
                setTimeout(() => row.remove(), 300);
            }

            // Show success notification
            showToast("success", `✓ ${entityName} berhasil dihapus!`);
        } catch (error) {
            showToast("error", `✗ Gagal menghapus ${entityName}: ${error.message}`);
        }
    }, true); // Use capturing phase to intercept forms
}

function detectEntityType(url) {
    if (url.includes("/api/artists/")) return "artis";
    if (url.includes("/api/users/")) return "user";
    if (url.includes("/api/konsers/")) return "konser";
    return "data";
}

function extractIdFromUrl(url) {
    const parts = url.split("/");
    return parts[parts.length - 1];
}

function detectEntityName(type, id) {
    const names = {
        artis: "artis",
        user: "pengguna",
        konser: "konser",
        data: "data"
    };
    return names[type] || "data";
}

// ====== PROFILE TABS ======
function switchProfileTab(tab, btn) {
    document
        .querySelectorAll(".profile-tab")
        .forEach((t) => t.classList.remove("active"));
    btn.classList.add("active");
    document.getElementById("profile-tab-orders").style.display =
        tab === "orders" ? "block" : "none";
    document.getElementById("profile-tab-settings").style.display =
        tab === "settings" ? "block" : "none";
}

// ====== TOAST ======
function showToast(type, msg) {
    const icons = {
        success: "fa-check-circle",
        error: "fa-times-circle",
        info: "fa-info-circle",
    };
    const container = document.getElementById("toast-container");
    const toast = document.createElement("div");
    toast.className = `toast-item ${type}`;
    toast.innerHTML = `<i class="fas ${icons[type] || "fa-info-circle"}"></i><span class="toast-msg">${msg}</span>`;
    container.appendChild(toast);
    requestAnimationFrame(() =>
        requestAnimationFrame(() => toast.classList.add("show")),
    );
    setTimeout(() => {
        toast.classList.remove("show");
        setTimeout(() => toast.remove(), 400);
    }, 3500);
}

// ====== SESSION MESSAGES (AUTH NOTIFICATIONS) ======
function checkSessionMessages() {
    // Cek notifikasi login required
    const notifLoginEl = document.querySelector('[data-notif-login]');
    if (notifLoginEl) {
        const message = notifLoginEl.getAttribute('data-notif-login');
        if (message) {
            showToast('info', '🔐 ' + message + ' Silakan login atau buat akun baru.');
        }
    }

    // Cek notifikasi error
    const notifErrorEl = document.querySelector('[data-notif-error]');
    if (notifErrorEl) {
        const message = notifErrorEl.getAttribute('data-notif-error');
        if (message) {
            showToast('error', '❌ ' + message);
        }
    }

    // Cek notifikasi success
    const notifSuccessEl = document.querySelector('[data-notif-success]');
    if (notifSuccessEl) {
        const message = notifSuccessEl.getAttribute('data-notif-success');
        if (message) {
            showToast('success', '✓ ' + message);
        }
    }
}

// ====== AOS ANIMATION ======
function initAOS() {
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((e) => {
                if (e.isIntersecting) e.target.classList.add("aos-animate");
            });
        },
        { threshold: 0.1 },
    );
    document
        .querySelectorAll("[data-aos]")
        .forEach((el) => observer.observe(el));
}

// ====== PARTICLES ======
function initParticles() {
    const container = document.getElementById("hero-particles");
    if (!container) return;
    for (let i = 0; i < 20; i++) {
        const p = document.createElement("div");
        p.className = "particle";
        p.style.cssText = `left:${Math.random() * 100}%;width:${Math.random() * 3 + 1}px;height:${Math.random() * 3 + 1}px;animation-duration:${Math.random() * 10 + 8}s;animation-delay:${Math.random() * 5}s;opacity:${Math.random() * 0.6 + 0.2};`;
        container.appendChild(p);
    }
}

// ====== NAVBAR SCROLL ======
window.addEventListener("scroll", () => {
    const navbar = document.getElementById("navbar");
    if (navbar) {
        navbar.classList.toggle("scrolled", window.scrollY > 50);
    }
});

// ====== NAVBAR DROPDOWN TOGGLE ======
function setupNavDropdown() {
    const navAvatar = document.querySelector(".nav-avatar");
    if (!navAvatar) return;

    // Toggle dropdown saat avatar diklik
    navAvatar.addEventListener("click", function (e) {
        e.stopPropagation();
        this.classList.toggle("active");
    });

    // Close dropdown saat item diklik
    document.querySelectorAll(".nav-dd-item").forEach((item) => {
        item.addEventListener("click", function () {
            navAvatar.classList.remove("active");
        });
    });

    // Close dropdown saat klik di luar
    document.addEventListener("click", function (e) {
        if (
            !navAvatar.contains(e.target) &&
            !e.target.closest(".nav-dropdown")
        ) {
            navAvatar.classList.remove("active");
        }
    });
}

// ====== CLOSE MODALS ON OVERLAY CLICK ======
document.querySelectorAll(".modal-overlay").forEach((overlay) => {
    overlay.addEventListener("click", function (e) {
        if (e.target === this) {
            if (this.id === "modal-auth") closeModal("auth");
            if (this.id === "modal-crud") closeModal("crud");
            if (this.id === "modal-video") closeModal("video");
        }
    });
});

// ====== INIT ======
document.addEventListener("DOMContentLoaded", () => {
    // Cek session messages untuk notifikasi auth
    checkSessionMessages();

    // Setup dropdown toggle
    setupNavDropdown();

    // Initialize admin page if on admin dashboard
    const pageAdmin = document.getElementById('page-admin');
    if (pageAdmin) {
        switchAdmin('admin');
    }

    renderHomeConcerts();
    initParticles();
    initAOS();
    setupDeleteFormHandlers();
    // Navbar search
    const navSearch = document.querySelector(".nav-search");
    if (navSearch) {
        navSearch.addEventListener("input", function () {
            if (this.value.length > 0) {
                navigate("concerts");
                const concertSearch = document.getElementById("concert-search");
                if (concertSearch) concertSearch.value = this.value;
                filterConcerts();
            }
        });
    }
});
function getYoutubeId(url) {
    const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
    const match = url.match(regExp);
    return (match && match[2].length === 11) ? match[2] : null;
}
