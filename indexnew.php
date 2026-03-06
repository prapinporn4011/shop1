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
    // ข้อมูลสินค้าแบบเต็ม
    // --------------------------------------------------------
    const products = [
        { id: 1, name: "Uthai Thani Home 2024", price: 790, isSale: false, type: "เหย้า", img: "2.jpg", desc: "เสื้อแข่งทีมอุทัยธานี เอฟซี ฤดูกาลล่าสุด" },
        { id: 2, name: "Buriram United Home", price: 690, oldPrice: 890, isSale: true, type: "เหย้า", img: "4.jpg", desc: "เสื้อสายฟ้า ปราสาทสายฟ้า ทรงเข้ารูป ใส่กระชับ" },
        { id: 3, name: "Thailand Edition", price: 590, isSale: false, type: "ซ้อม", img: "5.jpg", desc: "เสื้อเชียร์ทีมชาติไทย สวมใส่สบาย" },
        { id: 4, name: "Port FC Away Kit", price: 550, oldPrice: 750, isSale: true, type: "เยือน", img: "6.jpg", desc: "สิงห์เจ้าท่า สีเยือนเรียบหรู ดีไซน์คอปกคลาสสิก" },
        { id: 5, name: "Training BGPU", price: 490, isSale: false, type: "ซ้อม", img: "7.jpg", desc: "เสื้อซ้อมคุณภาพสูงจากสโมสรบีจี ปทุม ยูไนเต็ด" },
        { id: 6, name: "Muangthong Utd Home", price: 790, isSale: false, type: "เหย้า", img: "8.jpg", desc: "กิเลนผยอง คลาสสิกสีแดงดำ เนื้อผ้าทอพิเศษเบาสบาย" },
        { id: 7, name: "Chonburi FC Away", price: 450, oldPrice: 650, isSale: true, type: "เยือน", img: "9.jpg", desc: "ฉลามชล ดีไซน์สปอร์ตทันสมัย สีขาวตัดฟ้า" },
        { id: 8, name: "Special Training Limited", price: 390, isSale: false, type: "ซ้อม", img: "10.jpg", desc: "เสื้อซ้อมรุ่นลิมิเต็ด ผลิตจำนวนจำกัด" }
    ];

    // --------------------------------------------------------
    // ตัวแปรระบบ และฐานข้อมูลจำลอง (DB)
    // --------------------------------------------------------
    let usersDB = []; // จำลอง Table Users ใน Database
    let currentUser = null; // ผู้ใช้ที่ล็อกอินอยู่
    let cart = [];
    let discount = 0;
    let paymentMethod = 'COD';
    let qrTimerInterval;

    // --------------------------------------------------------
    // ฟังก์ชันเริ่มต้น
    // --------------------------------------------------------
    function initStore() {
        renderProducts(products);
        // โหลดข้อมูลฐานข้อมูลจำลองทั้งหมด
        const storedDB = localStorage.getItem('thanjai_users_db');
        if(storedDB) usersDB = JSON.parse(storedDB);

        // เช็คว่ามีใครล็อกอินค้างไว้ไหม
        const activeSession = localStorage.getItem('thanjai_active_session');
        if (activeSession) {
            const foundUser = usersDB.find(u => u.username === activeSession);
            if(foundUser) {
                currentUser = foundUser;
                cart = currentUser.cart || [];
                updateNavUI();
                updateCartBadge();
            }
        }
    }

    // ฟังก์ชันแจ้งเตือนแบบ Toast (สวยงามกว่า Alert)
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

    // ฟังก์ชันเซฟข้อมูลลงจำลอง Database
    function saveDatabase() {
        if(currentUser) {
            // อัปเดตข้อมูลของ currentUser ลงใน usersDB
            const index = usersDB.findIndex(u => u.username === currentUser.username);
            currentUser.cart = cart;
            if(index !== -1) usersDB[index] = currentUser;
            else usersDB.push(currentUser); // เผื่อกรณีตกหล่น
        }
        localStorage.setItem('thanjai_users_db', JSON.stringify(usersDB));
        if(currentUser) localStorage.setItem('thanjai_active_session', currentUser.username);
    }

    // --------------------------------------------------------
    // ระบบสมาชิก (Authentication & Validation)
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

        // 1. เช็คว่ากรอกครบไหม
        if(!user || !email || !phone || !pass) {
            return showToast('<i class="fa fa-exclamation-circle"></i> กรุณากรอกข้อมูลให้ครบถ้วน', 'error');
        }

        // 2. ตรวจสอบรูปแบบอีเมล (Validation Regex)
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if(!emailRegex.test(email)) {
            return showToast('<i class="fa fa-envelope"></i> รูปแบบอีเมลไม่ถูกต้อง (ต้องมี @ และ .)', 'error');
        }

        // 3. ตรวจสอบรูปแบบเบอร์โทร (ต้องเป็นตัวเลข 10 ตัว)
        const phoneRegex = /^\d{10}$/;
        if(!phoneRegex.test(phone)) {
            return showToast('<i class="fa fa-phone"></i> เบอร์โทรศัพท์ต้องเป็นตัวเลข 10 หลักเท่านั้น', 'error');
        }

        // 4. ตรวจสอบข้อมูลซ้ำในระบบ (Duplicate Check)
        const isUserExist = usersDB.some(u => u.username.toLowerCase() === user.toLowerCase());
        const isEmailExist = usersDB.some(u => u.email.toLowerCase() === email.toLowerCase());
        const isPhoneExist = usersDB.some(u => u.phone === phone);

        if(isUserExist) return showToast('ชื่อผู้ใช้นี้ถูกใช้งานแล้ว กรุณาตั้งชื่ออื่น', 'error');
        if(isEmailExist) return showToast('อีเมลนี้ถูกสมัครสมาชิกไปแล้ว', 'error');
        if(isPhoneExist) return showToast('เบอร์โทรศัพท์นี้ถูกลงทะเบียนไปแล้ว', 'error');

        // ผ่านทุกด่าน -> สร้างบัญชีใหม่
        currentUser = {
            username: user, name: user, email: email, phone: phone, password: pass,
            profilePic: 'https://api.dicebear.com/7.x/avataaars/svg?seed=' + user,
            cart: [], orders: [], coupons: [] // เพิ่มระบบจำคูปอง
        };
        
        usersDB.push(currentUser); // เพิ่มเข้าฐานข้อมูล
        saveDatabase(); // บันทึก
        
        bootstrap.Modal.getInstance(document.getElementById('authModal')).hide();
        updateNavUI();
        showToast('🎉 สมัครสมาชิกและเข้าสู่ระบบสำเร็จ!', 'success');
    }

    function login() {
        const user = document.getElementById('login-user').value.trim();
        const pass = document.getElementById('login-pass').value.trim();

        if(!user || !pass) return showToast('กรุณากรอกชื่อผู้ใช้และรหัสผ่าน', 'warning');

        // ค้นหาในระบบ
        const foundUser = usersDB.find(u => u.username.toLowerCase() === user.toLowerCase());
        
        if(!foundUser) {
            return showToast('❌ ไม่พบชื่อผู้ใช้นี้ในระบบ กรุณาสมัครสมาชิก', 'error');
        }

        if(foundUser.password !== pass) {
            return showToast('❌ รหัสผ่านไม่ถูกต้อง', 'error');
        }

        // ล็อกอินสำเร็จ
        currentUser = foundUser;
        cart = currentUser.cart || [];
        saveDatabase();
        bootstrap.Modal.getInstance(document.getElementById('authModal')).hide();
        updateNavUI();
        updateCartBadge();
        showToast(`ยินดีต้อนรับกลับมาครับคุณ ${currentUser.name}!`, 'success');
    }

    function logoutUser() {
        if(confirm("ต้องการออกจากระบบใช่หรือไม่?")) {
            localStorage.removeItem('thanjai_active_session');
            currentUser = null;
            cart = [];
            updateNavUI();
            updateCartBadge();
            showToast('ออกจากระบบเรียบร้อยแล้ว', 'success');
        }
    }

    function updateNavUI() {
        if(currentUser) {
            document.getElementById('guest-zone').classList.add('d-none');
            document.getElementById('user-zone').classList.remove('d-none');
            document.getElementById('nav-username').innerText = currentUser.username;
            document.getElementById('nav-profile-pic').src = currentUser.profilePic;
            
            // อัปเดตข้อมูลในฟอร์มโปรไฟล์
            document.getElementById('setting-profile-pic').src = currentUser.profilePic;
            document.getElementById('set-name').value = currentUser.name || currentUser.username;
            document.getElementById('set-phone').value = currentUser.phone || '';
            document.getElementById('set-email').value = currentUser.email || '';
        } else {
            document.getElementById('guest-zone').classList.remove('d-none');
            document.getElementById('user-zone').classList.add('d-none');
        }
    }

    // --------------------------------------------------------
    // ระบบโปรไฟล์
    // --------------------------------------------------------
    function uploadProfilePic(event) {
        const reader = new FileReader();
        reader.onload = function() {
            document.getElementById('setting-profile-pic').src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    function saveProfile() {
        if(!currentUser) return;
        
        const newPhone = document.getElementById('set-phone').value;
        if(!/^\d{10}$/.test(newPhone)) return showToast('เบอร์โทรต้องเป็นตัวเลข 10 หลัก', 'error');

        currentUser.name = document.getElementById('set-name').value;
        currentUser.phone = newPhone;
        currentUser.profilePic = document.getElementById('setting-profile-pic').src;
        
        const newPass = document.getElementById('set-password').value;
        if(newPass) currentUser.password = newPass;

        saveDatabase();
        updateNavUI();
        bootstrap.Modal.getInstance(document.getElementById('profileModal')).hide();
        showToast('อัปเดตข้อมูลโปรไฟล์เรียบร้อย', 'success');
    }

    // --------------------------------------------------------
    // ระบบคูปอง (Coupon System)
    // --------------------------------------------------------
    function collectCoupon(code) {
        if(!currentUser) {
            showToast('กรุณาเข้าสู่ระบบเพื่อเก็บคูปองครับ', 'warning');
            return openAuthModal('login');
        }

        if(!currentUser.coupons) currentUser.coupons = []; // เผื่อบัญชีเก่าไม่มี array นี้
        
        if(currentUser.coupons.includes(code)) {
            showToast('คุณเก็บคูปองนี้ไปแล้วครับ', 'warning');
        } else {
            currentUser.coupons.push(code);
            saveDatabase();
            showToast(`เก็บคูปอง <b>${code}</b> เข้ากระเป๋าแล้ว! นำไปใช้ตอนชำระเงินได้เลย`, 'success');
        }
    }

    // --------------------------------------------------------
    // ระบบแสดงสินค้า
    // --------------------------------------------------------
    function filterProducts(type) {
        document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.replace('btn-dark', 'btn-outline-dark'));
        event.target.classList.replace('btn-outline-dark', 'btn-dark');

        let filtered = products;
        if (type === 'โปรโมชั่น') filtered = products.filter(p => p.isSale);
        else if (type !== 'ทั้งหมด') filtered = products.filter(p => p.type === type);

        renderProducts(filtered);
    }

    function searchProducts() {
        const term = document.getElementById('search-input').value.toLowerCase();
        renderProducts(products.filter(p => p.name.toLowerCase().includes(term)));
    }

    function renderProducts(items) {
        document.getElementById('store-display').innerHTML = items.map(p => `
            <div class="col-6 col-md-3 mb-4">
                <div class="card product-card shadow-sm border-0 h-100">
                    ${p.isSale ? `<span class="sale-badge">SALE!</span>` : ''}
                    <img src="${p.img}" class="card-img-top" alt="${p.name}" onerror="this.src='https://placehold.co/400x400?text=Image'">
                    <div class="card-body d-flex flex-column">
                        <span class="badge bg-secondary mb-2 align-self-start" style="font-size: 10px;">${p.type}</span>
                        <h6 class="card-title fw-bold text-dark mb-1" style="font-size: 14px;">${p.name}</h6>
                        <div class="mt-auto pt-2">
                            <span class="text-danger fw-bold fs-5">฿${p.price.toLocaleString()}</span>
                            ${p.oldPrice ? `<span class="text-muted text-decoration-line-through small ms-1">฿${p.oldPrice}</span>` : ''}
                            
                            <div class="d-flex gap-1 mt-2">
                                <button class="btn btn-cart btn-sm flex-fill" onclick="quickAddToCart(${p.id}, false)" title="เพิ่มลงตะกร้า">
                                    <i class="fa fa-cart-plus"></i>
                                </button>
                                <button class="btn btn-buy-now btn-sm flex-fill fw-bold" onclick="openProduct(${p.id})">
                                    ซื้อ / ดูรายละเอียด
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    function openProduct(id) {
        const p = products.find(x => x.id === id);
        document.getElementById('detail-id').value = p.id;
        document.getElementById('detail-name').innerText = p.name;
        document.getElementById('detail-price').innerText = '฿' + p.price.toLocaleString();
        document.getElementById('detail-desc').innerText = p.desc;
        document.getElementById('detail-type').innerText = p.type;
        document.getElementById('detail-img').src = p.img;
        document.getElementById('detail-qty').value = 1;
        document.getElementById('detail-size').selectedIndex = 1;

        if(p.isSale) {
            document.getElementById('detail-sale-badge').classList.remove('d-none');
            document.getElementById('detail-old-price').classList.remove('d-none');
            document.getElementById('detail-old-price').innerText = '฿' + p.oldPrice;
        } else {
            document.getElementById('detail-sale-badge').classList.add('d-none');
            document.getElementById('detail-old-price').classList.add('d-none');
        }

        new bootstrap.Modal(document.getElementById('productDetailModal')).show();
    }

    // --------------------------------------------------------
    // ระบบตะกร้า & ปุ่มสั่งซื้อ
    // --------------------------------------------------------
    
    // ฟังก์ชันช่วยตอนกดปุ่มตะกร้าจากหน้าแรก
    function quickAddToCart(id, isBuyNow) {
        document.getElementById('detail-id').value = id;
        document.getElementById('detail-size').value = 'M'; // Default size
        document.getElementById('detail-qty').value = 1;
        addToCart(isBuyNow);
    }

    // ฟังก์ชันหลัก: รองรับ 2 ปุ่ม (เพิ่มตะกร้าปกติ vs ซื้อทันที)
    function addToCart(isBuyNow) {
        if(!currentUser) {
            const modalEl = document.getElementById('productDetailModal');
            if(bootstrap.Modal.getInstance(modalEl)) bootstrap.Modal.getInstance(modalEl).hide();
            
            showToast("กรุณาเข้าสู่ระบบหรือสมัครสมาชิกก่อนครับ", 'warning');
            return openAuthModal('login');
        }

        const id = parseInt(document.getElementById('detail-id').value);
        const size = document.getElementById('detail-size').value;
        const qty = parseInt(document.getElementById('detail-qty').value);
        const prod = products.find(p => p.id === id);

        const existing = cart.find(item => item.id === id && item.size === size);
        if(existing) existing.qty += qty;
        else cart.push({ ...prod, size, qty });

        saveDatabase();
        updateCartBadge();
        
        // ปิดหน้าต่างรายละเอียด
        const modalEl = document.getElementById('productDetailModal');
        if(bootstrap.Modal.getInstance(modalEl)) bootstrap.Modal.getInstance(modalEl).hide();
        
        if(isBuyNow) {
            // ถ้ากด "ซื้อทันที" ให้เปิดหน้าตะกร้าเลย
            setTimeout(() => openCart(), 300);
        } else {
            // ถ้ากด "เพิ่มลงตะกร้า" ให้แค่แจ้งเตือนแล้วช้อปต่อ
            showToast(`<i class="fa fa-check-circle"></i> เพิ่ม <b>${prod.name}</b> ลงตะกร้าแล้ว!`, 'success');
        }
    }

    function updateCartBadge() {
        document.getElementById('cart-count').innerText = cart.reduce((sum, item) => sum + item.qty, 0);
    }

    function openCart() {
        if(!currentUser) {
            showToast("กรุณาเข้าสู่ระบบก่อนครับ", 'warning');
            return openAuthModal('login');
        }
        if(cart.length === 0) return showToast("ยังไม่มีสินค้าในตะกร้าครับ เลือกช้อปได้เลย!", 'warning');

        // โหลดข้อมูลที่อยู่ลงฟอร์ม
        document.getElementById('ship-name').value = currentUser.name;
        document.getElementById('ship-phone').value = currentUser.phone;
        document.getElementById('ship-address').value = currentUser.address || '';
        document.getElementById('coupon-input').value = '';
        discount = 0;

        // แสดงคูปองที่ผู้ใช้มี
        const couponList = document.getElementById('my-coupons-list');
        if(currentUser.coupons && currentUser.coupons.length > 0) {
            couponList.innerHTML = `<span class="text-primary fw-bold"><i class="fa fa-ticket-alt"></i> คูปองของคุณ:</span> ` + 
                currentUser.coupons.map(c => `<span class="badge bg-warning text-dark mx-1" style="cursor:pointer;" onclick="document.getElementById('coupon-input').value='${c}'">${c}</span>`).join('');
        } else {
            couponList.innerHTML = `<span class="text-muted small">คุณยังไม่มีคูปองส่วนลด</span>`;
        }

        renderCartItems();
        new bootstrap.Modal(document.getElementById('checkoutModal')).show();
    }

    function renderCartItems() {
        const container = document.getElementById('cart-items-container');
        let subtotal = 0;
        
        container.innerHTML = cart.map((item, index) => {
            const itemTotal = item.price * item.qty;
            subtotal += itemTotal;
            return `
                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                    <img src="${item.img}" width="60" class="rounded shadow-sm me-3" onerror="this.src='https://placehold.co/60'">
                    <div class="flex-grow-1">
                        <h6 class="mb-0 fw-bold fs-6">${item.name}</h6>
                        <small class="text-muted">ไซส์: <strong>${item.size}</strong> | ฿${item.price} x ${item.qty}</small>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold text-dark mb-1">฿${itemTotal.toLocaleString()}</div>
                        <button class="btn btn-sm btn-outline-danger py-0 px-2" onclick="removeFromCart(${index})"><i class="fa fa-trash small"></i></button>
                    </div>
                </div>`;
        }).join('');

        updateSummary(subtotal);
    }

    function removeFromCart(index) {
        cart.splice(index, 1);
        saveDatabase();
        updateCartBadge();
        if(cart.length === 0) {
            bootstrap.Modal.getInstance(document.getElementById('checkoutModal')).hide();
            showToast('ตะกร้าสินค้าว่างเปล่าแล้ว', 'warning');
        } else {
            renderCartItems();
        }
    }

    function applyCoupon() {
        const code = document.getElementById('coupon-input').value.toUpperCase().trim();
        
        // เช็คว่าผู้ใช้เก็บคูปองนี้มาหรือยัง
        if(!currentUser.coupons || !currentUser.coupons.includes(code)) {
            discount = 0;
            renderCartItems();
            return showToast("คุณยังไม่ได้เก็บคูปองนี้ หรือรหัสไม่ถูกต้อง", 'error');
        }

        // ระบบเช็คเงื่อนไขคูปอง
        if(code === 'NEWBIE50') {
            discount = 50;
            showToast("ใช้งานคูปองส่วนลด 50 บาทสำเร็จ!", 'success');
        } else {
            discount = 0;
            showToast("คูปองไม่สามารถใช้งานได้", 'error');
        }
        renderCartItems(); 
    }

    function updateSummary(subtotal) {
        document.getElementById('summary-subtotal').innerText = `฿${subtotal.toLocaleString()}`;
        document.getElementById('summary-discount').innerText = `-฿${discount}`;
        const total = subtotal + 50 - discount; // ค่าส่ง 50
        document.getElementById('summary-total').innerText = `฿${total.toLocaleString()}`;
    }

    function selectPayment(method) {
        paymentMethod = method;
        document.getElementById('pay-cod').classList.toggle('active', method === 'COD');
        document.getElementById('pay-qr').classList.toggle('active', method === 'QR');
    }

    // --------------------------------------------------------
    // ระบบยืนยันคำสั่งซื้อ & QR Code
    // --------------------------------------------------------
    function confirmOrder() {
        const name = document.getElementById('ship-name').value.trim();
        const phone = document.getElementById('ship-phone').value.trim();
        const address = document.getElementById('ship-address').value.trim();

        if(!name || !phone || !address) return showToast("กรุณากรอกข้อมูลการจัดส่งให้ครบถ้วน", 'error');
        if(!/^\d{10}$/.test(phone)) return showToast("เบอร์โทรศัพท์ต้องมี 10 หลัก", 'error');

        // เซฟที่อยู่ไว้ใช้ครั้งหน้า
        currentUser.address = address;
        currentUser.phone = phone;
        
        // ถ้าใช้คูปองไปแล้ว ลบออกจากกระเป๋า (เพื่อให้ใช้ได้ครั้งเดียว)
        const usedCoupon = document.getElementById('coupon-input').value.toUpperCase().trim();
        if(discount > 0 && currentUser.coupons.includes(usedCoupon)) {
            currentUser.coupons = currentUser.coupons.filter(c => c !== usedCoupon);
        }

        saveDatabase();
        bootstrap.Modal.getInstance(document.getElementById('checkoutModal')).hide();

        if(paymentMethod === 'QR') {
            setTimeout(() => {
                new bootstrap.Modal(document.getElementById('qrModal')).show();
                startQrTimer();
            }, 400);
        } else {
            finalizeOrder('รอยืนยันจากแอดมิน');
            showToast("สั่งซื้อสำเร็จ! (เก็บเงินปลายทาง) รอแอดมินยืนยัน", 'success');
        }
    }

    function startQrTimer() {
        let timeLeft = 300; // 5 นาที
        clearInterval(qrTimerInterval);
        document.getElementById('qr-timer').innerText = "05:00";
        
        qrTimerInterval = setInterval(() => {
            timeLeft--;
            let m = Math.floor(timeLeft / 60).toString().padStart(2, '0');
            let s = (timeLeft % 60).toString().padStart(2, '0');
            document.getElementById('qr-timer').innerText = `${m}:${s}`;
            
            if(timeLeft <= 0) {
                clearInterval(qrTimerInterval);
                if(confirm("หมดเวลาชำระเงิน คุณต้องการทำการชำระเงินต่อหรือไม่?")) {
                    startQrTimer(); 
                } else {
                    qrPaymentCancel();
                }
            }
        }, 1000);
    }

    function qrPaymentSuccess() {
        clearInterval(qrTimerInterval);
        bootstrap.Modal.getInstance(document.getElementById('qrModal')).hide();
        finalizeOrder('รอการจัดส่ง');
        showToast("ได้รับยอดโอนเรียบร้อย ระบบกำลังเตรียมจัดส่งสินค้า", 'success');
    }

    function qrPaymentCancel() {
        clearInterval(qrTimerInterval);
        bootstrap.Modal.getInstance(document.getElementById('qrModal')).hide();
        showToast("ยกเลิกการชำระเงิน สินค้ายังอยู่ในตะกร้าของคุณ", 'warning');
    }

    function finalizeOrder(status) {
        const subtotal = cart.reduce((s, i) => s + (i.price * i.qty), 0);
        const total = subtotal + 50 - discount;
        
        const newOrder = {
            orderId: 'THJ' + Math.floor(1000 + Math.random() * 9000),
            date: new Date().toLocaleDateString('th-TH'),
            items: [...cart],
            total: total,
            method: paymentMethod,
            status: status,
            isReviewed: false
        };

        if(!currentUser.orders) currentUser.orders = [];
        currentUser.orders.unshift(newOrder); 
        
        cart = []; // เคลียร์ตะกร้า
        discount = 0;
        saveDatabase();
        updateCartBadge();
    }

    // --------------------------------------------------------
    // ระบบประวัติการสั่งซื้อ
    // --------------------------------------------------------
    function openOrderHistory() {
        const container = document.getElementById('history-container');
        if(!currentUser.orders || currentUser.orders.length === 0) {
            container.innerHTML = '<div class="text-center text-muted my-5"><i class="fa fa-box-open fa-3x mb-3"></i><br>ยังไม่มีประวัติการสั่งซื้อครับ</div>';
        } else {
            container.innerHTML = currentUser.orders.map((o, idx) => `
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-muted small">Order #${o.orderId}</span>
                        ${getStatusBadge(o.status)}
                    </div>
                    <div class="card-body">
                        ${o.items.map(item => `
                            <div class="d-flex mb-2">
                                <img src="${item.img}" width="50" height="50" class="rounded me-3 border" style="object-fit:cover" onerror="this.src='https://placehold.co/50'">
                                <div>
                                    <p class="mb-0 fw-bold small">${item.name} (${item.size})</p>
                                    <small class="text-muted">x${item.qty} | ฿${item.price * item.qty}</small>
                                </div>
                            </div>
                        `).join('')}
                        <hr class="my-2">
                        <div class="d-flex justify-content-between align-items-end">
                            <div>
                                <small class="text-muted d-block">วันที่: ${o.date} | ${o.method === 'COD' ? 'เก็บเงินปลายทาง' : 'โอนเงิน (QR)'}</small>
                                <strong class="text-danger">ยอดรวม: ฿${o.total.toLocaleString()}</strong>
                            </div>
                            <div class="text-end">
                                ${o.status === 'รอยืนยันจากแอดมิน' ? `<button class="btn btn-sm btn-outline-warning mb-1" onclick="simAdminConfirm(${idx})"><i class="fa fa-check"></i> จำลองแอดมินยืนยัน</button>` : ''}
                                ${o.status === 'รอการจัดส่ง' ? `<button class="btn btn-sm btn-outline-info mb-1" onclick="simDelivery(${idx})"><i class="fa fa-truck"></i> จำลองรับของ</button>` : ''}
                                ${o.status === 'จัดส่งสำเร็จ' && !o.isReviewed ? `
                                    <div class="mt-2 bg-light p-2 rounded text-start border" style="min-width: 200px;">
                                        <small class="fw-bold d-block mb-1">ให้คะแนนสินค้า:</small>
                                        <div class="star-rating mb-1" id="stars-${idx}">
                                            <i class="fa fa-star active" onclick="setStar(${idx}, 1)"></i>
                                            <i class="fa fa-star active" onclick="setStar(${idx}, 2)"></i>
                                            <i class="fa fa-star active" onclick="setStar(${idx}, 3)"></i>
                                            <i class="fa fa-star active" onclick="setStar(${idx}, 4)"></i>
                                            <i class="fa fa-star active" onclick="setStar(${idx}, 5)"></i>
                                        </div>
                                        <textarea class="form-control form-control-sm mb-2" id="review-${idx}" placeholder="เขียนรีวิว..."></textarea>
                                        <button class="btn btn-warning btn-sm w-100 fw-bold" onclick="submitReview(${idx})">ส่งรีวิว</button>
                                    </div>
                                ` : ''}
                                ${o.isReviewed ? `<span class="badge bg-success"><i class="fa fa-check"></i> ขอบคุณสำหรับรีวิว!</span>` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        }
        new bootstrap.Modal(document.getElementById('historyModal')).show();
    }

    function getStatusBadge(status) {
        if(status === 'รอยืนยันจากแอดมิน') return `<span class="badge bg-warning text-dark"><i class="fa fa-clock"></i> ${status}</span>`;
        if(status === 'รอการจัดส่ง') return `<span class="badge bg-primary"><i class="fa fa-box"></i> ${status}</span>`;
        if(status === 'จัดส่งสำเร็จ') return `<span class="badge bg-success"><i class="fa fa-check-circle"></i> ${status}</span>`;
        return `<span class="badge bg-secondary">${status}</span>`;
    }

    function simAdminConfirm(idx) {
        currentUser.orders[idx].status = 'รอการจัดส่ง';
        saveDatabase();
        openOrderHistory(); 
    }
    
    function simDelivery(idx) {
        currentUser.orders[idx].status = 'จัดส่งสำเร็จ';
        saveDatabase();
        openOrderHistory(); 
    }

    function setStar(idx, rating) {
        const stars = document.querySelectorAll(`#stars-${idx} i`);
        stars.forEach((star, i) => {
            if(i < rating) star.classList.add('active');
            else star.classList.remove('active');
        });
    }

    function submitReview(idx) {
        const text = document.getElementById(`review-${idx}`).value;
        if(!text) return showToast('กรุณาเขียนรีวิวด้วยครับ', 'warning');
        
        currentUser.orders[idx].isReviewed = true;
        saveDatabase();
        showToast('ขอบคุณสำหรับรีวิวครับ ได้รับ 5 แต้มสะสม!', 'success');
        openOrderHistory();
    }
</script>
</body>
</html>