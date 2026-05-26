
// ====== DATA ======
const concerts = [
  {id:0,title:'Music of the Spheres World Tour',artist:'Coldplay',genre:'Pop/Rock',city:'Jakarta',venue:'GBK Stadium, Jakarta',date:'15 Agustus 2025',time:'19.00 WIB',img:'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=600&q=80',price:750000,type:'internasional',status:'on-sale',desc:'Coldplay kembali menggelar tur dunia epik mereka ke Indonesia. Rasakan pengalaman konser spektakuler dengan laser show, confetti, dan ratusan ribu lampu LED yang akan membuat seluruh GBK Stadium bercahaya. Konser yang tak akan pernah kamu lupakan seumur hidup.',trailerYt:'dQw4w9WgXcQ',bg:'internasional'},
  {id:1,title:'Dunia Batas World Tour',artist:'Noah',genre:'Pop',city:'Bandung',venue:'Trans Studio Bandung',date:'20 September 2025',time:'20.00 WIB',img:'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=600&q=80',price:350000,type:'indonesia',status:'on-sale',desc:'Band legendaris Indonesia, NOAH, hadir dengan tur bertajuk "Dunia Batas" — sebuah perjalanan musikal yang memadukan hits nostalgia dan lagu-lagu terbaru mereka. Ariel dan kawan-kawan akan membawakan set list terbaik sepanjang karir mereka.',trailerYt:'dQw4w9WgXcQ',bg:'indonesia'},
  {id:2,title:'After Hours Til Dawn Tour',artist:'The Weeknd',genre:'R&B',city:'Surabaya',venue:'Gelora Bung Tomo',date:'5 Oktober 2025',time:'20.00 WIB',img:'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=600&q=80',price:1200000,type:'internasional',status:'on-sale',desc:'The Weeknd membawa konser megah "After Hours Til Dawn Tour" ke Surabaya. Dengan produksi panggung senilai jutaan dolar, visual show yang luar biasa, dan setlist hits dari semua era kariernya. Ini adalah konser yang akan masuk dalam sejarah.',trailerYt:'dQw4w9WgXcQ',bg:'internasional'},
  {id:3,title:'Padi Reborn: Back to the Past',artist:'Padi Reborn',genre:'Rock',city:'Jakarta',venue:'Istora Senayan',date:'10 Oktober 2025',time:'19.30 WIB',img:'https://images.unsplash.com/photo-1501612780327-45045538702b?w=600&q=80',price:300000,type:'indonesia',status:'on-sale',desc:'Padi Reborn hadir dengan tur reuni spektakuler membawakan semua hits legendaris mereka dari tahun 90-an hingga 2000-an.',trailerYt:'dQw4w9WgXcQ',bg:'indonesia'},
  {id:4,title:'Eras Tour Indonesia',artist:'Taylor Swift',genre:'Pop',city:'Jakarta',venue:'GBK Stadium, Jakarta',date:'22 November 2025',time:'19.00 WIB',img:'https://images.unsplash.com/photo-1459749411175-04bf5292ceea?w=600&q=80',price:1500000,type:'internasional',status:'on-sale',desc:'Taylor Swift akhirnya membawa Eras Tour ke Indonesia! Rasakan perjalanan luar biasa melintasi semua era musik Taylor Swift dalam satu malam yang tidak akan terlupakan.',trailerYt:'dQw4w9WgXcQ',bg:'internasional'},
  {id:5,title:'Live And Dangerous',artist:'Slank',genre:'Rock',city:'Yogyakarta',venue:'Stadion Kridosono',date:'8 Desember 2025',time:'18.00 WIB',img:'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?w=600&q=80',price:200000,type:'indonesia',status:'sold-out',desc:'Slank, band rock paling ikonik Indonesia, hadir dengan konser bertajuk "Live And Dangerous" — penuh energi dan kegilaan khas Slankers.',trailerYt:'dQw4w9WgXcQ',bg:'indonesia'},
  {id:6,title:'RENAISSANCE World Tour',artist:'Beyoncé',genre:'R&B/Pop',city:'Bali',venue:'Garuda Wisnu Kencana',date:'14 Januari 2026',time:'20.00 WIB',img:'https://images.unsplash.com/photo-1547153760-18fc86324498?w=600&q=80',price:2000000,type:'internasional',status:'on-sale',desc:'Queen Bey menghadirkan konser paling spektakuler tahun 2026 di Bali. RENAISSANCE World Tour akan menjadi pengalaman audio-visual yang tiada duanya.',trailerYt:'dQw4w9WgXcQ',bg:'internasional'},
  {id:7,title:'Dewa 19 Reunion',artist:'Dewa 19',genre:'Rock/Pop',city:'Jakarta',venue:'Istora Senayan',date:'20 Februari 2026',time:'19.00 WIB',img:'https://images.unsplash.com/photo-1514320291840-2e0a9bf2a9ae?w=600&q=80',price:450000,type:'indonesia',status:'on-sale',desc:'Dewa 19 kembali bersatu untuk konser reunion yang sudah ditunggu-tunggu jutaan penggemar. Ahmad Dhani dan Ahmad Band akan tampil bersama dalam satu panggung!',trailerYt:'dQw4w9WgXcQ',bg:'indonesia'},
];

