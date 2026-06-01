// ====== APP STATE ======
let currentPage = 'home';
let currentUser = null;
let selectedConcertId = null;
let selectedCategory = null;
let selectedQty = 1;
let selectedCategoryPrice = 0;

// In-memory cache agar tidak fetch ulang terus-menerus
let _concertsCache = null;
let _artistsCache = null;
let _ticketCategoriesCache = null;

// CSRF token helper (Laravel)
function csrfToken() {
  return document.querySelector('meta[name="csrf-token"]')?.content || '';
}

// ====== BASE API HELPER ======
async function apiFetch(url, options = {}) {
  const defaults = {
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': csrfToken(),
    },
  };
  const response = await fetch(url, { ...defaults, ...options, headers: { ...defaults.headers, ...(options.headers || {}) } });
  if (!response.ok) {
    const err = await response.json().catch(() => ({}));
    throw new Error(err.message || `HTTP ${response.status}`);
  }
  return response.json();
}

// ====== API: KONSERS ======
async function loadKonsersFromAPI() {
  try {
    if (_concertsCache) return _concertsCache;
    const data = await apiFetch('/api/konsers');
    _concertsCache = data.map(k => ({
      id: k.id,
      title: k.title,
      artist: k.artist,
      genre: k.genre || 'Pop',
      city: k.city,
      venue: k.venue,
      date: new Date(k.date).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }),
      time: k.time ? k.time.substring(0, 5) + ' WIB' : '19.00 WIB',
      img: k.image || 'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=600&q=80',
      price: parseInt(k.price) || 0,
      type: k.type || 'lokal',
      status: k.status === 'published' ? 'on-sale' : (k.status === 'sold_out' ? 'sold-out' : (k.status || 'on-sale')),
      desc: k.description || '',
      trailerYt: k.trailer_yt || 'dQw4w9WgXcQ',
      bg: k.type === 'internasional' ? 'internasional' : 'indonesia',
    }));
    return _concertsCache;
  } catch (error) {
    console.error('Error loading konsers:', error);
    showToast('error', 'Gagal memuat data konser');
    return [];
  }
}

async function getKonserById(id) {
  const list = await loadKonsersFromAPI();
  return list.find(c => c.id == id) || null;
}

async function saveKonserToAPI(isEdit = false, konserID = null) {
  const dateInput = document.getElementById('cf-date').value;
  const timeInput = document.getElementById('cf-time').value;
  const statusVal = document.querySelector('input[name="cf-status"]:checked')?.value || 'on-sale';

  const payload = {
    title: document.getElementById('cf-title').value,
    artist: document.getElementById('cf-artist').value,
    genre: document.getElementById('cf-genre').value,
    date: dateInput + ' ' + timeInput,
    time: timeInput,
    venue: document.getElementById('cf-venue').value,
    city: document.getElementById('cf-city').value,
    description: document.getElementById('cf-desc').value,
    price: document.getElementById('cf-price').value || 0,
    status: statusVal === 'on-sale' ? 'published' : statusVal,
    type: 'lokal',
    capacity: 1000,
  };

  const method = isEdit ? 'PUT' : 'POST';
  const url = isEdit ? `/api/konsers/${konserID}` : '/api/konsers';
  const result = await apiFetch(url, { method, body: JSON.stringify(payload) });
  _concertsCache = null; // invalidate cache
  return result;
}

async function deleteKonserFromAPI(konserID) {
  const result = await apiFetch(`/api/konsers/${konserID}`, { method: 'DELETE' });
  _concertsCache = null;
  return result;
}

// ====== API: ARTISTS ======
async function loadArtistsFromAPI() {
  try {
    if (_artistsCache) return _artistsCache;
    const data = await apiFetch('/api/artists');
    _artistsCache = data.map(a => ({
      id: a.id,
      name: a.name,
      genre: a.genre || 'Pop',
      img: a.image || 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=300&q=80',
      concerts: a.concerts_count || 0,
      country: a.origin || 'indonesia',
      bio: a.bio || '',
      instagram: a.instagram || '',
    }));
    return _artistsCache;
  } catch (error) {
    console.error('Error loading artists:', error);
    return [];
  }
}

// ====== API: TICKET CATEGORIES ======
async function loadTicketCategoriesFromAPI(konserID) {
  try {
    const data = await apiFetch(`/api/konsers/${konserID}/tickets`);
    return data.map(t => ({
      id: t.id,
      name: t.name,
      price: parseInt(t.price) || 0,
      stock: t.stock || 0,
      desc: t.description || '',
    }));
  } catch (error) {
    console.error('Error loading ticket categories:', error);
    return [
      { id: 1, name: 'FESTIVAL', price: 0, stock: 0, desc: 'Area Festival' },
    ];
  }
}

// ====== API: USERS ======
async function loadUsersFromAPI() {
  try {
    const data = await apiFetch('/api/users');
    return data.map(u => ({
      id: u.id,
      name: u.name,
      email: u.email,
      role: u.role || 'user',
      status: u.status || 'active',
      joined: new Date(u.created_at).toLocaleDateString('id-ID', { month: 'short', year: 'numeric' }),
    }));
  } catch (error) {
    console.error('Error loading users:', error);
    return [];
  }
}

// ====== API: TRANSACTIONS ======
async function loadTransactionsFromAPI() {
  try {
    const data = await apiFetch('/api/transactions');
    return data.map(t => ({
      id: t.id,
      user: t.user?.name || t.user_name || '-',
      concert: t.concert?.artist + ' — ' + t.concert?.title || t.concert_title || '-',
      qty: t.qty || t.quantity || 1,
      total: parseInt(t.total) || 0,
      status: t.status || 'pending',
      date: new Date(t.created_at || t.date).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }),
    }));
  } catch (error) {
    console.error('Error loading transactions:', error);
    return [];
  }
}

