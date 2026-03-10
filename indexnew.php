<?php
require_once 'db.php';

// =========================================================================
// 1. ระบบสุ่มภาพพื้นหลังเว็บ (Body Background)
// =========================================================================
$bgImages = [
    'https://images.unsplash.com/photo-1518605368461-1e1252220a77?q=80&w=1920&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?q=80&w=1920&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1489944440615-453fc2b6a9a9?q=80&w=1920&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1522778119026-d647f0596c20?q=80&w=1920&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1511886929837-354d827aae26?q=80&w=1920&auto=format&fit=crop'
];
$randomBg = $bgImages[array_rand($bgImages)];

// =========================================================================
// 2. ระบบคลังสไลด์โชว์แบนเนอร์ระดับพรีเมียม (สุ่มมาโชว์ 3 อัน)
// =========================================================================
$slidePool = [
    [
        'img' => 'https://images.unsplash.com/photo-1518605368461-1e1252220a77?q=80&w=1920&auto=format&fit=crop',
        'badge' => 'THANJAI EXCLUSIVE',
        'title_1' => 'NEW SEASON', 'title_2' => '2026',
        'desc' => 'คอลเลคชั่นใหม่ล่าสุดจากสโมสรดังทั่วโลก พร้อมส่งแล้ววันนี้! สัมผัสความพรีเมียมก่อนใคร',
        'btn_icon' => 'fa-bolt', 'btn_text' => 'เลือกช้อปเลย', 'link' => '#product-list'
    ],
    [
        'img' => 'https://images.unsplash.com/photo-1508344928928-7165b67de128?q=80&w=1920&auto=format&fit=crop',
        'badge' => 'PLAYER ISSUE',
        'title_1' => 'PREMIUM', 'title_2' => 'QUALITY',
        'desc' => 'สัมผัสประสบการณ์ระดับโลก ด้วยเสื้อแข่งเกรดเพลเยอร์ที่ดีที่สุด เนื้อผ้าระบายอากาศขั้นสุด',
        'btn_icon' => 'fa-fire', 'btn_text' => 'สินค้ามาใหม่', 'link' => '#product-list'
    ],
    [
        'img' => 'https://images.unsplash.com/photo-1553108715-26505600002f?q=80&w=1920&auto=format&fit=crop',
        'badge' => 'CHAMPION MINDSET',
        'title_1' => 'VICTORY', 'title_2' => 'IS YOURS',
        'desc' => 'สวมใส่ความมุ่งมั่น สวมใส่ความสำเร็จ ไปกับเรา ThanJai Shop',
        'btn_icon' => 'fa-trophy', 'btn_text' => 'ดูสินค้าทั้งหมด', 'link' => '#product-list'
    ],
    [
        'img' => 'https://images.unsplash.com/photo-1431324155629-1a6d0a11f582?q=80&w=1920&auto=format&fit=crop',
        'badge' => 'PRO EQUIPMENT',
        'title_1' => 'UNLEASH', 'title_2' => 'YOUR POWER',
        'desc' => 'อุปกรณ์กีฬาฟุตบอลมาตรฐานสากล สำหรับนักเตะตัวจริงที่ต้องการความสมบูรณ์แบบ',
        'btn_icon' => 'fa-futbol', 'btn_text' => 'ค้นหาไอเทมเด็ด', 'link' => '#product-list'
    ],
    [
        'img' => 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?q=80&w=1920&auto=format&fit=crop',
        'badge' => 'STADIUM READY',
        'title_1' => 'DOMINATE', 'title_2' => 'THE PITCH',
        'desc' => 'พร้อมลุยทุกแมตช์การแข่งขัน ด้วยชุดแข่งที่ออกแบบมาเพื่อชัยชนะ',
        'btn_icon' => 'fa-shield-halved', 'btn_text' => 'เตรียมความพร้อม', 'link' => '#product-list'
    ],
    [
        'img' => 'https://images.unsplash.com/photo-1511886929837-354d827aae26?q=80&w=1920&auto=format&fit=crop',
        'badge' => 'LIMITED EDITION',
        'title_1' => 'ICONIC', 'title_2' => 'GEAR',
        'desc' => 'รองเท้าและอุปกรณ์หายาก สเปคเดียวกับนักเตะระดับท็อปของยุโรป',
        'btn_icon' => 'fa-star', 'btn_text' => 'ไอเทมหายาก', 'link' => '#product-list'
    ]
];
shuffle($slidePool); // สับไพ่ (สุ่มลำดับ Array)
$activeSlides = array_slice($slidePool, 0, 3); // ดึงมาแสดงแค่ 3 สไลด์แรก

// =========================================================================

// ดึงข้อมูลสินค้า
$sql = "SELECT p.id, p.name, p.price, p.is_sale, p.old_price, p.description as `desc`, p.image as img, c.name as type 
        FROM products p LEFT JOIN categories c ON p.category_id = c.id";
$stmt = $pdo->query($sql);
$productsFromDB = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($productsFromDB as $key => $product) {
    $productsFromDB[$key]['price'] = (float)$product['price'];
    $productsFromDB[$key]['id'] = (int)$product['id'];
}