const adminUsers = [
  {id:1,name:'Adil Muslim Saputra',email:'adil@uhamka.ac.id',role:'admin',status:'active',joined:'Jan 2025'},
  {id:2,name:'Hafidah Ar\'ba Sabrina',email:'hafidah@gmail.com',role:'user',status:'active',joined:'Feb 2025'},
  {id:3,name:'Syifa Nur Fauziah',email:'syifa@gmail.com',role:'user',status:'active',joined:'Feb 2025'},
  {id:4,name:'Muhammad Nabil',email:'nabil@gmail.com',role:'user',status:'inactive',joined:'Mar 2025'},
  {id:5,name:'Munggar Fajar',email:'munggar@gmail.com',role:'user',status:'active',joined:'Mar 2025'},
];

const transactions = [
  {id:'TRX-001',user:'Adil Muslim',concert:'Coldplay — Music of the Spheres',qty:2,total:3010000,status:'success',date:'10 Jul 2025'},
  {id:'TRX-002',user:'Hafidah Sabrina',concert:'Noah — Dunia Batas',qty:1,total:510000,status:'pending',date:'11 Jul 2025'},
  {id:'TRX-003',user:'Syifa Fauziah',concert:'Taylor Swift — Eras Tour',qty:3,total:4510000,status:'success',date:'12 Jul 2025'},
  {id:'TRX-004',user:'Muhammad Nabil',concert:'The Weeknd — After Hours',qty:2,total:2410000,status:'success',date:'13 Jul 2025'},
  {id:'TRX-005',user:'Munggar Fajar',concert:'Dewa 19 Reunion',qty:4,total:1810000,status:'pending',date:'14 Jul 2025'},
];

// App State
let currentPage = 'home';
let currentUser = null;
let selectedConcertId = 0;
let selectedCategory = null;
let selectedQty = 1;
let selectedCategoryPrice = 0;

// ====== API FUNCTIONS FOR KONSER CONTROLLER ======
async function loadKonsersFromAPI() {
  try {
    const response = await fetch('/api/konsers');
    if (!response.ok) throw new Error('Failed to load konsers');
    const data = await response.json();
    // Transform API data to match concert card format
    return data.map(k => ({
      id: k.id,
      title: k.title,
      artist: k.artist,
      genre: k.genre || 'Pop',
      city: k.city,
      venue: k.venue,
      date: new Date(k.date).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }),
      time: k.time + ' WIB',
      img: k.image || 'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=600&q=80',
      price: parseInt(k.price),
      type: k.type || 'lokal',
      status: k.status === 'published' ? 'on-sale' : 'draft',
      desc: k.description || '',
      trailerYt: 'dQw4w9WgXcQ',
      bg: k.type === 'internasional' ? 'internasional' : 'indonesia'
    }));
  } catch (error) {
    console.error('Error loading konsers:', error);
    showToast('error', 'Gagal memuat data konser');
    return concerts; // Fallback to mock data
  }
}

async function saveKonserToAPI(formData, isEdit = false, konserID = null) {
  try {
    const dateInput = document.getElementById('cf-date').value;
    const timeInput = document.getElementById('cf-time').value;

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
      status: document.querySelector('input[name="cf-status"]:checked').value === 'on-sale' ? 'published' : document.querySelector('input[name="cf-status"]:checked').value,
      type: 'lokal', // Default, can be updated
      capacity: 1000
    };

    const method = isEdit ? 'PUT' : 'POST';
    const url = isEdit ? `/api/konsers/${konserID}` : '/api/konsers';

    const response = await fetch(url, {
      method: method,
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
      },
      body: JSON.stringify(payload)
    });

    if (!response.ok) {
      const error = await response.json();
      throw new Error(error.message || 'Failed to save konser');
    }

    const result = await response.json();
    return result;
  } catch (error) {
    console.error('Error saving konser:', error);
    throw error;
  }
}