// ====== API: AUTH ======
async function loginUserWithAPI(email, password) {
  return apiFetch('/api/login', {
    method: 'POST',
    body: JSON.stringify({ email, password }),
  });
}

async function registerUserWithAPI(payload) {
  return apiFetch('/api/register', {
    method: 'POST',
    body: JSON.stringify(payload),
  });
}

// ====== NAVIGATION ======
function navigate(page) {
  document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
  const el = document.getElementById('page-' + page);
  if (el) el.classList.add('active');
  const nl = document.getElementById('nl-' + page);
  if (nl) nl.classList.add('active');
  currentPage = page;
  window.scrollTo(0, 0);
  if (page === 'home') renderHomeConcerts();
  if (page === 'concerts') renderConcertsPage();
  if (page === 'artists') renderArtistsPage();
  if (page === 'gallery') renderGallery();
  if (page === 'admin') initAdmin();
}

// ====== RENDER CONCERTS ======
function concertCardHTML(c, i) {
  const badge = c.status === 'sold-out'
    ? '<span class="concert-card-badge sold-out">SOLD OUT</span>'
    : '<span class="concert-card-badge">ON SALE</span>';
  return `<div class="concert-card" onclick="openConcertDetail(${c.id})" data-artist="${c.artist.toLowerCase()}" data-genre="${c.genre.toLowerCase()}" data-city="${c.city.toLowerCase()}" data-type="${c.type}" data-price="${c.price}" data-aos style="animation-delay:${i * 0.08}s">
    <div class="concert-card-img">
      <img src="/storage/${c.img}" alt="${c.artist}" loading="lazy">
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
        <button class="btn-book ${c.status === 'sold-out' ? 'disabled' : ''}">${c.status === 'sold-out' ? 'HABIS' : 'BELI'}</button>
      </div>
    </div>
  </div>`;
}

function renderHomeConcerts() {
  const g = document.getElementById('home-concerts-grid');
  if (g) {
    g.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:40px;color:var(--gray);"><i class="fas fa-spinner fa-spin" style="font-size:24px;"></i></div>';
    loadKonsersFromAPI().then(data => {
      g.innerHTML = data.slice(0, 4).map((c, i) => concertCardHTML(c, i)).join('');
      initAOS();
    });
  }

  const a = document.getElementById('home-artists');
  if (a) {
    loadArtistsFromAPI().then(artists => {
      a.innerHTML = artists.slice(0, 8).map(ar => `
        <div class="artist-card" onclick="navigate('artists')">
          <img class="artist-card-img" src="/storage/${ar.img}" alt="${ar.name}">
          <div class="artist-card-name">${ar.name}</div>
          <div class="artist-card-genre">${ar.genre}</div>
        </div>`).join('');
    });
  }
}

function renderConcertsPage() {
  const g = document.getElementById('concerts-page-grid');
  if (!g) return;
  g.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:60px;color:var(--gray);"><i class="fas fa-spinner fa-spin" style="font-size:24px;"></i></div>';
  loadKonsersFromAPI().then(data => {
    g.innerHTML = data.map((c, i) => concertCardHTML(c, i)).join('');
    initAOS();
  });
}

// Current filtered list (async-aware)
let _currentFilteredConcerts = [];

function filterConcerts(typeFilter) {
  const search = (document.getElementById('concert-search') || {}).value || '';
  const genre = (document.getElementById('filter-genre') || {}).value || '';
  const city = (document.getElementById('filter-city') || {}).value || '';
  const sort = (document.getElementById('filter-sort') || {}).value || 'date';
  const g = document.getElementById('concerts-page-grid');

  loadKonsersFromAPI().then(allConcerts => {
    let filtered = allConcerts.filter(c => {
      const matchSearch = !search || c.artist.toLowerCase().includes(search.toLowerCase()) || c.title.toLowerCase().includes(search.toLowerCase());
      const matchGenre = !genre || c.genre.toLowerCase().includes(genre.toLowerCase());
      const matchCity = !city || c.city === city;
      const matchType = !typeFilter || typeFilter === 'all' || c.type === typeFilter;
      return matchSearch && matchGenre && matchCity && matchType;
    });
    if (sort === 'price-asc') filtered.sort((a, b) => a.price - b.price);
    if (sort === 'price-desc') filtered.sort((a, b) => b.price - a.price);
    if (g) {
      if (!filtered.length) {
        g.innerHTML = '<div style="padding:60px;text-align:center;color:var(--gray);grid-column:1/-1;"><i class="fas fa-search" style="font-size:36px;margin-bottom:16px;display:block;opacity:0.3;"></i>Tidak ada konser ditemukan</div>';
      } else {
        g.innerHTML = filtered.map((c, i) => concertCardHTML(c, i)).join('');
      }
    }
    initAOS();
  });
}

