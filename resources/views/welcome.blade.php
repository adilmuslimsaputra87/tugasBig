<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PrimeStage — Premium Concert Experience</title>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
:root {
  --bg: #0F0F0F;
  --bg2: #1A1A1A;
  --bg3: #242424;
  --red: #E50914;
  --red2: #B20710;
  --red3: #FF1F2E;
  --white: #FFFFFF;
  --gray: #999999;
  --gray2: #666666;
  --border: rgba(255,255,255,0.08);
  --card: rgba(26,26,26,0.95);
  --glass: rgba(255,255,255,0.04);
  --shadow: 0 8px 32px rgba(0,0,0,0.6);
  --shadow-red: 0 0 30px rgba(229,9,20,0.3);
  --font-head: 'Bebas Neue', sans-serif;
  --font-body: 'Poppins', sans-serif;
  --transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
  --radius: 12px;
  --radius-sm: 8px;
}
*{margin:0;padding:0;box-sizing:border-box;}
html{scroll-behavior:smooth;}
body{background:var(--bg);color:var(--white);font-family:var(--font-body);overflow-x:hidden;}
a{text-decoration:none;color:inherit;}
img{max-width:100%;}
button{cursor:pointer;border:none;font-family:var(--font-body);}
input,select,textarea{font-family:var(--font-body);}
::-webkit-scrollbar{width:6px;}
::-webkit-scrollbar-track{background:var(--bg);}
::-webkit-scrollbar-thumb{background:var(--red);border-radius:3px;}
.page{display:none;}
.page.active{display:block;}

/* ====== NAVBAR ====== */
#navbar{
  position:fixed;top:0;left:0;right:0;z-index:1000;
  padding:0 5%;height:70px;display:flex;align-items:center;justify-content:space-between;
  background:linear-gradient(to bottom,rgba(15,15,15,0.98) 0%,transparent 100%);
  transition:var(--transition);
}
#navbar.scrolled{background:rgba(15,15,15,0.98);border-bottom:1px solid var(--border);}
.nav-logo{display:flex;align-items:center;gap:10px;cursor:pointer;}
.nav-logo img{width:36px;height:36px;border-radius:8px;}
.nav-logo-text{font-family:var(--font-head);font-size:26px;letter-spacing:2px;color:var(--white);}
.nav-logo-text span{color:var(--red);}
.nav-menu{display:flex;align-items:center;gap:28px;}
.nav-link{font-size:14px;font-weight:500;color:var(--gray);transition:var(--transition);cursor:pointer;position:relative;padding-bottom:4px;}
.nav-link::after{content:'';position:absolute;bottom:0;left:0;width:0;height:2px;background:var(--red);transition:var(--transition);}
.nav-link:hover,.nav-link.active{color:var(--white);}
.nav-link:hover::after,.nav-link.active::after{width:100%;}
.nav-right{display:flex;align-items:center;gap:16px;}
.nav-search-wrap{position:relative;}
.nav-search{background:var(--glass);border:1px solid var(--border);border-radius:20px;padding:8px 16px 8px 40px;color:var(--white);font-size:13px;width:200px;transition:var(--transition);}
.nav-search:focus{outline:none;border-color:var(--red);width:240px;background:var(--bg2);}
.nav-search-icon{position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--gray);font-size:13px;}
.btn-login{background:transparent;border:1px solid var(--border);color:var(--white);padding:8px 20px;border-radius:20px;font-size:13px;font-weight:500;transition:var(--transition);}
.btn-login:hover{border-color:var(--white);}
.btn-signup{background:var(--red);color:var(--white);padding:8px 20px;border-radius:20px;font-size:13px;font-weight:600;transition:var(--transition);}
.btn-signup:hover{background:var(--red2);transform:translateY(-1px);}
.nav-avatar{width:36px;height:36px;border-radius:50%;background:var(--red);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;cursor:pointer;position:relative;}
.nav-dropdown{position:absolute;top:50px;right:0;background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius);width:180px;overflow:hidden;display:none;box-shadow:var(--shadow);}
.nav-avatar:hover .nav-dropdown{display:block;}
.nav-dd-item{padding:12px 16px;font-size:13px;cursor:pointer;transition:var(--transition);display:flex;align-items:center;gap:10px;}
.nav-dd-item:hover{background:var(--bg3);color:var(--red);}