async function deleteKonserFromAPI(konserID) {
  try {
    const response = await fetch(`/api/konsers/${konserID}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
      }
    });

    if (!response.ok) throw new Error('Failed to delete konser');
    return await response.json();
  } catch (error) {
    console.error('Error deleting konser:', error);
    throw error;
  }
}

// ====== NAVIGATION ======
function navigate(page){
  document.querySelectorAll('.page').forEach(p=>p.classList.remove('active'));
  document.querySelectorAll('.nav-link').forEach(l=>l.classList.remove('active'));
  const el = document.getElementById('page-'+page);
  if(el) el.classList.add('active');
  const nl = document.getElementById('nl-'+page);
  if(nl) nl.classList.add('active');
  currentPage = page;
  window.scrollTo(0,0);
  if(page==='home') renderHomeConcerts();
  if(page==='concerts') renderConcertsPage();
  if(page==='artists') renderArtistsPage();
  if(page==='gallery') renderGallery();
  if(page==='admin') initAdmin();
}

// ====== RENDER CONCERTS ======
function concertCardHTML(c,i){
  const badge = c.status==='sold-out' ? '<span class="concert-card-badge sold-out">SOLD OUT</span>' : '<span class="concert-card-badge">ON SALE</span>';
  return `<div class="concert-card" onclick="openConcertDetail(${c.id})" data-artist="${c.artist.toLowerCase()}" data-genre="${c.genre.toLowerCase()}" data-city="${c.city.toLowerCase()}" data-type="${c.type}" data-price="${c.price}" data-aos style="animation-delay:${i*0.08}s">
    <div class="concert-card-img">
      <img src="${c.img}" alt="${c.artist}" loading="lazy">
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
          <div class="concert-price">Rp <span>${(c.price/1000).toFixed(0)}</span>.000</div>
        </div>
        <button class="btn-book ${c.status==='sold-out'?'disabled':''}">${c.status==='sold-out'?'HABIS':'BELI'}</button>
      </div>
    </div>
  </div>`;
}

function renderHomeConcerts(){
  const g = document.getElementById('home-concerts-grid');
  if(!g) return;

  // Load from API
  loadKonsersFromAPI().then(konsersData => {
    g.innerHTML = konsersData.slice(0,4).map((c,i)=>concertCardHTML(c,i)).join('');
    initAOS();
  }).catch(error => {
    // Fallback to mock data if API fails
    g.innerHTML = concerts.slice(0,4).map((c,i)=>concertCardHTML(c,i)).join('');
    initAOS();
  });

  const a = document.getElementById('home-artists');
  if(a) a.innerHTML = artists.map(ar=>`
    <div class="artist-card" onclick="navigate('artists')">
      <img class="artist-card-img" src="${ar.img}" alt="${ar.name}">
      <div class="artist-card-name">${ar.name}</div>
      <div class="artist-card-genre">${ar.genre}</div>
    </div>`).join('');
}

function renderConcertsPage(){
  const g = document.getElementById('concerts-page-grid');
  if(!g) return;

  // Load from API
  loadKonsersFromAPI().then(konsersData => {
    g.innerHTML = konsersData.map((c,i)=>concertCardHTML(c,i)).join('');
    initAOS();
  }).catch(error => {
    // Fallback to mock data if API fails
    g.innerHTML = concerts.map((c,i)=>concertCardHTML(c,i)).join('');
    initAOS();
  });
}

function filterConcerts(typeFilter){
  const search = (document.getElementById('concert-search')||{}).value||'';
  const genre = (document.getElementById('filter-genre')||{}).value||'';
  const city = (document.getElementById('filter-city')||{}).value||'';
  const sort = (document.getElementById('filter-sort')||{}).value||'date';
  let filtered = concerts.filter(c=>{
    const matchSearch = !search || c.artist.toLowerCase().includes(search.toLowerCase()) || c.title.toLowerCase().includes(search.toLowerCase());
    const matchGenre = !genre || c.genre.toLowerCase().includes(genre.toLowerCase());
    const matchCity = !city || c.city===city;
    const matchType = !typeFilter || typeFilter==='all' || c.type===typeFilter;
    return matchSearch && matchGenre && matchCity && matchType;
  });
  if(sort==='price-asc') filtered.sort((a,b)=>a.price-b.price);
  if(sort==='price-desc') filtered.sort((a,b)=>b.price-a.price);
  const g = document.getElementById('concerts-page-grid');
  if(g){
    if(!filtered.length){
      g.innerHTML='<div style="padding:60px;text-align:center;color:var(--gray);grid-column:1/-1;"><i class="fas fa-search" style="font-size:36px;margin-bottom:16px;display:block;opacity:0.3;"></i>Tidak ada konser ditemukan</div>';
    } else {
      g.innerHTML = filtered.map((c,i)=>concertCardHTML(c,i)).join('');
    }
  }
  initAOS();
}

// ====== CONCERT DETAIL ======
function openConcertDetail(id){
  const c = concerts[id];
  selectedConcertId = id;
  document.getElementById('detail-bg-img').src = c.img;
  document.getElementById('detail-badge').textContent = c.genre.toUpperCase();
  document.getElementById('detail-title').textContent = c.title;
  document.getElementById('detail-artist').querySelector('span').textContent = c.artist;
  document.getElementById('detail-date').textContent = c.date;
  document.getElementById('detail-venue').textContent = c.venue;
  document.getElementById('detail-time').textContent = c.time;
  document.getElementById('detail-description').textContent = c.desc;
  document.getElementById('detail-lineup').innerHTML = [c.artist,'Special Guest','Opening Act 1','Opening Act 2'].map((name,i)=>`
    <div class="lineup-item">
      <div class="lineup-num">0${i+1}</div>
      <div class="lineup-info"><h4>${name}</h4><p>${i===0?'Headliner':'Supporting Artist'}</p></div>
      ${i===0?'<span class="status-badge status-on-sale">HEADLINER</span>':''}
    </div>`).join('');
  const galleryImgs = ['https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=300&q=80',
    'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=300&q=80',
    'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=300&q=80',
    'https://images.unsplash.com/photo-1501612780327-45045538702b?w=300&q=80'];
  document.getElementById('detail-gallery').innerHTML = galleryImgs.map(g=>`<div class="gallery-img"><img src="${g}" alt="gallery" loading="lazy"></div>`).join('');
  document.getElementById('detail-trailer-thumb').src = c.img;
  document.getElementById('detail-trailer-title').textContent = c.artist+' — Official Trailer';
  const cats = document.getElementById('ticket-categories');
  cats.innerHTML = ticketCategories.map(t=>`
    <div class="ticket-category" onclick="selectTicketCat(this,'${t.name}',${t.price})">
      <div>
        <div class="ticket-cat-name">${t.name}</div>
        <div class="ticket-cat-stock" style="font-size:11px;color:var(--gray);">${t.stock} sisa</div>
      </div>
      <div class="ticket-cat-price">Rp ${(t.price/1000).toFixed(0)}K</div>
    </div>`).join('');
  selectTicketCat(cats.firstElementChild, ticketCategories[0].name, ticketCategories[0].price);
  renderSeatMap();
  navigate('detail');
  document.getElementById('nl-concerts').classList.add('active');
}

function selectTicketCat(el, name, price){
  document.querySelectorAll('.ticket-category').forEach(t=>t.classList.remove('selected'));
  el.classList.add('selected');
  selectedCategory = name;
  selectedCategoryPrice = price;
  updateBookingSummary();
}

function changeQty(delta){
  selectedQty = Math.max(1, Math.min(10, selectedQty + delta));
  document.getElementById('qty-display').textContent = selectedQty;
  updateBookingSummary();
}

function updateBookingSummary(){
  const total = selectedCategoryPrice * selectedQty + 10000;
  document.getElementById('s-price').textContent = 'Rp '+selectedCategoryPrice.toLocaleString('id-ID');
  document.getElementById('s-qty').textContent = 'x'+selectedQty;
  document.getElementById('s-total').textContent = 'Rp '+total.toLocaleString('id-ID');
}

function renderSeatMap(){
  const rows = ['A','B','C','D','E'];
  let html = '';
  rows.forEach(row=>{
    html += '<div class="seat-row">';
    for(let i=1;i<=10;i++){
      const sold = Math.random()>0.7;
      html += `<div class="seat ${sold?'sold':''}" onclick="${sold?'':(`selectSeat(this,'${row}${i}')`)}"></div>`;
    }
    html += '</div>';
  });
  document.getElementById('seat-map-rows').innerHTML = html;
}

function selectSeat(el){
  el.classList.toggle('selected');
}

function goCheckout(){
  if(!currentUser){openModal('login');showToast('error','Silakan login terlebih dahulu');return;}
  const c = concerts[selectedConcertId];
  document.getElementById('checkout-img').src = c.img;
  document.getElementById('checkout-title').textContent = c.artist+' — '+c.title;
  document.getElementById('checkout-meta').textContent = c.date+' · '+c.venue;
  document.getElementById('checkout-cat').textContent = selectedCategory+' × '+selectedQty;
  const unit = selectedCategoryPrice;
  const sub = unit * selectedQty;
  const total = sub + 10000;
  document.getElementById('co-unit').textContent = 'Rp '+unit.toLocaleString('id-ID');
  document.getElementById('co-qty').textContent = selectedQty;
  document.getElementById('co-sub').textContent = 'Rp '+sub.toLocaleString('id-ID');
  document.getElementById('co-total').textContent = 'Rp '+total.toLocaleString('id-ID');
  navigate('checkout');
}

// ====== ARTISTS PAGE ======
function renderArtistsPage(){
  filterArtists();
}
function filterArtists(){
  const search = (document.getElementById('artist-search')||{}).value||'';
  const g = document.getElementById('artists-grid');
  if(!g) return;
  const filtered = artists.filter(a=> !search || a.name.toLowerCase().includes(search.toLowerCase()));
  g.innerHTML = filtered.map(a=>`
    <div class="artist-full-card">
      <img class="artist-full-img" src="${a.img}" alt="${a.name}" loading="lazy">
      <div class="artist-full-name">${a.name}</div>
      <div class="artist-full-genre">${a.genre}</div>
      <div class="artist-full-concerts"><i class="fas fa-music" style="margin-right:4px;"></i>${a.concerts} konser</div>
      <button class="btn-book" style="margin-top:12px;" onclick="event.stopPropagation();navigate('concerts')">Lihat Konser</button>
    </div>`).join('');
}
function setArtistFilter(val,btn){
  document.querySelectorAll('.filter-btn').forEach(b=>{if(b.onclick&&b.onclick.toString().includes('setArtistFilter'))b.classList.remove('active');});
  btn.classList.add('active');
  const g = document.getElementById('artists-grid');
  if(!g) return;
  const filtered = val==='all' ? artists : artists.filter(a=>a.country===val);
  g.innerHTML = filtered.map(a=>`
    <div class="artist-full-card">
      <img class="artist-full-img" src="${a.img}" alt="${a.name}" loading="lazy">
      <div class="artist-full-name">${a.name}</div>
      <div class="artist-full-genre">${a.genre}</div>
      <div class="artist-full-concerts"><i class="fas fa-music" style="margin-right:4px;"></i>${a.concerts} konser</div>
      <button class="btn-book" style="margin-top:12px;">Lihat Konser</button>
    </div>`).join('');
}

// ====== GALLERY PAGE ======
function renderGallery(){
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
  if(g) g.innerHTML = galleryImgs.map(img=>`
    <div style="break-inside:avoid;margin-bottom:16px;border-radius:var(--radius);overflow:hidden;cursor:pointer;transition:var(--transition);"
         onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
      <img src="${img}" style="width:100%;display:block;" loading="lazy">
    </div>`).join('');
}

// ====== ADMIN ======
function initAdmin(){
  document.getElementById('admin-date').textContent = new Date().toLocaleDateString('id-ID',{weekday:'long',year:'numeric',month:'long',day:'numeric'});
  switchAdmin('admin');
}

function switchAdmin(section){
  ['admin','concerts','artists','tickets','transactions','users'].forEach(s=>{
    const el = document.getElementById('admin-section-'+s);
    const nav = document.getElementById('adm-'+s);
    if(el) el.style.display = s===section ? 'block' : 'none';
    if(nav) nav.classList.toggle('active', s===section);
  });
  if(section==='admin') buildAdminDashboard();
  if(section==='concerts') buildConcertsTable();
  if(section==='artists') buildArtistsTable();
  if(section==='tickets') buildTicketsTable();
  if(section==='transactions') buildTransactionsTable();
  if(section==='users') buildUsersTable();
}

function buildAdminDashboard(){
  const chart = document.getElementById('admin-chart');
  if(!chart) return;
  const data = [145,210,180,265,220,310,285];
  const days = ['Sen','Sel','Rab','Kam','Jum','Sab','Min'];
  const max = Math.max(...data);
  chart.innerHTML = data.map((v,i)=>`
    <div class="chart-bar-wrap">
      <div class="chart-bar" style="height:${(v/max*140).toFixed(0)}px;" title="${v} tiket"></div>
      <div class="chart-bar-label">${days[i]}</div>
    </div>`).join('');
  const tx = document.getElementById('recent-tx-table');
  if(tx) tx.innerHTML = `<thead><tr><th>ID</th><th>User</th><th>Konser</th><th>Total</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>${transactions.slice(0,5).map(t=>`
    <tr><td><code style="color:var(--red);font-size:12px;">${t.id}</code></td>
    <td>${t.user}</td>
    <td style="color:var(--gray);font-size:13px;">${t.concert}</td>
    <td><strong>Rp ${t.total.toLocaleString('id-ID')}</strong></td>
    <td><span class="status-badge status-${t.status}">${t.status.toUpperCase()}</span></td>
    <td><div class="td-actions"><button class="btn-view" onclick="showToast('info','Detail transaksi ${t.id}')"><i class="fas fa-eye"></i></button></div></td>
    </tr>`).join('')}</tbody>`;
}

function buildConcertsTable(){
  const t = document.getElementById('admin-concerts-table');
  if(!t) return;

  // Load from API
  loadKonsersFromAPI().then(konsersData => {
    t.innerHTML = `<thead><tr><th>#</th><th>Poster</th><th>Konser</th><th>Artis</th><th>Tanggal</th><th>Kota</th><th>Harga</th><th>Status</th><th>Aksi</th></tr></thead>
      <tbody>${konsersData.map((c,i)=>`
      <tr>
        <td>${i+1}</td>
        <td><img class="concert-thumb" src="${c.img}" alt=""></td>
        <td><div class="td-name">${c.title}</div></td>
        <td class="td-artist">${c.artist}</td>
        <td style="font-size:13px;">${c.date}</td>
        <td>${c.city}</td>
        <td>Rp ${(c.price/1000).toFixed(0)}K</td>
        <td><span class="status-badge ${c.status==='on-sale'?'status-on-sale':'status-sold-out'}">${c.status==='on-sale'?'ON SALE':'SOLD OUT'}</span></td>
        <td><div class="td-actions">
          <a class="btn-edit" href="/api/konsers/${c.id}/edit"><i class="fas fa-edit"></i></a>
          <button class="btn-del" onclick="deleteConcert(${c.id},this)"><i class="fas fa-trash"></i></button>
          <button class="btn-view" onclick="openConcertDetail(${c.id})"><i class="fas fa-eye"></i></button>
        </div></td>
      </tr>`).join('')}</tbody>`;
  }).catch(error => {
    console.error('Failed to build concerts table:', error);
    t.innerHTML = '<tr><td colspan="9" style="text-align:center;color:var(--gray);">Gagal memuat data konser</td></tr>';
  });
}

function buildTransactionsTable(){
  const t = document.getElementById('admin-transactions-table');
  if(!t) return;
  t.innerHTML = `<thead><tr><th>ID</th><th>User</th><th>Konser</th><th>Qty</th><th>Total</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr></thead>
    <tbody>${transactions.map(tx=>`
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
}

function editConcert(id){
  openCrudModal('concert', concerts[id]);
}
function deleteConcert(id,btn){
  const row = btn.closest('tr');
  deleteKonserFromAPI(id).then(result => {
    row.style.opacity='0';
    row.style.transition='opacity 0.3s';
    setTimeout(()=>row.remove(),300);
    showToast('success','Konser berhasil dihapus');
  }).catch(error => {
    showToast('error', error.message || 'Gagal menghapus konser');
  });
}
function deleteRow(btn){
  const row = btn.closest('tr');
  row.style.opacity='0';row.style.transition='opacity 0.3s';
  setTimeout(()=>row.remove(),300);
  showToast('success','Data berhasil dihapus');
}
function filterAdminTable(input,tableId){
  const val = input.value.toLowerCase();
  const rows = document.querySelectorAll('#'+tableId+' tbody tr');
  rows.forEach(r=>{r.style.display=r.textContent.toLowerCase().includes(val)?'':'none';});
}

// ====== CRUD MODALS ======
const crudForms = {
  concert: `
    <div class="form-group"><label class="form-label">Nama Konser/Tour</label><input class="form-input" type="text" id="cf-title" placeholder="Music of the Spheres..."></div>
    <div class="form-row">
      <div class="form-group"><label class="form-label">Artis</label>
        <select class="form-input" id="cf-artist">${artists.map(a=>`<option>${a.name}</option>`).join('')}</select>
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
    <div class="form-group"><label class="form-label">Konser</label><select class="form-input">${concerts.map(c=>`<option>${c.artist} — ${c.title}</option>`).join('')}</select></div>
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

function openCrudModal(type, data){
  const titles = {concert:'TAMBAH KONSER',artist:'TAMBAH ARTIS',ticket:'TAMBAH TIKET KATEGORI',user:'TAMBAH USER'};
  document.getElementById('crud-modal-title').textContent = data ? 'EDIT '+type.toUpperCase() : titles[type];
  document.getElementById('crud-modal-body').innerHTML = crudForms[type] || '';
  if(data && type==='concert'){
    setTimeout(()=>{
      if(document.getElementById('cf-title')) document.getElementById('cf-title').value = data.title;
    },50);
  }
  document.getElementById('modal-crud').classList.add('show');
}

function saveCrudForm(type){
  closeModal('crud');
  if(type === 'concert') {
    saveKonserToAPI(null, false).then(result => {
      showToast('success', 'Konser berhasil disimpan!');
      setTimeout(() => buildConcertsTable(), 300);
    }).catch(error => {
      showToast('error', error.message || 'Gagal menyimpan konser');
    });
  } else {
    showToast('success', type.charAt(0).toUpperCase()+type.slice(1)+' berhasil disimpan!');
    if(type==='concert') setTimeout(()=>buildConcertsTable(),300);
  }
}

// ====== AUTH ======
function openModal(type){
  if(type==='login' || type==='register'){
    document.getElementById('modal-auth').classList.add('show');
    switchAuthTab(type);
  } else if(type==='editProfile'){
    showToast('info','Edit profil tersedia di tab Pengaturan Akun');
  }
}
function closeModal(type){
  if(type==='auth') document.getElementById('modal-auth').classList.remove('show');
  if(type==='crud') document.getElementById('modal-crud').classList.remove('show');
  if(type==='video'){
    document.getElementById('modal-video').classList.remove('show');
    document.getElementById('yt-iframe').src='';
  }
}
function switchAuthTab(tab){
  document.getElementById('tab-login').classList.toggle('active',tab==='login');
  document.getElementById('tab-register').classList.toggle('active',tab==='register');
  document.getElementById('auth-login-form').style.display = tab==='login'?'block':'none';
  document.getElementById('auth-register-form').style.display = tab==='register'?'block':'none';
}

function loginUser(){
  const email = document.getElementById('login-email').value;
  const pass = document.getElementById('login-password').value;
  if(!email || !pass){showToast('error','Email dan password harus diisi');return;}
  currentUser = {name:'Adil Muslim',email:email,avatar:'A'};
  document.getElementById('nav-auth-btns').style.display='none';
  document.getElementById('nav-user').style.display='block';
  document.getElementById('nav-avatar-initial').textContent = currentUser.name.charAt(0).toUpperCase();
  closeModal('auth');
  showToast('success','Selamat datang, '+currentUser.name+'!');
}

function loginDemo(){
  document.getElementById('login-email').value='adil@uhamka.ac.id';
  document.getElementById('login-password').value='primestage123';
  loginUser();
}

function registerUser(){
  const email = document.getElementById('reg-email').value;
  const pass = document.getElementById('reg-password').value;
  const first = document.getElementById('reg-firstname').value;
  if(!email || !pass || !first){showToast('error','Harap isi semua field yang diperlukan');return;}
  closeModal('auth');
  showToast('success','Akun berhasil dibuat! Silakan masuk.');
  setTimeout(()=>openModal('login'),1000);
}

function logout(){
  currentUser = null;
  document.getElementById('nav-auth-btns').style.display='flex';
  document.getElementById('nav-user').style.display='none';
  navigate('home');
  showToast('info','Anda telah keluar');
}

// ====== CHECKOUT ======
function selectPayment(input){
  document.querySelectorAll('[id^="pay-"]').forEach(el=>el.classList.remove('selected'));
  document.getElementById('pay-'+input.value).classList.add('selected');
}
function applyPromo(){
  const code = document.getElementById('promo-code').value;
  if(code.toUpperCase()==='PRIMESTAGE20'){
    document.getElementById('co-discount-row').style.display='flex';
    document.getElementById('co-disc').textContent='-Rp 150.000';
    showToast('success','Promo PRIMESTAGE20 berhasil digunakan! Diskon Rp 150.000');
  } else {
    showToast('error','Kode promo tidak valid');
  }
}
function submitOrder(){
  if(!document.getElementById('agree-tos').checked){showToast('error','Harap setujui syarat & ketentuan');return;}
  showToast('success','Pemesanan berhasil! Cek email untuk tiket Anda.');
  setTimeout(()=>navigate('profile'),1500);
}

// ====== VIDEO MODAL ======
function openVideoModal(type){
  const ytIds = {coldplay:'YkgkThdzX-8',noah:'wk39snYWQdc',weeknd:'XXYlFuWEuKI'};
  const ytId = ytIds[type] || 'YkgkThdzX-8';
  document.getElementById('yt-iframe').src = `https://www.youtube.com/embed/${ytId}?autoplay=1`;
  document.getElementById('video-modal-title').textContent = type ? type.charAt(0).toUpperCase()+type.slice(1)+' — Official Trailer' : 'Concert Trailer';
  document.getElementById('modal-video').classList.add('show');
}

// ====== WISHLIST ======
function toggleWishlist(el){
  const icon = el.querySelector('i');
  icon.classList.toggle('far');
  icon.classList.toggle('fas');
  if(icon.classList.contains('fas')){
    el.style.background='var(--red)';
    el.style.color='white';
    showToast('success','Ditambahkan ke wishlist!');
  } else {
    el.style.background='rgba(0,0,0,0.5)';
    el.style.color='var(--gray)';
  }
}

// ====== PROFILE TABS ======
function switchProfileTab(tab, btn){
  document.querySelectorAll('.profile-tab').forEach(t=>t.classList.remove('active'));
  btn.classList.add('active');
  document.getElementById('profile-tab-orders').style.display = tab==='orders'?'block':'none';
  document.getElementById('profile-tab-settings').style.display = tab==='settings'?'block':'none';
}

// ====== TOAST ======
function showToast(type, msg){
  const icons = {success:'fa-check-circle',error:'fa-times-circle',info:'fa-info-circle'};
  const container = document.getElementById('toast-container');
  const toast = document.createElement('div');
  toast.className = `toast-item ${type}`;
  toast.innerHTML = `<i class="fas ${icons[type]||'fa-info-circle'}"></i><span class="toast-msg">${msg}</span>`;
  container.appendChild(toast);
  requestAnimationFrame(()=>requestAnimationFrame(()=>toast.classList.add('show')));
  setTimeout(()=>{toast.classList.remove('show');setTimeout(()=>toast.remove(),400);},3500);
}

// ====== AOS ANIMATION ======
function initAOS(){
  const observer = new IntersectionObserver(entries=>{
    entries.forEach(e=>{if(e.isIntersecting) e.target.classList.add('aos-animate');});
  },{threshold:0.1});
  document.querySelectorAll('[data-aos]').forEach(el=>observer.observe(el));
}

// ====== PARTICLES ======
function initParticles(){
  const container = document.getElementById('hero-particles');
  if(!container) return;
  for(let i=0;i<20;i++){
    const p = document.createElement('div');
    p.className='particle';
    p.style.cssText=`left:${Math.random()*100}%;width:${Math.random()*3+1}px;height:${Math.random()*3+1}px;animation-duration:${Math.random()*10+8}s;animation-delay:${Math.random()*5}s;opacity:${Math.random()*0.6+0.2};`;
    container.appendChild(p);
  }
}

// ====== NAVBAR SCROLL ======
window.addEventListener('scroll',()=>{
  document.getElementById('navbar').classList.toggle('scrolled',window.scrollY>50);
});

// ====== CLOSE MODALS ON OVERLAY CLICK ======
document.querySelectorAll('.modal-overlay').forEach(overlay=>{
  overlay.addEventListener('click',function(e){
    if(e.target===this){
      if(this.id==='modal-auth') closeModal('auth');
      if(this.id==='modal-crud') closeModal('crud');
      if(this.id==='modal-video') closeModal('video');
    }
  });
});

// ====== INIT ======
document.addEventListener('DOMContentLoaded',()=>{
  renderHomeConcerts();
  initParticles();
  initAOS();
  // Navbar search
  document.querySelector('.nav-search').addEventListener('input',function(){
    if(this.value.length>0){navigate('concerts');document.getElementById('concert-search').value=this.value;filterConcerts();}
  });
});