// ====== CONCERT DETAIL ======
function openConcertDetail(id) {
  selectedConcertId = id;
  navigate('detail');
  document.getElementById('nl-concerts').classList.add('active');

  getKonserById(id).then(c => {
    if (!c) { showToast('error', 'Konser tidak ditemukan'); return; }

    document.getElementById('detail-bg-img').src = '/storage/' + c.img;
    document.getElementById('detail-badge').textContent = c.genre.toUpperCase();
    document.getElementById('detail-title').textContent = c.title;
    document.getElementById('detail-artist').querySelector('span').textContent = c.artist;
    document.getElementById('detail-date').textContent = c.date;
    document.getElementById('detail-venue').textContent = c.venue;
    document.getElementById('detail-time').textContent = c.time;
    document.getElementById('detail-description').textContent = c.desc;

    document.getElementById('detail-lineup').innerHTML = [c.artist, 'Special Guest', 'Opening Act 1', 'Opening Act 2'].map((name, i) => `
      <div class="lineup-item">
        <div class="lineup-num">0${i + 1}</div>
        <div class="lineup-info"><h4>${name}</h4><p>${i === 0 ? 'Headliner' : 'Supporting Artist'}</p></div>
        ${i === 0 ? '<span class="status-badge status-on-sale">HEADLINER</span>' : ''}
      </div>`).join('');

    const galleryImgs = [
      'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=300&q=80',
      'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=300&q=80',
      'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=300&q=80',
      'https://images.unsplash.com/photo-1501612780327-45045538702b?w=300&q=80',
    ];
    document.getElementById('detail-gallery').innerHTML = galleryImgs.map(g => `<div class="gallery-img"><img src="${g}" alt="gallery" loading="lazy"></div>`).join('');
    document.getElementById('detail-trailer-thumb').src = '/storage/' + c.img;
    document.getElementById('detail-trailer-title').textContent = c.artist + ' — Official Trailer';

    // Load ticket categories dari API
    const cats = document.getElementById('ticket-categories');
    cats.innerHTML = '<div style="text-align:center;padding:20px;color:var(--gray);"><i class="fas fa-spinner fa-spin"></i></div>';
    loadTicketCategoriesFromAPI(id).then(ticketCategories => {
      if (!ticketCategories.length) {
        cats.innerHTML = '<div style="text-align:center;padding:20px;color:var(--gray);">Belum ada kategori tiket</div>';
        return;
      }
      cats.innerHTML = ticketCategories.map(t => `
        <div class="ticket-category" onclick="selectTicketCat(this,'${t.name}',${t.price})">
          <div>
            <div class="ticket-cat-name">${t.name}</div>
            <div class="ticket-cat-stock" style="font-size:11px;color:var(--gray);">${t.stock} sisa</div>
          </div>
          <div class="ticket-cat-price">Rp ${(t.price / 1000).toFixed(0)}K</div>
        </div>`).join('');
      selectTicketCat(cats.firstElementChild, ticketCategories[0].name, ticketCategories[0].price);
      renderSeatMap();
    });
  });
}

function selectTicketCat(el, name, price) {
  document.querySelectorAll('.ticket-category').forEach(t => t.classList.remove('selected'));
  el.classList.add('selected');
  selectedCategory = name;
  selectedCategoryPrice = price;
  updateBookingSummary();
}

function changeQty(delta) {
  selectedQty = Math.max(1, Math.min(10, selectedQty + delta));
  document.getElementById('qty-display').textContent = selectedQty;
  updateBookingSummary();
}