/* ====== HERO ====== */
.hero{min-height:100vh;position:relative;display:flex;align-items:center;overflow:hidden;}
.hero-bg{position:absolute;inset:0;background:
  radial-gradient(ellipse at 70% 50%,rgba(229,9,20,0.15) 0%,transparent 60%),
  radial-gradient(ellipse at 20% 80%,rgba(229,9,20,0.08) 0%,transparent 50%),
  linear-gradient(135deg,#0F0F0F 0%,#1a0505 100%);
}
.hero-particles{position:absolute;inset:0;overflow:hidden;}
.particle{position:absolute;width:2px;height:2px;background:var(--red);border-radius:50%;animation:float linear infinite;}
@keyframes float{0%{transform:translateY(100vh) translateX(0);opacity:0;}10%{opacity:1;}90%{opacity:1;}100%{transform:translateY(-100px) translateX(50px);opacity:0;}}
.hero-grid{position:absolute;inset:0;background-image:linear-gradient(rgba(229,9,20,0.05) 1px,transparent 1px),linear-gradient(90deg,rgba(229,9,20,0.05) 1px,transparent 1px);background-size:60px 60px;}
.hero-content{position:relative;z-index:2;padding:0 5%;max-width:700px;margin-top:70px;animation:heroIn 1s ease-out;}
@keyframes heroIn{from{opacity:0;transform:translateY(40px);}to{opacity:1;transform:translateY(0);}}
.hero-badge{display:inline-flex;align-items:center;gap:8px;background:rgba(229,9,20,0.15);border:1px solid rgba(229,9,20,0.3);padding:6px 14px;border-radius:20px;font-size:12px;color:var(--red);font-weight:600;margin-bottom:24px;letter-spacing:1px;}
.hero-badge i{font-size:10px;animation:pulse 1.5s infinite;}
@keyframes pulse{0%,100%{opacity:1;}50%{opacity:0.4;}}
.hero-title{font-family:var(--font-head);font-size:clamp(52px,8vw,100px);line-height:0.95;letter-spacing:3px;margin-bottom:20px;}
.hero-title .line2{color:var(--red);display:block;-webkit-text-stroke:2px var(--red);}
.hero-subtitle{font-size:16px;color:var(--gray);line-height:1.7;max-width:500px;margin-bottom:36px;}
.hero-cta{display:flex;align-items:center;gap:16px;flex-wrap:wrap;}
.btn-primary{display:inline-flex;align-items:center;gap:10px;background:var(--red);color:var(--white);padding:14px 32px;border-radius:4px;font-size:15px;font-weight:700;letter-spacing:1px;text-transform:uppercase;transition:var(--transition);position:relative;overflow:hidden;}
.btn-primary::before{content:'';position:absolute;inset:0;background:linear-gradient(45deg,transparent,rgba(255,255,255,0.1),transparent);transform:translateX(-100%);transition:0.5s;}
.btn-primary:hover{background:var(--red2);transform:translateY(-2px);box-shadow:var(--shadow-red);}
.btn-primary:hover::before{transform:translateX(100%);}
.btn-outline{display:inline-flex;align-items:center;gap:10px;background:transparent;color:var(--white);padding:13px 32px;border-radius:4px;font-size:15px;font-weight:600;border:2px solid rgba(255,255,255,0.3);transition:var(--transition);}
.btn-outline:hover{border-color:var(--white);background:rgba(255,255,255,0.05);}
.hero-stats{display:flex;gap:40px;margin-top:60px;padding-top:40px;border-top:1px solid var(--border);}
.stat{text-align:left;}
.stat-num{font-family:var(--font-head);font-size:36px;color:var(--white);letter-spacing:2px;}
.stat-label{font-size:12px;color:var(--gray);text-transform:uppercase;letter-spacing:1px;}
.hero-featured{position:absolute;right:5%;top:50%;transform:translateY(-50%);z-index:2;display:flex;flex-direction:column;gap:16px;animation:heroRightIn 1.2s ease-out;}
@keyframes heroRightIn{from{opacity:0;transform:translateY(-50%) translateX(40px);}to{opacity:1;transform:translateY(-50%) translateX(0);}}
.hero-card-mini{background:var(--card);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;width:220px;transition:var(--transition);cursor:pointer;backdrop-filter:blur(10px);}
.hero-card-mini:hover{transform:translateX(-4px);border-color:rgba(229,9,20,0.3);}
.hero-card-mini img{width:100%;height:110px;object-fit:cover;}
.hero-card-mini-info{padding:10px 12px;}
.hero-card-mini-artist{font-size:13px;font-weight:700;color:var(--white);}
.hero-card-mini-date{font-size:11px;color:var(--gray);}
.hero-card-mini-price{font-size:13px;font-weight:700;color:var(--red);margin-top:4px;}
.hero-scroll-indicator{position:absolute;bottom:30px;left:50%;transform:translateX(-50%);display:flex;flex-direction:column;align-items:center;gap:8px;color:var(--gray);font-size:11px;letter-spacing:2px;animation:bounce 2s infinite;}
@keyframes bounce{0%,100%{transform:translateX(-50%) translateY(0);}50%{transform:translateX(-50%) translateY(6px);}}

/* ====== SECTION COMMON ====== */
.section{padding:80px 5%;}
.section-header{display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:48px;}
.section-tag{font-size:12px;font-weight:600;color:var(--red);text-transform:uppercase;letter-spacing:3px;margin-bottom:8px;}
.section-title{font-family:var(--font-head);font-size:clamp(32px,4vw,52px);letter-spacing:3px;line-height:1;}
.section-title span{color:var(--red);}
.section-link{font-size:13px;color:var(--gray);font-weight:500;display:flex;align-items:center;gap:6px;transition:var(--transition);cursor:pointer;}
.section-link:hover{color:var(--red);}
.section-link i{transition:var(--transition);}
.section-link:hover i{transform:translateX(4px);}

/* ====== CONCERT CARDS ====== */
.concerts-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:24px;}
.concert-card{background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;transition:var(--transition);cursor:pointer;position:relative;group;}
.concert-card:hover{transform:translateY(-8px);border-color:rgba(229,9,20,0.4);box-shadow:0 20px 40px rgba(0,0,0,0.5),0 0 0 1px rgba(229,9,20,0.2);}
.concert-card-img{position:relative;height:200px;overflow:hidden;}
.concert-card-img img{width:100%;height:100%;object-fit:cover;transition:transform 0.6s ease;}
.concert-card:hover .concert-card-img img{transform:scale(1.08);}
.concert-card-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(26,26,26,0.9) 0%,transparent 60%);}
.concert-card-badge{position:absolute;top:14px;left:14px;background:var(--red);color:var(--white);font-size:11px;font-weight:700;padding:4px 10px;border-radius:4px;letter-spacing:1px;}
.concert-card-badge.sold-out{background:#333;color:var(--gray);}
.concert-card-wishlist{position:absolute;top:14px;right:14px;width:34px;height:34px;background:rgba(0,0,0,0.5);border-radius:50%;display:flex;align-items:center;justify-content:center;color:var(--gray);font-size:14px;transition:var(--transition);backdrop-filter:blur(4px);}
.concert-card:hover .concert-card-wishlist{opacity:1;}
.concert-card-wishlist:hover{background:var(--red);color:var(--white);}
.concert-card-genre{position:absolute;bottom:14px;right:14px;background:rgba(229,9,20,0.2);border:1px solid rgba(229,9,20,0.4);color:var(--red);font-size:10px;font-weight:600;padding:3px 8px;border-radius:3px;letter-spacing:1px;}
.concert-card-body{padding:18px;}
.concert-card-artist{font-size:18px;font-weight:700;color:var(--white);margin-bottom:4px;line-height:1.2;}
.concert-card-title{font-size:13px;color:var(--gray);margin-bottom:14px;}
.concert-card-meta{display:flex;flex-direction:column;gap:6px;}
.concert-card-meta-item{display:flex;align-items:center;gap:8px;font-size:12px;color:var(--gray);}
.concert-card-meta-item i{color:var(--red);font-size:12px;width:14px;}
.concert-card-footer{display:flex;align-items:center;justify-content:space-between;margin-top:16px;padding-top:16px;border-top:1px solid var(--border);}
.concert-price-label{font-size:10px;color:var(--gray2);text-transform:uppercase;letter-spacing:1px;}
.concert-price{font-family:var(--font-head);font-size:22px;color:var(--white);letter-spacing:1px;}
.concert-price span{color:var(--red);}
.btn-book{background:var(--red);color:var(--white);padding:8px 18px;border-radius:4px;font-size:12px;font-weight:700;letter-spacing:1px;text-transform:uppercase;transition:var(--transition);}
.btn-book:hover{background:var(--red2);box-shadow:var(--shadow-red);}
.btn-book.disabled{background:#333;color:var(--gray);cursor:not-allowed;}

/* ====== FEATURED BANNER ====== */
.featured-banner{margin:0 5%;border-radius:var(--radius);overflow:hidden;position:relative;height:400px;cursor:pointer;background:linear-gradient(135deg,#1a0505,#0F0F0F);}
.featured-banner-bg{position:absolute;inset:0;background:linear-gradient(to right,rgba(15,15,15,0.95) 30%,rgba(15,15,15,0.3) 100%);}
.featured-banner-img{position:absolute;right:0;top:0;height:100%;width:65%;object-fit:cover;opacity:0.5;}
.featured-banner-content{position:relative;z-index:2;padding:50px 50px;max-width:550px;}
.featured-label{font-size:11px;font-weight:700;color:var(--red);text-transform:uppercase;letter-spacing:3px;margin-bottom:12px;}
.featured-title{font-family:var(--font-head);font-size:clamp(36px,5vw,64px);letter-spacing:3px;line-height:1;margin-bottom:16px;}
.featured-desc{font-size:14px;color:var(--gray);line-height:1.7;margin-bottom:28px;}
.featured-meta{display:flex;gap:24px;margin-bottom:28px;}
.featured-meta-item{display:flex;align-items:center;gap:8px;font-size:13px;color:rgba(255,255,255,0.7);}
.featured-meta-item i{color:var(--red);}

/* ====== ARTISTS SECTION ====== */
.artists-scroll{display:flex;gap:20px;overflow-x:auto;padding-bottom:12px;scrollbar-width:thin;}
.artists-scroll::-webkit-scrollbar{height:3px;}
.artists-scroll::-webkit-scrollbar-thumb{background:var(--red);}
.artist-card{flex:0 0 160px;cursor:pointer;transition:var(--transition);}
.artist-card:hover{transform:translateY(-6px);}
.artist-card-img{width:160px;height:160px;border-radius:50%;object-fit:cover;border:3px solid var(--border);transition:var(--transition);margin-bottom:12px;}
.artist-card:hover .artist-card-img{border-color:var(--red);box-shadow:0 0 20px rgba(229,9,20,0.4);}
.artist-card-name{font-size:14px;font-weight:700;text-align:center;color:var(--white);}
.artist-card-genre{font-size:12px;color:var(--gray);text-align:center;margin-top:2px;}

/* ====== VIDEO SECTION ====== */
.video-section{background:var(--bg2);padding:80px 5%;position:relative;overflow:hidden;}
.video-section::before{content:'';position:absolute;left:0;top:0;bottom:0;width:4px;background:var(--red);}
.video-grid{display:grid;grid-template-columns:1fr 1fr 1fr;gap:20px;margin-top:40px;}
.video-item{position:relative;border-radius:var(--radius);overflow:hidden;cursor:pointer;background:#000;aspect-ratio:16/9;}
.video-item img{width:100%;height:100%;object-fit:cover;transition:var(--transition);opacity:0.7;}
.video-item:hover img{opacity:0.5;transform:scale(1.05);}
.video-play-btn{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:52px;height:52px;background:var(--red);border-radius:50%;display:flex;align-items:center;justify-content:center;color:var(--white);font-size:18px;transition:var(--transition);}
.video-item:hover .video-play-btn{transform:translate(-50%,-50%) scale(1.1);box-shadow:var(--shadow-red);}
.video-item-info{position:absolute;bottom:0;left:0;right:0;padding:16px;background:linear-gradient(to top,rgba(0,0,0,0.9),transparent);}
.video-item-title{font-size:14px;font-weight:700;}
.video-item-dur{font-size:11px;color:var(--gray);margin-top:2px;}
.video-item-featured{grid-column:span 2;grid-row:span 2;}

/* ====== CONCERTS PAGE ====== */
.page-header{padding:100px 5% 40px;background:linear-gradient(to bottom,rgba(229,9,20,0.08),transparent);}
.page-title{font-family:var(--font-head);font-size:clamp(40px,6vw,72px);letter-spacing:4px;margin-bottom:8px;}
.page-subtitle{font-size:15px;color:var(--gray);}
.filter-bar{display:flex;gap:12px;flex-wrap:wrap;padding:24px 5%;background:var(--bg2);border-bottom:1px solid var(--border);position:sticky;top:70px;z-index:100;}
.filter-search{flex:1;min-width:200px;position:relative;}
.filter-search input{width:100%;background:var(--bg3);border:1px solid var(--border);border-radius:var(--radius-sm);padding:10px 16px 10px 40px;color:var(--white);font-size:14px;}
.filter-search input:focus{outline:none;border-color:var(--red);}
.filter-search i{position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--gray);font-size:14px;}
.filter-select{background:var(--bg3);border:1px solid var(--border);border-radius:var(--radius-sm);padding:10px 16px;color:var(--white);font-size:13px;cursor:pointer;}
.filter-select:focus{outline:none;border-color:var(--red);}
.filter-btn{background:var(--bg3);border:1px solid var(--border);border-radius:var(--radius-sm);padding:10px 16px;color:var(--gray);font-size:13px;display:flex;align-items:center;gap:8px;transition:var(--transition);}
.filter-btn:hover,.filter-btn.active{background:var(--red);border-color:var(--red);color:var(--white);}
.concerts-page-grid{padding:32px 5%;display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:24px;}

/* ====== CONCERT DETAIL PAGE ====== */
.detail-hero{min-height:60vh;position:relative;display:flex;align-items:flex-end;padding:0 5% 60px;}
.detail-hero-bg{position:absolute;inset:0;}
.detail-hero-bg img{width:100%;height:100%;object-fit:cover;opacity:0.3;}
.detail-hero-overlay{position:absolute;inset:0;background:linear-gradient(to top,var(--bg) 30%,rgba(15,15,15,0.5) 100%),linear-gradient(to right,rgba(15,15,15,0.8) 0%,transparent 60%);}
.detail-hero-content{position:relative;z-index:2;max-width:700px;}
.detail-genre-badge{display:inline-block;background:var(--red);color:var(--white);font-size:11px;font-weight:700;padding:4px 12px;border-radius:4px;letter-spacing:2px;margin-bottom:16px;}
.detail-title{font-family:var(--font-head);font-size:clamp(36px,7vw,80px);letter-spacing:4px;line-height:1;margin-bottom:12px;}
.detail-artist{font-size:18px;font-weight:700;color:var(--red);margin-bottom:20px;display:flex;align-items:center;gap:10px;}
.detail-meta-row{display:flex;gap:30px;flex-wrap:wrap;}
.detail-meta-item{display:flex;align-items:center;gap:8px;font-size:14px;color:rgba(255,255,255,0.8);}
.detail-meta-item i{color:var(--red);}
.detail-body{padding:60px 5%;display:grid;grid-template-columns:1fr 360px;gap:48px;}
.detail-desc{font-size:15px;color:var(--gray);line-height:1.9;}
.detail-desc h3{font-family:var(--font-head);font-size:28px;letter-spacing:2px;color:var(--white);margin-bottom:16px;margin-top:40px;}
.detail-desc h3:first-child{margin-top:0;}
.lineup-item{display:flex;align-items:center;gap:16px;padding:14px 0;border-bottom:1px solid var(--border);}
.lineup-num{font-family:var(--font-head);font-size:24px;color:var(--red);width:40px;}
.lineup-info h4{font-size:15px;font-weight:700;}
.lineup-info p{font-size:12px;color:var(--gray);}
.gallery-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:16px;}
.gallery-img{border-radius:var(--radius-sm);overflow:hidden;aspect-ratio:4/3;cursor:pointer;}
.gallery-img img{width:100%;height:100%;object-fit:cover;transition:var(--transition);}
.gallery-img:hover img{transform:scale(1.05);}
/* Ticket Booking Sidebar */
.ticket-sidebar{position:sticky;top:90px;}
.ticket-box{background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius);padding:28px;margin-bottom:16px;}
.ticket-box h3{font-family:var(--font-head);font-size:24px;letter-spacing:2px;margin-bottom:20px;}
.ticket-category{border:2px solid var(--border);border-radius:var(--radius-sm);padding:14px 16px;margin-bottom:10px;cursor:pointer;transition:var(--transition);display:flex;justify-content:space-between;align-items:center;}
.ticket-category:hover{border-color:rgba(229,9,20,0.4);}
.ticket-category.selected{border-color:var(--red);background:rgba(229,9,20,0.08);}
.ticket-cat-name{font-size:14px;font-weight:700;}
.ticket-cat-stock{font-size:11px;color:var(--gray);margin-top:2px;}
.ticket-cat-price{font-family:var(--font-head);font-size:20px;color:var(--red);}
.qty-control{display:flex;align-items:center;gap:12px;margin-top:20px;}
.qty-btn{width:36px;height:36px;background:var(--bg3);border:1px solid var(--border);border-radius:var(--radius-sm);color:var(--white);font-size:18px;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:var(--transition);}
.qty-btn:hover{background:var(--red);border-color:var(--red);}
.qty-display{font-family:var(--font-head);font-size:24px;min-width:40px;text-align:center;}
.booking-summary{margin-top:20px;padding-top:20px;border-top:1px solid var(--border);}
.summary-row{display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;font-size:14px;}
.summary-row.total{border-top:1px solid var(--border);padding-top:14px;margin-top:4px;}
.summary-row.total .summary-label{font-weight:700;font-size:16px;}
.summary-row.total .summary-value{font-family:var(--font-head);font-size:24px;color:var(--red);}
.btn-book-now{width:100%;background:var(--red);color:var(--white);padding:16px;border-radius:var(--radius-sm);font-size:15px;font-weight:700;letter-spacing:2px;text-transform:uppercase;transition:var(--transition);margin-top:20px;}
.btn-book-now:hover{background:var(--red2);transform:translateY(-2px);box-shadow:var(--shadow-red);}

/* ====== ARTISTS PAGE ====== */
.artists-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:24px;padding:32px 5%;}
.artist-full-card{background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;cursor:pointer;transition:var(--transition);text-align:center;padding:24px;}
.artist-full-card:hover{transform:translateY(-8px);border-color:rgba(229,9,20,0.4);}
.artist-full-img{width:120px;height:120px;border-radius:50%;object-fit:cover;border:3px solid var(--border);margin:0 auto 16px;transition:var(--transition);}
.artist-full-card:hover .artist-full-img{border-color:var(--red);box-shadow:0 0 20px rgba(229,9,20,0.4);}
.artist-full-name{font-size:16px;font-weight:700;margin-bottom:4px;}
.artist-full-genre{font-size:12px;color:var(--gray);margin-bottom:12px;}
.artist-full-concerts{font-size:12px;color:var(--red);font-weight:600;}

/* ====== ADMIN DASHBOARD ====== */
.admin-layout{display:flex;min-height:100vh;padding-top:70px;}
.admin-sidebar{width:240px;background:var(--bg2);border-right:1px solid var(--border);padding:24px 0;position:fixed;top:70px;left:0;bottom:0;overflow-y:auto;z-index:50;}
.admin-sidebar-title{font-size:10px;font-weight:700;color:var(--gray2);text-transform:uppercase;letter-spacing:2px;padding:0 20px;margin-bottom:12px;margin-top:24px;}
.admin-sidebar-title:first-child{margin-top:0;}
.admin-nav-item{display:flex;align-items:center;gap:12px;padding:11px 20px;cursor:pointer;transition:var(--transition);font-size:14px;color:var(--gray);position:relative;}
.admin-nav-item:hover,.admin-nav-item.active{color:var(--white);background:rgba(229,9,20,0.1);}
.admin-nav-item.active::before{content:'';position:absolute;left:0;top:0;bottom:0;width:3px;background:var(--red);}
.admin-nav-item i{width:20px;font-size:16px;}
.admin-nav-badge{margin-left:auto;background:var(--red);color:var(--white);font-size:10px;font-weight:700;padding:2px 7px;border-radius:10px;}
.admin-main{margin-left:240px;flex:1;padding:32px;}
.admin-topbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:32px;}
.admin-topbar h1{font-family:var(--font-head);font-size:32px;letter-spacing:3px;}
.stat-cards{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;margin-bottom:32px;}
.stat-card{background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius);padding:22px;position:relative;overflow:hidden;}
.stat-card::after{content:'';position:absolute;right:-20px;top:-20px;width:80px;height:80px;border-radius:50%;background:rgba(229,9,20,0.07);}
.stat-card-icon{width:44px;height:44px;border-radius:var(--radius-sm);background:rgba(229,9,20,0.15);display:flex;align-items:center;justify-content:center;color:var(--red);font-size:20px;margin-bottom:16px;}
.stat-card-value{font-family:var(--font-head);font-size:32px;letter-spacing:2px;color:var(--white);}
.stat-card-label{font-size:13px;color:var(--gray);margin-top:4px;}
.stat-card-change{font-size:12px;margin-top:8px;display:flex;align-items:center;gap:4px;}
.stat-card-change.up{color:#22c55e;}
.stat-card-change.down{color:var(--red);}
.admin-table-wrap{background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;margin-bottom:24px;}
.table-header{display:flex;align-items:center;justify-content:space-between;padding:20px 24px;border-bottom:1px solid var(--border);}
.table-header h3{font-family:var(--font-head);font-size:22px;letter-spacing:2px;}
.table-actions{display:flex;gap:10px;align-items:center;}
.btn-add{background:var(--red);color:var(--white);padding:8px 16px;border-radius:var(--radius-sm);font-size:13px;font-weight:600;display:flex;align-items:center;gap:8px;transition:var(--transition);}
.btn-add:hover{background:var(--red2);}
.admin-table{width:100%;border-collapse:collapse;}
.admin-table th{padding:12px 16px;text-align:left;font-size:11px;font-weight:700;color:var(--gray2);text-transform:uppercase;letter-spacing:1px;background:rgba(255,255,255,0.02);border-bottom:1px solid var(--border);}
.admin-table td{padding:14px 16px;font-size:14px;border-bottom:1px solid var(--border);}
.admin-table tr:last-child td{border-bottom:none;}
.admin-table tr:hover td{background:rgba(255,255,255,0.02);}
.admin-table td .concert-thumb{width:50px;height:50px;border-radius:var(--radius-sm);object-fit:cover;}
.td-name{font-weight:600;}
.td-artist{color:var(--gray);}
.status-badge{padding:4px 10px;border-radius:4px;font-size:11px;font-weight:700;letter-spacing:1px;}
.status-on-sale{background:rgba(34,197,94,0.15);color:#22c55e;}
.status-sold-out{background:rgba(229,9,20,0.15);color:var(--red);}
.status-pending{background:rgba(245,158,11,0.15);color:#f59e0b;}
.status-success{background:rgba(34,197,94,0.15);color:#22c55e;}
.td-actions{display:flex;gap:6px;}
.btn-edit{background:rgba(59,130,246,0.15);color:#60a5fa;border:none;padding:5px 10px;border-radius:var(--radius-sm);font-size:12px;cursor:pointer;transition:var(--transition);}
.btn-edit:hover{background:rgba(59,130,246,0.3);}
.btn-del{background:rgba(229,9,20,0.15);color:var(--red);border:none;padding:5px 10px;border-radius:var(--radius-sm);font-size:12px;cursor:pointer;transition:var(--transition);}
.btn-del:hover{background:rgba(229,9,20,0.3);}
.btn-view{background:rgba(255,255,255,0.08);color:var(--gray);border:none;padding:5px 10px;border-radius:var(--radius-sm);font-size:12px;cursor:pointer;transition:var(--transition);}
.btn-view:hover{color:var(--white);background:rgba(255,255,255,0.15);}
.chart-row{display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-bottom:24px;}
.chart-box{background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius);padding:24px;}
.chart-box h3{font-family:var(--font-head);font-size:22px;letter-spacing:2px;margin-bottom:20px;}
.chart-bars{display:flex;align-items:flex-end;gap:8px;height:160px;}
.chart-bar-wrap{flex:1;display:flex;flex-direction:column;align-items:center;gap:6px;}
.chart-bar{width:100%;background:rgba(229,9,20,0.2);border-radius:4px 4px 0 0;border-top:2px solid var(--red);transition:var(--transition);}
.chart-bar:hover{background:rgba(229,9,20,0.4);}
.chart-bar-label{font-size:10px;color:var(--gray2);}
.donut-wrap{display:flex;flex-direction:column;gap:12px;margin-top:12px;}
.donut-item{display:flex;align-items:center;justify-content:space-between;font-size:13px;}
.donut-dot{width:10px;height:10px;border-radius:50%;margin-right:10px;flex-shrink:0;}
.donut-label{display:flex;align-items:center;color:var(--gray);}
.donut-pct{font-weight:700;color:var(--white);}

/* ====== MODAL FORM ====== */
.modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,0.85);z-index:2000;display:none;align-items:center;justify-content:center;backdrop-filter:blur(4px);}
.modal-overlay.show{display:flex;animation:fadeIn 0.3s ease;}
@keyframes fadeIn{from{opacity:0;}to{opacity:1;}}
.modal{background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius);width:100%;max-width:480px;padding:36px;position:relative;animation:slideUp 0.3s ease;}
@keyframes slideUp{from{opacity:0;transform:translateY(30px);}to{opacity:1;transform:translateY(0);}}
.modal-close{position:absolute;top:16px;right:16px;background:var(--bg3);border:none;color:var(--gray);width:32px;height:32px;border-radius:50%;font-size:16px;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:var(--transition);}
.modal-close:hover{background:var(--red);color:var(--white);}
.modal-title{font-family:var(--font-head);font-size:32px;letter-spacing:3px;margin-bottom:6px;}
.modal-subtitle{font-size:13px;color:var(--gray);margin-bottom:28px;}
.modal-tabs{display:flex;gap:0;margin-bottom:28px;background:var(--bg3);border-radius:var(--radius-sm);padding:4px;}
.modal-tab{flex:1;padding:10px;text-align:center;font-size:13px;font-weight:600;cursor:pointer;border-radius:var(--radius-sm);transition:var(--transition);color:var(--gray);}
.modal-tab.active{background:var(--red);color:var(--white);}
.form-group{margin-bottom:18px;}
.form-label{display:block;font-size:12px;font-weight:600;color:var(--gray);margin-bottom:8px;text-transform:uppercase;letter-spacing:1px;}
.form-input{width:100%;background:var(--bg3);border:1px solid var(--border);border-radius:var(--radius-sm);padding:12px 16px;color:var(--white);font-size:14px;transition:var(--transition);}
.form-input:focus{outline:none;border-color:var(--red);background:rgba(229,9,20,0.04);}
.form-input::placeholder{color:var(--gray2);}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
.form-checkbox{display:flex;align-items:center;gap:10px;font-size:13px;color:var(--gray);cursor:pointer;}
.form-checkbox input{accent-color:var(--red);width:16px;height:16px;}
.form-social{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:16px;}
.btn-social{background:var(--bg3);border:1px solid var(--border);border-radius:var(--radius-sm);padding:11px;font-size:13px;color:var(--white);display:flex;align-items:center;justify-content:center;gap:8px;cursor:pointer;transition:var(--transition);}
.btn-social:hover{border-color:rgba(255,255,255,0.3);}
.form-divider{text-align:center;color:var(--gray2);font-size:12px;margin:16px 0;position:relative;}
.form-divider::before{content:'';position:absolute;top:50%;left:0;right:0;height:1px;background:var(--border);}
.form-divider span{background:var(--bg2);padding:0 12px;position:relative;}
.btn-submit{width:100%;background:var(--red);color:var(--white);padding:14px;border-radius:var(--radius-sm);font-size:15px;font-weight:700;letter-spacing:2px;text-transform:uppercase;margin-top:8px;transition:var(--transition);}
.btn-submit:hover{background:var(--red2);box-shadow:var(--shadow-red);}

/* ====== CRUD MODAL ====== */
.crud-modal{max-width:600px;}
.crud-modal .form-input[type="file"]{padding:8px;}

/* ====== CHECKOUT PAGE ====== */
.checkout-wrap{padding:100px 5% 60px;display:grid;grid-template-columns:1fr 380px;gap:40px;max-width:1200px;margin:0 auto;}
.checkout-form h2{font-family:var(--font-head);font-size:28px;letter-spacing:2px;margin-bottom:24px;padding-bottom:16px;border-bottom:1px solid var(--border);}
.checkout-section{margin-bottom:32px;}
.checkout-section h3{font-size:16px;font-weight:700;margin-bottom:16px;display:flex;align-items:center;gap:10px;}
.checkout-section h3 span{display:flex;align-items:center;justify-content:center;width:26px;height:26px;background:var(--red);border-radius:50%;font-size:12px;}
.order-summary-box{background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius);padding:24px;position:sticky;top:90px;}
.order-concert-card{display:flex;gap:16px;margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid var(--border);}
.order-concert-img{width:80px;height:80px;border-radius:var(--radius-sm);object-fit:cover;}
.order-concert-info h4{font-size:16px;font-weight:700;margin-bottom:4px;}
.order-concert-info p{font-size:12px;color:var(--gray);}
.promo-input{display:flex;gap:8px;margin-top:14px;}
.promo-input input{flex:1;}
.btn-promo{background:var(--bg3);border:1px solid var(--border);color:var(--white);padding:0 16px;border-radius:var(--radius-sm);font-size:13px;cursor:pointer;transition:var(--transition);}
.btn-promo:hover{background:var(--red);border-color:var(--red);}

