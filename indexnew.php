<?php
// ดึงไฟล์เชื่อมต่อฐานข้อมูลเข้ามา (ต้องมีไฟล์ db.php อยู่ในโฟลเดอร์เดียวกัน)
require_once 'db.php';

// ดึงข้อมูลสินค้า พร้อมกับชื่อประเภทสินค้า (JOIN ตาราง)
$sql = "SELECT p.id, p.name, p.price, p.description as `desc`, p.image as img, c.name as type 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id";
$stmt = $pdo->query($sql);
$productsFromDB = $stmt->fetchAll(PDO::FETCH_ASSOC);

// แปลงข้อมูลให้อยู่ในรูปแบบตัวเลขที่ถูกต้อง
foreach ($productsFromDB as $key => $product) {
    $productsFromDB[$key]['price'] = (float)$product['price'];
    $productsFromDB[$key]['id'] = (int)$product['id'];
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ThanJai Shop - Store (Pro Version)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #1a1a1a; --accent: #ffae00; }
        body { font-family: 'Sarabun', sans-serif; background: #f8f9fa; }
        .navbar { background: var(--primary) !important; }
        .hero-banner { 
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1510566337590-2fc1f21d0faa?q=80&w=2070&auto=format&fit=crop');
            background-size: cover; background-position: center; height: 350px; display: flex; align-items: center; color: white;
        }
        .product-card { border: none; border-radius: 15px; transition: 0.3s; overflow: hidden; height: 100%; position: relative; }
        .product-card:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(0,0,0,0.1); }
        .product-card img { height: 250px; object-fit: cover; }
        .btn-cart { background: #f1f1f1; color: var(--primary); border-radius: 8px; font-weight: bold; }
        .btn-cart:hover { background: #e2e2e2; }
        .btn-buy-now { background: var(--accent); color: var(--primary); border-radius: 8px; font-weight: bold; }
        .btn-buy-now:hover { background: #e69d00; }
        .member-badge { font-size: 0.7rem; background: var(--accent); color: var(--primary); padding: 2px 8px; border-radius: 10px; font-weight: bold; }
        .cart-badge { font-size: 0.6rem; padding: 2px 5px; }
        .payment-box { border: 2px solid #ddd; border-radius: 8px; padding: 15px; cursor: pointer; transition: 0.2s; text-align: center; }
        .payment-box:hover { border-color: var(--accent); background: #fffdf5; }
        .payment-box.active { border-color: var(--accent); background: #fff8e1; font-weight: bold; }
        .sale-badge { position: absolute; top: 10px; right: 10px; background: #dc3545; color: white; padding: 5px 12px; border-radius: 5px; font-weight: bold; font-size: 12px; z-index: 10; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
        .profile-img-preview { width: 100px; height: 100px; object-fit: cover; border-radius: 50%; border: 3px solid var(--accent); cursor: pointer; }
        .star-rating i { color: #ddd; cursor: pointer; }
        .star-rating i.active { color: #ffc107; }
        /* Toast Styles */
        .toast-container { z-index: 1060; }
        .toast { border-radius: 10px; font-weight: bold; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
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

<nav class="navbar navbar-expand-lg navbar-dark sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#" onclick="filterProducts('ทั้งหมด')">ThanJai <span class="text-warning">Shop</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link active" href="#" onclick="filterProducts('ทั้งหมด')"><i class="fa fa-home me-1"></i>หน้าหลัก</a></li>
                <li class="nav-item"><a class="nav-link" href="#product-list" onclick="filterProducts('ทั้งหมด')"><i class="fa fa-list me-1"></i>สินค้าทั้งหมด</a></li>
                <li class="nav-item"><a class="nav-link text-danger fw-bold" href="#" onclick="filterProducts('โปรโมชั่น')"><i class="fa fa-tags me-1"></i>โปรโมชั่นลดราคา</a></li>
            </ul>
            
            <div class="d-flex align-items-center gap-3">
                <div class="input-group input-group-sm d-none d-md-flex">
                    <input type="text" id="search-input" class="form-control" placeholder="ค้นหาชื่อทีม..." onkeyup="searchProducts()">
                </div>

                <div id="guest-zone">
                    <button class="btn btn-outline-warning btn-sm fw-bold" onclick="openAuthModal('login')">เข้าสู่ระบบ / สมัครสมาชิก</button>
                </div>

                <div id="user-zone" class="d-none">
                    <div class="dropdown text-white">
                        <span class="me-2 d-none d-md-inline">ยินดีต้อนรับ, <strong id="nav-username">ผู้ใช้</strong> <span class="member-badge">Member</span></span>
                        <a href="#" class="link-light dropdown-toggle" data-bs-toggle="dropdown">
                            <img id="nav-profile-pic" src="" width="35" height="35" class="rounded-circle border" style="object-fit: cover;">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profileModal"><i class="fa fa-cog me-2"></i>ตั้งค่าบัญชี</a></li>
                            <li><a class="dropdown-item" href="#" onclick="openOrderHistory()"><i class="fa fa-box-open me-2"></i>ประวัติสั่งซื้อ & รีวิว</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="#" onclick="logoutUser()"><i class="fa fa-sign-out-alt me-2"></i>ออกจากระบบ</a></li>
                        </ul>
                    </div>
                </div>
                
                <a href="#" class="text-white position-relative" onclick="openCart()">
                    <i class="fa fa-shopping-cart fs-5"></i>
                    <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-badge">0</span>
                </a>
            </div>
        </div>
    </div>
</nav>

<header class="hero-banner text-center" id="hero-banner">
    <div class="container">
        <h1 class="display-4 fw-bold">NEW SEASON 2026</h1>
        <p class="lead">คอลเลคชั่นใหม่ล่าสุดจากสโมสรดังทั่วโลก พร้อมส่งแล้ววันนี้!</p>
        <a href="#product-list" class="btn btn-warning btn-lg px-5 fw-bold">เลือกช้อปเลย</a>
    </div>
</header>

<div class="container mt-4" id="promo-banner">
    <div class="alert alert-danger shadow-sm border-0 rounded-3 text-center position-relative overflow-hidden">
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: url('https://www.transparenttextures.com/patterns/cubes.png'); opacity: 0.1;"></div>
        <h3 class="fw-bold mb-2 position-relative"><i class="fa fa-gift me-2"></i> โปรโมชั่นพิเศษ!</h3>
        <p class="mb-2 fs-5 position-relative">โค้ดส่วนลด 50 บาท (โค้ด: <strong>NEWBIE50</strong>)</p>
        <button class="btn btn-warning fw-bold position-relative shadow-sm px-4" onclick="collectCoupon('NEWBIE50')">
            <i class="fa fa-hand-holding-heart me-1"></i> เก็บและใช้งานคูปอง
        </button>
    </div>
</div>

<main class="container my-5" id="product-list">
    <div class="d-flex justify-content-center gap-2 mb-4 overflow-auto pb-2" id="category-filters">
        <button class="btn btn-dark px-4 rounded-pill filter-btn active" onclick="filterProducts('ทั้งหมด')">ทั้งหมด</button>
        <button class="btn btn-outline-dark px-4 rounded-pill filter-btn" onclick="filterProducts('เหย้า')">เหย้า</button>
        <button class="btn btn-outline-dark px-4 rounded-pill filter-btn" onclick="filterProducts('เยือน')">เยือน</button>
        <button class="btn btn-outline-dark px-4 rounded-pill filter-btn" onclick="filterProducts('ซ้อม')">เสื้อซ้อม</button>
    </div>
    <div class="row g-4" id="store-display"></div>
</main>

<div class="modal fade" id="authModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold" id="authModalTitle">เข้าสู่ระบบ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="login-section">
                    <input type="text" id="login-user" class="form-control mb-2" placeholder="ชื่อผู้ใช้ (Username)">
                    <input type="password" id="login-pass" class="form-control mb-3" placeholder="รหัสผ่าน">
                    <button class="btn btn-warning w-100 fw-bold mb-2" onclick="login()">เข้าสู่ระบบ</button>
                    <p class="text-center small mt-2 mb-0">ผู้ใช้ใหม่ใช่ไหม? <a href="#" onclick="toggleAuth('register')" class="text-primary fw-bold">สมัครสมาชิกที่นี่</a></p>
                </div>
                <div id="register-section" class="d-none">
                    <input type="text" id="reg-user" class="form-control mb-2" placeholder="ตั้งชื่อผู้ใช้ (Username)">
                    <input type="email" id="reg-email" class="form-control mb-2" placeholder="อีเมล (เช่น name@email.com)">
                    <input type="text" id="reg-phone" class="form-control mb-2" placeholder="เบอร์โทรศัพท์ (10 หลัก)" maxlength="10">
                    <input type="password" id="reg-pass" class="form-control mb-3" placeholder="ตั้งรหัสผ่าน">
                    <button class="btn btn-success w-100 fw-bold mb-2" onclick="register()">ลงทะเบียน</button>
                    <p class="text-center small mt-2 mb-0">มีบัญชีอยู่แล้ว? <a href="#" onclick="toggleAuth('login')" class="text-primary fw-bold">เข้าสู่ระบบ</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="profileModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold"><i class="fa fa-user-cog me-2"></i>ตั้งค่าบัญชี</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <label for="profile-upload" class="position-relative d-inline-block">
                    <img id="setting-profile-pic" src="" class="profile-img-preview mb-2 shadow-sm">
                    <div class="position-absolute bottom-0 end-0 bg-warning rounded-circle p-1 px-2 border border-dark" style="cursor: pointer;"><i class="fa fa-camera small"></i></div>
                </label>
                <input type="file" id="profile-upload" class="d-none" accept="image/*" onchange="uploadProfilePic(event)">
                
                <div class="text-start mt-3">
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label small fw-bold">ชื่อแสดงผล / ชื่อจริง</label>
                            <input type="text" id="set-name" class="form-control form-control-sm">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">เบอร์โทรศัพท์ (10 หลัก)</label>
                            <input type="text" id="set-phone" class="form-control form-control-sm" maxlength="10">
                        </div>
                    </div>
                    <label class="form-label small fw-bold">อีเมล</label>
                    <input type="email" id="set-email" class="form-control form-control-sm mb-2" disabled>
                    <small class="text-muted d-block mb-2">ไม่สามารถเปลี่ยนอีเมลได้ หากต้องการเปลี่ยนกรุณาติดต่อแอดมิน</small>
                    
                    <label class="form-label small fw-bold">รหัสผ่านใหม่ (ปล่อยว่างหากไม่ต้องการเปลี่ยน)</label>
                    <input type="password" id="set-password" class="form-control form-control-sm mb-3">
                </div>
                <button class="btn btn-warning w-100 fw-bold" onclick="saveProfile()">บันทึกการเปลี่ยนแปลง</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="productDetailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">รายละเอียดสินค้า</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5 text-center mb-3 mb-md-0 position-relative">
                        <span id="detail-sale-badge" class="sale-badge d-none">ลดราคา!</span>
                        <img id="detail-img" src="" class="img-fluid rounded shadow" style="max-height: 350px; object-fit: contain;">
                    </div>
                    <div class="col-md-7">
                        <span id="detail-type" class="badge bg-secondary mb-2"></span>
                        <h4 class="fw-bold" id="detail-name">ชื่อสินค้า</h4>
                        <div class="mb-2">
                            <span class="text-danger fw-bold fs-3" id="detail-price">฿0</span>
                            <span class="text-muted text-decoration-line-through ms-2 d-none" id="detail-old-price">฿0</span>
                        </div>
                        <p class="text-muted small mb-2" id="detail-desc">รายละเอียดสินค้า...</p>
                        
                        <div class="bg-light p-2 rounded mb-3 small border">
                            <p class="mb-1 text-primary"><i class="fa fa-truck me-2"></i> ค่าจัดส่ง: <strong>฿50</strong></p>
                            <p class="mb-0 text-success"><i class="fa fa-clock me-2"></i> ระยะเวลาจัดส่ง: <strong>2-3 วันทำการ</strong></p>
                        </div>

                        <hr>
                        <div class="row g-3 align-items-end mb-4">
                            <div class="col-8">
                                <label class="form-label fw-bold small">เลือกไซส์ (Size):</label>
                                <select id="detail-size" class="form-select border-dark">
                                    <option value="S">S (อก 36")</option>
                                    <option value="M" selected>M (อก 38")</option>
                                    <option value="L">L (อก 40")</option>
                                    <option value="XL">XL (อก 42")</option>
                                    <option value="2XL">2XL (อก 44")</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <label class="form-label fw-bold small">จำนวน:</label>
                                <input type="number" id="detail-qty" class="form-control border-dark text-center" value="1" min="1">
                            </div>
                        </div>
                        
                        <input type="hidden" id="detail-id">
                        <div class="row g-2">
                            <div class="col-6">
                                <button type="button" class="btn btn-cart w-100 py-2" onclick="addToCart(false)">
                                    <i class="fa fa-cart-plus me-1"></i> เพิ่มลงตะกร้า
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-buy-now w-100 py-2" onclick="addToCart(true)">
                                    <i class="fa fa-bolt me-1"></i> สั่งซื้อทันที
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="checkoutModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold"><i class="fa fa-shopping-cart me-2"></i>ตะกร้าและชำระเงิน</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="row g-0">
                    <div class="col-lg-5 p-4 border-end bg-white">
                        <h6 class="fw-bold border-bottom pb-2 mb-3">รายการสินค้าของคุณ</h6>
                        <div id="cart-items-container" style="max-height: 400px; overflow-y: auto; overflow-x: hidden;"></div>
                    </div>
                    
                    <div class="col-lg-7 p-4 bg-light">
                        <h6 class="fw-bold border-bottom pb-2 mb-3">ข้อมูลการจัดส่ง</h6>
                        <div class="row g-2 mb-3">
                            <div class="col-sm-6"><input type="text" id="ship-name" class="form-control form-control-sm" placeholder="ชื่อ-นามสกุลผู้รับ"></div>
                            <div class="col-sm-6"><input type="text" id="ship-phone" class="form-control form-control-sm" placeholder="เบอร์โทรศัพท์ (10 หลัก)" maxlength="10"></div>
                            <div class="col-12"><textarea id="ship-address" class="form-control form-control-sm" rows="2" placeholder="ที่อยู่จัดส่งแบบละเอียด..."></textarea></div>
                        </div>

                        <h6 class="fw-bold border-bottom pb-2 mb-3">เลือกวิธีชำระเงิน</h6>
                        <div class="row g-2 mb-4">
                            <div class="col-6">
                                <div class="payment-box active" id="pay-cod" onclick="selectPayment('COD')">
                                    <i class="fa fa-truck fa-2x mb-2 text-dark"></i>
                                    <div class="small">เก็บเงินปลายทาง</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="payment-box" id="pay-qr" onclick="selectPayment('QR')">
                                    <i class="fa fa-qrcode fa-2x mb-2 text-primary"></i>
                                    <div class="small">โอนเงิน (QR Code)</div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3">สรุปคำสั่งซื้อ</h6>
                                <div class="input-group input-group-sm mb-3">
                                    <input type="text" id="coupon-input" class="form-control" placeholder="พิมพ์รหัสคูปองที่มี (เช่น NEWBIE50)">
                                    <button class="btn btn-dark" onclick="applyCoupon()">ใช้คูปอง</button>
                                </div>
                                <div id="my-coupons-list" class="mb-3 small"></div>
                                
                                <div class="d-flex justify-content-between small mb-1"><span>รวมราคาสินค้า:</span> <span id="summary-subtotal">฿0</span></div>
                                <div class="d-flex justify-content-between small mb-1"><span>ค่าจัดส่ง:</span> <span>฿50</span></div>
                                <div class="d-flex justify-content-between small mb-2 text-success"><span>ส่วนลดคูปอง:</span> <span id="summary-discount">-฿0</span></div>
                                <hr class="my-2">
                                <div class="d-flex justify-content-between fw-bold fs-5 mb-3"><span>ยอดสุทธิทั้งหมด:</span> <span class="text-danger" id="summary-total">฿0</span></div>
                                <button class="btn btn-success w-100 fw-bold py-2" onclick="confirmOrder()"><i class="fa fa-check-circle me-1"></i> ยืนยันคำสั่งซื้อ</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="qrModal" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content text-center py-4 border-primary" style="border-width: 3px;">
            <div class="modal-body">
                <h5 class="fw-bold text-primary mb-3">สแกนเพื่อชำระเงิน</h5>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=081xxxxxxx" class="img-thumbnail shadow-sm mb-3">
                <p class="text-danger fw-bold display-6 mb-0" id="qr-timer">05:00</p>
                <p class="small text-muted mb-4">กรุณาโอนเงินภายในเวลาที่กำหนด</p>
                <button class="btn btn-primary w-100 fw-bold mb-2" onclick="qrPaymentSuccess()">จำลองการโอนสำเร็จ</button>
                <button class="btn btn-outline-danger w-100" onclick="qrPaymentCancel()">ยกเลิกคำสั่งซื้อ</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="historyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-light">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold"><i class="fa fa-history me-2"></i>ประวัติการสั่งซื้อของคุณ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="history-container"></div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // --------------------------------------------------------
    // ตัวแปรระบบ และฐานข้อมูล
    // --------------------------------------------------------
    const dbProducts = <?php echo json_encode($productsFromDB); ?>;
    const products = dbProducts.map(p => ({
        id: parseInt(p.id),
        name: p.name,
        price: parseFloat(p.price),
        oldPrice: parseFloat(p.price) + 200, 
        isSale: false, 
        type: p.type || "ทั่วไป",
        img: p.img ? p.img : 'default.jpg',
        desc: p.desc
    }));

    let currentUser = null; 
    let cart = [];
    let discount = 0;
    let paymentMethod = 'COD';
    let qrTimerInterval;

    // --------------------------------------------------------
    // ฟังก์ชันเริ่มต้น (โหลดหน้าเว็บ)
    // --------------------------------------------------------
    function initStore() {
        renderProducts(products);
        
        // เช็ค Session จากฝั่งเซิร์ฟเวอร์
        fetch('api_auth.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'same-origin',
            body: JSON.stringify({ action: 'check_session' })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                setupUserSession(data.user);
            }
        })
        .catch(err => console.error("Session check failed:", err));
    }

    function setupUserSession(userData) {
        currentUser = userData;
        
        // ถ้ามีรูปโปรไฟล์ในฐานข้อมูลให้ใช้รูปนั้น ถ้าไม่มีค่อยสุ่มรูปการ์ตูนให้
        if (userData.profilePic) {
            currentUser.profilePic = userData.profilePic;
        } else {
            currentUser.profilePic = 'https://api.dicebear.com/7.x/avataaars/svg?seed=' + currentUser.username;
        }
        
        const localData = JSON.parse(localStorage.getItem('thanjai_data_' + currentUser.username)) || {};
        cart = localData.cart || [];
        currentUser.orders = localData.orders || [];
        currentUser.coupons = localData.coupons || [];

        updateNavUI();
        updateCartBadge();
    }

    // เซฟตะกร้าลง LocalStorage (อิงตามชื่อผู้ใช้)
    function saveDatabase() {
        if(currentUser) {
            const localData = {
                cart: cart,
                orders: currentUser.orders,
                coupons: currentUser.coupons
            };
            localStorage.setItem('thanjai_data_' + currentUser.username, JSON.stringify(localData));
        }
    }

    function showToast(message, type = 'success') {
        const toastEl = document.getElementById('liveToast');
        const toastBody = document.getElementById('toast-body');
        
        toastEl.classList.remove('bg-success', 'bg-danger', 'bg-warning', 'text-dark');
        
        if(type === 'success') toastEl.classList.add('bg-success');
        else if(type === 'error') toastEl.classList.add('bg-danger');
        else if(type === 'warning') toastEl.classList.add('bg-warning', 'text-dark');

        document.getElementById('toast-msg').innerHTML = message;
        const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
        toast.show();
    }

    // --------------------------------------------------------
    // ระบบสมาชิก (Login & Register) - ต่อ Database
    // --------------------------------------------------------
    function openAuthModal(type) {
        toggleAuth(type);
        new bootstrap.Modal(document.getElementById('authModal')).show();
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

        if(!user || !email || !phone || !pass) return showToast('<i class="fa fa-exclamation-circle"></i> กรุณากรอกข้อมูลให้ครบถ้วน', 'error');
        if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) return showToast('<i class="fa fa-envelope"></i> รูปแบบอีเมลไม่ถูกต้อง', 'error');
        if(!/^\d{10}$/.test(phone)) return showToast('<i class="fa fa-phone"></i> เบอร์โทรศัพท์ต้องเป็นตัวเลข 10 หลัก', 'error');

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
            } else {
                showToast('❌ ' + data.message, 'error');
            }
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
                setupUserSession(data.user);
                bootstrap.Modal.getInstance(document.getElementById('authModal')).hide();
                showToast(`ยินดีต้อนรับกลับมาครับคุณ ${currentUser.name}!`, 'success');
            } else {
                showToast('❌ ' + data.message, 'error');
            }
        });
    }

    function logoutUser() {
        if(confirm("ต้องการออกจากระบบใช่หรือไม่?")) {
            fetch('api_auth.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                credentials: 'same-origin',
                body: JSON.stringify({ action: 'logout' })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    currentUser = null;
                    cart = [];
                    updateNavUI();
                    updateCartBadge();
                    showToast('ออกจากระบบเรียบร้อยแล้ว', 'success');
                }
            });
        }
    }

    function updateNavUI() {
        if(currentUser) {
            document.getElementById('guest-zone').classList.add('d-none');
            document.getElementById('user-zone').classList.remove('d-none');
            
            // ----------------------------------------------------
            // จุดสำคัญที่แก้ปัญหาชื่อไม่เปลี่ยน: 
            // ให้ดึง currentUser.name มาโชว์ก่อน ถ้าไม่มีถึงจะใช้ username
            // ----------------------------------------------------
            document.getElementById('nav-username').innerText = currentUser.name || currentUser.username;
            
            // จัดการรูปโปรไฟล์ (ถ้าไม่มีรูปให้สุ่มรูปการ์ตูน)
            const picUrl = currentUser.profilePic ? currentUser.profilePic : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' + currentUser.username;
            
            document.getElementById('nav-profile-pic').src = picUrl;
            document.getElementById('setting-profile-pic').src = picUrl;
            
            // เติมข้อมูลลงในฟอร์มตั้งค่า
            document.getElementById('set-name').value = currentUser.name || currentUser.username;
            document.getElementById('set-phone').value = currentUser.phone || '';
            document.getElementById('set-email').value = currentUser.email || '';
            document.getElementById('set-password').value = '';
        } else {
            document.getElementById('guest-zone').classList.remove('d-none');
            document.getElementById('user-zone').classList.add('d-none');
        }
    }
    // --------------------------------------------------------
    // จัดการโปรไฟล์ - ต่อ Database
    // --------------------------------------------------------
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
        
        // เช็คว่ามีการเปลี่ยนรูปใหม่ไหม (เพื่อป้องกันการส่งข้อมูลรูปซ้ำๆ ให้เซิร์ฟเวอร์ทำงานหนัก)
        let picDataToSend = '';
        if(newPic.startsWith('data:image')) {
            picDataToSend = newPic;
        }

        // ส่งข้อมูลชื่อใหม่ไปอัปเดตที่หลังบ้าน (api_auth.php)
        fetch('api_auth.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'same-origin', // บังคับให้ส่งตั๋วล็อกอิน (Session) ไปด้วย
            body: JSON.stringify({ 
                action: 'update_profile', 
                fullname: newName, 
                phone: newPhone, 
                password: newPass,
                profile_pic: picDataToSend
            })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                // ----------------------------------------------------
                // เมื่อ Database อัปเดตสำเร็จ ให้เปลี่ยนชื่อในตัวแปรระบบด้วย
                // ----------------------------------------------------
                currentUser.name = newName;
                currentUser.phone = newPhone;
                
                if(data.profile_pic) {
                    currentUser.profilePic = data.profile_pic;
                }
                
                // สั่งให้อัปเดตหน้าจอใหม่ (ชื่อที่เมนูจะเปลี่ยนทันที)
                updateNavUI();
                bootstrap.Modal.getInstance(document.getElementById('profileModal')).hide();
                showToast('อัปเดตข้อมูลบัญชีเรียบร้อย', 'success');
            } else {
                showToast('❌ ' + data.message, 'error');
            }
        })
        .catch(err => {
            console.error(err);
            showToast('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์', 'error');
        });
    }
    // --------------------------------------------------------
    // แสดงผลสินค้า
    // --------------------------------------------------------
    function renderProducts(items) {
        const container = document.getElementById('store-display');
        if(items.length === 0) {
            container.innerHTML = `<div class="col-12 text-center text-muted my-5"><h5>ไม่พบสินค้าที่คุณค้นหา</h5></div>`;
            return;
        }

        container.innerHTML = items.map(p => {
            const saleBadge = p.isSale ? `<span class="sale-badge">SALE!</span>` : '';
            const oldPriceStr = p.oldPrice ? `<small class="text-muted text-decoration-line-through">฿${p.oldPrice}</small>` : '';
            return `
            <div class="col-6 col-md-3 mb-4">
                <div class="card product-card shadow-sm border-0">
                    ${saleBadge}
                    <img src="${p.img}" class="card-img-top" alt="${p.name}">
                    <div class="card-body d-flex flex-column">
                        <span class="badge bg-secondary mb-2 align-self-start" style="font-size: 10px;">${p.type}</span>
                        <h6 class="card-title fw-bold text-dark mb-1" style="font-size: 14px;">${p.name}</h6>
                        <div class="mt-auto pt-3">
                            <span class="text-danger fw-bold fs-5 d-inline-block mb-1">฿${p.price.toLocaleString()}</span>
                            ${oldPriceStr}
                            <div class="mt-2">
                                <button class="btn btn-dark w-100 btn-sm fw-bold rounded-pill" onclick="openProductDetail(${p.id})">
                                    <i class="fa fa-shopping-cart me-1"></i> เลือกสินค้า
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            `;
        }).join('');
    }

    function searchProducts() {
        const term = document.getElementById('search-input').value.toLowerCase();
        const filtered = products.filter(p => p.name.toLowerCase().includes(term));
        renderProducts(filtered);
    }

    function filterProducts(type) {
        if(event && event.currentTarget.classList.contains('filter-btn')) {
            document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active', 'btn-dark'));
            document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.add('btn-outline-dark'));
            event.currentTarget.classList.remove('btn-outline-dark');
            event.currentTarget.classList.add('btn-dark', 'active');
        }

        if (type === 'โปรโมชั่น') {
            renderProducts(products.filter(p => p.isSale));
        } else {
            const filtered = type === 'ทั้งหมด' ? products : products.filter(p => p.type === type);
            renderProducts(filtered);
        }
    }

    // --------------------------------------------------------
    // รายละเอียดสินค้า & ตะกร้า
    // --------------------------------------------------------
    function openProductDetail(id) {
        const p = products.find(x => x.id === id);
        document.getElementById('detail-id').value = p.id;
        document.getElementById('detail-name').innerText = p.name;
        document.getElementById('detail-price').innerText = '฿' + p.price.toLocaleString();
        
        const oldPriceEl = document.getElementById('detail-old-price');
        const saleBadgeEl = document.getElementById('detail-sale-badge');
        
        if(p.oldPrice) {
            oldPriceEl.innerText = '฿' + p.oldPrice.toLocaleString();
            oldPriceEl.classList.remove('d-none');
            saleBadgeEl.classList.remove('d-none');
        } else {
            oldPriceEl.classList.add('d-none');
            saleBadgeEl.classList.add('d-none');
        }

        document.getElementById('detail-desc').innerText = p.desc;
        document.getElementById('detail-type').innerText = p.type;
        document.getElementById('detail-img').src = p.img; 
        document.getElementById('detail-qty').value = 1;
        document.getElementById('detail-size').selectedIndex = 1; 

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
        if (existingItemIndex > -1) {
            cart[existingItemIndex].qty += qty; 
        } else {
            cart.push({ ...product, size: size, qty: qty }); 
        }

        saveDatabase();
        updateCartBadge();
        bootstrap.Modal.getInstance(document.getElementById('productDetailModal')).hide();
        showToast(`<i class="fa fa-check-circle"></i> เพิ่ม <strong>${product.name}</strong> ลงตะกร้าแล้ว`);

        if(isBuyNow) setTimeout(openCart, 500);
    }
    
    function updateCartBadge() {
        document.getElementById('cart-count').innerText = cart.reduce((sum, item) => sum + item.qty, 0);
    }

    // --------------------------------------------------------
    // ระบบชำระเงิน & คูปอง & สั่งซื้อ - ต่อ Database
    // --------------------------------------------------------
    function openCart() {
        if(!currentUser) return openAuthModal('login');
        
        document.getElementById('ship-name').value = currentUser.name || '';
        document.getElementById('ship-phone').value = currentUser.phone || '';
        
        renderCartItems();
        updateSummary();
        new bootstrap.Modal(document.getElementById('checkoutModal')).show();
    }

    function renderCartItems() {
        const container = document.getElementById('cart-items-container');
        if(cart.length === 0) {
            container.innerHTML = `
                <div class="text-center py-5">
                    <i class="fa fa-shopping-basket fa-3x text-muted mb-3"></i>
                    <p class="text-muted">ตะกร้าของคุณว่างเปล่า</p>
                </div>`;
            return;
        }
        
        container.innerHTML = cart.map((item, index) => `
            <div class="d-flex align-items-center mb-3 p-3 border rounded bg-white shadow-sm">
                <img src="${item.img}" width="80" height="80" class="rounded me-3 border" style="object-fit: cover;">
                <div class="flex-grow-1">
                    <h6 class="mb-1 fw-bold" style="font-size: 15px;">${item.name}</h6>
                    <small class="text-muted d-block mb-1">ไซส์: <strong>${item.size}</strong></small>
                    <span class="badge bg-light text-dark border">จำนวน: ${item.qty}</span>
                </div>
                <div class="text-end ms-2">
                    <div class="fw-bold text-danger mb-2">฿${(item.price * item.qty).toLocaleString()}</div>
                    <button class="btn btn-sm btn-outline-danger py-0 px-2" onclick="removeFromCart(${index})">
                        <i class="fa fa-trash"></i> ลบ
                    </button>
                </div>
            </div>
        `).join('');
    }

    function removeFromCart(index) {
        cart.splice(index, 1);
        saveDatabase();
        updateCartBadge();
        renderCartItems();
        updateSummary();
    }

    function collectCoupon(code) {
        if(!currentUser) return openAuthModal('login');
        if(!currentUser.coupons) currentUser.coupons = [];
        
        if(currentUser.coupons.includes(code)) return showToast('คุณเก็บคูปองนี้ไปแล้ว', 'warning');
        
        currentUser.coupons.push(code);
        saveDatabase();
        showToast(`🎉 เก็บโค้ด ${code} สำเร็จ! นำไปใช้ในหน้าชำระเงินได้เลย`, 'success');
    }

    function renderMyCoupons() {
        const container = document.getElementById('my-coupons-list');
        if(!currentUser || !currentUser.coupons || currentUser.coupons.length === 0) {
            container.innerHTML = '';
            return;
        }
        container.innerHTML = '<strong>คูปองของคุณ (คลิกเพื่อใช้):</strong> ' + currentUser.coupons.map(c => 
            `<span class="badge bg-warning text-dark me-1 shadow-sm p-2" style="cursor:pointer;" onclick="document.getElementById('coupon-input').value='${c}'; applyCoupon();"><i class="fa fa-ticket-alt"></i> ${c}</span>`
        ).join('');
    }

    function applyCoupon() {
        const code = document.getElementById('coupon-input').value.trim().toUpperCase();
        if(code === 'NEWBIE50') {
            if(!currentUser.coupons || !currentUser.coupons.includes(code)) {
                return showToast('กรุณากดเก็บคูปองที่หน้าหลักก่อนใช้งาน', 'error');
            }
            discount = 50;
            showToast('✅ ใช้คูปองส่วนลด 50 บาทสำเร็จ!', 'success');
            updateSummary();
        } else if (code === '') {
            showToast('กรุณากรอกรหัสคูปอง', 'warning');
        } else {
            showToast('รหัสคูปองไม่ถูกต้อง หรือหมดอายุแล้ว', 'error');
        }
    }

    function updateSummary() {
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        const shipping = cart.length > 0 ? 50 : 0;
        let total = subtotal + shipping - discount;
        if(total < 0) total = 0;

        document.getElementById('summary-subtotal').innerText = '฿' + subtotal.toLocaleString();
        document.getElementById('summary-discount').innerText = '-฿' + discount.toLocaleString();
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
        } else {
            processOrderSuccess();
        }
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

    function qrPaymentSuccess() {
        clearInterval(qrTimerInterval);
        bootstrap.Modal.getInstance(document.getElementById('qrModal')).hide();
        processOrderSuccess();
    }

    function qrPaymentCancel() {
        clearInterval(qrTimerInterval);
        bootstrap.Modal.getInstance(document.getElementById('qrModal')).hide();
        showToast('หมดเวลา/ยกเลิก การชำระเงิน', 'error');
        new bootstrap.Modal(document.getElementById('checkoutModal')).show();
    }

    function processOrderSuccess() {
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        const total = subtotal + 50 - discount;
        const addressStr = document.getElementById('ship-address').value.trim();
        const statusStr = paymentMethod === 'QR' ? 'paid' : 'pending';
        
        const orderData = {
            username: currentUser ? currentUser.username : null,
            total: total,
            status: statusStr,
            address: addressStr,
            items: cart
        };

        fetch('save_order.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(orderData)
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                const newOrder = {
                    orderId: 'ORD-' + String(data.order_id).padStart(5, '0'),
                    date: new Date().toLocaleString('th-TH'),
                    items: [...cart],
                    total: total,
                    status: paymentMethod === 'QR' ? 'ชำระเงินแล้ว' : 'รอเก็บเงินปลายทาง (COD)',
                    address: addressStr
                };

                if(!currentUser.orders) currentUser.orders = [];
                currentUser.orders.unshift(newOrder); 
                
                cart = []; discount = 0;
                
                if(currentUser.coupons && currentUser.coupons.includes('NEWBIE50')) {
                    currentUser.coupons = currentUser.coupons.filter(c => c !== 'NEWBIE50');
                }
                
                saveDatabase();
                updateCartBadge();
                
                const checkoutModalEl = document.getElementById('checkoutModal');
                if(checkoutModalEl.classList.contains('show')) {
                    bootstrap.Modal.getInstance(checkoutModalEl).hide();
                }
                
                showToast('🎉 สั่งซื้อสำเร็จ! ขอบคุณที่ใช้บริการ', 'success');
                setTimeout(openOrderHistory, 1500); 
            } else {
                showToast('เกิดข้อผิดพลาดจากเซิร์ฟเวอร์: ' + data.message, 'error');
            }
        })
        .catch(err => {
            console.error(err);
            showToast('เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
        });
    }

    // --------------------------------------------------------
    // ประวัติการสั่งซื้อ
    // --------------------------------------------------------
    function openOrderHistory() {
        if(!currentUser) return;
        const container = document.getElementById('history-container');
        
        if(!currentUser.orders || currentUser.orders.length === 0) {
            container.innerHTML = `
                <div class="text-center py-5">
                    <i class="fa fa-box-open fa-3x text-muted mb-3"></i>
                    <p class="text-muted">คุณยังไม่มีประวัติการสั่งซื้อ</p>
                    <button class="btn btn-warning mt-2" data-bs-dismiss="modal">ไปช้อปปิ้งเลย</button>
                </div>`;
        } else {
            container.innerHTML = currentUser.orders.map(o => `
                <div class="card mb-4 border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-bottom">
                        <div>
                            <span class="fw-bold text-dark"><i class="fa fa-receipt text-primary me-2"></i>ออเดอร์: ${o.orderId}</span>
                            <small class="text-muted d-block mt-1"><i class="fa fa-calendar-alt me-1"></i> ${o.date}</small>
                        </div>
                        <span class="badge ${o.status.includes('ชำระ') ? 'bg-success' : 'bg-warning text-dark'} px-3 py-2">${o.status}</span>
                    </div>
                    <div class="card-body bg-light">
                        ${o.items.map(item => `
                            <div class="d-flex justify-content-between align-items-center small mb-2 border-bottom pb-2">
                                <div class="d-flex align-items-center">
                                    <img src="${item.img}" width="40" class="rounded me-2">
                                    <span>${item.name} <span class="text-muted">(Size: ${item.size})</span></span>
                                </div>
                                <span>x ${item.qty} = <strong>฿${(item.price * item.qty).toLocaleString()}</strong></span>
                            </div>
                        `).join('')}
                        <div class="d-flex justify-content-between align-items-end mt-3">
                            <small class="text-muted" style="max-width: 60%;"><i class="fa fa-map-marker-alt"></i> จัดส่ง: ${o.address}</small>
                            <div class="text-end fw-bold">ยอดสุทธิ: <span class="text-danger fs-5 ms-2">฿${o.total.toLocaleString()}</span></div>
                        </div>
                    </div>
                </div>
            `).join('');
        }
        
        new bootstrap.Modal(document.getElementById('historyModal')).show();
    }
</script>
</body>
</html>