function updateBookingSummary() {
  const total = selectedCategoryPrice * selectedQty + 10000;
  document.getElementById('s-price').textContent = 'Rp ' + selectedCategoryPrice.toLocaleString('id-ID');
  document.getElementById('s-qty').textContent = 'x' + selectedQty;
  document.getElementById('s-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
}

function renderSeatMap() {
  const rows = ['A', 'B', 'C', 'D', 'E'];
  let html = '';
  rows.forEach(row => {
    html += '<div class="seat-row">';
    for (let i = 1; i <= 10; i++) {
      const sold = Math.random() > 0.7;
      html += `<div class="seat ${sold ? 'sold' : ''}" onclick="${sold ? '' : `selectSeat(this,'${row}${i}')`}"></div>`;
    }
    html += '</div>';
  });
  document.getElementById('seat-map-rows').innerHTML = html;
}

function selectSeat(el) {
  el.classList.toggle('selected');
}

function goCheckout() {
  if (!currentUser) { openModal('login'); showToast('error', 'Silakan login terlebih dahulu'); return; }
  getKonserById(selectedConcertId).then(c => {
    if (!c) return;
    document.getElementById('checkout-img').src = '/storage/' + c.img;
    document.getElementById('checkout-title').textContent = c.artist + ' — ' + c.title;
    document.getElementById('checkout-meta').textContent = c.date + ' · ' + c.venue;
    document.getElementById('checkout-cat').textContent = selectedCategory + ' × ' + selectedQty;
    const unit = selectedCategoryPrice;
    const sub = unit * selectedQty;
    const total = sub + 10000;
    document.getElementById('co-unit').textContent = 'Rp ' + unit.toLocaleString('id-ID');
    document.getElementById('co-qty').textContent = selectedQty;
    document.getElementById('co-sub').textContent = 'Rp ' + sub.toLocaleString('id-ID');
    document.getElementById('co-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
    navigate('checkout');
  });
}

// ====== ARTISTS PAGE ======
function renderArtistsPage() {
  filterArtists();
}

function filterArtists() {
  const search = (document.getElementById('artist-search') || {}).value || '';
  const g = document.getElementById('artists-grid');
  if (!g) return;
  loadArtistsFromAPI().then(artists => {
    const filtered = artists.filter(a => !search || a.name.toLowerCase().includes(search.toLowerCase()));
    g.innerHTML = filtered.map(a => `
      <div class="artist-full-card">
        <img class="artist-full-img" src="/storage/${a.img}" alt="${a.name}" loading="lazy">
        <div class="artist-full-name">${a.name}</div>
        <div class="artist-full-genre">${a.genre}</div>
        <div class="artist-full-concerts"><i class="fas fa-music" style="margin-right:4px;"></i>${a.concerts} konser</div>
        <button class="btn-book" style="margin-top:12px;" onclick="event.stopPropagation();navigate('concerts')">Lihat Konser</button>
      </div>`).join('');
  });
}

function setArtistFilter(val, btn) {
  document.querySelectorAll('.filter-btn').forEach(b => {
    if (b.onclick && b.onclick.toString().includes('setArtistFilter')) b.classList.remove('active');
  });
  btn.classList.add('active');
  const g = document.getElementById('artists-grid');
  if (!g) return;
  loadArtistsFromAPI().then(artists => {
    const filtered = val === 'all' ? artists : artists.filter(a => a.country === val);
    g.innerHTML = filtered.map(a => `
      <div class="artist-full-card">
        <img class="artist-full-img" src="/storage/${a.img}" alt="${a.name}" loading="lazy">
        <div class="artist-full-name">${a.name}</div>
        <div class="artist-full-genre">${a.genre}</div>
        <div class="artist-full-concerts"><i class="fas fa-music" style="margin-right:4px;"></i>${a.concerts} konser</div>
        <button class="btn-book" style="margin-top:12px;">Lihat Konser</button>
      </div>`).join('');
  });
}

// ====== GALLERY PAGE ======
function renderGallery() {
  const galleryImgs = [
    'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=400&q=80',
    'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=400&q=80',
    'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=400&q=80',
    'https://images.unsplash.com/photo-1501612780327-45045538702b?w=400&q=80',
    'https://images.unsplash.com/photo-1459749411175-04bf5292ceea?w=400&q=80',
    'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?w=400&q=80',
    'https://images.unsplash.com/photo-1547153760-18fc86324498?w=400&q=80',
    'https://images.unsplash.com/photo-1514320291840-2e0a9bf2a9ae?w=400&q=80',
    'https://images.unsplash.com/photo-1504680177321-2e6a879aac86?w=400&q=80',
    'https://images.unsplash.com/photo-1516280440614-37939bbacd81?w=400&q=80',
    'https://images.unsplash.com/photo-1545128485-c400ce7b17eb?w=400&q=80',
    'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?w=400&q=80',
  ];
  const g = document.getElementById('gallery-grid');
  if (g) g.innerHTML = galleryImgs.map(img => `
    <div style="break-inside:avoid;margin-bottom:16px;border-radius:var(--radius);overflow:hidden;cursor:pointer;transition:var(--transition);"
         onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
      <img src="${img}" style="width:100%;display:block;" loading="lazy">
    </div>`).join('');
}

// ====== ADMIN ======
function initAdmin() {
  document.getElementById('admin-date').textContent = new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
  switchAdmin('admin');
}

function switchAdmin(section) {
  ['admin', 'concerts', 'artists', 'tickets', 'transactions', 'users'].forEach(s => {
    const el = document.getElementById('admin-section-' + s);
    const nav = document.getElementById('adm-' + s);
    if (el) el.style.display = s === section ? 'block' : 'none';
    if (nav) nav.classList.toggle('active', s === section);
  });
  if (section === 'admin') buildAdminDashboard();
  if (section === 'concerts') buildConcertsTable();
  if (section === 'artists') buildArtistsTable();
  if (section === 'tickets') buildTicketsTable();
  if (section === 'transactions') buildTransactionsTable();
  if (section === 'users') buildUsersTable();
}

function buildAdminDashboard() {
  const chart = document.getElementById('admin-chart');
  if (chart) {
    // Chart bar dari API statistik (jika ada), atau data sederhana
    apiFetch('/api/dashboard/stats').then(stats => {
      const data = stats.weekly_sales || [0, 0, 0, 0, 0, 0, 0];
      const days = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
      const max = Math.max(...data) || 1;
      chart.innerHTML = data.map((v, i) => `
        <div class="chart-bar-wrap">
          <div class="chart-bar" style="height:${(v / max * 140).toFixed(0)}px;" title="${v} tiket"></div>
          <div class="chart-bar-label">${days[i]}</div>
        </div>`).join('');
    }).catch(() => {
      // Fallback chart kosong
      const days = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
      chart.innerHTML = days.map(d => `
        <div class="chart-bar-wrap">
          <div class="chart-bar" style="height:0px;"></div>
          <div class="chart-bar-label">${d}</div>
        </div>`).join('');
    });
  }

  const tx = document.getElementById('recent-tx-table');
  if (tx) {
    tx.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:20px;color:var(--gray);"><i class="fas fa-spinner fa-spin"></i></td></tr>';
    loadTransactionsFromAPI().then(transactions => {
      tx.innerHTML = `<thead><tr><th>ID</th><th>User</th><th>Konser</th><th>Total</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>${transactions.slice(0, 5).map(t => `
        <tr><td><code style="color:var(--red);font-size:12px;">${t.id}</code></td>
        <td>${t.user}</td>
        <td style="color:var(--gray);font-size:13px;">${t.concert}</td>
        <td><strong>Rp ${t.total.toLocaleString('id-ID')}</strong></td>
        <td><span class="status-badge status-${t.status}">${t.status.toUpperCase()}</span></td>
        <td><div class="td-actions"><button class="btn-view" onclick="showToast('info','Detail transaksi ${t.id}')"><i class="fas fa-eye"></i></button></div></td>
        </tr>`).join('')}</tbody>`;
    });
  }
}

function buildConcertsTable() {
  const t = document.getElementById('admin-concerts-table');
  if (!t) return;
  t.innerHTML = '<tr><td colspan="9" style="text-align:center;padding:30px;color:var(--gray);"><i class="fas fa-spinner fa-spin"></i> Memuat data...</td></tr>';
  loadKonsersFromAPI().then(konsersData => {
    t.innerHTML = `<thead><tr><th>#</th><th>Poster</th><th>Konser</th><th>Artis</th><th>Tanggal</th><th>Kota</th><th>Harga</th><th>Status</th><th>Aksi</th></tr></thead>
      <tbody>${konsersData.map((c, i) => `
      <tr>
        <td>${i + 1}</td>
        <td><img class="concert-thumb" src="/storage/${c.img}" alt=""></td>
        <td><div class="td-name">${c.title}</div></td>
        <td class="td-artist">${c.artist}</td>
        <td style="font-size:13px;">${c.date}</td>
        <td>${c.city}</td>
        <td>Rp ${(c.price / 1000).toFixed(0)}K</td>
        <td><span class="status-badge ${c.status === 'on-sale' ? 'status-on-sale' : 'status-sold-out'}">${c.status === 'on-sale' ? 'ON SALE' : 'SOLD OUT'}</span></td>
        <td><div class="td-actions">
          <button class="btn-edit" onclick="editConcert(${c.id})"><i class="fas fa-edit"></i></button>
          <button class="btn-del" onclick="deleteConcert(${c.id},this)"><i class="fas fa-trash"></i></button>
          <button class="btn-view" onclick="openConcertDetail(${c.id})"><i class="fas fa-eye"></i></button>
        </div></td>
      </tr>`).join('')}</tbody>`;
  }).catch(() => {
    t.innerHTML = '<tr><td colspan="9" style="text-align:center;color:var(--gray);">Gagal memuat data konser</td></tr>';
  });
}

function buildArtistsTable() {
  const t = document.getElementById('admin-artists-table');
  if (!t) return;
  t.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:30px;color:var(--gray);"><i class="fas fa-spinner fa-spin"></i> Memuat data...</td></tr>';
  loadArtistsFromAPI().then(artists => {
    t.innerHTML = `<thead><tr><th>#</th><th>Foto</th><th>Nama</th><th>Genre</th><th>Konser</th><th>Aksi</th></tr></thead>
      <tbody>${artists.map((a, i) => `
      <tr>
        <td>${i + 1}</td>
        <td><img class="concert-thumb" src="/storage/${a.img}" alt="" style="border-radius:50%;width:40px;height:40px;object-fit:cover;"></td>
        <td><div class="td-name">${a.name}</div></td>
        <td>${a.genre}</td>
        <td>${a.concerts}</td>
        <td><div class="td-actions">
          <button class="btn-edit" onclick="showToast('info','Edit artis ${a.name}')"><i class="fas fa-edit"></i></button>
          <button class="btn-del" onclick="deleteRow(this)"><i class="fas fa-trash"></i></button>
        </div></td>
      </tr>`).join('')}</tbody>`;
  }).catch(() => {
    t.innerHTML = '<tr><td colspan="6" style="text-align:center;color:var(--gray);">Gagal memuat data artis</td></tr>';
  });
}

function buildTransactionsTable() {
  const t = document.getElementById('admin-transactions-table');
  if (!t) return;
  t.innerHTML = '<tr><td colspan="8" style="text-align:center;padding:30px;color:var(--gray);"><i class="fas fa-spinner fa-spin"></i> Memuat data...</td></tr>';
  loadTransactionsFromAPI().then(transactions => {
    t.innerHTML = `<thead><tr><th>ID</th><th>User</th><th>Konser</th><th>Qty</th><th>Total</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr></thead>
      <tbody>${transactions.map(tx => `
      <tr>
        <td><code style="color:var(--red);font-size:12px;">${tx.id}</code></td>
        <td>${tx.user}</td>
        <td style="font-size:13px;color:var(--gray);">${tx.concert}</td>
        <td>${tx.qty}</td>
        <td><strong>Rp ${tx.total.toLocaleString('id-ID')}</strong></td>
        <td><span class="status-badge status-${tx.status}">${tx.status.toUpperCase()}</span></td>
        <td style="font-size:13px;">${tx.date}</td>
        <td><div class="td-actions">
          <button class="btn-view" onclick="showToast('info','Detail ${tx.id}')"><i class="fas fa-eye"></i></button>
          <button class="btn-edit" onclick="showToast('success','Status diperbarui')"><i class="fas fa-check"></i></button>
        </div></td>
      </tr>`).join('')}</tbody>`;
  }).catch(() => {
    t.innerHTML = '<tr><td colspan="8" style="text-align:center;color:var(--gray);">Gagal memuat data transaksi</td></tr>';
  });
}

function buildUsersTable() {
  const t = document.getElementById('admin-users-table');
  if (!t) return;
  t.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:30px;color:var(--gray);"><i class="fas fa-spinner fa-spin"></i> Memuat data...</td></tr>';
  loadUsersFromAPI().then(users => {
    t.innerHTML = `<thead><tr><th>#</th><th>Nama</th><th>Email</th><th>Role</th><th>Status</th><th>Aksi</th></tr></thead>
      <tbody>${users.map((u, i) => `
      <tr>
        <td>${i + 1}</td>
        <td><div class="td-name">${u.name}</div></td>
        <td style="font-size:13px;color:var(--gray);">${u.email}</td>
        <td><span class="status-badge ${u.role === 'admin' ? 'status-on-sale' : ''}">${u.role.toUpperCase()}</span></td>
        <td><span class="status-badge ${u.status === 'active' ? 'status-on-sale' : 'status-sold-out'}">${u.status.toUpperCase()}</span></td>
        <td><div class="td-actions">
          <button class="btn-edit" onclick="showToast('info','Edit user ${u.name}')"><i class="fas fa-edit"></i></button>
          <button class="btn-del" onclick="deleteRow(this)"><i class="fas fa-trash"></i></button>
        </div></td>
      </tr>`).join('')}</tbody>`;
  }).catch(() => {
    t.innerHTML = '<tr><td colspan="6" style="text-align:center;color:var(--gray);">Gagal memuat data user</td></tr>';
  });
}

function editConcert(id) {
  getKonserById(id).then(c => {
    if (c) openCrudModal('concert', c, id);
  });
}

function deleteConcert(id, btn) {
  const row = btn.closest('tr');
  deleteKonserFromAPI(id).then(() => {
    row.style.opacity = '0';
    row.style.transition = 'opacity 0.3s';
    setTimeout(() => row.remove(), 300);
    showToast('success', 'Konser berhasil dihapus');
  }).catch(error => {
    showToast('error', error.message || 'Gagal menghapus konser');
  });
}

function deleteRow(btn) {
  const row = btn.closest('tr');
  row.style.opacity = '0';
  row.style.transition = 'opacity 0.3s';
  setTimeout(() => row.remove(), 300);
  showToast('success', 'Data berhasil dihapus');
}

function filterAdminTable(input, tableId) {
  const val = input.value.toLowerCase();
  const rows = document.querySelectorAll('#' + tableId + ' tbody tr');
  rows.forEach(r => { r.style.display = r.textContent.toLowerCase().includes(val) ? '' : 'none'; });
}

// ====== CRUD MODALS ======
function buildCrudForms(artists) {
  return {
    concert: `
      <div class="form-group"><label class="form-label">Nama Konser/Tour</label><input class="form-input" type="text" id="cf-title" placeholder="Music of the Spheres..."></div>
      <div class="form-row">
        <div class="form-group"><label class="form-label">Artis</label>
          <select class="form-input" id="cf-artist">${artists.map(a => `<option>${a.name}</option>`).join('')}</select>
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
      <div class="form-group"><label class="form-label">Nama Artis/Band</label><input class="form-input" type="text" placeholder="Nama artis..."></div>
      <div class="form-row">
        <div class="form-group"><label class="form-label">Genre</label><select class="form-input"><option>Pop</option><option>Rock</option><option>R&B</option><option>Metal</option><option>Indie</option></select></div>
        <div class="form-group"><label class="form-label">Asal</label>
          <div style="display:flex;gap:16px;margin-top:8px;">
            <label style="display:flex;align-items:center;gap:8px;font-size:14px;cursor:pointer;"><input type="radio" name="artist-origin" checked style="accent-color:var(--red);"> Indonesia</label>
            <label style="display:flex;align-items:center;gap:8px;font-size:14px;cursor:pointer;"><input type="radio" name="artist-origin" style="accent-color:var(--red);"> Internasional</label>
          </div>
        </div>
      </div>
      <div class="form-group"><label class="form-label">Foto Artis</label><input class="form-input" type="file" accept="image/*"></div>
      <div class="form-group"><label class="form-label">Bio Singkat</label><textarea class="form-input" rows="3" placeholder="Ceritakan tentang artis..."></textarea></div>
      <div class="form-group"><label class="form-label">Instagram</label><input class="form-input" type="text" placeholder="@username"></div>
      <button class="btn-submit" onclick="saveCrudForm('artist')">SIMPAN ARTIS</button>`,

    ticket: `
      <div class="form-group"><label class="form-label">Konser</label>
        <select class="form-input" id="cf-ticket-concert">
          <option value="">-- Memuat konser... --</option>
        </select>
      </div>
      <div class="form-group"><label class="form-label">Nama Kategori</label><input class="form-input" type="text" placeholder="VVIP / VIP / Festival..."></div>
      <div class="form-row">
        <div class="form-group"><label class="form-label">Harga (Rp)</label><input class="form-input" type="number" placeholder="750000" min="0"></div>
        <div class="form-group"><label class="form-label">Stok</label><input class="form-input" type="number" placeholder="500" min="1"></div>
      </div>
      <div class="form-group"><label class="form-label">Deskripsi Area</label><input class="form-input" type="text" placeholder="Deskripsi area tiket..."></div>
      <div class="form-group"><label class="form-label">Harga Promo <span style="color:var(--gray);font-size:11px;">(opsional)</span></label>
        <div style="display:flex;gap:8px;align-items:center;">
          <input class="form-input" type="number" placeholder="600000" style="flex:1;">
          <input class="form-input" type="date" style="flex:1;" placeholder="Berlaku s/d">
        </div>
      </div>
      <div class="form-group"><label class="form-label">Batas Pembelian per User</label>
        <input class="form-input" type="range" min="1" max="10" value="4" oninput="this.nextElementSibling.textContent='Max '+this.value+' tiket'" class="price-range" style="margin-top:8px;">
        <div style="font-size:13px;color:var(--gray);margin-top:4px;">Max 4 tiket</div>
      </div>
      <button class="btn-submit" onclick="saveCrudForm('ticket')">SIMPAN TIKET</button>`,

    user: `
      <div class="form-row">
        <div class="form-group"><label class="form-label">Nama Depan</label><input class="form-input" type="text" placeholder="John"></div>
        <div class="form-group"><label class="form-label">Nama Belakang</label><input class="form-input" type="text" placeholder="Doe"></div>
      </div>
      <div class="form-group"><label class="form-label">Email</label><input class="form-input" type="email" placeholder="user@example.com"></div>
      <div class="form-group"><label class="form-label">Nomor HP</label><input class="form-input" type="tel" placeholder="+62 812 ..."></div>
      <div class="form-group"><label class="form-label">Password</label><input class="form-input" type="password" placeholder="••••••••"></div>
      <div class="form-group"><label class="form-label">Role</label>
        <div style="display:flex;gap:16px;margin-top:8px;">
          <label style="display:flex;align-items:center;gap:8px;font-size:14px;cursor:pointer;"><input type="radio" name="user-role" value="user" checked style="accent-color:var(--red);"> User</label>
          <label style="display:flex;align-items:center;gap:8px;font-size:14px;cursor:pointer;"><input type="radio" name="user-role" value="admin" style="accent-color:var(--red);"> Admin</label>
        </div>
      </div>
      <div class="form-group"><label class="form-label">Status</label>
        <select class="form-input"><option>Active</option><option>Inactive</option><option>Banned</option></select>
      </div>
      <button class="btn-submit" onclick="saveCrudForm('user')">SIMPAN USER</button>`,
  };
}

function openCrudModal(type, data, konserID = null) {
  const titles = { concert: 'TAMBAH KONSER', artist: 'TAMBAH ARTIS', ticket: 'TAMBAH TIKET KATEGORI', user: 'TAMBAH USER' };
  document.getElementById('crud-modal-title').textContent = data ? 'EDIT ' + type.toUpperCase() : titles[type];
  document.getElementById('crud-modal-body').innerHTML = '<div style="text-align:center;padding:30px;color:var(--gray);"><i class="fas fa-spinner fa-spin" style="font-size:20px;"></i></div>';
  document.getElementById('modal-crud').classList.add('show');

  loadArtistsFromAPI().then(artists => {
    const forms = buildCrudForms(artists);
    document.getElementById('crud-modal-body').innerHTML = forms[type] || '';

    // Prefill form jika edit
    if (data && type === 'concert') {
      setTimeout(() => {
        if (document.getElementById('cf-title')) document.getElementById('cf-title').value = data.title || '';
        if (document.getElementById('cf-artist')) document.getElementById('cf-artist').value = data.artist || '';
        if (document.getElementById('cf-genre')) document.getElementById('cf-genre').value = data.genre || 'Pop';
        if (document.getElementById('cf-venue')) document.getElementById('cf-venue').value = data.venue || '';
        if (document.getElementById('cf-city')) document.getElementById('cf-city').value = data.city || 'Jakarta';
        if (document.getElementById('cf-price')) document.getElementById('cf-price').value = data.price || '';
        if (document.getElementById('cf-desc')) document.getElementById('cf-desc').value = data.desc || '';
        // Simpan ID konser yang sedang diedit
        document.getElementById('crud-modal-body').dataset.editId = konserID || '';
      }, 50);
    }

    // Populate select konser untuk form tiket
    if (type === 'ticket') {
      loadKonsersFromAPI().then(konsers => {
        const sel = document.getElementById('cf-ticket-concert');
        if (sel) sel.innerHTML = konsers.map(c => `<option value="${c.id}">${c.artist} — ${c.title}</option>`).join('');
      });
    }
  });
}

function saveCrudForm(type) {
  closeModal('crud');
  if (type === 'concert') {
    const editId = document.getElementById('crud-modal-body')?.dataset.editId;
    const isEdit = !!editId;
    saveKonserToAPI(isEdit, editId || null).then(() => {
      showToast('success', 'Konser berhasil disimpan!');
      setTimeout(() => buildConcertsTable(), 300);
    }).catch(error => {
      showToast('error', error.message || 'Gagal menyimpan konser');
    });
  } else {
    showToast('success', type.charAt(0).toUpperCase() + type.slice(1) + ' berhasil disimpan!');
  }
}

// ====== AUTH ======
function openModal(type) {
  if (type === 'login' || type === 'register') {
    document.getElementById('modal-auth').classList.add('show');
    switchAuthTab(type);
  } else if (type === 'editProfile') {
    showToast('info', 'Edit profil tersedia di tab Pengaturan Akun');
  }
}

function closeModal(type) {
  if (type === 'auth') document.getElementById('modal-auth').classList.remove('show');
  if (type === 'crud') document.getElementById('modal-crud').classList.remove('show');
  if (type === 'video') {
    document.getElementById('modal-video').classList.remove('show');
    document.getElementById('yt-iframe').src = '';
  }
}

function switchAuthTab(tab) {
  document.getElementById('tab-login').classList.toggle('active', tab === 'login');
  document.getElementById('tab-register').classList.toggle('active', tab === 'register');
  document.getElementById('auth-login-form').style.display = tab === 'login' ? 'block' : 'none';
  document.getElementById('auth-register-form').style.display = tab === 'register' ? 'block' : 'none';
}

function loginUser() {
  const email = document.getElementById('login-email').value;
  const pass = document.getElementById('login-password').value;
  if (!email || !pass) { showToast('error', 'Email dan password harus diisi'); return; }

  loginUserWithAPI(email, pass).then(data => {
    const user = data.user || data;
    currentUser = {
      name: user.name || email.split('@')[0],
      email: user.email || email,
      avatar: (user.name || email).charAt(0).toUpperCase(),
    };
    document.getElementById('nav-auth-btns').style.display = 'none';
    document.getElementById('nav-user').style.display = 'block';
    document.getElementById('nav-avatar-initial').textContent = currentUser.avatar;
    closeModal('auth');
    showToast('success', 'Selamat datang, ' + currentUser.name + '!');
  }).catch(error => {
    showToast('error', error.message || 'Email atau password salah');
  });
}

function loginDemo() {
  document.getElementById('login-email').value = 'adil@uhamka.ac.id';
  document.getElementById('login-password').value = 'primestage123';
  loginUser();
}

function registerUser() {
  const email = document.getElementById('reg-email').value;
  const pass = document.getElementById('reg-password').value;
  const first = document.getElementById('reg-firstname').value;
  if (!email || !pass || !first) { showToast('error', 'Harap isi semua field yang diperlukan'); return; }

  registerUserWithAPI({ name: first, email, password: pass, password_confirmation: pass }).then(() => {
    closeModal('auth');
    showToast('success', 'Akun berhasil dibuat! Silakan masuk.');
    setTimeout(() => openModal('login'), 1000);
  }).catch(error => {
    showToast('error', error.message || 'Gagal membuat akun');
  });
}

function logout() {
  axios.post('/logout')
    .then(() => { window.location.href = '/dashboard'; })
    .catch(error => { console.error('Logout Gagal = ', error); });
}

// ====== CHECKOUT ======
function selectPayment(input) {
  document.querySelectorAll('[id^="pay-"]').forEach(el => el.classList.remove('selected'));
  document.getElementById('pay-' + input.value).classList.add('selected');
}

function applyPromo() {
  const code = document.getElementById('promo-code').value;
  if (code.toUpperCase() === 'PRIMESTAGE20') {
    document.getElementById('co-discount-row').style.display = 'flex';
    document.getElementById('co-disc').textContent = '-Rp 150.000';
    showToast('success', 'Promo PRIMESTAGE20 berhasil digunakan! Diskon Rp 150.000');
  } else {
    showToast('error', 'Kode promo tidak valid');
  }
}

function submitOrder() {
  if (!document.getElementById('agree-tos').checked) { showToast('error', 'Harap setujui syarat & ketentuan'); return; }
  showToast('success', 'Pemesanan berhasil! Cek email untuk tiket Anda.');
  setTimeout(() => navigate('profile'), 1500);
}

// ====== VIDEO MODAL ======
function openVideoModal(type) {
  const ytIds = { coldplay: 'YkgkThdzX-8', noah: 'wk39snYWQdc', weeknd: 'XXYlFuWEuKI' };
  const ytId = ytIds[type] || 'YkgkThdzX-8';
  document.getElementById('yt-iframe').src = `https://www.youtube.com/embed/${ytId}?autoplay=1`;
  document.getElementById('video-modal-title').textContent = type ? type.charAt(0).toUpperCase() + type.slice(1) + ' — Official Trailer' : 'Concert Trailer';
  document.getElementById('modal-video').classList.add('show');
}

// ====== WISHLIST ======
function toggleWishlist(el) {
  const icon = el.querySelector('i');
  icon.classList.toggle('far');
  icon.classList.toggle('fas');
  if (icon.classList.contains('fas')) {
    el.style.background = 'var(--red)';
    el.style.color = 'white';
    showToast('success', 'Ditambahkan ke wishlist!');
  } else {
    el.style.background = 'rgba(0,0,0,0.5)';
    el.style.color = 'var(--gray)';
  }
}

// ====== PROFILE TABS ======
function switchProfileTab(tab, btn) {
  document.querySelectorAll('.profile-tab').forEach(t => t.classList.remove('active'));
  btn.classList.add('active');
  document.getElementById('profile-tab-orders').style.display = tab === 'orders' ? 'block' : 'none';
  document.getElementById('profile-tab-settings').style.display = tab === 'settings' ? 'block' : 'none';
}

// ====== TOAST ======
function showToast(type, msg) {
  const icons = { success: 'fa-check-circle', error: 'fa-times-circle', info: 'fa-info-circle' };
  const container = document.getElementById('toast-container');
  const toast = document.createElement('div');
  toast.className = `toast-item ${type}`;
  toast.innerHTML = `<i class="fas ${icons[type] || 'fa-info-circle'}"></i><span class="toast-msg">${msg}</span>`;
  container.appendChild(toast);
  requestAnimationFrame(() => requestAnimationFrame(() => toast.classList.add('show')));
  setTimeout(() => { toast.classList.remove('show'); setTimeout(() => toast.remove(), 400); }, 3500);
}

// ====== AOS ANIMATION ======
function initAOS() {
  const observer = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('aos-animate'); });
  }, { threshold: 0.1 });
  document.querySelectorAll('[data-aos]').forEach(el => observer.observe(el));
}