/* ====== PROFILE PAGE ====== */
.profile-wrap{padding:100px 5% 60px;max-width:900px;margin:0 auto;}
.profile-header{display:flex;gap:24px;align-items:center;margin-bottom:40px;padding:28px;background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius);}
.profile-avatar-lg{width:88px;height:88px;border-radius:50%;background:var(--red);display:flex;align-items:center;justify-content:center;font-family:var(--font-head);font-size:36px;flex-shrink:0;}
.profile-name{font-family:var(--font-head);font-size:36px;letter-spacing:3px;}
.profile-email{font-size:13px;color:var(--gray);}
.profile-badges{display:flex;gap:8px;margin-top:8px;}
.profile-badge{background:rgba(229,9,20,0.15);border:1px solid rgba(229,9,20,0.3);color:var(--red);padding:3px 10px;border-radius:4px;font-size:11px;font-weight:700;letter-spacing:1px;}
.profile-tabs{display:flex;gap:0;background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius);padding:4px;margin-bottom:24px;}
.profile-tab{flex:1;padding:11px;text-align:center;font-size:14px;font-weight:600;cursor:pointer;border-radius:var(--radius-sm);transition:var(--transition);color:var(--gray);}
.profile-tab.active{background:var(--red);color:var(--white);}
.history-item{background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius);padding:20px;margin-bottom:12px;display:grid;grid-template-columns:80px 1fr auto;gap:16px;align-items:center;}
.history-img{width:80px;height:80px;border-radius:var(--radius-sm);object-fit:cover;}
.history-title{font-size:16px;font-weight:700;margin-bottom:4px;}
.history-meta{font-size:12px;color:var(--gray);}
.history-total{text-align:right;}
.history-amount{font-family:var(--font-head);font-size:22px;color:var(--red);}
.history-status{font-size:11px;margin-top:4px;}

