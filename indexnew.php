<?php
require_once 'db.php';

// ดึงข้อมูลสินค้า พร้อมกับข้อมูลลดราคา
$sql = "SELECT p.id, p.name, p.price, p.is_sale, p.old_price, p.description as `desc`, p.image as img, c.name as type 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id";
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
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Sarabun:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { 
            --primary: #0a0a0a; /* ดำเข้ม */
            --accent: #FBBF24; /* เหลืองทองสปอร์ต */
            --secondary: #1e293b; /* กรมท่าเข้ม */
        }
        body { font-family: 'Sarabun', sans-serif; background: #f1f5f9; }
        h1, h2, h3, h4, h5, h6, .nav-link, .btn, .navbar-brand, .sport-font { font-family: 'Kanit', sans-serif; }
        
        .navbar { background: var(--primary) !important; border-bottom: 3px solid var(--accent); padding: 15px 0;}
        .navbar-brand { font-size: 1.5rem; text-transform: uppercase; letter-spacing: 1px; }
        
        /* แบนเนอร์ CR7 */
       /* แบนเนอร์ CR7 หน้าร้าน */
        .hero-banner { 
            background: linear-gradient(to right, rgba(10, 10, 10, 0.9), rgba(10, 10, 10, 0.2)), url('https://i.pinimg.com/originals/24/ce/68/24ce68fdfb5cf287ccf615fca58f5043.jpg');
            background-size: cover; background-position: top center; height: 450px; display: flex; align-items: center; color: white;
            border-bottom: 5px solid var(--accent);
        }
        .hero-banner h1 { font-size: 4rem; text-transform: uppercase; font-weight: 800; font-style: italic; letter-spacing: 2px; text-shadow: 2px 2px 10px rgba(0,0,0,0.8); }
        .hero-banner p { font-size: 1.2rem; text-shadow: 1px 1px 5px rgba(0,0,0,0.8); }
        
        .btn-warning { background-color: var(--accent); border: none; color: #000; font-weight: 600; border-radius: 4px; text-transform: uppercase; }
        .btn-warning:hover { background-color: #eab308; color: #000; transform: scale(1.02); transition: 0.2s;}
        .btn-dark { background-color: var(--primary); border: none; border-radius: 4px; font-weight: 500;}
        
        .product-card { border: none; border-radius: 8px; transition: 0.3s; overflow: hidden; height: 100%; position: relative; background: #fff; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .product-card:hover { transform: translateY(-7px); box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); border-bottom: 4px solid var(--accent); }
        .product-card img { height: 260px; object-fit: cover; }
        .product-card .card-title { font-size: 15px; }
        
        .sale-badge { position: absolute; top: 12px; right: 12px; background: #dc2626; color: white; padding: 5px 15px; border-radius: 4px; font-weight: bold; font-size: 12px; z-index: 10; box-shadow: 0 4px 6px rgba(0,0,0,0.3); font-family: 'Kanit'; text-transform: uppercase; letter-spacing: 1px;}
        .member-badge { font-size: 0.7rem; background: var(--accent); color: var(--primary); padding: 3px 10px; border-radius: 4px; font-weight: bold; }
        .cart-badge { font-size: 0.7rem; padding: 3px 6px; }
        
        .payment-box { border: 2px solid #e2e8f0; border-radius: 8px; padding: 15px; cursor: pointer; transition: 0.2s; text-align: center; font-family: 'Kanit'; }
        .payment-box:hover { border-color: var(--accent); background: #fefce8; }
        .payment-box.active { border-color: var(--accent); background: #fef9c3; font-weight: bold; }
        .profile-img-preview { width: 100px; height: 100px; object-fit: cover; border-radius: 50%; border: 3px solid var(--accent); cursor: pointer; }
        .star-rating i { color: #cbd5e1; cursor: pointer; transition: 0.2s; }
        .star-rating i.active { color: var(--accent); }
        .star-rating i:hover { transform: scale(1.2); }
        .toast-container { z-index: 1060; }
        .toast { border-radius: 8px; font-weight: bold; border: none; box-shadow: 0 10px 15px rgba(0,0,0,0.2); font-family: 'Kanit';}
        
        /* ปุ่มตัวกรอง */
        .filter-btn { font-family: 'Kanit'; text-transform: uppercase; letter-spacing: 0.5px; border-radius: 4px !important; font-weight: 500; }
    </style>
</head>
<body onload="initStore()">

<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body text-white d-flex justify-content-between align-items-center" id="toast-body">
            <span id="toast-msg">ข้อความ</span>
            <button type="button" class="btn-close btn-close-white ms-2" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top shadow">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#" onclick="filterProducts('ทั้งหมด')">ThanJai <span style="color: var(--accent);">Shop</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link active" href="#" onclick="filterProducts('ทั้งหมด')"><i class="fa fa-home me-1"></i> หน้าหลัก</a></li>
                <li class="nav-item"><a class="nav-link" href="#product-list" onclick="filterProducts('ทั้งหมด')"><i class="fa fa-list me-1"></i> สินค้าทั้งหมด</a></li>
                <li class="nav-item"><a class="nav-link text-danger fw-bold" href="#" onclick="filterProducts('โปรโมชั่น')"><i class="fa fa-fire me-1"></i> โปรโมชั่นลดราคา</a></li>
            </ul>
            
            <div class="d-flex align-items-center gap-3">
                <div class="input-group input-group-sm d-none d-md-flex">
                    <input type="text" id="search-input" class="form-control border-0" placeholder="ค้นหาชื่อทีม..." onkeyup="searchProducts()" style="font-family: 'Kanit';">
                </div>

                <div id="guest-zone">
                    <button class="btn btn-warning btn-sm shadow-sm" onclick="openAuthModal('login')"><i class="fa fa-sign-in-alt me-1"></i> เข้าสู่ระบบ</button>
                </div>

                <div id="user-zone" class="d-none">
                    <div class="dropdown text-white">
                        <span class="me-2 d-none d-md-inline sport-font">ยินดีต้อนรับ, <strong id="nav-username" class="text-warning">ผู้ใช้</strong> <span class="member-badge ms-1">member</span></span>
                        <a href="#" class="link-light dropdown-toggle" data-bs-toggle="dropdown">
                            <img id="nav-profile-pic" src="" width="40" height="40" class="rounded-circle border border-warning" style="object-fit: cover;">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow sport-font">
                            <li id="nav-admin-link" class="d-none"><a class="dropdown-item text-warning fw-bold bg-dark" href="admin_orders.php"><i class="fa fa-user-shield me-2"></i>ระบบหลังบ้าน (Admin)</a></li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profileModal"><i class="fa fa-cog me-2"></i>ตั้งค่าบัญชี</a></li>
                            <li><a class="dropdown-item" href="#" onclick="openOrderHistory()"><i class="fa fa-box-open me-2"></i>ประวัติสั่งซื้อ & รีวิว</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger fw-bold" href="#" onclick="logoutUser()"><i class="fa fa-sign-out-alt me-2"></i>ออกจากระบบ</a></li>
                        </ul>
                    </div>
                </div>
                
                <a href="#" class="text-white position-relative ms-2" onclick="openCart()">
                    <i class="fa fa-shopping-cart fs-4"></i>
                    <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-badge border border-light">0</span>
                </a>
            </div>
        </div>
    </div>
</nav>

<header class="hero-banner" id="hero-banner">
    <div class="container">
        <h1 class="mb-3">NEW SEASON <span class="text-warning">2026</span></h1>
        <p class="lead mb-4">คอลเลคชั่นใหม่ล่าสุดจากสโมสรดังทั่วโลก พร้อมส่งแล้ววันนี้! สวมใส่ความมุ่งมั่น สวมใส่ความสำเร็จ</p>
        <a href="#product-list" class="btn btn-warning btn-lg px-5 py-3 shadow"><i class="fa fa-bolt me-2"></i> เลือกช้อปเลย</a>
    </div>
</header>

<div class="container mt-4" id="promo-banner">
    <div class="alert shadow-sm border-0 text-center position-relative overflow-hidden sport-font" style="background-color: var(--secondary); color: white;">
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: url('https://www.transparenttextures.com/patterns/carbon-fibre.png'); opacity: 0.3;"></div>
        <h3 class="fw-bold mb-2 position-relative text-warning"><i class="fa fa-truck-fast me-2"></i> โค้ดส่งฟรี! สำหรับสมาชิกใหม่</h3>
        <p class="mb-3 fs-5 position-relative">รับสิทธิ์จัดส่งฟรีทั่วประเทศ (โค้ด: <span class="bg-danger px-2 py-1 rounded ms-1">FREESHIP</span>)</p>
        <button class="btn btn-light fw-bold position-relative px-4" onclick="collectCoupon('FREESHIP')">
            <i class="fa fa-hand-holding-heart me-1 text-danger"></i> เก็บและใช้งานคูปอง
        </button>
    </div>
</div>

<main class="container my-5" id="product-list">
    <div class="d-flex justify-content-center gap-2 mb-5 overflow-auto pb-2" id="category-filters">
        <button class="btn btn-dark px-4 filter-btn active shadow-sm" onclick="filterProducts('ทั้งหมด')">ทั้งหมด</button>
        <?php foreach($categoriesFromDB as $cat): ?>
            <button class="btn btn-outline-dark px-4 filter-btn" onclick="filterProducts('<?= htmlspecialchars($cat['name']) ?>')"><?= htmlspecialchars($cat['name']) ?></button>
        <?php endforeach; ?>
    </div>
    <div class="row g-4" id="store-display"></div>
</main>

<div class="modal fade" id="authModal" tabindex="-1"><div class="modal-dialog modal-dialog-centered modal-sm"><div class="modal-content"><div class="modal-header bg-dark text-white"><h5 class="modal-title fw-bold sport-font" id="authModalTitle">เข้าสู่ระบบ</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div><div class="modal-body sport-font"><div id="login-section"><input type="text" id="login-user" class="form-control mb-2" placeholder="ชื่อผู้ใช้ (Username)"><input type="password" id="login-pass" class="form-control mb-3" placeholder="รหัสผ่าน"><button class="btn btn-warning w-100 fw-bold mb-2" onclick="login()">เข้าสู่ระบบ</button><p class="text-center small mt-2 mb-0">ผู้ใช้ใหม่ใช่ไหม? <a href="#" onclick="toggleAuth('register')" class="text-primary fw-bold">สมัครสมาชิกที่นี่</a></p></div><div id="register-section" class="d-none"><input type="text" id="reg-user" class="form-control mb-2" placeholder="ตั้งชื่อผู้ใช้ (Username)"><input type="email" id="reg-email" class="form-control mb-2" placeholder="อีเมล (เช่น name@email.com)"><input type="text" id="reg-phone" class="form-control mb-2" placeholder="เบอร์โทรศัพท์ (10 หลัก)" maxlength="10"><input type="password" id="reg-pass" class="form-control mb-3" placeholder="ตั้งรหัสผ่าน"><button class="btn btn-dark w-100 fw-bold mb-2" onclick="register()">ลงทะเบียน</button><p class="text-center small mt-2 mb-0">มีบัญชีอยู่แล้ว? <a href="#" onclick="toggleAuth('login')" class="text-primary fw-bold">เข้าสู่ระบบ</a></p></div></div></div></div></div>
<div class="modal fade" id="profileModal" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><div class="modal-content border-0"><div class="modal-header bg-dark text-white"><h5 class="modal-title fw-bold sport-font"><i class="fa fa-user-cog me-2 text-warning"></i>ตั้งค่าบัญชี</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div><div class="modal-body text-center"><label for="profile-upload" class="position-relative d-inline-block"><img id="setting-profile-pic" src="" class="profile-img-preview mb-2 shadow"><div class="position-absolute bottom-0 end-0 bg-warning rounded-circle p-2 border border-dark shadow" style="cursor: pointer;"><i class="fa fa-camera small"></i></div></label><input type="file" id="profile-upload" class="d-none" accept="image/*" onchange="uploadProfilePic(event)"><div class="text-start mt-4 sport-font"><label class="form-label small fw-bold text-primary">ชื่อผู้ใช้สำหรับล็อกอิน (Username)</label><input type="text" id="set-username" class="form-control form-control-sm mb-3 bg-light" disabled><div class="row g-3 mb-3"><div class="col-6"><label class="form-label small fw-bold">ชื่อแสดงผล (Display Name)</label><input type="text" id="set-name" class="form-control form-control-sm"></div><div class="col-6"><label class="form-label small fw-bold">เบอร์โทรศัพท์</label><input type="text" id="set-phone" class="form-control form-control-sm" maxlength="10"></div></div><label class="form-label small fw-bold">อีเมล</label><input type="email" id="set-email" class="form-control form-control-sm mb-1" disabled><small class="text-muted d-block mb-3" style="font-family:'Sarabun';">ไม่สามารถเปลี่ยนชื่อผู้ใช้และอีเมลได้</small><label class="form-label small fw-bold text-danger">รหัสผ่านใหม่ (ปล่อยว่างหากไม่ต้องการเปลี่ยน)</label><input type="password" id="set-password" class="form-control form-control-sm mb-4"></div><button class="btn btn-warning w-100 fw-bold sport-font shadow-sm py-2" onclick="saveProfile()">บันทึกการเปลี่ยนแปลง</button></div></div></div></div>
<div class="modal fade" id="productDetailModal" tabindex="-1"><div class="modal-dialog modal-dialog-centered modal-lg"><div class="modal-content border-0"><div class="modal-header bg-dark text-white"><h5 class="modal-title fw-bold sport-font">รายละเอียดสินค้า</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div><div class="modal-body"><div class="row mb-3"><div class="col-md-5 text-center mb-3 mb-md-0 position-relative"><span id="detail-sale-badge" class="sale-badge d-none">ลดราคา!</span><img id="detail-img" src="" class="img-fluid rounded shadow-sm" style="max-height: 350px; object-fit: contain;" onerror="this.src='https://via.placeholder.com/350?text=No+Image'"></div><div class="col-md-7"><span id="detail-type" class="badge bg-secondary mb-2 sport-font"></span><h3 class="fw-bold sport-font" id="detail-name">ชื่อสินค้า</h3><div class="mb-3 sport-font"><span class="text-danger fw-bold fs-2" id="detail-price">฿0</span><span class="text-muted text-decoration-line-through ms-2 fs-5 d-none" id="detail-old-price">฿0</span></div><p class="text-muted small mb-3" id="detail-desc" style="line-height: 1.6;">รายละเอียดสินค้า...</p><div class="bg-light p-3 rounded mb-4 small border sport-font"><p class="mb-2 text-primary"><i class="fa fa-truck me-2"></i> ค่าจัดส่ง: <strong id="detail-shipping">฿50</strong></p><p class="mb-0 text-success"><i class="fa fa-clock me-2"></i> ระยะเวลาจัดส่ง: <strong>2-3 วันทำการ</strong></p></div><div class="row g-3 align-items-end mb-4 sport-font"><div class="col-8"><label class="form-label fw-bold small">เลือกไซส์ (Size):</label><select id="detail-size" class="form-select border-dark"><option value="S">S (อก 36")</option><option value="M" selected>M (อก 38")</option><option value="L">L (อก 40")</option><option value="XL">XL (อก 42")</option><option value="2XL">2XL (อก 44")</option></select></div><div class="col-4"><label class="form-label fw-bold small">จำนวน:</label><input type="number" id="detail-qty" class="form-control border-dark text-center" value="1" min="1"></div></div><input type="hidden" id="detail-id"><div class="row g-2 sport-font"><div class="col-6"><button type="button" class="btn btn-dark w-100 py-2 fw-bold" onclick="addToCart(false)"><i class="fa fa-cart-plus me-1"></i> เพิ่มลงตะกร้า</button></div><div class="col-6"><button type="button" class="btn btn-warning w-100 py-2 fw-bold" onclick="addToCart(true)"><i class="fa fa-bolt me-1"></i> สั่งซื้อทันที</button></div></div></div></div><hr><h5 class="fw-bold sport-font mb-3"><i class="fa fa-comments text-warning me-2"></i>รีวิวจากลูกค้า</h5><div id="detail-reviews" class="bg-light p-3 rounded border" style="max-height: 250px; overflow-y: auto;"></div></div></div></div></div>
<div class="modal fade" id="checkoutModal" tabindex="-1"><div class="modal-dialog modal-xl"><div class="modal-content border-0"><div class="modal-header bg-dark text-white"><h5 class="modal-title fw-bold sport-font"><i class="fa fa-shopping-cart me-2 text-warning"></i>ตะกร้าและชำระเงิน</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div><div class="modal-body p-0"><div class="row g-0"><div class="col-lg-5 p-4 border-end bg-white"><h6 class="fw-bold border-bottom pb-2 mb-3 sport-font">รายการสินค้าของคุณ</h6><div id="cart-items-container" style="max-height: 400px; overflow-y: auto; overflow-x: hidden;"></div></div><div class="col-lg-7 p-4 bg-light sport-font"><h6 class="fw-bold border-bottom pb-2 mb-3">ข้อมูลการจัดส่ง</h6><div class="row g-2 mb-3"><div class="col-sm-6"><input type="text" id="ship-name" class="form-control" placeholder="ชื่อ-นามสกุลผู้รับ"></div><div class="col-sm-6"><input type="text" id="ship-phone" class="form-control" placeholder="เบอร์โทรศัพท์ (10 หลัก)" maxlength="10"></div><div class="col-12"><textarea id="ship-address" class="form-control" rows="2" placeholder="ที่อยู่จัดส่งแบบละเอียด..."></textarea></div></div><h6 class="fw-bold border-bottom pb-2 mb-3 mt-4">เลือกวิธีชำระเงิน</h6><div class="row g-3 mb-4"><div class="col-6"><div class="payment-box active shadow-sm bg-white" id="pay-cod" onclick="selectPayment('COD')"><i class="fa fa-truck fa-2x mb-2 text-dark"></i><div>เก็บเงินปลายทาง</div></div></div><div class="col-6"><div class="payment-box shadow-sm bg-white" id="pay-qr" onclick="selectPayment('QR')"><i class="fa fa-qrcode fa-2x mb-2 text-primary"></i><div>โอนเงิน (QR Code)</div></div></div></div><div class="card border-0 shadow-sm"><div class="card-body"><h6 class="fw-bold mb-3">สรุปคำสั่งซื้อ</h6><div class="input-group mb-3"><input type="text" id="coupon-input" class="form-control" placeholder="พิมพ์รหัสคูปอง (เช่น FREESHIP)"><button class="btn btn-dark fw-bold" onclick="applyCoupon()">ใช้คูปอง</button></div><div id="my-coupons-list" class="mb-3 small"></div><div class="d-flex justify-content-between mb-2"><span>รวมราคาสินค้า:</span> <span id="summary-subtotal" class="fw-bold">฿0</span></div><div class="d-flex justify-content-between mb-2"><span>ค่าจัดส่ง:</span> <span id="summary-shipping" class="fw-bold">฿50</span></div><hr class="my-3"><div class="d-flex justify-content-between fw-bold fs-4 mb-4"><span>ยอดสุทธิทั้งหมด:</span> <span class="text-danger" id="summary-total">฿0</span></div><button class="btn btn-success w-100 fw-bold py-3 fs-5" onclick="confirmOrder()"><i class="fa fa-check-circle me-2"></i> ยืนยันคำสั่งซื้อ</button></div></div></div></div></div></div></div></div>
<div class="modal fade" id="qrModal" data-bs-backdrop="static" tabindex="-1"><div class="modal-dialog modal-dialog-centered modal-sm"><div class="modal-content text-center py-4 border-warning" style="border-width: 4px;"><div class="modal-body sport-font"><h4 class="fw-bold text-primary mb-3">สแกนเพื่อชำระเงิน</h4><img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=081xxxxxxx" class="img-thumbnail shadow mb-3"><p class="text-danger fw-bold display-5 mb-0" id="qr-timer">05:00</p><p class="small text-muted mb-4" style="font-family:'Sarabun';">กรุณาโอนเงินภายในเวลาที่กำหนด</p><button class="btn btn-primary w-100 fw-bold mb-2 py-2" onclick="qrPaymentSuccess()">จำลองการโอนสำเร็จ</button><button class="btn btn-outline-danger w-100 py-2" onclick="qrPaymentCancel()">ยกเลิกคำสั่งซื้อ</button></div></div></div></div>
<div class="modal fade" id="historyModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content border-0 bg-light"><div class="modal-header bg-dark text-white"><h5 class="modal-title fw-bold sport-font"><i class="fa fa-history text-warning me-2"></i>ประวัติการสั่งซื้อของคุณ</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div><div class="modal-body p-4" id="history-container"></div></div></div></div>
<div class="modal fade" id="reviewModal" tabindex="-1"><div class="modal-dialog modal-dialog-centered modal-sm"><div class="modal-content border-0"><div class="modal-header bg-warning"><h5 class="modal-title fw-bold sport-font"><i class="fa fa-star text-dark me-1"></i> ให้คะแนนสินค้า</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body text-center sport-font"><p class="small text-muted mb-2">ความพึงพอใจในสินค้าและการจัดส่ง</p><div class="star-rating fs-1 mb-3" id="review-stars"><i class="fa fa-star" onclick="setRating(1)"></i><i class="fa fa-star" onclick="setRating(2)"></i><i class="fa fa-star" onclick="setRating(3)"></i><i class="fa fa-star" onclick="setRating(4)"></i><i class="fa fa-star" onclick="setRating(5)"></i></div><textarea id="review-comment" class="form-control border-dark mb-3" rows="3" placeholder="เขียนความรู้สึกประทับใจของคุณที่นี่..."></textarea><button class="btn btn-dark w-100 fw-bold py-2" onclick="submitReview()">ส่งรีวิว</button></div></div></div></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const dbProducts = <?php echo json_encode($productsFromDB); ?>;
    
    const products = dbProducts.map(p => ({
        id: parseInt(p.id),
        name: p.name,
        price: parseFloat(p.price),
        isSale: parseInt(p.is_sale) === 1, 
        oldPrice: parseFloat(p.old_price) > 0 ? parseFloat(p.old_price) : null, 
        type: p.type || "ทั่วไป",
        img: p.img ? (p.img.startsWith('http') || p.img.startsWith('data:') ? p.img : 'uploads/' + p.img) : 'default.jpg',
        desc: p.desc
    }));

    let currentUser = null; 
    let cart = [];
    let isFreeShipping = false; 
    let paymentMethod = 'COD';
    let qrTimerInterval;
    let currentReviewProduct = null;
    let currentReviewOrder = null;
    let selectedRating = 0;

    function initStore() {
        renderProducts(products);
        fetch('api_auth.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'same-origin',
            body: JSON.stringify({ action: 'check_session' })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) setupUserSession(data.user);
        }).catch(err => console.error("Session check failed:", err));
    }

    function setupUserSession(userData) {
        currentUser = userData;
        if (userData.profilePic && userData.profilePic.startsWith('data:image')) {
            currentUser.profilePic = userData.profilePic;
        } else {
            currentUser.profilePic = 'https://api.dicebear.com/7.x/avataaars/svg?seed=' + currentUser.username;
        }
        const localData = JSON.parse(localStorage.getItem('thanjai_data_' + currentUser.username)) || {};
        cart = localData.cart || [];
        currentUser.orders = localData.orders || [];
        currentUser.coupons = localData.coupons || [];
        updateNavUI(); updateCartBadge();
    }

    function saveDatabase() {
        if(currentUser) {
            const localData = { cart: cart, orders: currentUser.orders, coupons: currentUser.coupons };
            localStorage.setItem('thanjai_data_' + currentUser.username, JSON.stringify(localData));
        }
    }

    function showToast(message, type = 'success') {
        const toastEl = document.getElementById('liveToast');
        toastEl.classList.remove('bg-success', 'bg-danger', 'bg-warning', 'text-dark');
        if(type === 'success') toastEl.classList.add('bg-success');
        else if(type === 'error') toastEl.classList.add('bg-danger');
        else if(type === 'warning') toastEl.classList.add('bg-warning', 'text-dark');
        document.getElementById('toast-msg').innerHTML = message;
        const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
        toast.show();
    }

    function openAuthModal(type) {
        toggleAuth(type); new bootstrap.Modal(document.getElementById('authModal')).show();
    }

    function toggleAuth(type) {
        document.getElementById('login-section').classList.toggle('d-none', type !== 'login');
        document.getElementById('register-section').classList.toggle('d-none', type !== 'register');
        document.getElementById('authModalTitle').innerText = type === 'login' ? 'เข้าสู่ระบบบัญชีของคุณ' : 'สมัครสมาชิกใหม่';
    }

    function register() {
        const user = document.getElementById('reg-user').value.trim();
        const email = document.getElementById('reg-email').value.trim();
        const phone = document.getElementById('reg-phone').value.trim();
        const pass = document.getElementById('reg-pass').value.trim();

        if(!user || !email || !phone || !pass) return showToast('กรุณากรอกข้อมูลให้ครบถ้วน', 'error');
        if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) return showToast('รูปแบบอีเมลไม่ถูกต้อง', 'error');
        if(!/^\d{10}$/.test(phone)) return showToast('เบอร์โทรศัพท์ต้องเป็น 10 หลัก', 'error');

        fetch('api_auth.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'same-origin',
            body: JSON.stringify({ action: 'register', username: user, email: email, phone: phone, password: pass })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                setupUserSession(data.user);
                bootstrap.Modal.getInstance(document.getElementById('authModal')).hide();
                showToast('🎉 สมัครสมาชิกและเข้าสู่ระบบสำเร็จ!', 'success');
            } else { showToast('❌ ' + data.message, 'error'); }
        });
    }

    function login() {
        const user = document.getElementById('login-user').value.trim();
        const pass = document.getElementById('login-pass').value.trim();

        if(!user || !pass) return showToast('กรุณากรอกชื่อผู้ใช้และรหัสผ่าน', 'warning');

        fetch('api_auth.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'same-origin',
            body: JSON.stringify({ action: 'login', username: user, password: pass })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                if(data.user.role === 'admin') {
                    window.location.href = 'admin_orders.php';
                } else {
                    setupUserSession(data.user);
                    bootstrap.Modal.getInstance(document.getElementById('authModal')).hide();
                    showToast(`ยินดีต้อนรับกลับมาครับ!`, 'success');
                }
            } else { showToast('❌ ' + data.message, 'error'); }
        });
    }

    function logoutUser() {
        if(confirm("ต้องการออกจากระบบใช่หรือไม่?")) {
            fetch('api_auth.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                credentials: 'same-origin',
                body: JSON.stringify({ action: 'logout' })
            }).then(res => res.json()).then(data => {
                if(data.success) {
                    currentUser = null; cart = [];
                    updateNavUI(); updateCartBadge();
                    showToast('ออกจากระบบเรียบร้อยแล้ว', 'success');
                }
            });
        }
    }

    function updateNavUI() {
        if(currentUser) {
            document.getElementById('guest-zone').classList.add('d-none');
            document.getElementById('user-zone').classList.remove('d-none');
            document.getElementById('nav-username').innerText = currentUser.name || currentUser.username;
            document.getElementById('nav-profile-pic').src = currentUser.profilePic;
            document.getElementById('setting-profile-pic').src = currentUser.profilePic;
            document.getElementById('set-username').value = currentUser.username; 
            document.getElementById('set-name').value = currentUser.name || currentUser.username;
            document.getElementById('set-phone').value = currentUser.phone || '';
            document.getElementById('set-email').value = currentUser.email || '';
            document.getElementById('set-password').value = '';

            if(currentUser.role === 'admin') document.getElementById('nav-admin-link').classList.remove('d-none');
            else document.getElementById('nav-admin-link').classList.add('d-none');
        } else {
            document.getElementById('guest-zone').classList.remove('d-none');
            document.getElementById('user-zone').classList.add('d-none');
        }
    }

    function uploadProfilePic(event) {
        const file = event.target.files[0];
        if(file) {
            const reader = new FileReader();
            reader.onload = function(e) { document.getElementById('setting-profile-pic').src = e.target.result; }
            reader.readAsDataURL(file);
        }
    }

   function saveProfile() {
        const newName = document.getElementById('set-name').value.trim();
        const newPhone = document.getElementById('set-phone').value.trim();
        const newPass = document.getElementById('set-password').value.trim();
        const newPic = document.getElementById('setting-profile-pic').src;

        if(!newName || !newPhone) return showToast('กรุณากรอกชื่อและเบอร์โทร', 'warning');
        if(newPhone.length !== 10) return showToast('เบอร์โทรศัพท์ต้องมี 10 หลัก', 'warning');
        
        let picDataToSend = '';
        if(newPic.startsWith('data:image')) picDataToSend = newPic;

        fetch('api_auth.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'same-origin',
            body: JSON.stringify({ action: 'update_profile', fullname: newName, phone: newPhone, password: newPass, profile_pic: picDataToSend })
        }).then(res => res.json()).then(data => {
            if(data.success) {
                currentUser.name = newName; currentUser.phone = newPhone;
                if(data.profile_pic) currentUser.profilePic = data.profile_pic;
                updateNavUI();
                bootstrap.Modal.getInstance(document.getElementById('profileModal')).hide();
                showToast('อัปเดตข้อมูลบัญชีเรียบร้อย', 'success');
            } else { showToast('❌ ' + data.message, 'error'); }
        });
    }

    function renderProducts(items) {
        const container = document.getElementById('store-display');
        if(items.length === 0) {
            container.innerHTML = `<div class="col-12 text-center text-muted my-5"><h5 class="sport-font">ไม่พบสินค้าที่คุณค้นหา</h5></div>`;
            return;
        }
        container.innerHTML = items.map(p => {
            const saleBadge = p.isSale ? `<span class="sale-badge">SALE!</span>` : '';
            const oldPriceStr = (p.isSale && p.oldPrice) ? `<small class="text-muted text-decoration-line-through">฿${p.oldPrice.toLocaleString()}</small>` : '';
            return `
            <div class="col-6 col-md-3 mb-4">
                <div class="card product-card sport-font">
                    ${saleBadge}
                    <img src="${p.img}" onerror="this.src='https://via.placeholder.com/250?text=No+Image'" class="card-img-top" alt="${p.name}">
                    <div class="card-body d-flex flex-column p-3">
                        <span class="badge bg-dark mb-2 align-self-start" style="font-size: 11px;">${p.type}</span>
                        <h6 class="card-title fw-bold text-dark mb-1" style="font-size: 15px;">${p.name}</h6>
                        <div class="mt-auto pt-3">
                            <span class="text-danger fw-bold fs-4 d-inline-block mb-1">฿${p.price.toLocaleString()}</span>
                            ${oldPriceStr}
                            <div class="mt-2">
                                <button class="btn btn-dark w-100 btn-sm fw-bold shadow-sm py-2" onclick="openProductDetail(${p.id})">
                                    <i class="fa fa-shopping-cart me-1 text-warning"></i> เลือกสินค้า
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
        }).join('');
    }

    function searchProducts() {
        const term = document.getElementById('search-input').value.toLowerCase();
        renderProducts(products.filter(p => p.name.toLowerCase().includes(term)));
    }

    function filterProducts(type) {
        if(event && event.currentTarget.classList.contains('filter-btn')) {
            document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active', 'btn-dark'));
            document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.add('btn-outline-dark'));
            event.currentTarget.classList.remove('btn-outline-dark');
            event.currentTarget.classList.add('btn-dark', 'active');
        }
        if (type === 'โปรโมชั่น') renderProducts(products.filter(p => p.isSale));
        else renderProducts(type === 'ทั้งหมด' ? products : products.filter(p => p.type === type));
    }

    function openProductDetail(id) {
        const p = products.find(x => x.id === id);
        document.getElementById('detail-id').value = p.id;
        document.getElementById('detail-name').innerText = p.name;
        document.getElementById('detail-price').innerText = '฿' + p.price.toLocaleString();
        
        const oldPriceEl = document.getElementById('detail-old-price');
        const saleBadgeEl = document.getElementById('detail-sale-badge');
        
        if(p.isSale && p.oldPrice) {
            oldPriceEl.innerText = '฿' + p.oldPrice.toLocaleString();
            oldPriceEl.classList.remove('d-none'); saleBadgeEl.classList.remove('d-none');
        } else {
            oldPriceEl.classList.add('d-none'); saleBadgeEl.classList.add('d-none');
        }

        document.getElementById('detail-desc').innerText = p.desc;
        document.getElementById('detail-type').innerText = p.type;
        document.getElementById('detail-img').src = p.img; 
        document.getElementById('detail-qty').value = 1;
        document.getElementById('detail-size').selectedIndex = 1; 

        const hasFreeShip = currentUser && currentUser.coupons && currentUser.coupons.includes('FREESHIP');
        document.getElementById('detail-shipping').innerHTML = hasFreeShip ? '<span class="text-success fw-bold">ส่งฟรี (ใช้คูปองได้)</span>' : '฿50';

        const reviewContainer = document.getElementById('detail-reviews');
        reviewContainer.innerHTML = '<div class="text-center py-3"><div class="spinner-border text-warning spinner-border-sm"></div></div>';
        
        fetch('api_auth.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'get_reviews', product_id: id })
        }).then(res => res.json()).then(data => {
            if(data.success && data.reviews.length > 0) {
                reviewContainer.innerHTML = data.reviews.map(r => {
                    let stars = '';
                    for(let i=0; i<5; i++) stars += `<i class="fa fa-star ${i < r.rating ? 'text-warning' : 'text-muted'}"></i>`;
                    let pic = r.profile_pic ? r.profile_pic : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' + r.username;
                    return `
                    <div class="d-flex mb-3 border-bottom pb-2 font-sarabun">
                        <img src="${pic}" width="45" height="45" class="rounded-circle border border-warning me-3 bg-white shadow-sm" style="object-fit:cover;">
                        <div>
                            <div class="fw-bold text-primary">${r.fullname || r.username}</div>
                            <div class="small mb-1">${stars}</div>
                            <p class="small mb-1 text-dark">${r.comment}</p>
                            <small class="text-muted" style="font-size: 11px;">รีวิวเมื่อ: ${r.created_at}</small>
                        </div>
                    </div>`;
                }).join('');
            } else {
                reviewContainer.innerHTML = '<p class="text-muted small text-center mb-0 my-3 font-sarabun">ยังไม่มีรีวิวสำหรับสินค้านี้ เป็นคนแรกที่รีวิวสิ!</p>';
            }
        });

        new bootstrap.Modal(document.getElementById('productDetailModal')).show();
    }

    function addToCart(isBuyNow) {
        if(!currentUser) {
            bootstrap.Modal.getInstance(document.getElementById('productDetailModal')).hide();
            return openAuthModal('login');
        }
        const id = parseInt(document.getElementById('detail-id').value);
        const size = document.getElementById('detail-size').value;
        const qty = parseInt(document.getElementById('detail-qty').value);
        const product = products.find(p => p.id === id);

        const existingItemIndex = cart.findIndex(item => item.id === id && item.size === size);
        if (existingItemIndex > -1) cart[existingItemIndex].qty += qty; 
        else cart.push({ ...product, size: size, qty: qty }); 

        saveDatabase(); updateCartBadge();
        bootstrap.Modal.getInstance(document.getElementById('productDetailModal')).hide();
        showToast(`<i class="fa fa-check-circle"></i> เพิ่ม <strong>${product.name}</strong> ลงตะกร้าแล้ว`);
        if(isBuyNow) setTimeout(openCart, 500);
    }
    
    function updateCartBadge() { document.getElementById('cart-count').innerText = cart.reduce((sum, item) => sum + item.qty, 0); }

    function openCart() {
        if(!currentUser) return openAuthModal('login');
        document.getElementById('ship-name').value = currentUser.name || '';
        document.getElementById('ship-phone').value = currentUser.phone || '';
        isFreeShipping = false; 
        document.getElementById('coupon-input').value = '';
        renderCartItems(); updateSummary();
        new bootstrap.Modal(document.getElementById('checkoutModal')).show();
    }

    function renderCartItems() {
        const container = document.getElementById('cart-items-container');
        if(cart.length === 0) {
            container.innerHTML = `<div class="text-center py-5"><i class="fa fa-shopping-basket fa-3x text-muted mb-3"></i><p class="text-muted sport-font">ตะกร้าของคุณว่างเปล่า</p></div>`;
            return;
        }
        container.innerHTML = cart.map((item, index) => `
            <div class="d-flex align-items-center mb-3 p-3 border rounded bg-white shadow-sm sport-font">
                <img src="${item.img}" onerror="this.src='https://via.placeholder.com/150?text=No+Image'" width="80" height="80" class="rounded me-3 border bg-light" style="object-fit: cover;">
                <div class="flex-grow-1">
                    <h6 class="mb-1 fw-bold fs-5">${item.name}</h6>
                    <small class="text-muted d-block mb-1">ไซส์: <strong class="text-dark">${item.size}</strong></small>
                    <span class="badge bg-light text-dark border">จำนวน: ${item.qty}</span>
                </div>
                <div class="text-end ms-2">
                    <div class="fw-bold text-danger mb-2 fs-5">฿${(item.price * item.qty).toLocaleString()}</div>
                    <button class="btn btn-sm btn-outline-danger py-0 px-2 fw-bold" onclick="removeFromCart(${index})"><i class="fa fa-trash"></i> ลบ</button>
                </div>
            </div>`).join('');
    }

    function removeFromCart(index) { cart.splice(index, 1); saveDatabase(); updateCartBadge(); renderCartItems(); updateSummary(); }

    function collectCoupon(code) {
        if(!currentUser) return openAuthModal('login');
        if(!currentUser.coupons) currentUser.coupons = [];
        if(currentUser.coupons.includes(code)) return showToast('คุณเก็บคูปองนี้ไปแล้ว', 'warning');
        
        currentUser.coupons.push(code); saveDatabase();
        showToast(`🎉 เก็บโค้ด ${code} สำเร็จ! นำไปใช้ในหน้าชำระเงินได้เลย`, 'success');
    }

    function renderMyCoupons() {
        const container = document.getElementById('my-coupons-list');
        if(!currentUser || !currentUser.coupons || currentUser.coupons.length === 0) { container.innerHTML = ''; return; }
        container.innerHTML = '<strong class="text-dark">คูปองของคุณ (คลิกเพื่อใช้):</strong> ' + currentUser.coupons.map(c => 
            `<span class="badge bg-dark text-warning me-1 shadow-sm p-2 border border-warning" style="cursor:pointer;" onclick="document.getElementById('coupon-input').value='${c}'; applyCoupon();"><i class="fa fa-ticket-alt"></i> ${c}</span>`
        ).join('');
    }

    function applyCoupon() {
        const code = document.getElementById('coupon-input').value.trim().toUpperCase();
        if(code === 'FREESHIP') {
            if(!currentUser.coupons || !currentUser.coupons.includes(code)) return showToast('กรุณากดเก็บคูปองที่หน้าหลักก่อนใช้งาน', 'error');
            isFreeShipping = true; showToast('✅ ใช้คูปองส่วนลดค่าจัดส่งฟรี สำเร็จ!', 'success'); updateSummary();
        } else if (code === '') showToast('กรุณากรอกรหัสคูปอง', 'warning');
        else showToast('รหัสคูปองไม่ถูกต้อง หรือหมดอายุแล้ว', 'error'); 
    }

    function updateSummary() {
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        let shipping = cart.length > 0 ? 50 : 0;
        if(isFreeShipping && cart.length > 0) {
            document.getElementById('summary-shipping').innerHTML = '<span class="text-decoration-line-through text-muted">฿50</span> <strong class="text-success fs-5">ฟรี</strong>';
            shipping = 0;
        } else {
            document.getElementById('summary-shipping').innerText = '฿' + shipping;
        }

        let total = subtotal + shipping;
        document.getElementById('summary-subtotal').innerText = '฿' + subtotal.toLocaleString();
        document.getElementById('summary-total').innerText = '฿' + total.toLocaleString();
        renderMyCoupons();
    }

    function selectPayment(method) {
        paymentMethod = method;
        document.getElementById('pay-cod').classList.toggle('active', method === 'COD');
        document.getElementById('pay-qr').classList.toggle('active', method === 'QR');
    }

    function confirmOrder() {
        if(cart.length === 0) return showToast('ไม่มีสินค้าในตะกร้า', 'warning');
        const address = document.getElementById('ship-address').value.trim();
        if(!address) return showToast('กรุณากรอกที่อยู่จัดส่ง', 'error');
        if(paymentMethod === 'QR') {
            bootstrap.Modal.getInstance(document.getElementById('checkoutModal')).hide();
            new bootstrap.Modal(document.getElementById('qrModal')).show();
            startQrTimer();
        } else { processOrderSuccess(); }
    }

    function startQrTimer() {
        let time = 300; 
        const display = document.getElementById('qr-timer');
        clearInterval(qrTimerInterval);
        qrTimerInterval = setInterval(() => {
            const m = Math.floor(time / 60).toString().padStart(2, '0');
            const s = (time % 60).toString().padStart(2, '0');
            display.innerText = `${m}:${s}`;
            if(time <= 0) qrPaymentCancel();
            time--;
        }, 1000);
    }

    function qrPaymentSuccess() { clearInterval(qrTimerInterval); bootstrap.Modal.getInstance(document.getElementById('qrModal')).hide(); processOrderSuccess(); }
    function qrPaymentCancel() { clearInterval(qrTimerInterval); bootstrap.Modal.getInstance(document.getElementById('qrModal')).hide(); showToast('หมดเวลา/ยกเลิก การชำระเงิน', 'error'); new bootstrap.Modal(document.getElementById('checkoutModal')).show(); }

    function processOrderSuccess() {
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        let shipping = cart.length > 0 ? 50 : 0;
        if(isFreeShipping) shipping = 0;
        const total = subtotal + shipping;

        const addressStr = document.getElementById('ship-address').value.trim();
        const statusStr = paymentMethod === 'QR' ? 'paid' : 'pending';
        
        fetch('save_order.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ username: currentUser ? currentUser.username : null, total: total, status: statusStr, address: addressStr, items: cart })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                cart = []; 
                if(isFreeShipping && currentUser.coupons && currentUser.coupons.includes('FREESHIP')) {
                    currentUser.coupons = currentUser.coupons.filter(c => c !== 'FREESHIP');
                }
                isFreeShipping = false;
                saveDatabase(); updateCartBadge();
                const checkoutModalEl = document.getElementById('checkoutModal');
                if(checkoutModalEl.classList.contains('show')) bootstrap.Modal.getInstance(checkoutModalEl).hide();
                showToast('🎉 สั่งซื้อสำเร็จ! ขอบคุณที่ใช้บริการ', 'success');
                setTimeout(openOrderHistory, 1500); 
            } else { showToast('เกิดข้อผิดพลาด: ' + data.message, 'error'); }
        }).catch(err => { console.error(err); showToast('เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error'); });
    }

    function setRating(stars) {
        selectedRating = stars;
        const starElements = document.getElementById('review-stars').children;
        for(let i=0; i<5; i++) {
            if(i < stars) starElements[i].classList.add('text-warning', 'active');
            else starElements[i].classList.remove('text-warning', 'active');
        }
    }

    function openReviewModal(prodId, orderId) {
        currentReviewProduct = prodId; currentReviewOrder = orderId; setRating(0);
        document.getElementById('review-comment').value = '';
        bootstrap.Modal.getInstance(document.getElementById('historyModal')).hide();
        new bootstrap.Modal(document.getElementById('reviewModal')).show();
    }

    function submitReview() {
        if(selectedRating === 0) return showToast('กรุณาเลือกดาวให้คะแนน', 'warning');
        const comment = document.getElementById('review-comment').value.trim();
        fetch('api_auth.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'same-origin',
            body: JSON.stringify({ action: 'add_review', product_id: currentReviewProduct, order_id: currentReviewOrder, rating: selectedRating, comment: comment })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                bootstrap.Modal.getInstance(document.getElementById('reviewModal')).hide();
                showToast('ขอบคุณสำหรับรีวิวครับ!', 'success');
                setTimeout(openOrderHistory, 500);
            } else { showToast('❌ ' + data.message, 'error'); }
        });
    }

    function openOrderHistory() {
        if(!currentUser) return showToast('กรุณาเข้าสู่ระบบก่อนดูประวัติ', 'warning');
        const container = document.getElementById('history-container');
        container.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div><p class="mt-2 text-muted sport-font">กำลังดึงข้อมูลออเดอร์ล่าสุด...</p></div>';
        
        const historyModalEl = document.getElementById('historyModal');
        if(!historyModalEl.classList.contains('show')) new bootstrap.Modal(historyModalEl).show();

        fetch('api_auth.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'same-origin',
            body: JSON.stringify({ action: 'get_my_orders' })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                const orders = data.orders;
                if(orders.length === 0) {
                    container.innerHTML = `<div class="text-center py-5"><i class="fa fa-box-open fa-3x text-muted mb-3"></i><p class="text-muted sport-font fs-5">คุณยังไม่มีประวัติการสั่งซื้อ</p><button class="btn btn-warning mt-2 fw-bold" data-bs-dismiss="modal">ไปช้อปปิ้งเลย</button></div>`;
                } else {
                    container.innerHTML = orders.map(o => {
                        let badgeClass = 'bg-secondary'; let statusText = o.status;
                        let currentStatus = (o.status || '').trim().toLowerCase();
                        if(currentStatus === 'pending') { badgeClass = 'bg-warning text-dark'; statusText = 'รอชำระเงิน / COD'; }
                        else if(currentStatus === 'paid') { badgeClass = 'bg-success'; statusText = 'ชำระเงินแล้ว'; }
                        else if(currentStatus === 'shipped') { badgeClass = 'bg-primary text-white'; statusText = 'จัดส่งแล้ว'; }
                        else if(currentStatus === 'cancelled') { badgeClass = 'bg-danger'; statusText = 'ยกเลิกคำสั่งซื้อ'; }

                        return `
                        <div class="card mb-4 border-0 shadow-sm rounded-3 sport-font">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-bottom">
                                <div><span class="fw-bold fs-5 text-dark"><i class="fa fa-receipt text-warning me-2"></i>ออเดอร์: ${o.orderId}</span><small class="text-muted d-block mt-1"><i class="fa fa-calendar-alt me-1"></i> ${o.date}</small></div>
                                <span class="badge ${badgeClass} px-3 py-2 fs-6 shadow-sm">${statusText}</span>
                            </div>
                            <div class="card-body bg-light">
                                ${o.items.map(item => {
                                    let reviewBtn = '';
                                    if(currentStatus === 'shipped' || statusText === 'จัดส่งแล้ว') {
                                        reviewBtn = `<button class="btn btn-sm btn-dark mt-2 px-3 py-1 shadow-sm fw-bold text-warning" onclick="openReviewModal(${item.product_id}, ${o.rawOrderId})"><i class="fa fa-star text-white"></i> ให้คะแนนและรีวิว</button>`;
                                    }
                                    return `
                                    <div class="d-flex justify-content-between align-items-center small mb-3 border-bottom pb-3">
                                        <div class="d-flex align-items-center">
                                            <img src="${item.img}" onerror="this.src='https://via.placeholder.com/150?text=No+Image'" width="60" height="60" style="object-fit:cover;" class="rounded me-3 border border-dark bg-white shadow-sm">
                                            <div><span class="fw-bold fs-6">${item.name} <span class="text-muted fw-normal">(Size: ${item.size})</span></span><br>${reviewBtn}</div>
                                        </div>
                                        <span class="fs-6">x ${item.qty} = <strong class="text-danger fs-5">฿${(item.price * item.qty).toLocaleString()}</strong></span>
                                    </div>`;
                                }).join('')}
                                <div class="d-flex justify-content-between align-items-end mt-3">
                                    <small class="text-muted" style="max-width: 60%;"><i class="fa fa-map-marker-alt"></i> จัดส่ง: ${o.address}</small>
                                    <div class="text-end fw-bold">ยอดสุทธิ: <span class="text-danger fs-3 ms-2">฿${o.total.toLocaleString()}</span></div>
                                </div>
                            </div>
                        </div>`;
                    }).join('');
                }
            } else { container.innerHTML = '<div class="alert alert-danger">เกิดข้อผิดพลาด: ' + data.message + '</div>'; }
        }).catch(err => { container.innerHTML = '<div class="alert alert-danger">ไม่สามารถดึงข้อมูลได้ โปรดลองอีกครั้ง</div>'; });
    }
</script>
</body>
</html>