// ====== PARTICLES ======
function initParticles() {
  const container = document.getElementById('hero-particles');
  if (!container) return;
  for (let i = 0; i < 20; i++) {
    const p = document.createElement('div');
    p.className = 'particle';
    p.style.cssText = `left:${Math.random() * 100}%;width:${Math.random() * 3 + 1}px;height:${Math.random() * 3 + 1}px;animation-duration:${Math.random() * 10 + 8}s;animation-delay:${Math.random() * 5}s;opacity:${Math.random() * 0.6 + 0.2};`;
    container.appendChild(p);
  }
}

// ====== NAVBAR SCROLL ======
window.addEventListener('scroll', () => {
  document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 50);
});

// ====== CLOSE MODALS ON OVERLAY CLICK ======
document.querySelectorAll('.modal-overlay').forEach(overlay => {
  overlay.addEventListener('click', function (e) {
    if (e.target === this) {
      if (this.id === 'modal-auth') closeModal('auth');
      if (this.id === 'modal-crud') closeModal('crud');
      if (this.id === 'modal-video') closeModal('video');
    }
  });
});

// ====== INIT ======
document.addEventListener('DOMContentLoaded', () => {
  renderHomeConcerts();
  initParticles();
  initAOS();
  // Navbar search
  const navSearch = document.querySelector('.nav-search');
  if (navSearch) {
    navSearch.addEventListener('input', function () {
      if (this.value.length > 0) {
        navigate('concerts');
        const concertSearch = document.getElementById('concert-search');
        if (concertSearch) concertSearch.value = this.value;
        filterConcerts();
      }
    });
  }
});