/* ====== TOAST ====== */
.toast{position:fixed;bottom:30px;right:30px;z-index:9999;display:flex;flex-direction:column;gap:10px;pointer-events:none;}
.toast-item{background:var(--bg2);border:1px solid var(--border);border-radius:var(--radius-sm);padding:14px 20px;display:flex;align-items:center;gap:12px;min-width:280px;box-shadow:var(--shadow);pointer-events:all;transform:translateX(120%);transition:transform 0.4s cubic-bezier(0.4,0,0.2,1);border-left:3px solid var(--red);}
.toast-item.show{transform:translateX(0);}
.toast-item.success{border-left-color:#22c55e;}
.toast-item.error{border-left-color:var(--red);}
.toast-item i{font-size:18px;}
.toast-item.success i{color:#22c55e;}
.toast-item.error i{color:var(--red);}
.toast-msg{font-size:13px;font-weight:500;}

/* ====== FOOTER ====== */
footer{background:var(--bg2);border-top:1px solid var(--border);padding:60px 5% 30px;}
.footer-grid{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:40px;margin-bottom:40px;}
.footer-logo-text{font-family:var(--font-head);font-size:32px;letter-spacing:3px;margin-bottom:12px;}
.footer-desc{font-size:13px;color:var(--gray);line-height:1.8;max-width:280px;}
.footer-social{display:flex;gap:12px;margin-top:20px;}
.social-btn{width:38px;height:38px;background:var(--bg3);border:1px solid var(--border);border-radius:50%;display:flex;align-items:center;justify-content:center;color:var(--gray);font-size:15px;cursor:pointer;transition:var(--transition);}
.social-btn:hover{background:var(--red);border-color:var(--red);color:var(--white);}
.footer-heading{font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:2px;color:var(--white);margin-bottom:16px;}
.footer-link{display:block;font-size:13px;color:var(--gray);cursor:pointer;margin-bottom:10px;transition:var(--transition);}
.footer-link:hover{color:var(--red);}
.footer-bottom{border-top:1px solid var(--border);padding-top:24px;display:flex;justify-content:space-between;align-items:center;font-size:12px;color:var(--gray);}

/* ====== AOS-LIKE ANIMATIONS ====== */
[data-aos]{opacity:0;transform:translateY(30px);transition:opacity 0.6s ease,transform 0.6s ease;}
[data-aos].aos-animate{opacity:1;transform:translateY(0);}
[data-aos="fade-left"]{transform:translateX(30px);}
[data-aos="fade-left"].aos-animate{transform:translateX(0);}
[data-aos="zoom-in"]{transform:scale(0.9);}
[data-aos="zoom-in"].aos-animate{transform:scale(1);}

/* ====== LOADING SKELETON ====== */
.skeleton{background:linear-gradient(90deg,var(--bg2) 25%,var(--bg3) 50%,var(--bg2) 75%);background-size:200% 100%;animation:shimmer 1.5s infinite;}
@keyframes shimmer{0%{background-position:200% 0;}100%{background-position:-200% 0;}}
.skeleton-card{border-radius:var(--radius);overflow:hidden;border:1px solid var(--border);}

/* ====== SEAT MAP ====== */
.seat-map{background:var(--bg3);border:1px solid var(--border);border-radius:var(--radius);padding:20px;margin-bottom:16px;}
.seat-stage{background:rgba(229,9,20,0.15);border:1px solid rgba(229,9,20,0.3);border-radius:4px;text-align:center;padding:8px;font-size:12px;font-weight:700;color:var(--red);letter-spacing:3px;margin-bottom:16px;}
.seat-row{display:flex;justify-content:center;gap:4px;margin-bottom:4px;}
.seat{width:20px;height:20px;border-radius:3px;background:var(--bg2);border:1px solid var(--border);cursor:pointer;transition:var(--transition);font-size:8px;display:flex;align-items:center;justify-content:center;}
.seat:hover{background:rgba(229,9,20,0.3);border-color:var(--red);}
.seat.selected{background:var(--red);border-color:var(--red);}
.seat.sold{background:var(--border);cursor:not-allowed;opacity:0.4;}
.seat-legend{display:flex;gap:16px;justify-content:center;margin-top:12px;}
.seat-legend-item{display:flex;align-items:center;gap:6px;font-size:11px;color:var(--gray);}
.seat-legend-dot{width:12px;height:12px;border-radius:2px;}

/* ====== RANGE SLIDER ====== */
.price-range input[type=range]{-webkit-appearance:none;width:100%;height:4px;background:var(--bg3);border-radius:2px;outline:none;}
.price-range input[type=range]::-webkit-slider-thumb{-webkit-appearance:none;width:18px;height:18px;border-radius:50%;background:var(--red);cursor:pointer;}

/* Responsive Basics */
@media(max-width:1024px){
  .detail-body{grid-template-columns:1fr;}
  .checkout-wrap{grid-template-columns:1fr;}
  .stat-cards{grid-template-columns:repeat(2,1fr);}
  .chart-row{grid-template-columns:1fr;}
  .admin-sidebar{width:200px;}
  .admin-main{margin-left:200px;}
  .footer-grid{grid-template-columns:1fr 1fr;}
  .video-grid{grid-template-columns:1fr 1fr;}
  .video-item-featured{grid-column:span 2;}
}
@media(max-width:768px){
  .nav-menu{display:none;}
  .hero-featured{display:none;}
  .admin-layout{flex-direction:column;}
  .admin-sidebar{width:100%;position:relative;top:0;}
  .admin-main{margin-left:0;}
  .stat-cards{grid-template-columns:1fr 1fr;}
  .footer-grid{grid-template-columns:1fr;}
  .video-grid{grid-template-columns:1fr;}
  .video-item-featured{grid-column:span 1;}
  .concerts-page-grid{grid-template-columns:1fr 1fr;}
}
@media(max-width:480px){
  .stat-cards{grid-template-columns:1fr;}
  .concerts-page-grid{grid-template-columns:1fr;}
  .hero-stats{gap:20px;}
}
</style>
</head>
<body>

<!-- ====== NAVBAR ====== -->
<nav id="navbar">
  <div class="nav-logo" onclick="navigate('home')">
    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/08/Netflix_2015_logo.svg/1024px-Netflix_2015_logo.svg.png" alt="logo" style="display:none;">
    <div style="width:36px;height:36px;border-radius:8px;background:#E50914;display:flex;align-items:center;justify-content:center;font-family:'Bebas Neue',sans-serif;font-size:22px;color:white;letter-spacing:1px;">P</div>
    <span class="nav-logo-text">PRIME<span>STAGE</span></span>
  </div>
  <div class="nav-menu">
    <span class="nav-link active" onclick="navigate('home')" id="nl-home">Home</span>
    <span class="nav-link" onclick="navigate('concerts')" id="nl-concerts">Concerts</span>
    <span class="nav-link" onclick="navigate('artists')" id="nl-artists">Artists</span>
    <span class="nav-link" onclick="navigate('gallery')" id="nl-gallery">Gallery</span>
    <span class="nav-link" onclick="navigate('admin')" id="nl-admin" style="color:#E50914;">Admin</span>
  </div>
  <div class="nav-right">
    <div class="nav-search-wrap">
      <i class="fas fa-search nav-search-icon"></i>
      <input type="text" class="nav-search" placeholder="Cari konser...">
    </div>
    <div id="nav-auth-btns">
      <button class="btn-login" onclick="openModal('login')" style="margin-right:8px;">Masuk</button>
      <button class="btn-signup" onclick="openModal('register')">Daftar</button>
    </div>
    <div id="nav-user" style="display:none;">
      <div class="nav-avatar">
        <span id="nav-avatar-initial">A</span>
        <div class="nav-dropdown">
          <div class="nav-dd-item" onclick="navigate('profile')"><i class="fas fa-user"></i> Profil Saya</div>
          <div class="nav-dd-item" onclick="navigate('history')"><i class="fas fa-ticket-alt"></i> Tiket Saya</div>
          <div class="nav-dd-item" onclick="navigate('admin')"><i class="fas fa-shield-alt"></i> Admin Panel</div>
          <div class="nav-dd-item" style="border-top:1px solid var(--border);margin-top:4px;" onclick="logout()"><i class="fas fa-sign-out-alt"></i> Keluar</div>
        </div>
      </div>
    </div>
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
      <p class="hero-subtitle">Platform tiket konser premium Indonesia. Dapatkan akses eksklusif ke konser band lokal hingga artis internasional terbaik.</p>
      <div class="hero-cta">
        <button class="btn-primary" onclick="navigate('concerts')"><i class="fas fa-ticket-alt"></i> Beli Tiket Sekarang</button>
        <button class="btn-outline" onclick="openVideoModal()"><i class="fas fa-play"></i> Tonton Trailer</button>
      </div>
      <div class="hero-stats">
        <div class="stat"><div class="stat-num">200+</div><div class="stat-label">Konser Live</div></div>
        <div class="stat"><div class="stat-num">500K+</div><div class="stat-label">Tiket Terjual</div></div>
        <div class="stat"><div class="stat-num">150+</div><div class="stat-label">Artis</div></div>
      </div>
    </div>
    <div class="hero-featured">
      <div class="hero-card-mini" onclick="openConcertDetail(0)">
        <img src="https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=400&q=80" alt="concert">
        <div class="hero-card-mini-info">
          <div class="hero-card-mini-artist">Coldplay</div>
          <div class="hero-card-mini-date"><i class="fas fa-calendar-alt" style="color:var(--red);margin-right:4px;"></i>15 Aug 2025 · Jakarta</div>
          <div class="hero-card-mini-price">Mulai Rp 750.000</div>
        </div>
      </div>
      <div class="hero-card-mini" onclick="openConcertDetail(1)">
        <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=400&q=80" alt="concert">
        <div class="hero-card-mini-info">
          <div class="hero-card-mini-artist">Noah</div>
          <div class="hero-card-mini-date"><i class="fas fa-calendar-alt" style="color:var(--red);margin-right:4px;"></i>20 Sep 2025 · Bandung</div>
          <div class="hero-card-mini-price">Mulai Rp 350.000</div>
        </div>
      </div>
      <div class="hero-card-mini" onclick="openConcertDetail(2)">
        <img src="https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=400&q=80" alt="concert">
        <div class="hero-card-mini-info">
          <div class="hero-card-mini-artist">The Weeknd</div>
          <div class="hero-card-mini-date"><i class="fas fa-calendar-alt" style="color:var(--red);margin-right:4px;"></i>5 Oct 2025 · Surabaya</div>
          <div class="hero-card-mini-price">Mulai Rp 1.200.000</div>
        </div>
      </div>
    </div>
    <div class="hero-scroll-indicator">
      <span>SCROLL</span>
      <i class="fas fa-chevron-down"></i>
    </div>
  </section>

  <!-- Featured Banner -->
  <div class="featured-banner" data-aos onclick="openConcertDetail(0)">
    <img class="featured-banner-img" src="https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=1200&q=80" alt="">
    <div class="featured-banner-bg"></div>
    <div class="featured-banner-content">
      <div class="featured-label"><i class="fas fa-star"></i> &nbsp;Featured Concert</div>
      <h2 class="featured-title">COLDPLAY<br>MUSIC OF THE SPHERES</h2>
      <p class="featured-desc">Konser spektakuler dari salah satu band terbesar dunia dengan visual show yang luar biasa dan pengalaman live yang tak terlupakan.</p>
      <div class="featured-meta">
        <div class="featured-meta-item"><i class="fas fa-calendar"></i> 15 Agustus 2025</div>
        <div class="featured-meta-item"><i class="fas fa-map-marker-alt"></i> GBK Jakarta</div>
        <div class="featured-meta-item"><i class="fas fa-clock"></i> 19.00 WIB</div>
      </div>
      <button class="btn-primary" style="font-size:13px;padding:11px 28px;"><i class="fas fa-ticket-alt"></i> Beli Tiket</button>
    </div>
  </div>

  <!-- Upcoming Concerts -->
  <section class="section">
    <div class="section-header" data-aos>
      <div>
        <div class="section-tag">On Sale Now</div>
        <h2 class="section-title">UPCOMING <span>CONCERTS</span></h2>
      </div>
      <span class="section-link" onclick="navigate('concerts')">Lihat Semua <i class="fas fa-arrow-right"></i></span>
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
      <span class="section-link" onclick="navigate('artists')">Lihat Semua <i class="fas fa-arrow-right"></i></span>
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
    <div class="video-grid">
      <div class="video-item video-item-featured" onclick="openVideoModal('coldplay')">
        <img src="https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=800&q=80" alt="">
        <div class="video-play-btn"><i class="fas fa-play"></i></div>
        <div class="video-item-info">
          <div class="video-item-title">Coldplay — Music of the Spheres Live</div>
          <div class="video-item-dur"><i class="fas fa-clock" style="margin-right:4px;"></i> 3:45 min preview</div>
        </div>
      </div>
      <div class="video-item" onclick="openVideoModal('noah')">
        <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=400&q=80" alt="">
        <div class="video-play-btn"><i class="fas fa-play"></i></div>
        <div class="video-item-info">
          <div class="video-item-title">NOAH — Dunia Batas</div>
          <div class="video-item-dur"><i class="fas fa-clock" style="margin-right:4px;"></i> 2:30 min</div>
        </div>
      </div>
      <div class="video-item" onclick="openVideoModal('weeknd')">
        <img src="https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=400&q=80" alt="">
        <div class="video-play-btn"><i class="fas fa-play"></i></div>
        <div class="video-item-info">
          <div class="video-item-title">The Weeknd — After Hours Tour</div>
          <div class="video-item-dur"><i class="fas fa-clock" style="margin-right:4px;"></i> 4:15 min</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <div class="footer-grid">
      <div>
        <div class="footer-logo-text">PRIME<span style="color:var(--red);">STAGE</span></div>
        <p class="footer-desc">Platform tiket konser premium terpercaya di Indonesia. Rasakan pengalaman live music terbaik bersama kami.</p>
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
      <input type="text" id="concert-search" placeholder="Cari artis atau konser..." oninput="filterConcerts()">
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
    <button class="filter-btn active" onclick="filterConcerts('all')"><i class="fas fa-th"></i> Semua</button>
    <button class="filter-btn" onclick="filterConcerts('indonesia')"><i class="fas fa-flag"></i> Lokal</button>
    <button class="filter-btn" onclick="filterConcerts('internasional')"><i class="fas fa-globe"></i> Internasional</button>
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
        <div class="detail-meta-item"><i class="fas fa-calendar"></i><span id="detail-date">Date</span></div>
        <div class="detail-meta-item"><i class="fas fa-map-marker-alt"></i><span id="detail-venue">Venue</span></div>
        <div class="detail-meta-item"><i class="fas fa-clock"></i><span id="detail-time">19.00 WIB</span></div>
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
        <div class="video-item-info"><div class="video-item-title" id="detail-trailer-title">Tonton Trailer</div></div>
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
        <button class="btn-book-now" onclick="goCheckout()"><i class="fas fa-ticket-alt"></i>&nbsp; BELI TIKET</button>
      </div>
      <!-- Seat Map -->
      <div class="seat-map">
        <div class="seat-stage">— PANGGUNG —</div>
        <div id="seat-map-rows"></div>
        <div class="seat-legend">
          <div class="seat-legend-item"><div class="seat-legend-dot" style="background:var(--bg2);border:1px solid var(--border);"></div>Tersedia</div>
          <div class="seat-legend-item"><div class="seat-legend-dot" style="background:var(--red);"></div>Dipilih</div>
          <div class="seat-legend-item"><div class="seat-legend-dot" style="background:var(--border);"></div>Terjual</div>
        </div>
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

<!-- ====== CHECKOUT PAGE ====== -->
<div class="page" id="page-checkout">
  <div class="checkout-wrap">
    <div class="checkout-form">
      <h2>CHECKOUT</h2>
      <div class="checkout-section">
        <h3><span>1</span> Data Diri</h3>
        <div class="form-row">
          <div class="form-group"><label class="form-label">Nama Depan</label><input class="form-input" type="text" placeholder="John"></div>
          <div class="form-group"><label class="form-label">Nama Belakang</label><input class="form-input" type="text" placeholder="Doe"></div>
        </div>
        <div class="form-group"><label class="form-label">Email</label><input class="form-input" type="email" placeholder="john@example.com"></div>
        <div class="form-group"><label class="form-label">Nomor HP</label><input class="form-input" type="tel" placeholder="+62 812 3456 7890"></div>
        <div class="form-group"><label class="form-label">NIK (Opsional)</label><input class="form-input" type="text" placeholder="3273xxxxxxxx"></div>
      </div>
      <div class="checkout-section">
        <h3><span>2</span> Metode Pembayaran</h3>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;">
          <label style="cursor:pointer;">
            <input type="radio" name="payment" value="bca" style="display:none;" onclick="selectPayment(this)">
            <div class="ticket-category" id="pay-bca">
              <div>
                <div class="ticket-cat-name">Bank BCA</div>
                <div class="ticket-cat-stock" style="color:var(--gray);">Transfer VA</div>
              </div>
              <i class="fas fa-university" style="color:var(--gray);"></i>
            </div>
          </label>
          <label style="cursor:pointer;">
            <input type="radio" name="payment" value="gopay" style="display:none;" onclick="selectPayment(this)">
            <div class="ticket-category" id="pay-gopay">
              <div>
                <div class="ticket-cat-name">GoPay</div>
                <div class="ticket-cat-stock" style="color:var(--gray);">E-Wallet</div>
              </div>
              <i class="fas fa-wallet" style="color:var(--gray);"></i>
            </div>
          </label>
          <label style="cursor:pointer;">
            <input type="radio" name="payment" value="qris" style="display:none;" onclick="selectPayment(this)">
            <div class="ticket-category" id="pay-qris">
              <div>
                <div class="ticket-cat-name">QRIS</div>
                <div class="ticket-cat-stock" style="color:var(--gray);">Scan & Pay</div>
              </div>
              <i class="fas fa-qrcode" style="color:var(--gray);"></i>
            </div>
          </label>
        </div>
      </div>
      <div class="checkout-section">
        <h3><span>3</span> Kode Promo</h3>
        <div class="promo-input">
          <input class="form-input" type="text" placeholder="Masukkan kode promo" id="promo-code">
          <button class="btn-promo" onclick="applyPromo()">Pakai</button>
        </div>
      </div>
      <label class="form-checkbox" style="margin-bottom:20px;">
        <input type="checkbox" id="agree-tos"> Saya setuju dengan Syarat &amp; Ketentuan PrimeStage
      </label>
      <button class="btn-book-now" onclick="submitOrder()" style="max-width:400px;"><i class="fas fa-lock"></i>&nbsp; KONFIRMASI PEMBELIAN</button>
    </div>
    <div>
      <div class="order-summary-box">
        <h3 style="font-family:var(--font-head);font-size:22px;letter-spacing:2px;margin-bottom:20px;">RINGKASAN PESANAN</h3>
        <div class="order-concert-card">
          <img class="order-concert-img" id="checkout-img" src="" alt="">
          <div class="order-concert-info">
            <h4 id="checkout-title">Concert Title</h4>
            <p id="checkout-meta">Date · Venue</p>
            <p id="checkout-cat" style="color:var(--red);font-weight:700;margin-top:4px;"></p>
          </div>
        </div>
        <div class="summary-row"><span class="summary-label">Harga Satuan</span><span id="co-unit">Rp 0</span></div>
        <div class="summary-row"><span class="summary-label">Jumlah</span><span id="co-qty">1</span></div>
        <div class="summary-row"><span class="summary-label">Subtotal</span><span id="co-sub">Rp 0</span></div>
        <div class="summary-row"><span class="summary-label">Biaya Admin</span><span>Rp 10.000</span></div>
        <div class="summary-row" id="co-discount-row" style="display:none;"><span class="summary-label">Diskon</span><span id="co-disc" style="color:#22c55e;">-Rp 0</span></div>
        <div class="summary-row total"><span class="summary-label">TOTAL</span><span class="summary-value" id="co-total">Rp 0</span></div>
      </div>
    </div>
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
      <button class="btn-outline" style="margin-left:auto;font-size:13px;" onclick="openModal('editProfile')"><i class="fas fa-edit"></i>&nbsp; Edit Profil</button>
    </div>
    <div class="profile-tabs">
      <div class="profile-tab active" onclick="switchProfileTab('orders',this)">Riwayat Pembelian</div>
      <div class="profile-tab" onclick="switchProfileTab('settings',this)">Pengaturan Akun</div>
    </div>
    <div id="profile-tab-orders">
      <div class="history-item" onclick="navigate('checkout')">
        <img class="history-img" src="https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=200&q=80" alt="">
        <div>
          <div class="history-title">Coldplay — Music of the Spheres</div>
          <div class="history-meta"><i class="fas fa-calendar" style="margin-right:6px;"></i>15 Agustus 2025 · GBK Jakarta</div>
          <div class="history-meta" style="margin-top:4px;"><i class="fas fa-ticket-alt" style="margin-right:6px;"></i>2x Festival — Rp 1.500.000/tiket</div>
        </div>
        <div class="history-total">
          <div class="history-amount">Rp 3.010.000</div>
          <div><span class="status-badge status-success">SUKSES</span></div>
        </div>
      </div>
      <div class="history-item">
        <img class="history-img" src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=200&q=80" alt="">
        <div>
          <div class="history-title">NOAH — Dunia Batas World Tour</div>
          <div class="history-meta"><i class="fas fa-calendar" style="margin-right:6px;"></i>20 September 2025 · Trans Studio Bandung</div>
          <div class="history-meta" style="margin-top:4px;"><i class="fas fa-ticket-alt" style="margin-right:6px;"></i>1x VIP — Rp 500.000/tiket</div>
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
        <div class="form-group"><label class="form-label">Nama Lengkap</label><input class="form-input" type="text" value="Adil Muslim Saputra"></div>
        <div class="form-group"><label class="form-label">Email</label><input class="form-input" type="email" value="adil@example.com"></div>
        <div class="form-group"><label class="form-label">Nomor HP</label><input class="form-input" type="tel" value="+62 812 3456 7890"></div>
        <div class="form-group"><label class="form-label">Password Baru</label><input class="form-input" type="password" placeholder="••••••••"></div>
        <button class="btn-book-now" onclick="showToast('success','Profil berhasil diperbarui!')" style="max-width:200px;">Simpan</button>
      </div>
    </div>
  </div>
</div>

<!-- ====== ADMIN DASHBOARD ====== -->
<div class="page" id="page-admin">
  <div class="admin-layout">
    <div class="admin-sidebar">
      <div class="admin-nav-item active" id="adm-dashboard" onclick="switchAdmin('dashboard')"><i class="fas fa-chart-pie"></i> Dashboard</div>
      <div class="admin-sidebar-title">Master Data</div>
      <div class="admin-nav-item" id="adm-concerts" onclick="switchAdmin('concerts')"><i class="fas fa-music"></i> Konser <span class="admin-nav-badge">24</span></div>
      <div class="admin-nav-item" id="adm-artists" onclick="switchAdmin('artists')"><i class="fas fa-microphone"></i> Artis <span class="admin-nav-badge">18</span></div>
      <div class="admin-nav-item" id="adm-tickets" onclick="switchAdmin('tickets')"><i class="fas fa-ticket-alt"></i> Tiket</div>
      <div class="admin-sidebar-title">Transaksi</div>
      <div class="admin-nav-item" id="adm-transactions" onclick="switchAdmin('transactions')"><i class="fas fa-receipt"></i> Transaksi <span class="admin-nav-badge" style="background:#22c55e;">12</span></div>
      <div class="admin-nav-item" id="adm-users" onclick="switchAdmin('users')"><i class="fas fa-users"></i> Users</div>
      <div class="admin-sidebar-title">Media</div>
      <div class="admin-nav-item" onclick="showToast('info','Fitur upload media')"><i class="fas fa-upload"></i> Upload Media</div>
      <div style="padding:20px;border-top:1px solid var(--border);margin-top:auto;">
        <button style="width:100%;background:rgba(229,9,20,0.1);border:1px solid rgba(229,9,20,0.2);color:var(--red);padding:10px;border-radius:var(--radius-sm);font-size:13px;cursor:pointer;" onclick="navigate('home')"><i class="fas fa-arrow-left"></i> Kembali ke Site</button>
      </div>
    </div>
    <div class="admin-main">
      <!-- Dashboard -->
      <div id="admin-section-dashboard">
        <div class="admin-topbar">
          <h1>DASHBOARD</h1>
          <div style="font-size:13px;color:var(--gray);">Selamat datang, <strong style="color:var(--white);">Admin</strong> — <span id="admin-date"></span></div>
        </div>
        <div class="stat-cards">
          <div class="stat-card">
            <div class="stat-card-icon"><i class="fas fa-ticket-alt"></i></div>
            <div class="stat-card-value">12,847</div>
            <div class="stat-card-label">Total Tiket Terjual</div>
            <div class="stat-card-change up"><i class="fas fa-arrow-up"></i> +14.5% dari bulan lalu</div>
          </div>
          <div class="stat-card">
            <div class="stat-card-icon"><i class="fas fa-money-bill-wave"></i></div>
            <div class="stat-card-value">Rp 5,2M</div>
            <div class="stat-card-label">Total Pendapatan</div>
            <div class="stat-card-change up"><i class="fas fa-arrow-up"></i> +22.3% dari bulan lalu</div>
          </div>
          <div class="stat-card">
            <div class="stat-card-icon"><i class="fas fa-music"></i></div>
            <div class="stat-card-value">24</div>
            <div class="stat-card-label">Konser Aktif</div>
            <div class="stat-card-change up"><i class="fas fa-arrow-up"></i> +3 konser baru</div>
          </div>
          <div class="stat-card">
            <div class="stat-card-icon"><i class="fas fa-users"></i></div>
            <div class="stat-card-value">8,543</div>
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
                <circle cx="21" cy="21" r="15.915" fill="none" stroke="var(--bg3)" stroke-width="4"/>
                <circle cx="21" cy="21" r="15.915" fill="none" stroke="#E50914" stroke-width="4" stroke-dasharray="40 60" stroke-dashoffset="0"/>
                <circle cx="21" cy="21" r="15.915" fill="none" stroke="#22c55e" stroke-width="4" stroke-dasharray="25 75" stroke-dashoffset="-40"/>
                <circle cx="21" cy="21" r="15.915" fill="none" stroke="#f59e0b" stroke-width="4" stroke-dasharray="20 80" stroke-dashoffset="-65"/>
                <circle cx="21" cy="21" r="15.915" fill="none" stroke="#60a5fa" stroke-width="4" stroke-dasharray="15 85" stroke-dashoffset="-85"/>
              </svg>
              <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;font-family:var(--font-head);font-size:18px;">100%</div>
            </div>
            <div class="donut-wrap">
              <div class="donut-item"><span class="donut-label"><span class="donut-dot" style="background:#E50914;"></span>Festival</span><span class="donut-pct">40%</span></div>
              <div class="donut-item"><span class="donut-label"><span class="donut-dot" style="background:#22c55e;"></span>VIP</span><span class="donut-pct">25%</span></div>
              <div class="donut-item"><span class="donut-label"><span class="donut-dot" style="background:#f59e0b;"></span>Regular</span><span class="donut-pct">20%</span></div>
              <div class="donut-item"><span class="donut-label"><span class="donut-dot" style="background:#60a5fa;"></span>VVIP</span><span class="donut-pct">15%</span></div>
            </div>
          </div>
        </div>
        <div class="admin-table-wrap">
          <div class="table-header">
            <h3>TRANSAKSI TERBARU</h3>
            <div class="table-actions">
              <button class="btn-add" onclick="switchAdmin('transactions')"><i class="fas fa-eye"></i> Lihat Semua</button>
            </div>
          </div>
          <table class="admin-table" id="recent-tx-table"></table>
        </div>
      </div>

      <!-- Concerts CRUD -->
      <div id="admin-section-concerts" style="display:none;">
        <div class="admin-topbar">
          <h1>KELOLA KONSER</h1>
          <button class="btn-add" onclick="openCrudModal('concert')"><i class="fas fa-plus"></i> Tambah Konser</button>
        </div>
        <div class="admin-table-wrap">
          <div class="table-header">
            <h3>DAFTAR KONSER</h3>
            <div class="table-actions">
              <div class="filter-search" style="width:220px;">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Cari konser..." oninput="filterAdminTable(this,'admin-concerts-table')" style="padding:8px 12px 8px 36px;background:var(--bg3);border:1px solid var(--border);border-radius:var(--radius-sm);color:var(--white);font-size:13px;width:100%;">
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
          <button class="btn-add" onclick="openCrudModal('artist')"><i class="fas fa-plus"></i> Tambah Artis</button>
        </div>
        <div class="admin-table-wrap">
          <div class="table-header"><h3>DAFTAR ARTIS</h3></div>
          <table class="admin-table" id="admin-artists-table"></table>
        </div>
      </div>

      <!-- Tickets CRUD -->
      <div id="admin-section-tickets" style="display:none;">
        <div class="admin-topbar">
          <h1>KELOLA TIKET</h1>
          <button class="btn-add" onclick="openCrudModal('ticket')"><i class="fas fa-plus"></i> Tambah Kategori Tiket</button>
        </div>
        <div class="admin-table-wrap">
          <div class="table-header"><h3>DAFTAR TIKET</h3></div>
          <table class="admin-table" id="admin-tickets-table"></table>
        </div>
      </div>

      <!-- Transactions -->
      <div id="admin-section-transactions" style="display:none;">
        <div class="admin-topbar"><h1>TRANSAKSI</h1></div>
        <div class="admin-table-wrap">
          <div class="table-header"><h3>SEMUA TRANSAKSI</h3></div>
          <table class="admin-table" id="admin-transactions-table"></table>
        </div>
      </div>

      <!-- Users CRUD -->
      <div id="admin-section-users" style="display:none;">
        <div class="admin-topbar">
          <h1>KELOLA USER</h1>
          <button class="btn-add" onclick="openCrudModal('user')"><i class="fas fa-plus"></i> Tambah User</button>
        </div>
        <div class="admin-table-wrap">
          <div class="table-header"><h3>DAFTAR USER</h3></div>
          <table class="admin-table" id="admin-users-table"></table>
        </div>
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
      <div class="modal-tab active" id="tab-login" onclick="switchAuthTab('login')">Masuk</div>
      <div class="modal-tab" id="tab-register" onclick="switchAuthTab('register')">Daftar</div>
    </div>
    <!-- Login Form -->
    <div id="auth-login-form">
      <div class="form-social">
        <button class="btn-social" onclick="loginDemo()"><i class="fab fa-google" style="color:#ea4335;"></i> Google</button>
        <button class="btn-social"><i class="fab fa-facebook" style="color:#1877f2;"></i> Facebook</button>
      </div>
      <div class="form-divider"><span>atau dengan email</span></div>
      <div class="form-group"><label class="form-label">Email</label><input class="form-input" type="email" id="login-email" placeholder="email@example.com"></div>
      <div class="form-group"><label class="form-label">Password</label><input class="form-input" type="password" id="login-password" placeholder="••••••••"></div>
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
        <label class="form-checkbox"><input type="checkbox"> Ingat saya</label>
        <span style="font-size:12px;color:var(--red);cursor:pointer;">Lupa password?</span>
      </div>
      <button class="btn-submit" onclick="loginUser()">MASUK</button>
    </div>
    <!-- Register Form -->
    <div id="auth-register-form" style="display:none;">
      <div class="form-row">
        <div class="form-group"><label class="form-label">Nama Depan</label><input class="form-input" type="text" id="reg-firstname" placeholder="John"></div>
        <div class="form-group"><label class="form-label">Nama Belakang</label><input class="form-input" type="text" id="reg-lastname" placeholder="Doe"></div>
      </div>
      <div class="form-group"><label class="form-label">Email</label><input class="form-input" type="email" id="reg-email" placeholder="email@example.com"></div>
      <div class="form-group"><label class="form-label">Nomor HP</label><input class="form-input" type="tel" placeholder="+62 812 3456 7890"></div>
      <div class="form-group"><label class="form-label">Tanggal Lahir</label><input class="form-input" type="date" id="reg-dob"></div>
      <div class="form-group"><label class="form-label">Password</label><input class="form-input" type="password" id="reg-password" placeholder="Min. 8 karakter"></div>
      <div class="form-group"><label class="form-label">Konfirmasi Password</label><input class="form-input" type="password" placeholder="Ulangi password"></div>
      <label class="form-checkbox" style="margin-bottom:16px;"><input type="checkbox" required> Saya setuju dengan <span style="color:var(--red);">Syarat &amp; Ketentuan</span></label>
      <button class="btn-submit" onclick="registerUser()">BUAT AKUN</button>
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
    <button class="modal-close" onclick="closeModal('video')" style="z-index:10;"><i class="fas fa-times"></i></button>
    <div style="background:#000;border-radius:var(--radius);overflow:hidden;aspect-ratio:16/9;display:flex;align-items:center;justify-content:center;">
      <iframe id="yt-iframe" width="100%" height="100%" src="" frameborder="0" allow="autoplay;encrypted-media" allowfullscreen style="aspect-ratio:16/9;"></iframe>
    </div>
    <div style="padding:12px 4px 4px;font-family:var(--font-head);font-size:20px;letter-spacing:2px;" id="video-modal-title"></div>
  </div>
</div>

<!-- Toast Container -->
<div class="toast" id="toast-container"></div>

<script>
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

const artists = [
  {name:'Coldplay',genre:'Pop/Rock',country:'internasional',img:'https://images.unsplash.com/photo-1545128485-c400ce7b17eb?w=300&q=80',concerts:2},
  {name:'Noah',genre:'Pop',country:'indonesia',img:'https://images.unsplash.com/photo-1516280440614-37939bbacd81?w=300&q=80',concerts:1},
  {name:'The Weeknd',genre:'R&B',country:'internasional',img:'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=300&q=80',concerts:1},
  {name:'Taylor Swift',genre:'Pop',country:'internasional',img:'https://images.unsplash.com/photo-1459749411175-04bf5292ceea?w=300&q=80',concerts:1},
  {name:'Slank',genre:'Rock',country:'indonesia',img:'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=300&q=80',concerts:1},
  {name:'Dewa 19',genre:'Rock/Pop',country:'indonesia',img:'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=300&q=80',concerts:1},
  {name:'Beyoncé',genre:'R&B/Pop',country:'internasional',img:'https://images.unsplash.com/photo-1547153760-18fc86324498?w=300&q=80',concerts:1},
  {name:'Padi Reborn',genre:'Rock',country:'indonesia',img:'https://images.unsplash.com/photo-1501612780327-45045538702b?w=300&q=80',concerts:1},
];

const ticketCategories = [
  {name:'VVIP',price:2500000,stock:200,desc:'Depan panggung'},
  {name:'VIP',price:1500000,stock:500,desc:'Area premium'},
  {name:'Festival A',price:750000,stock:1500,desc:'Festival zone A'},
  {name:'Festival B',price:500000,stock:3000,desc:'Festival zone B'},
  {name:'Tribune',price:350000,stock:5000,desc:'Tribun stadion'},
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
  if(g) g.innerHTML = concerts.slice(0,4).map((c,i)=>concertCardHTML(c,i)).join('');
  const a = document.getElementById('home-artists');
  if(a) a.innerHTML = artists.map(ar=>`
    <div class="artist-card" onclick="navigate('artists')">
      <img class="artist-card-img" src="${ar.img}" alt="${ar.name}">
      <div class="artist-card-name">${ar.name}</div>
      <div class="artist-card-genre">${ar.genre}</div>
    </div>`).join('');
  initAOS();
}

function renderConcertsPage(){
  const g = document.getElementById('concerts-page-grid');
  if(g) g.innerHTML = concerts.map((c,i)=>concertCardHTML(c,i)).join('');
  initAOS();
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
  switchAdmin('dashboard');
}

function switchAdmin(section){
  ['dashboard','concerts','artists','tickets','transactions','users'].forEach(s=>{
    const el = document.getElementById('admin-section-'+s);
    const nav = document.getElementById('adm-'+s);
    if(el) el.style.display = s===section ? 'block' : 'none';
    if(nav) nav.classList.toggle('active', s===section);
  });
  if(section==='dashboard') buildAdminDashboard();
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
  t.innerHTML = `<thead><tr><th>#</th><th>Poster</th><th>Konser</th><th>Artis</th><th>Tanggal</th><th>Kota</th><th>Harga</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>${concerts.map((c,i)=>`
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
        <button class="btn-edit" onclick="editConcert(${c.id})"><i class="fas fa-edit"></i></button>
        <button class="btn-del" onclick="deleteConcert(${c.id},this)"><i class="fas fa-trash"></i></button>
        <button class="btn-view" onclick="openConcertDetail(${c.id})"><i class="fas fa-eye"></i></button>
      </div></td>
    </tr>`).join('')}</tbody>`;
}

function buildArtistsTable(){
  const t = document.getElementById('admin-artists-table');
  if(!t) return;
  t.innerHTML = `<thead><tr><th>#</th><th>Foto</th><th>Nama</th><th>Genre</th><th>Asal</th><th>Konser</th><th>Aksi</th></tr></thead>
    <tbody>${artists.map((a,i)=>`
    <tr>
      <td>${i+1}</td>
      <td><img class="concert-thumb" src="${a.img}" alt="" style="border-radius:50%;"></td>
      <td class="td-name">${a.name}</td>
      <td>${a.genre}</td>
      <td><span class="status-badge ${a.country==='indonesia'?'status-on-sale':'status-pending'}">${a.country==='indonesia'?'🇮🇩 Lokal':'🌍 International'}</span></td>
      <td>${a.concerts}</td>
      <td><div class="td-actions">
        <button class="btn-edit" onclick="showToast('info','Edit artis ${a.name}')"><i class="fas fa-edit"></i></button>
        <button class="btn-del" onclick="deleteRow(this)"><i class="fas fa-trash"></i></button>
      </div></td>
    </tr>`).join('')}</tbody>`;
}

function buildTicketsTable(){
  const t = document.getElementById('admin-tickets-table');
  if(!t) return;
  t.innerHTML = `<thead><tr><th>#</th><th>Kategori</th><th>Harga</th><th>Stok Awal</th><th>Terjual</th><th>Sisa</th><th>Aksi</th></tr></thead>
    <tbody>${ticketCategories.map((tk,i)=>{
    const sold = Math.floor(Math.random()*tk.stock*0.7);
    return `<tr>
      <td>${i+1}</td>
      <td class="td-name">${tk.name}</td>
      <td>Rp ${tk.price.toLocaleString('id-ID')}</td>
      <td>${tk.stock}</td>
      <td style="color:#22c55e;">${sold}</td>
      <td>${tk.stock-sold}</td>
      <td><div class="td-actions">
        <button class="btn-edit" onclick="showToast('info','Edit tiket ${tk.name}')"><i class="fas fa-edit"></i></button>
        <button class="btn-del" onclick="deleteRow(this)"><i class="fas fa-trash"></i></button>
      </div></td>
    </tr>`}).join('')}</tbody>`;
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

function buildUsersTable(){
  const t = document.getElementById('admin-users-table');
  if(!t) return;
  t.innerHTML = `<thead><tr><th>#</th><th>Nama</th><th>Email</th><th>Role</th><th>Status</th><th>Bergabung</th><th>Aksi</th></tr></thead>
    <tbody>${adminUsers.map(u=>`
    <tr>
      <td>${u.id}</td>
      <td class="td-name">${u.name}</td>
      <td style="color:var(--gray);font-size:13px;">${u.email}</td>
      <td><span class="status-badge ${u.role==='admin'?'status-sold-out':'status-pending'}">${u.role.toUpperCase()}</span></td>
      <td><span class="status-badge ${u.status==='active'?'status-on-sale':'status-sold-out'}">${u.status.toUpperCase()}</span></td>
      <td style="font-size:13px;">${u.joined}</td>
      <td><div class="td-actions">
        <button class="btn-edit" onclick="showToast('info','Edit user ${u.name}')"><i class="fas fa-edit"></i></button>
        <button class="btn-del" onclick="deleteRow(this)"><i class="fas fa-trash"></i></button>
      </div></td>
    </tr>`).join('')}</tbody>`;
}

function editConcert(id){
  openCrudModal('concert', concerts[id]);
}
function deleteConcert(id,btn){
  const row = btn.closest('tr');
  row.style.opacity='0';row.style.transition='opacity 0.3s';
  setTimeout(()=>row.remove(),300);
  showToast('success','Konser berhasil dihapus');
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
  showToast('success', type.charAt(0).toUpperCase()+type.slice(1)+' berhasil disimpan!');
  if(type==='concert') setTimeout(()=>buildConcertsTable(),300);
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
</script>
</body>
</html>