$stmtCats = $pdo->query("SELECT * FROM categories ORDER BY id ASC");
$categoriesFromDB = $stmtCats->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ThanJai Shop - Premium Sports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Sarabun:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #111111; --accent: #ffb800; --bg-light: #f5f5f5; }
        body { 
            font-family: 'Sarabun', sans-serif; color: #333; 
            background: linear-gradient(rgba(245, 245, 245, 0.85), rgba(245, 245, 245, 0.95)), url('<?= $randomBg ?>') no-repeat center center fixed;
            background-size: cover;
        }
        h1, h2, h3, h4, h5, h6, .nav-link, .btn, .navbar-brand, .sport-font { font-family: 'Kanit', sans-serif; }
        
        .navbar { background: var(--primary) !important; padding: 15px 0; border-bottom: 2px solid var(--accent); z-index: 1050;}
        .navbar-brand { font-size: 1.5rem; text-transform: uppercase; letter-spacing: 1px; color: #fff !important; }
        
        /* ========================================================
           ✨ HERO CAROUSEL: SUPER PREMIUM SPORT STYLE ✨
           ======================================================== */
        #heroCarousel { height: 550px; background: var(--primary); overflow: hidden; border-bottom: 4px solid var(--accent); position: relative; }
        .carousel-item { height: 550px; }
        
        /* เอฟเฟกต์ Ken Burns (ซูมเข้าช้าๆ) */
        .slide-bg { 
            position: absolute; top: 0; left: 0; width: 100%; height: 100%; 
            background-size: cover; background-position: center 30%; 
            transform: scale(1); transition: transform 8s linear; 
        }
        .carousel-item.active .slide-bg { transform: scale(1.1); } 
        
        /* เคลือบสีดำไล่ระดับจากซ้ายไปขวา (ทำให้ตัวหนังสือเด่น) */
        .slide-bg::after { 
            content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; 
            background: linear-gradient(100deg, rgba(0,0,0,0.95) 0%, rgba(0,0,0,0.5) 45%, rgba(0,0,0,0.1) 100%); 
        }

        /* ลายตะแกรงโปร่งแสง (Sport Mesh Texture) */
        .slide-texture {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0IiBoZWlnaHQ9IjQiPjxyZWN0IHdpZHRoPSI0IiBoZWlnaHQ9IjQiIGZpbGw9Im5vbmUiLz48cGF0aCBkPSJNMCAwTDQgNFpNNCAwTDAgNFoiIHN0cm9rZT0icmdiYSgyNTUsMjU1LDI1NSwwLjAzKSIgc3Ryb2tlLXdpZHRoPSIxIi8+PC9zdmc+');
            pointer-events: none; z-index: 1;
        }
        
        /* การจัดวางกล่องข้อความ */
        .carousel-caption { bottom: auto; top: 50%; transform: translateY(-50%); text-align: left; left: 0; padding: 0; width: 100%; z-index: 10; }
        .slide-content-wrapper { padding-left: 2.5rem; border-left: 6px solid var(--accent); max-width: 800px; }
        
        /* ตั้งค่าเริ่มต้นให้หายไปก่อน (รอเล่นอนิเมชั่น) */
        .slide-content-wrapper h1, .slide-content-wrapper p, .slide-content-wrapper .badge, .slide-btn-group { opacity: 0; }
        .slide-content-wrapper .badge { transform: translateY(-20px); }
        .slide-content-wrapper h1, .slide-content-wrapper p { transform: translateX(-50px); text-shadow: 2px 4px 15px rgba(0,0,0,0.8); }
        .slide-btn-group { transform: translateY(30px); }

        /* อนิเมชั่นวิ่งเข้าเมื่อสไลด์ถูกแสดง (Staggered Animation) */
        .carousel-item.active .slide-content-wrapper .badge { animation: fadeDown 0.8s cubic-bezier(0.25, 1, 0.5, 1) forwards 0.2s; }
        .carousel-item.active .slide-content-wrapper h1 { animation: slideRight 0.8s cubic-bezier(0.25, 1, 0.5, 1) forwards 0.4s; font-size: 3.8rem; }
        .carousel-item.active .slide-content-wrapper p { animation: slideRight 0.8s cubic-bezier(0.25, 1, 0.5, 1) forwards 0.6s; font-size: 1.25rem; color: #cbd5e1; }
        .carousel-item.active .slide-btn-group { animation: fadeInUp 0.8s cubic-bezier(0.25, 1, 0.5, 1) forwards 0.8s; }

        @keyframes slideRight { to { opacity: 1; transform: translateX(0); } }
        @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeDown { to { opacity: 1; transform: translateY(0); } }

        /* อินดิเคเตอร์สไตล์เท่ๆ ยืดหดได้ */
        .carousel-indicators { bottom: 20px; justify-content: flex-start; left: 5%; margin-left: 0; padding-left: 2.5rem;}
        .carousel-indicators [data-bs-target] { width: 35px; height: 5px; background-color: #fff; opacity: 0.3; border: none; transition: 0.4s cubic-bezier(0.25, 1, 0.5, 1); margin-right: 8px; }
        .carousel-indicators .active { opacity: 1; width: 75px; background-color: var(--accent); box-shadow: 0 0 10px rgba(255, 184, 0, 0.8); }

        /* ปุ่มสไลด์ (โฮเวอร์แล้วยกตัว+เรืองแสง) */
        .slide-btn { position: relative; overflow: hidden; transition: all 0.3s ease; box-shadow: 0 5px 15px rgba(255, 184, 0, 0.2); }
        .slide-btn:hover { transform: translateY(-4px); background-color: #eab308; box-shadow: 0 10px 25px rgba(255, 184, 0, 0.5); }
        
        /* ======================================================== */

        .btn-warning { background-color: var(--accent); border: none; color: #000; font-weight: 700; border-radius: 0; text-transform: uppercase; transition: 0.2s;}
        .btn-warning:hover { background-color: #e6a600; color: #000; transform: scale(1.02); }
        .btn-dark { background-color: var(--primary); border: none; border-radius: 0; font-weight: 500;}
        
        .product-card { border: none; transition: 0.3s; background: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.05);}
        .product-card:hover .product-img-wrap img { transform: scale(1.05); }
        .product-img-wrap { background-color: var(--bg-light); aspect-ratio: 3/4; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative; }
        .product-img-wrap img { width: 100%; height: 100%; object-fit: cover; object-position: top; transition: transform 0.5s ease; }
        .product-card .card-body { padding: 15px 0; }
        .product-card .card-title { font-size: 15px; color: #111; margin-bottom: 5px; }
        
        .sale-badge { position: absolute; top: 10px; right: 10px; background: #e11d48; color: white; padding: 4px 10px; font-weight: bold; font-size: 11px; z-index: 10; font-family: 'Kanit'; text-transform: uppercase; }
        .payment-box { border: 1px solid #ddd; padding: 15px; cursor: pointer; transition: 0.2s; text-align: center; font-family: 'Kanit'; }
        .payment-box.active { border-color: var(--primary); border-width: 2px; font-weight: bold; }
        
        .filter-btn { font-family: 'Kanit'; text-transform: uppercase; letter-spacing: 0.5px; border-radius: 0 !important; font-weight: 500; border: 1px solid #ddd; color: #555; background: #fff; transition: 0.2s;}
        .filter-btn.active { background: var(--primary); color: #fff; border-color: var(--primary); }
        .filter-btn:hover { background: #eee; }
        .filter-btn.active:hover { background: var(--primary); }

        .modal-content { border-radius: 0; border: none; }
    </style>
</head>
<body onload="initStore()">

<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1060;">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body text-white d-flex justify-content-between align-items-center" id="toast-body">
            <span id="toast-msg">ข้อความ</span>
            <button type="button" class="btn-close btn-close-white ms-2" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#" onclick="filterProducts('ทั้งหมด')">THANJAI <span style="color: var(--accent);">SHOP</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link active" href="#" onclick="filterProducts('ทั้งหมด')"><i class="fa fa-home me-1"></i> หน้าหลัก</a></li>
                <li class="nav-item"><a class="nav-link" href="#product-list" onclick="filterProducts('ทั้งหมด')"><i class="fa fa-list me-1"></i> สินค้าทั้งหมด</a></li>
                <li class="nav-item"><a class="nav-link text-danger fw-bold" href="#" onclick="filterProducts('โปรโมชั่น')"><i class="fa fa-fire me-1"></i> โปรโมชั่นลดราคา</a></li>
            </ul>
            <div class="d-flex align-items-center gap-3">
                <div class="input-group input-group-sm d-none d-md-flex"><input type="text" id="search-input" class="form-control rounded-0" placeholder="ค้นหาชื่อทีม..." onkeyup="searchProducts()" style="font-family: 'Kanit';"></div>
                <div id="guest-zone"><button class="btn btn-warning btn-sm" onclick="openAuthModal('login')"><i class="fa fa-sign-in-alt me-1"></i> เข้าสู่ระบบ</button></div>
                <div id="user-zone" class="d-none">
                    <div class="dropdown text-white">
                        <span class="me-2 d-none d-md-inline sport-font">ยินดีต้อนรับ, <strong id="nav-username" class="text-warning">ผู้ใช้</strong></span>
                        <a href="#" class="link-light dropdown-toggle" data-bs-toggle="dropdown"><img id="nav-profile-pic" src="" width="35" height="35" class="rounded-circle border border-warning" style="object-fit: cover;"></a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm sport-font rounded-0">
                            <li id="nav-admin-link" class="d-none"><a class="dropdown-item text-warning fw-bold bg-dark" href="admin_orders.php"><i class="fa fa-user-shield me-2"></i>ระบบหลังบ้าน (Admin)</a></li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profileModal"><i class="fa fa-cog me-2"></i>ตั้งค่าบัญชี</a></li>
                            <li><a class="dropdown-item" href="#" onclick="openOrderHistory()"><i class="fa fa-box-open me-2"></i>ประวัติสั่งซื้อ & รีวิว</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger fw-bold" href="#" onclick="logoutUser()"><i class="fa fa-sign-out-alt me-2"></i>ออกจากระบบ</a></li>
                        </ul>
                    </div>
                </div>
                <a href="#" class="text-white position-relative ms-2" onclick="openCart()"><i class="fa fa-shopping-cart fs-5"></i><span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger" style="font-size: 0.6rem;">0</span></a>
            </div>
        </div>
    </div>
</nav>

<header id="hero-banner">
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-pause="false" data-bs-interval="5000">
        
        <div class="carousel-indicators">
            <?php foreach($activeSlides as $index => $slide): ?>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?= $index ?>" class="<?= $index === 0 ? 'active' : '' ?>"></button>
            <?php endforeach; ?>
        </div>

        <div class="carousel-inner">
            <?php foreach($activeSlides as $index => $slide): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <div class="slide-bg" style="background-image: url('<?= $slide['img'] ?>');"></div>
                    <div class="slide-texture"></div> <div class="carousel-caption d-flex flex-column justify-content-center h-100">
                        <div class="container position-relative">
                            <div class="slide-content-wrapper">
                                <span class="badge bg-warning text-dark mb-3 px-3 py-2 fw-bold sport-font shadow-sm" style="letter-spacing: 2px;"><?= $slide['badge'] ?></span>
                                <h1 class="mb-1 fw-bolder fst-italic text-white text-uppercase" style="letter-spacing: 1px;">
                                    <?= $slide['title_1'] ?> <span class="text-warning"><?= $slide['title_2'] ?></span>
                                </h1>
                                <p class="mb-4 fw-light font-sarabun">
                                    <?= $slide['desc'] ?>
                                </p>
                                <div class="slide-btn-group">
                                    <a href="<?= $slide['link'] ?>" class="btn btn-warning slide-btn btn-lg px-5 py-3 fw-bold rounded-0 sport-font text-dark">
                                        <i class="fa <?= $slide['btn_icon'] ?> me-2"></i> <?= $slide['btn_text'] ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev" style="width: 5%; z-index: 20;">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next" style="width: 5%; z-index: 20;">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
        </button>
    </div>
</header>

<div class="container mt-4" id="promo-banner">
    <div class="alert shadow-sm border-0 text-center sport-font text-white position-relative overflow-hidden p-4" style="background: linear-gradient(to right, rgba(17, 17, 17, 0.95), rgba(17, 17, 17, 0.7)), url('https://images.unsplash.com/photo-1518605368461-1e1252220a77?q=80&w=1200&auto=format&fit=crop'); background-size: cover; background-position: center;">
        <h3 class="fw-bold mb-2 text-warning"><i class="fa fa-truck-fast me-2"></i> โค้ดส่งฟรี! สำหรับสมาชิกใหม่</h3>
        <p class="mb-3 fs-5">รับสิทธิ์จัดส่งฟรีทั่วประเทศ (โค้ด: <span class="bg-warning text-dark px-3 py-1 ms-1 fw-bold rounded-1">FREESHIP</span>)</p>
        <button class="btn btn-warning fw-bold px-5 py-2 rounded-0 shadow-sm" onclick="collectCoupon('FREESHIP')">เก็บและใช้งานคูปอง</button>
    </div>
</div>

<main class="container my-5" id="product-list">
    <div class="d-flex justify-content-center gap-2 mb-5 overflow-auto pb-2" id="category-filters">
        <button class="btn px-4 filter-btn active" onclick="filterProducts('ทั้งหมด')">ทั้งหมด</button>
        <?php foreach($categoriesFromDB as $cat): ?>
            <button class="btn px-4 filter-btn" onclick="filterProducts('<?= htmlspecialchars($cat['name']) ?>')"><?= htmlspecialchars($cat['name']) ?></button>
        <?php endforeach; ?>
    </div>
    <div class="row g-4" id="store-display"></div>
</main>

<div class="modal fade" id="authModal" tabindex="-1"><div class="modal-dialog modal-dialog-centered modal-sm"><div class="modal-content shadow-lg"><div class="modal-header border-0 text-white position-relative" style="background: linear-gradient(to bottom, rgba(0,0,0,0.2), rgba(17,17,17,1)), url('https://images.unsplash.com/photo-1600250395178-40fe752e5189?q=80&w=400&auto=format&fit=crop'); background-size: cover; height: 130px; align-items: flex-end;"><button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button><h4 class="modal-title fw-bold sport-font w-100 text-center text-warning pb-2" id="authModalTitle">เข้าสู่ระบบ</h4></div><div class="modal-body sport-font px-4 pb-4 bg-white"><div id="login-section"><input type="text" id="login-user" class="form-control rounded-0 mb-3" placeholder="ชื่อผู้ใช้ (Username)"><input type="password" id="login-pass" class="form-control rounded-0 mb-4" placeholder="รหัสผ่าน"><button class="btn btn-warning w-100 fw-bold py-2 mb-3 rounded-0" onclick="login()">เข้าสู่ระบบ</button><p class="text-center small mt-2 mb-0">ผู้ใช้ใหม่ใช่ไหม? <a href="#" onclick="toggleAuth('register')" class="text-primary fw-bold text-decoration-none">สมัครสมาชิก</a></p></div><div id="register-section" class="d-none"><input type="text" id="reg-user" class="form-control rounded-0 mb-2" placeholder="ตั้งชื่อผู้ใช้ (Username)"><input type="email" id="reg-email" class="form-control rounded-0 mb-2" placeholder="อีเมล (เช่น name@email.com)"><input type="text" id="reg-phone" class="form-control rounded-0 mb-2" placeholder="เบอร์โทรศัพท์ (10 หลัก)" maxlength="10"><input type="password" id="reg-pass" class="form-control rounded-0 mb-4" placeholder="ตั้งรหัสผ่าน"><button class="btn btn-dark w-100 fw-bold py-2 mb-3 rounded-0" onclick="register()">ลงทะเบียน</button><p class="text-center small mt-2 mb-0">มีบัญชีอยู่แล้ว? <a href="#" onclick="toggleAuth('login')" class="text-primary fw-bold text-decoration-none">เข้าสู่ระบบ</a></p></div></div></div></div></div>
<div class="modal fade" id="profileModal" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><div class="modal-content shadow-lg"><div class="modal-header border-0 text-white position-relative" style="background: linear-gradient(to bottom, rgba(0,0,0,0.3), rgba(17,17,17,1)), url('https://images.unsplash.com/photo-1517466787929-bc90951d0974?q=80&w=600&auto=format&fit=crop'); background-size: cover; background-position: center; height: 150px; align-items: flex-end;"><button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button><h5 class="modal-title fw-bold sport-font w-100 text-center text-warning pb-3"><i class="fa fa-user-cog me-2"></i>ตั้งค่าบัญชี</h5></div><div class="modal-body text-center bg-white px-4 pb-4" style="margin-top: -65px;"><label for="profile-upload" class="position-relative d-inline-block"><img id="setting-profile-pic" src="" class="rounded-circle border border-3 border-dark bg-white shadow" style="width:110px;height:110px;object-fit:cover;cursor:pointer; position: relative; z-index: 2;"><div class="position-absolute bottom-0 end-0 bg-warning rounded-circle p-2 shadow-sm" style="cursor:pointer; z-index: 3;"><i class="fa fa-camera small text-dark"></i></div></label><input type="file" id="profile-upload" class="d-none" accept="image/*" onchange="uploadProfilePic(event)"><div class="text-start mt-4 sport-font"><label class="form-label small fw-bold">ชื่อผู้ใช้ (Username)</label><input type="text" id="set-username" class="form-control form-control-sm rounded-0 mb-3 bg-light" disabled><div class="row g-3 mb-3"><div class="col-6"><label class="form-label small fw-bold">ชื่อแสดงผล</label><input type="text" id="set-name" class="form-control form-control-sm rounded-0"></div><div class="col-6"><label class="form-label small fw-bold">เบอร์โทรศัพท์</label><input type="text" id="set-phone" class="form-control form-control-sm rounded-0" maxlength="10"></div></div><label class="form-label small fw-bold">อีเมล</label><input type="email" id="set-email" class="form-control form-control-sm rounded-0 mb-1" disabled><label class="form-label small fw-bold text-danger mt-3">รหัสผ่านใหม่ (ปล่อยว่างหากไม่เปลี่ยน)</label><input type="password" id="set-password" class="form-control form-control-sm rounded-0 mb-4"></div><button class="btn btn-warning w-100 fw-bold py-2 sport-font rounded-0" onclick="saveProfile()">บันทึกการเปลี่ยนแปลง</button></div></div></div></div>
<div class="modal fade" id="productDetailModal" tabindex="-1"><div class="modal-dialog modal-dialog-centered modal-lg"><div class="modal-content shadow-lg"><div class="modal-header bg-light rounded-0"><h5 class="modal-title fw-bold sport-font">รายละเอียดสินค้า</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><div class="row mb-3"><div class="col-md-5 text-center mb-3 mb-md-0 position-relative"><span id="detail-sale-badge" class="sale-badge d-none">ลดราคา!</span><img id="detail-img" src="" class="img-fluid" style="max-height: 400px; object-fit: contain;" onerror="this.src='https://placehold.co/350x450/111111/ffb800?text=NO+IMAGE&font=Montserrat'"></div><div class="col-md-7"><span id="detail-type" class="badge bg-dark mb-2 sport-font"></span><h3 class="fw-bold sport-font" id="detail-name">ชื่อสินค้า</h3><div class="mb-3 sport-font"><span class="text-danger fw-bold fs-2" id="detail-price">฿0</span><span class="text-muted text-decoration-line-through ms-2 fs-5 d-none" id="detail-old-price">฿0</span></div><p class="text-muted small mb-4" id="detail-desc">รายละเอียดสินค้า...</p><div class="bg-light p-3 mb-4 small sport-font"><p class="mb-2"><i class="fa fa-truck me-2"></i> ค่าจัดส่ง: <strong id="detail-shipping">฿50</strong></p><p class="mb-0 text-success"><i class="fa fa-clock me-2"></i> จัดส่งภายใน 2-3 วัน</p></div><div class="row g-3 align-items-end mb-4 sport-font"><div class="col-8"><label class="form-label fw-bold small">ไซส์ (Size):</label><select id="detail-size" class="form-select rounded-0 border-dark"><option value="S">S</option><option value="M" selected>M</option><option value="L">L</option><option value="XL">XL</option><option value="2XL">2XL</option></select></div><div class="col-4"><label class="form-label fw-bold small">จำนวน:</label><input type="number" id="detail-qty" class="form-control rounded-0 border-dark text-center" value="1" min="1"></div></div><input type="hidden" id="detail-id"><div class="row g-2 sport-font"><div class="col-6"><button type="button" class="btn btn-dark w-100 py-2 fw-bold" onclick="addToCart(false)">เพิ่มลงตะกร้า</button></div><div class="col-6"><button type="button" class="btn btn-warning w-100 py-2 fw-bold" onclick="addToCart(true)">ซื้อทันที</button></div></div></div></div><hr><h6 class="fw-bold sport-font mb-3"><i class="fa fa-comments text-warning me-2"></i>รีวิวจากลูกค้า</h6><div id="detail-reviews" class="bg-light p-3" style="max-height: 200px; overflow-y: auto;"></div></div></div></div></div>
<div class="modal fade" id="checkoutModal" tabindex="-1"><div class="modal-dialog modal-xl"><div class="modal-content shadow-lg"><div class="modal-header bg-dark text-white rounded-0"><h5 class="modal-title fw-bold sport-font"><i class="fa fa-shopping-cart me-2 text-warning"></i>ตะกร้าสินค้า</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div><div class="modal-body p-0"><div class="row g-0"><div class="col-lg-6 p-4 border-end bg-white"><h6 class="fw-bold border-bottom pb-2 mb-3 sport-font">รายการสั่งซื้อ</h6><div id="cart-items-container" style="max-height: 400px; overflow-y: auto;"></div></div><div class="col-lg-6 p-4 bg-light sport-font"><h6 class="fw-bold border-bottom pb-2 mb-3">ที่อยู่จัดส่ง</h6><div class="row g-2 mb-3"><div class="col-sm-6"><input type="text" id="ship-name" class="form-control rounded-0" placeholder="ชื่อผู้รับ"></div><div class="col-sm-6"><input type="text" id="ship-phone" class="form-control rounded-0" placeholder="เบอร์โทร (10หลัก)" maxlength="10"></div><div class="col-12"><textarea id="ship-address" class="form-control rounded-0" rows="2" placeholder="ที่อยู่..."></textarea></div></div><h6 class="fw-bold border-bottom pb-2 mb-3 mt-4">วิธีชำระเงิน</h6><div class="row g-3 mb-4"><div class="col-6"><div class="payment-box active bg-white" id="pay-cod" onclick="selectPayment('COD')"><i class="fa fa-truck fa-2x mb-2 text-dark"></i><div>เก็บเงินปลายทาง</div></div></div><div class="col-6"><div class="payment-box bg-white" id="pay-qr" onclick="selectPayment('QR')"><i class="fa fa-qrcode fa-2x mb-2 text-dark"></i><div>โอนเงิน (QR)</div></div></div></div><div class="card rounded-0 border-0"><div class="card-body bg-white"><div class="input-group mb-3"><input type="text" id="coupon-input" class="form-control rounded-0" placeholder="โค้ดส่วนลด"><button class="btn btn-dark rounded-0 fw-bold" onclick="applyCoupon()">ใช้โค้ด</button></div><div id="my-coupons-list" class="mb-3 small"></div><div class="d-flex justify-content-between mb-2"><span>ราคาสินค้า:</span> <span id="summary-subtotal" class="fw-bold">฿0</span></div><div class="d-flex justify-content-between mb-2"><span>ค่าจัดส่ง:</span> <span id="summary-shipping" class="fw-bold">฿50</span></div><hr><div class="d-flex justify-content-between fw-bold fs-4 mb-4"><span>ยอดสุทธิ:</span> <span class="text-danger" id="summary-total">฿0</span></div><button class="btn btn-warning w-100 fw-bold py-3 fs-5 rounded-0" onclick="confirmOrder()">ยืนยันคำสั่งซื้อ</button></div></div></div></div></div></div></div></div>
<div class="modal fade" id="qrModal" data-bs-backdrop="static" tabindex="-1"><div class="modal-dialog modal-dialog-centered modal-sm"><div class="modal-content text-center py-4 rounded-0 border-dark" style="border-width: 2px;"><div class="modal-body sport-font"><h4 class="fw-bold mb-3">สแกนเพื่อชำระเงิน</h4><img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=081xxxxxxx" class="img-thumbnail mb-3"><p class="text-danger fw-bold display-5 mb-0" id="qr-timer">05:00</p><p class="small text-muted mb-4">กรุณาโอนเงินภายในเวลาที่กำหนด</p><button class="btn btn-dark w-100 fw-bold mb-2 rounded-0" onclick="qrPaymentSuccess()">จำลองการโอนสำเร็จ</button><button class="btn btn-outline-dark w-100 rounded-0" onclick="qrPaymentCancel()">ยกเลิก</button></div></div></div></div>

<div class="modal fade" id="historyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow-lg bg-light">
            <div class="modal-header border-0 text-white rounded-0 position-relative" style="background: linear-gradient(to right, rgba(17,17,17,1), rgba(17,17,17,0.4)), url('<?= $randomBg ?>'); background-size: cover; background-position: center; height: 100px; align-items: center;">
                <h4 class="modal-title fw-bold sport-font text-warning"><i class="fa fa-history me-2"></i>ประวัติการสั่งซื้อของคุณ</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="history-container"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="reviewModal" tabindex="-1"><div class="modal-dialog modal-dialog-centered modal-sm"><div class="modal-content shadow"><div class="modal-header bg-warning rounded-0"><h5 class="modal-title fw-bold sport-font">ให้คะแนนสินค้า</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body text-center sport-font"><div class="star-rating fs-1 mb-3" id="review-stars"><i class="fa fa-star" onclick="setRating(1)"></i><i class="fa fa-star" onclick="setRating(2)"></i><i class="fa fa-star" onclick="setRating(3)"></i><i class="fa fa-star" onclick="setRating(4)"></i><i class="fa fa-star" onclick="setRating(5)"></i></div><textarea id="review-comment" class="form-control rounded-0 mb-3" rows="3" placeholder="เขียนรีวิว..."></textarea><button class="btn btn-dark w-100 fw-bold rounded-0" onclick="submitReview()">ส่งรีวิว</button></div></div></div></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // สั่งให้สไลด์วิ่งอัตโนมัติ 5 วิ
    document.addEventListener("DOMContentLoaded", function() {
        var myCarousel = document.querySelector('#heroCarousel');
        var carousel = new bootstrap.Carousel(myCarousel, {
            interval: 5000,
            ride: 'carousel',
            pause: 'hover'
        });
    });

    const dbProducts = <?php echo json_encode($productsFromDB); ?>;
    const products = dbProducts.map(p => ({
        id: parseInt(p.id), name: p.name, price: parseFloat(p.price),
        isSale: parseInt(p.is_sale) === 1, oldPrice: parseFloat(p.old_price) > 0 ? parseFloat(p.old_price) : null, 
        type: p.type || "ทั่วไป", img: p.img ? (p.img.startsWith('http') || p.img.startsWith('data:') ? p.img : 'uploads/' + p.img) : 'default.jpg', desc: p.desc
    }));

    let currentUser = null; let cart = []; let isFreeShipping = false; let paymentMethod = 'COD';
    let qrTimerInterval; let currentReviewProduct = null; let currentReviewOrder = null; let selectedRating = 0;

    function initStore() {
        renderProducts(products);
        fetch('api_auth.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ action: 'check_session' }) })
        .then(res => res.json()).then(data => { if(data.success) setupUserSession(data.user); });
    }

    function setupUserSession(userData) {
        currentUser = userData;
        currentUser.profilePic = (userData.profilePic && userData.profilePic.startsWith('data:image')) ? userData.profilePic : `https://ui-avatars.com/api/?name=${encodeURIComponent(currentUser.username)}&background=111111&color=ffb800&bold=true`;
        const localData = JSON.parse(localStorage.getItem('thanjai_data_' + currentUser.username)) || {};
        cart = localData.cart || []; currentUser.orders = localData.orders || []; currentUser.coupons = localData.coupons || [];
        updateNavUI(); updateCartBadge();
    }

    function saveDatabase() { if(currentUser) localStorage.setItem('thanjai_data_' + currentUser.username, JSON.stringify({ cart: cart, orders: currentUser.orders, coupons: currentUser.coupons })); }

    function showToast(message, type = 'success') {
        const toastEl = document.getElementById('liveToast');
        toastEl.classList.remove('bg-success', 'bg-danger', 'bg-warning', 'text-dark', 'bg-dark');
        if(type === 'success') toastEl.classList.add('bg-dark'); 
        else if(type === 'error') toastEl.classList.add('bg-danger');
        else if(type === 'warning') toastEl.classList.add('bg-warning', 'text-dark');
        document.getElementById('toast-msg').innerHTML = message;
        new bootstrap.Toast(toastEl, { delay: 3000 }).show();
    }

    function openAuthModal(type) { toggleAuth(type); new bootstrap.Modal(document.getElementById('authModal')).show(); }
    function toggleAuth(type) { document.getElementById('login-section').classList.toggle('d-none', type !== 'login'); document.getElementById('register-section').classList.toggle('d-none', type !== 'register'); document.getElementById('authModalTitle').innerText = type === 'login' ? 'เข้าสู่ระบบ' : 'สมัครสมาชิกใหม่'; }

    function register() {
        const user = document.getElementById('reg-user').value.trim(), email = document.getElementById('reg-email').value.trim(), phone = document.getElementById('reg-phone').value.trim(), pass = document.getElementById('reg-pass').value.trim();
        if(!user || !email || !phone || !pass) return showToast('กรุณากรอกข้อมูลให้ครบ', 'error');
        fetch('api_auth.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ action: 'register', username: user, email: email, phone: phone, password: pass }) })
        .then(res => res.json()).then(data => { if(data.success) { setupUserSession(data.user); bootstrap.Modal.getInstance(document.getElementById('authModal')).hide(); showToast('สมัครสมาชิกสำเร็จ', 'success'); } else showToast(data.message, 'error'); });
    }

    function login() {
        const user = document.getElementById('login-user').value.trim(), pass = document.getElementById('login-pass').value.trim();
        if(!user || !pass) return showToast('กรุณากรอกข้อมูล', 'warning');
        fetch('api_auth.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ action: 'login', username: user, password: pass }) })
        .then(res => res.json()).then(data => {
            if(data.success) {
                if(data.user.role === 'admin') window.location.href = 'admin_orders.php';
                else { setupUserSession(data.user); bootstrap.Modal.getInstance(document.getElementById('authModal')).hide(); showToast(`ยินดีต้อนรับ!`, 'success'); }
            } else showToast(data.message, 'error');
        });
    }

    function logoutUser() {
        if(confirm("ออกจากระบบ?")) {
            fetch('api_auth.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ action: 'logout' }) })
            .then(res => res.json()).then(data => { if(data.success) { currentUser = null; cart = []; updateNavUI(); updateCartBadge(); showToast('ออกจากระบบแล้ว', 'success'); } });
        }
    }

    function updateNavUI() {
        if(currentUser) {
            document.getElementById('guest-zone').classList.add('d-none'); document.getElementById('user-zone').classList.remove('d-none');
            document.getElementById('nav-username').innerText = currentUser.name || currentUser.username;
            document.getElementById('nav-profile-pic').src = currentUser.profilePic; document.getElementById('setting-profile-pic').src = currentUser.profilePic;
            document.getElementById('set-username').value = currentUser.username; document.getElementById('set-name').value = currentUser.name || currentUser.username;
            document.getElementById('set-phone').value = currentUser.phone || ''; document.getElementById('set-email').value = currentUser.email || ''; document.getElementById('set-password').value = '';
            document.getElementById('nav-admin-link').classList.toggle('d-none', currentUser.role !== 'admin');
        } else { document.getElementById('guest-zone').classList.remove('d-none'); document.getElementById('user-zone').classList.add('d-none'); }
    }

    function uploadProfilePic(event) { const file = event.target.files[0]; if(file) { const reader = new FileReader(); reader.onload = function(e) { document.getElementById('setting-profile-pic').src = e.target.result; }; reader.readAsDataURL(file); } }

    function saveProfile() {
        const newName = document.getElementById('set-name').value.trim(), newPhone = document.getElementById('set-phone').value.trim(), newPass = document.getElementById('set-password').value.trim(), newPic = document.getElementById('setting-profile-pic').src;
        if(!newName || !newPhone) return showToast('กรุณากรอกชื่อและเบอร์โทร', 'warning');
        let picDataToSend = newPic.startsWith('data:image') ? newPic : '';
        fetch('api_auth.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ action: 'update_profile', fullname: newName, phone: newPhone, password: newPass, profile_pic: picDataToSend }) })
        .then(res => res.json()).then(data => {
            if(data.success) { currentUser.name = newName; currentUser.phone = newPhone; if(data.profile_pic) currentUser.profilePic = data.profile_pic; updateNavUI(); bootstrap.Modal.getInstance(document.getElementById('profileModal')).hide(); showToast('อัปเดตข้อมูลแล้ว', 'success'); } else showToast(data.message, 'error');
        });
    }

    function renderProducts(items) {
        const container = document.getElementById('store-display');
        if(items.length === 0) { container.innerHTML = `<div class="col-12 text-center text-muted my-5"><h5 class="sport-font">ไม่พบสินค้า</h5></div>`; return; }
        container.innerHTML = items.map(p => {
            const saleBadge = p.isSale ? `<span class="sale-badge">SALE</span>` : '';
            const oldPriceStr = (p.isSale && p.oldPrice) ? `<small class="text-muted text-decoration-line-through">฿${p.oldPrice.toLocaleString()}</small>` : '';
            return `
            <div class="col-6 col-md-3 mb-4">
                <div class="card product-card h-100 rounded-0 shadow-sm border-0">
                    <div class="product-img-wrap" onclick="openProductDetail(${p.id})" style="cursor:pointer;">
                        ${saleBadge}
                        <img src="${p.img}" onerror="this.src='https://placehold.co/350x450/111111/ffb800?text=NO+IMAGE&font=Montserrat'" alt="${p.name}">
                    </div>
                    <div class="card-body d-flex flex-column text-center p-3">
                        <small class="text-muted mb-1" style="font-size: 10px; text-transform:uppercase;">${p.type}</small>
                        <h6 class="card-title fw-bold text-dark mb-2" style="font-size: 14px;">${p.name}</h6>
                        <div class="mt-auto"><span class="text-dark fw-bold fs-5">฿${p.price.toLocaleString()}</span> ${oldPriceStr}</div>
                    </div>
                </div>
            </div>`;
        }).join('');
    }

    function searchProducts() { const term = document.getElementById('search-input').value.toLowerCase(); renderProducts(products.filter(p => p.name.toLowerCase().includes(term))); }
    function filterProducts(type) {
        if(event && event.currentTarget.classList.contains('filter-btn')) {
            document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active', 'btn-dark'));
            document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.add('btn-outline-dark'));
            event.currentTarget.classList.remove('btn-outline-dark'); event.currentTarget.classList.add('btn-dark', 'active');
        }
        if (type === 'โปรโมชั่น') renderProducts(products.filter(p => p.isSale));
        else renderProducts(type === 'ทั้งหมด' ? products : products.filter(p => p.type === type));
    }

    function openProductDetail(id) {
        const p = products.find(x => x.id === id);
        document.getElementById('detail-id').value = p.id; document.getElementById('detail-name').innerText = p.name; document.getElementById('detail-price').innerText = '฿' + p.price.toLocaleString();
        if(p.isSale && p.oldPrice) { document.getElementById('detail-old-price').innerText = '฿' + p.oldPrice.toLocaleString(); document.getElementById('detail-old-price').classList.remove('d-none'); document.getElementById('detail-sale-badge').classList.remove('d-none'); } 
        else { document.getElementById('detail-old-price').classList.add('d-none'); document.getElementById('detail-sale-badge').classList.add('d-none'); }
        document.getElementById('detail-desc').innerText = p.desc; document.getElementById('detail-type').innerText = p.type; document.getElementById('detail-img').src = p.img; document.getElementById('detail-qty').value = 1; document.getElementById('detail-size').selectedIndex = 1; 
        document.getElementById('detail-shipping').innerHTML = (currentUser && currentUser.coupons && currentUser.coupons.includes('FREESHIP')) ? '<span class="text-success fw-bold">ส่งฟรี</span>' : '฿50';
        
        const reviewContainer = document.getElementById('detail-reviews'); reviewContainer.innerHTML = '<div class="text-center py-2"><div class="spinner-border text-dark spinner-border-sm"></div></div>';
        fetch('api_auth.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ action: 'get_reviews', product_id: id }) })
        .then(res => res.json()).then(data => {
            if(data.success && data.reviews.length > 0) {
                reviewContainer.innerHTML = data.reviews.map(r => {
                    let stars = ''; for(let i=0; i<5; i++) stars += `<i class="fa fa-star ${i < r.rating ? 'text-warning' : 'text-muted'}"></i>`;
                    let pic = r.profile_pic ? r.profile_pic : `https://ui-avatars.com/api/?name=${encodeURIComponent(r.username)}&background=111111&color=ffb800&bold=true`;
                    return `<div class="d-flex mb-3 border-bottom pb-2"><img src="${pic}" width="40" height="40" class="rounded-circle me-3 border border-dark" style="object-fit:cover;"><div><div class="fw-bold">${r.fullname || r.username}</div><div class="small mb-1">${stars}</div><p class="small mb-1">${r.comment}</p><small class="text-muted" style="font-size: 10px;">${r.created_at}</small></div></div>`;
                }).join('');
            } else reviewContainer.innerHTML = '<p class="text-muted small text-center my-2">ยังไม่มีรีวิว</p>';
        });
        new bootstrap.Modal(document.getElementById('productDetailModal')).show();
    }

    function addToCart(isBuyNow) {
        if(!currentUser) { bootstrap.Modal.getInstance(document.getElementById('productDetailModal')).hide(); return openAuthModal('login'); }
        const id = parseInt(document.getElementById('detail-id').value), size = document.getElementById('detail-size').value, qty = parseInt(document.getElementById('detail-qty').value);
        const product = products.find(p => p.id === id);
        const existingItemIndex = cart.findIndex(item => item.id === id && item.size === size);
        if (existingItemIndex > -1) cart[existingItemIndex].qty += qty; else cart.push({ ...product, size: size, qty: qty }); 
        saveDatabase(); updateCartBadge(); bootstrap.Modal.getInstance(document.getElementById('productDetailModal')).hide(); showToast('เพิ่มลงตะกร้าแล้ว', 'success');
        if(isBuyNow) setTimeout(openCart, 500);
    }
    
    function updateCartBadge() { document.getElementById('cart-count').innerText = cart.reduce((sum, item) => sum + item.qty, 0); }

    function openCart() {
        if(!currentUser) return openAuthModal('login');
        document.getElementById('ship-name').value = currentUser.name || ''; document.getElementById('ship-phone').value = currentUser.phone || '';
        isFreeShipping = false; document.getElementById('coupon-input').value = ''; renderCartItems(); updateSummary(); new bootstrap.Modal(document.getElementById('checkoutModal')).show();
    }

    function renderCartItems() {
        const container = document.getElementById('cart-items-container');
        if(cart.length === 0) { container.innerHTML = `<div class="text-center py-5"><p class="text-muted">ตะกร้าว่างเปล่า</p></div>`; return; }
        container.innerHTML = cart.map((item, index) => `
            <div class="d-flex align-items-center mb-3 p-3 border bg-white">
                <img src="${item.img}" width="70" height="90" class="me-3 bg-light" style="object-fit: cover;" onerror="this.src='https://placehold.co/350x450/111111/ffb800?text=NO+IMAGE&font=Montserrat'">
                <div class="flex-grow-1"><h6 class="mb-1 fw-bold">${item.name}</h6><small class="text-muted d-block">ไซส์: ${item.size} | จำนวน: ${item.qty}</small></div>
                <div class="text-end ms-2"><div class="fw-bold mb-2">฿${(item.price * item.qty).toLocaleString()}</div><button class="btn btn-sm btn-outline-danger py-0 px-2" onclick="removeFromCart(${index})">ลบ</button></div>
            </div>`).join('');
    }

    function removeFromCart(index) { cart.splice(index, 1); saveDatabase(); updateCartBadge(); renderCartItems(); updateSummary(); }

    function collectCoupon(code) {
        if(!currentUser) return openAuthModal('login');
        if(!currentUser.coupons) currentUser.coupons = [];
        if(currentUser.coupons.includes(code)) return showToast('เก็บคูปองนี้ไปแล้ว', 'warning');
        currentUser.coupons.push(code); saveDatabase(); showToast(`เก็บโค้ดสำเร็จ!`, 'success');
    }

    function renderMyCoupons() {
        const container = document.getElementById('my-coupons-list');
        if(!currentUser || !currentUser.coupons || currentUser.coupons.length === 0) { container.innerHTML = ''; return; }
        container.innerHTML = '<strong class="text-dark">คูปองของคุณ:</strong> ' + currentUser.coupons.map(c => `<span class="badge bg-dark text-white mx-1 p-2" style="cursor:pointer;" onclick="document.getElementById('coupon-input').value='${c}'; applyCoupon();">${c}</span>`).join('');
    }

    function applyCoupon() {
        const code = document.getElementById('coupon-input').value.trim().toUpperCase();
        if(code === 'FREESHIP') {
            if(!currentUser.coupons || !currentUser.coupons.includes(code)) return showToast('ไม่มีคูปองนี้', 'error');
            isFreeShipping = true; showToast('ใช้คูปองส่งฟรีสำเร็จ!', 'success'); updateSummary();
        } else showToast('รหัสคูปองไม่ถูกต้อง', 'error'); 
    }

    function updateSummary() {
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        let shipping = cart.length > 0 ? 50 : 0;
        if(isFreeShipping && cart.length > 0) { document.getElementById('summary-shipping').innerHTML = '<s class="text-muted">฿50</s> <strong class="text-success">ฟรี</strong>'; shipping = 0; } 
        else document.getElementById('summary-shipping').innerText = '฿' + shipping;
        document.getElementById('summary-subtotal').innerText = '฿' + subtotal.toLocaleString();
        document.getElementById('summary-total').innerText = '฿' + (subtotal + shipping).toLocaleString();
        renderMyCoupons();
    }

    function selectPayment(method) { paymentMethod = method; document.getElementById('pay-cod').classList.toggle('active', method === 'COD'); document.getElementById('pay-qr').classList.toggle('active', method === 'QR'); }

    function confirmOrder() {
        if(cart.length === 0) return showToast('ไม่มีสินค้าในตะกร้า', 'warning');
        if(!document.getElementById('ship-address').value.trim()) return showToast('กรุณากรอกที่อยู่', 'error');
        if(paymentMethod === 'QR') { bootstrap.Modal.getInstance(document.getElementById('checkoutModal')).hide(); new bootstrap.Modal(document.getElementById('qrModal')).show(); startQrTimer(); } 
        else processOrderSuccess();
    }

    function startQrTimer() {
        let time = 300; const display = document.getElementById('qr-timer'); clearInterval(qrTimerInterval);
        qrTimerInterval = setInterval(() => { const m = Math.floor(time / 60).toString().padStart(2, '0'), s = (time % 60).toString().padStart(2, '0'); display.innerText = `${m}:${s}`; if(time <= 0) qrPaymentCancel(); time--; }, 1000);
    }
    function qrPaymentSuccess() { clearInterval(qrTimerInterval); bootstrap.Modal.getInstance(document.getElementById('qrModal')).hide(); processOrderSuccess(); }
    function qrPaymentCancel() { clearInterval(qrTimerInterval); bootstrap.Modal.getInstance(document.getElementById('qrModal')).hide(); showToast('ยกเลิกชำระเงิน', 'error'); new bootstrap.Modal(document.getElementById('checkoutModal')).show(); }

    function processOrderSuccess() {
        const total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0) + (isFreeShipping ? 0 : (cart.length > 0 ? 50 : 0));
        fetch('save_order.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ username: currentUser ? currentUser.username : null, total: total, status: paymentMethod === 'QR' ? 'paid' : 'pending', address: document.getElementById('ship-address').value.trim(), items: cart }) })
        .then(res => res.json()).then(data => {
            if(data.success) {
                cart = []; if(isFreeShipping && currentUser.coupons) currentUser.coupons = currentUser.coupons.filter(c => c !== 'FREESHIP'); isFreeShipping = false; saveDatabase(); updateCartBadge();
                if(document.getElementById('checkoutModal').classList.contains('show')) bootstrap.Modal.getInstance(document.getElementById('checkoutModal')).hide();
                showToast('สั่งซื้อสำเร็จ!', 'success'); setTimeout(openOrderHistory, 1500); 
            } else showToast(data.message, 'error');
        });
    }

    function setRating(stars) { selectedRating = stars; const els = document.getElementById('review-stars').children; for(let i=0; i<5; i++) { if(i < stars) els[i].classList.add('text-warning', 'active'); else els[i].classList.remove('text-warning', 'active'); } }
    function openReviewModal(prodId, orderId) { currentReviewProduct = prodId; currentReviewOrder = orderId; setRating(0); document.getElementById('review-comment').value = ''; bootstrap.Modal.getInstance(document.getElementById('historyModal')).hide(); new bootstrap.Modal(document.getElementById('reviewModal')).show(); }
    function submitReview() {
        if(selectedRating === 0) return showToast('กรุณาให้ดาว', 'warning');
        fetch('api_auth.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ action: 'add_review', product_id: currentReviewProduct, order_id: currentReviewOrder, rating: selectedRating, comment: document.getElementById('review-comment').value.trim() }) })
        .then(res => res.json()).then(data => { if(data.success) { bootstrap.Modal.getInstance(document.getElementById('reviewModal')).hide(); showToast('ส่งรีวิวสำเร็จ', 'success'); setTimeout(openOrderHistory, 500); } else showToast(data.message, 'error'); });
    }

    function openOrderHistory() {
        if(!currentUser) return showToast('กรุณาเข้าสู่ระบบ', 'warning');
        const container = document.getElementById('history-container'); container.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-dark"></div></div>';
        if(!document.getElementById('historyModal').classList.contains('show')) new bootstrap.Modal(document.getElementById('historyModal')).show();
        fetch('api_auth.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ action: 'get_my_orders' }) }).then(res => res.json()).then(data => {
            if(data.success) {
                if(data.orders.length === 0) container.innerHTML = `<div class="text-center py-4"><p>ไม่มีประวัติสั่งซื้อ</p></div>`;
                else container.innerHTML = data.orders.map(o => {
                    let badgeClass = 'bg-secondary', statusText = o.status, currentStatus = (o.status || '').trim().toLowerCase();
                    if(currentStatus === 'pending') { badgeClass = 'bg-dark'; statusText = 'รอชำระเงิน / COD'; } else if(currentStatus === 'paid') { badgeClass = 'bg-success'; statusText = 'ชำระเงินแล้ว'; } else if(currentStatus === 'shipped') { badgeClass = 'bg-primary'; statusText = 'จัดส่งแล้ว'; } else if(currentStatus === 'cancelled') { badgeClass = 'bg-danger'; statusText = 'ยกเลิก'; }
                    return `
                    <div class="card mb-3 rounded-0 border sport-font shadow-sm">
                        <div class="card-header bg-light d-flex justify-content-between"><div><b>${o.orderId}</b><br><small>${o.date}</small></div><span class="badge ${badgeClass} align-self-center py-2">${statusText}</span></div>
                        <div class="card-body">
                            ${o.items.map(item => `
                            <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                <div class="d-flex"><img src="${item.img}" onerror="this.src='https://placehold.co/350x450/111111/ffb800?text=NO+IMAGE&font=Montserrat'" width="50" height="65" class="me-3 bg-light border border-dark" style="object-fit:cover;"><div><b>${item.name}</b> <small>(${item.size})</small><br>${(currentStatus === 'shipped' || statusText === 'จัดส่งแล้ว') ? `<button class="btn btn-sm btn-dark mt-1 py-0" onclick="openReviewModal(${item.product_id}, ${o.rawOrderId})">รีวิว</button>` : ''}</div></div>
                                <span>x${item.qty} = <b>฿${(item.price * item.qty).toLocaleString()}</b></span>
                            </div>`).join('')}
                            <div class="text-end mt-2">ยอดสุทธิ: <b class="text-danger fs-5">฿${o.total.toLocaleString()}</b></div>
                        </div>
                    </div>`;
                }).join('');
            }
        });
    }
</script>
</body>
</html>