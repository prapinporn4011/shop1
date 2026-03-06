<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ThanJai Shop - Store</title>
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
        .product-card { border: none; border-radius: 15px; transition: 0.3s; overflow: hidden; height: 100%; position: relative;}
        .product-card:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(0,0,0,0.1); }
        .product-card img { height: 250px; object-fit: cover; }
        .btn-buy { background: var(--primary); color: white; border-radius: 8px; }
        .btn-buy:hover { background: var(--accent); color: var(--primary); }
        .member-badge { font-size: 0.7rem; background: var(--accent); color: var(--primary); padding: 2px 8px; border-radius: 10px; font-weight: bold; }
        .cart-badge { font-size: 0.6rem; padding: 2px 5px; }
        #cart-items-list { max-height: 400px; overflow-y: auto; }
        .payment-box { border: 2px solid #ddd; border-radius: 8px; padding: 15px; cursor: pointer; transition: 0.2s; text-align: center; }
        .payment-box:hover { border-color: var(--accent); background: #fffdf5; }
        .payment-box.active { border-color: var(--accent); background: #fff8e1; font-weight: bold; }
        .sale-badge { position: absolute; top: 10px; right: 10px; background: red; color: white; padding: 5px 10px; border-radius: 5px; font-weight: bold; font-size: 12px; z-index: 10;}
        .profile-img-preview { width: 100px; height: 100px; object-fit: cover; border-radius: 50%; border: 3px solid var(--accent); cursor: pointer;}
    </style>
</head>
<body onload="initStore()">

<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#" onclick="filterProducts('ทั้งหมด')"><i class="fa fa-home me-1"></i>ThanJai <span class="text-warning">Shop</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link active" href="#" onclick="filterProducts('ทั้งหมด')"><i class="fa fa-tshirt me-1"></i>หน้าหลัก</a></li>
                <li class="nav-item"><a class="nav-link" href="#product-list"><i class="fa fa-list me-1"></i>สินค้าทั้งหมด</a></li>
                <li class="nav-item"><a class="nav-link text-danger fw-bold" href="#" onclick="showPromotions()"><i class="fa fa-tags me-1"></i>โปรโมชั่น</a></li>
                <li class="nav-item"><a class="nav-link text-warning fw-bold" href="#" data-bs-toggle="modal" data-bs-target="#contactModal"><i class="fa fa-phone-alt me-1"></i>ติดต่อแอดมิน</a></li>
            </ul>
            
            <div class="d-flex align-items-center gap-3">
                <div class="input-group input-group-sm d-none d-md-flex">
                    <input type="text" id="search-input" class="form-control" placeholder="ค้นหาชื่อทีม..." onkeyup="searchProducts()">
                </div>

                <div id="guest-zone">
                    <button class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#loginModal">เข้าสู่ระบบ / สมัครสมาชิก</button>
                </div>

                <div id="user-zone" class="d-none">
                    <div class="dropdown text-white">
                        <span class="me-2 d-none d-md-inline">ยินดีต้อนรับ, <strong id="display-username">ผู้ใช้</strong> <span class="member-badge">Member</span></span>
                        <a href="#" class="link-light dropdown-toggle" data-bs-toggle="dropdown">
                            <img id="nav-profile-pic" src="https://api.dicebear.com/7.x/avataaars/svg?seed=User" width="35" height="35" class="rounded-circle border" style="object-fit: cover;">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profileModal"><i class="fa fa-user-cog me-2"></i>ตั้งค่าบัญชี</a></li>
                            <li><a class="dropdown-item" href="#" onclick="openOrderHistory()"><i class="fa fa-box-open me-2"></i>ประวัติการสั่งซื้อ/รีวิว</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="#" onclick="logoutUser()"><i class="fa fa-sign-out-alt me-2"></i>ออกจากระบบ</a></li>
                        </ul>
                    </div>
                </div>
                
                <a href="#" class="text-white position-relative" onclick="checkAuthBeforeAction(() => new bootstrap.Modal(document.getElementById('cartModal')).show())">
                    <i class="fa fa-shopping-cart fs-5"></i>
                    <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-badge">0</span>
                </a>
            </div>
        </div>
    </div>
</nav>

<header class="hero-banner text-center" id="home-banner">
    <div class="container">
        <h1 class="display-4 fw-bold">NEW SEASON 2026</h1>
        <p class="lead">คอลเลคชั่นใหม่ล่าสุดจากสโมสรดังทั่วโลก พร้อมส่งแล้ววันนี้!</p>
        <a href="#product-list" class="btn btn-warning btn-lg px-5 fw-bold">เลือกช้อปเลย</a>
    </div>
</header>

<div class="container mt-4 d-none" id="promo-banner">
    <div class="alert alert-danger text-center shadow-sm rounded-3">
        <h3 class="fw-bold"><i class="fa fa-gift"></i> โค้ดส่วนลดลูกค้าใหม่!</h3>
        <p>เก็บโค้ด <strong>"NEWBIE50"</strong> เพื่อรับส่วนลด 50 บาท ในการสั่งซื้อครั้งแรก</p>
    </div>
</div>

<main class="container my-5" id="product-list">
    <div class="d-flex justify-content-center gap-2 mb-5 overflow-auto pb-2">
        <button class="btn btn-dark px-4 rounded-pill filter-btn" onclick="filterProducts('ทั้งหมด')">ทั้งหมด</button>
        <button class="btn btn-outline-dark px-4 rounded-pill filter-btn" onclick="filterProducts('เหย้า')">เหย้า</button>
        <button class="btn btn-outline-dark px-4 rounded-pill filter-btn" onclick="filterProducts('เยือน')">เยือน</button>
        <button class="btn btn-outline-dark px-4 rounded-pill filter-btn" onclick="filterProducts('ซ้อม')">เสื้อซ้อม</button>
    </div>
    <div class="row g-4" id="store-display"></div>
</main>

<div class="modal fade" id="loginModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold" id="authModalTitle">เข้าสู่ระบบ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="login-form">
                    <input type="text" id="login-user" class="form-control mb-2" placeholder="ชื่อผู้ใช้ (Username)">
                    <input type="password" id="login-pass" class="form-control mb-3" placeholder="รหัสผ่าน">
                    <button class="btn btn-warning w-100 fw-bold mb-2" onclick="login()">เข้าสู่ระบบ</button>
                    <p class="text-center small mt-2">ยังไม่มีบัญชี? <a href="#" onclick="toggleAuthForm('register')">สมัครสมาชิก</a></p>
                </div>
                <div id="register-form" class="d-none">
                    <input type="text" id="reg-user" class="form-control mb-2" placeholder="ชื่อผู้ใช้">
                    <input type="email" id="reg-email" class="form-control mb-2" placeholder="อีเมล">
                    <input type="password" id="reg-pass" class="form-control mb-3" placeholder="รหัสผ่าน">
                    <button class="btn btn-success w-100 fw-bold mb-2" onclick="register()">สมัครสมาชิก</button>
                    <p class="text-center small mt-2">มีบัญชีอยู่แล้ว? <a href="#" onclick="toggleAuthForm('login')">เข้าสู่ระบบ</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="profileModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold">ตั้งค่าบัญชีส่วนตัว</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <label for="profile-upload">
                    <img id="setting-profile-img" src="https://api.dicebear.com/7.x/avataaars/svg?seed=User" class="profile-img-preview mb-2" title="คลิกเพื่อเปลี่ยนรูป">
                </label>
                <input type="file" id="profile-upload" class="d-none" accept="image/*" onchange="previewProfilePic(event)">
                <p class="small text-muted">คลิกที่รูปเพื่อเปลี่ยนภาพโปรไฟล์</p>
                
                <div class="text-start mt-3">
                    <label class="form-label small fw-bold">ชื่อแสดงผล</label>
                    <input type="text" id="set-displayname" class="form-control mb-2">
                    <label class="form-label small fw-bold">เบอร์โทรศัพท์</label>
                    <input type="text" id="set-phone" class="form-control mb-2">
                    <label class="form-label small fw-bold">รหัสผ่านใหม่ (ปล่อยว่างถ้าไม่เปลี่ยน)</label>
                    <input type="password" id="set-password" class="form-control mb-3">
                </div>
                <button class="btn btn-warning w-100 fw-bold" onclick="saveProfile()">บันทึกข้อมูล</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="productDetailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold" id="detail-title">รายละเอียดสินค้า</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5 text-center mb-3 mb-md-0 position-relative">
                        <span id="detail-sale-badge" class="sale-badge d-none">ลดราคา!</span>
                        <img id="detail-img" src="" class="img-fluid rounded shadow-sm" style="max-height: 350px; object-fit: contain;">
                    </div>
                    <div class="col-md-7">
                        <span id="detail-type" class="badge bg-secondary mb-2"></span>
                        <h4 class="fw-bold" id="detail-name">ชื่อสินค้า</h4>
                        <div class="mb-2">
                            <span class="text-danger fw-bold fs-3" id="detail-price">฿0</span>
                            <span class="text-muted text-decoration-line-through ms-2 d-none" id="detail-old-price">฿0</span>
                        </div>
                        <p class="text-muted small mb-1" id="detail-desc">รายละเอียด...</p>
                        <p class="text-primary small mb-1"><i class="fa fa-truck"></i> ค่าจัดส่ง: <strong>฿50</strong></p>
                        <p class="text-success small mb-3"><i class="fa fa-clock"></i> ระยะเวลาจัดส่ง: <strong>2-3 วันทำการ</strong></p>
                        <hr>
                        <div class="row g-3 align-items-end">
                            <div class="col-6">
                                <label class="form-label fw-bold">ไซส์ (Size):</label>
                                <select id="detail-size" class="form-select border-dark">
                                    <option value="S">S (อก 36")</option>
                                    <option value="M" selected>M (อก 38")</option>
                                    <option value="L">L (อก 40")</option>
                                    <option value="XL">XL (อก 42")</option>
                                    <option value="2XL">2XL (อก 44")</option>
                                    <option value="3XL">3XL (อก 46")</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold">จำนวน:</label>
                                <input type="number" id="detail-qty" class="form-control border-dark" value="1" min="1">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <input type="hidden" id="detail-id">
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" id="btn-confirm-action" class="btn btn-warning fw-bold px-4" onclick="checkAuthBeforeAction(confirmProductSelection)">
                    ยืนยัน
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="cartModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold"><i class="fa fa-shopping-cart me-2"></i>ตะกร้าสินค้า</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="cart-items-list"></div>
            <div class="modal-footer d-flex justify-content-between align-items-center bg-light">
                <h4 class="text-danger fw-bold mb-0" id="cart-total">฿0</h4>
                <button type="button" class="btn btn-warning fw-bold" onclick="openCheckoutModal()">ชำระเงิน <i class="fa fa-arrow-right"></i></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="checkoutModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold"><i class="fa fa-check-circle me-2"></i>ยืนยันคำสั่งซื้อ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6 class="fw-bold border-bottom pb-2">ที่อยู่จัดส่ง</h6>
                        <input type="text" id="ship-name" class="form-control mb-2" placeholder="ชื่อผู้รับ">
                        <input type="text" id="ship-phone" class="form-control mb-2" placeholder="เบอร์ติดต่อ">
                        <textarea id="ship-address" class="form-control mb-3" rows="3" placeholder="ที่อยู่จัดส่ง..."></textarea>
                        
                        <h6 class="fw-bold border-bottom pb-2 mt-4">เลือกวิธีชำระเงิน</h6>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="payment-box active" id="pay-cod" onclick="selectPaymentMethod('COD')">
                                    <i class="fa fa-truck fa-2x mb-2 text-dark"></i>
                                    <div class="small">เก็บเงินปลายทาง</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="payment-box" id="pay-qr" onclick="selectPaymentMethod('QR')">
                                    <i class="fa fa-qrcode fa-2x mb-2 text-primary"></i>
                                    <div class="small">QR PromptPay</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 bg-light p-3 rounded">
                        <h6 class="fw-bold border-bottom pb-2">สรุปรายการ</h6>
                        <div id="checkout-summary-list" style="max-height: 150px; overflow-y: auto;" class="small mb-2"></div>
                        <hr>
                        <div class="input-group input-group-sm mb-3">
                            <input type="text" id="coupon-code" class="form-control" placeholder="ใส่โค้ดส่วนลด (ถ้ามี)">
                            <button class="btn btn-outline-secondary" onclick="applyCoupon()">ใช้โค้ด</button>
                        </div>
                        <div class="d-flex justify-content-between small"><span>รวมค่าสินค้า:</span> <span id="chk-subtotal">฿0</span></div>
                        <div class="d-flex justify-content-between small"><span>ค่าจัดส่ง:</span> <span>฿50</span></div>
                        <div class="d-flex justify-content-between small text-success"><span>ส่วนลด:</span> <span id="chk-discount">-฿0</span></div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-5"><span>ยอดสุทธิ:</span> <span class="text-danger" id="chk-total">฿0</span></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success fw-bold w-100" onclick="processOrder()"><i class="fa fa-check"></i> ยืนยันการสั่งซื้อ</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="qrModal" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content text-center py-4">
            <div class="modal-body">
                <h5 class="fw-bold text-primary">สแกนเพื่อชำระเงิน</h5>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=PAYMENT_DATA" class="img-thumbnail my-3">
                <p class="text-danger fw-bold fs-4 mb-1" id="qr-timer">05:00</p>
                <p class="small text-muted mb-3">กรุณาชำระเงินภายในเวลาที่กำหนด</p>
                <button class="btn btn-success w-100 mb-2" onclick="confirmQRPayment()">แจ้งโอนเงินเรียบร้อย</button>
                <button class="btn btn-outline-danger w-100" onclick="cancelQRPayment()">ยกเลิก</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="orderHistoryModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold"><i class="fa fa-box-open me-2"></i>ประวัติการสั่งซื้อ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="order-history-list">
                <div class="border rounded p-3 mb-3">
                    <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                        <span class="fw-bold text-muted">Order #THJ2026</span>
                        <span class="badge bg-warning text-dark"><i class="fa fa-clock"></i> รอยืนยันจากแอดมิน (COD)</span>
                    </div>
                    <div class="d-flex">
                        <img src="4.jpg" width="50" height="50" class="rounded me-3" style="object-fit:cover" onerror="this.src='https://placehold.co/50'">
                        <div>
                            <p class="mb-0 fw-bold">Buriram United Home (M)</p>
                            <small class="text-muted">จำนวน: 1 | ยอดรวม: ฿740</small>
                        </div>
                    </div>
                </div>
                
                <div class="border rounded p-3 mb-3 bg-light">
                    <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                        <span class="fw-bold text-muted">Order #THJ1999</span>
                        <span class="badge bg-success"><i class="fa fa-check-circle"></i> จัดส่งสำเร็จ</span>
                    </div>
                    <div class="d-flex">
                        <img src="5.jpg" width="50" height="50" class="rounded me-3" style="object-fit:cover" onerror="this.src='https://placehold.co/50'">
                        <div class="flex-grow-1">
                            <p class="mb-0 fw-bold">Thailand Edition (L)</p>
                            <small class="text-muted">จำนวน: 1 | ได้รับเมื่อ: 5 มี.ค. 2026</small>
                            <div class="mt-2">
                                <textarea class="form-control form-control-sm mb-1" placeholder="เขียนรีวิวสินค้า..."></textarea>
                                <button class="btn btn-outline-warning btn-sm" onclick="alert('ขอบคุณสำหรับรีวิวครับ!')">ส่งรีวิว</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="contactModal" tabindex="-1">...</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // เพิ่มข้อมูลลดราคา
    const originalProducts = [
        { id: 2, name: "Uthai Thani Home 2024", price: 790, type: "เหย้า", img: "2.jpg", desc: "เสื้อแข่งทีมอุทัยธานี เอฟซี ฤดูกาลล่าสุด" },
        { id: 4, name: "Buriram United Home", price: 690, oldPrice: 890, isSale: true, type: "เหย้า", img: "4.jpg", desc: "เสื้อสายฟ้า ปราสาทสายฟ้า" },
        { id: 5, name: "Thailand Edition", price: 590, type: "ซ้อม", img: "5.jpg", desc: "เสื้อเชียร์ทีมชาติไทย" },
        { id: 6, name: "Port FC Away Kit", price: 550, oldPrice: 750, isSale: true, type: "เยือน", img: "6.jpg", desc: "สิงห์เจ้าท่า สีเยือนเรียบหรู" },
        // ... (ใส่ตัวอื่นๆ ตามเดิม)
    ];

    let cart = [];
    let currentUser = null; 
    let currentAction = 'cart';
    let selectedPayment = 'COD';
    let discountAmount = 0;
    let qrTimerInterval;

    // --- 1. Initialization & Auth ---
    function initStore() {
        renderProducts(originalProducts);
        // ตรวจสอบ LocalStorage ว่ามีคนล็อกอินค้างไว้ไหม
        const savedUser = localStorage.getItem('thanjai_user');
        if (savedUser) {
            currentUser = JSON.parse(savedUser);
            updateNavAuthUI();
        }
    }

    function toggleAuthForm(type) {
        document.getElementById('login-form').classList.toggle('d-none', type !== 'login');
        document.getElementById('register-form').classList.toggle('d-none', type !== 'register');
        document.getElementById('authModalTitle').innerText = type === 'login' ? 'เข้าสู่ระบบ' : 'สมัครสมาชิก';
    }

    function register() {
        const user = document.getElementById('reg-user').value;
        const email = document.getElementById('reg-email').value;
        if(!user || !email) return alert('กรุณากรอกข้อมูลให้ครบ');
        
        currentUser = { username: user, email: email, profilePic: 'https://api.dicebear.com/7.x/avataaars/svg?seed='+user };
        localStorage.setItem('thanjai_user', JSON.stringify(currentUser));
        bootstrap.Modal.getInstance(document.getElementById('loginModal')).hide();
        updateNavAuthUI();
        alert('สมัครสมาชิกและเข้าสู่ระบบสำเร็จ!');
    }

    function login() {
        const user = document.getElementById('login-user').value;
        if(!user) return alert('กรุณากรอกชื่อผู้ใช้');
        
        // จำลองการดึงข้อมูลจาก DB
        currentUser = { username: user, profilePic: localStorage.getItem('thanjai_profilePic') || 'https://api.dicebear.com/7.x/avataaars/svg?seed='+user };
        localStorage.setItem('thanjai_user', JSON.stringify(currentUser));
        bootstrap.Modal.getInstance(document.getElementById('loginModal')).hide();
        updateNavAuthUI();
    }

    function logoutUser() {
        if(confirm("ออกจากระบบ?")) {
            localStorage.removeItem('thanjai_user');
            currentUser = null;
            cart = [];
            updateCartUI();
            updateNavAuthUI();
        }
    }

    function updateNavAuthUI() {
        if (currentUser) {
            document.getElementById('guest-zone').classList.add('d-none');
            document.getElementById('user-zone').classList.remove('d-none');
            document.getElementById('display-username').innerText = currentUser.username;
            document.getElementById('set-displayname').value = currentUser.username;
            
            const pic = localStorage.getItem('thanjai_profilePic') || currentUser.profilePic;
            document.getElementById('nav-profile-pic').src = pic;
            document.getElementById('setting-profile-img').src = pic;
        } else {
            document.getElementById('guest-zone').classList.remove('d-none');
            document.getElementById('user-zone').classList.add('d-none');
        }
    }

    function checkAuthBeforeAction(callback) {
        if (currentUser) { callback(); } 
        else {
            alert("กรุณาเข้าสู่ระบบ หรือ สมัครสมาชิกก่อนทำการสั่งซื้อครับ");
            new bootstrap.Modal(document.getElementById('loginModal')).show();
        }
    }

    // --- 2. Profile Settings ---
    function previewProfilePic(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const dataURL = reader.result;
            document.getElementById('setting-profile-img').src = dataURL;
            localStorage.setItem('thanjai_profilePic', dataURL); // เซฟลง LocalStorage
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    function saveProfile() {
        currentUser.username = document.getElementById('set-displayname').value;
        localStorage.setItem('thanjai_user', JSON.stringify(currentUser));
        updateNavAuthUI();
        alert('บันทึกข้อมูลสำเร็จ!');
        bootstrap.Modal.getInstance(document.getElementById('profileModal')).hide();
    }

    // --- 3. Products & Display ---
    function showPromotions() {
        document.getElementById('home-banner').classList.add('d-none');
        document.getElementById('promo-banner').classList.remove('d-none');
        filterProducts('ทั้งหมด', true);
    }

    function filterProducts(type, onlySale = false) {
        if(type === 'ทั้งหมด') {
            document.getElementById('home-banner').classList.remove('d-none');
            document.getElementById('promo-banner').classList.add('d-none');
        }
        let filtered = originalProducts;
        if(type !== 'ทั้งหมด') filtered = filtered.filter(p => p.type === type);
        if(onlySale) filtered = filtered.filter(p => p.isSale);
        renderProducts(filtered);
    }

    function renderProducts(items) {
        const container = document.getElementById('store-display');
        container.innerHTML = items.map(p => `
            <div class="col-6 col-md-3 mb-4">
                <div class="card product-card shadow-sm border-0">
                    ${p.isSale ? `<span class="sale-badge">SALE!</span>` : ''}
                    <img src="${p.img}" class="card-img-top" alt="${p.name}" onerror="this.src='https://placehold.co/400x400?text=No+Image'">
                    <div class="card-body">
                        <span class="badge bg-secondary mb-2" style="font-size: 10px;">${p.type}</span>
                        <h6 class="card-title fw-bold text-dark mb-1" style="font-size: 14px;">${p.name}</h6>
                        <div class="mt-2">
                            <span class="text-danger fw-bold fs-5 mb-2">฿${p.price.toLocaleString()}</span>
                            ${p.oldPrice ? `<span class="text-muted text-decoration-line-through small ms-1">฿${p.oldPrice}</span>` : ''}
                            <div class="d-flex gap-1 mt-2">
                                <button class="btn btn-buy btn-sm flex-fill fw-bold w-100" onclick="openProductDetail(${p.id})">
                                    <i class="fa fa-shopping-bag text-warning"></i> เลือกดูสินค้า
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    function openProductDetail(id) {
        const p = originalProducts.find(x => x.id === id);
        document.getElementById('detail-id').value = p.id;
        document.getElementById('detail-name').innerText = p.name;
        document.getElementById('detail-price').innerText = '฿' + p.price.toLocaleString();
        document.getElementById('detail-desc').innerText = p.desc;
        document.getElementById('detail-type').innerText = p.type;
        document.getElementById('detail-img').src = p.img; 
        
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

    // --- 4. Cart & Checkout ---
    function confirmProductSelection() {
        const id = parseInt(document.getElementById('detail-id').value);
        const size = document.getElementById('detail-size').value;
        const qty = parseInt(document.getElementById('detail-qty').value);
        const product = originalProducts.find(p => p.id === id);

        const existIndex = cart.findIndex(item => item.id === id && item.size === size);
        if (existIndex > -1) cart[existIndex].qty += qty; 
        else cart.push({ ...product, size: size, qty: qty }); 

        updateCartUI();
        bootstrap.Modal.getInstance(document.getElementById('productDetailModal')).hide();
        setTimeout(() => new bootstrap.Modal(document.getElementById('cartModal')).show(), 400); 
    }

    function updateCartUI() {
        document.getElementById('cart-count').innerText = cart.reduce((s, i) => s + i.qty, 0);
        const list = document.getElementById('cart-items-list');
        if (cart.length === 0) { list.innerHTML = '<p class="text-center">ตะกร้าว่างเปล่า</p>'; document.getElementById('cart-total').innerText = '฿0'; return; }

        let sum = 0;
        list.innerHTML = cart.map((item, index) => {
            sum += item.price * item.qty;
            return `
                <div class="d-flex align-items-center mb-2 pb-2 border-bottom">
                    <img src="${item.img}" width="50" class="rounded me-2" onerror="this.src='https://placehold.co/50'">
                    <div class="flex-grow-1">
                        <h6 class="mb-0 small fw-bold">${item.name}</h6>
                        <small class="text-muted">Size: ${item.size} | Qty: ${item.qty}</small>
                    </div>
                    <div class="fw-bold me-2">฿${(item.price * item.qty).toLocaleString()}</div>
                    <button class="btn btn-sm text-danger" onclick="cart.splice(${index},1); updateCartUI();"><i class="fa fa-trash"></i></button>
                </div>`;
        }).join('');
        document.getElementById('cart-total').innerText = `฿${sum.toLocaleString()}`;
    }

    function openCheckoutModal() {
        if (cart.length === 0) return alert("ตะกร้าว่างเปล่า");
        bootstrap.Modal.getInstance(document.getElementById('cartModal')).hide();
        
        let subtotal = 0;
        document.getElementById('checkout-summary-list').innerHTML = cart.map(item => {
            subtotal += item.price * item.qty;
            return `<div class="d-flex justify-content-between"><span>${item.name} (${item.size}) x${item.qty}</span> <span>฿${item.price * item.qty}</span></div>`;
        }).join('');
        
        document.getElementById('chk-subtotal').innerText = `฿${subtotal}`;
        calculateTotal(subtotal);
        setTimeout(() => new bootstrap.Modal(document.getElementById('checkoutModal')).show(), 400);
    }

    function applyCoupon() {
        const code = document.getElementById('coupon-code').value.toUpperCase();
        if(code === 'NEWBIE50') { discountAmount = 50; alert("ใช้โค้ดส่วนลด 50 บาทสำเร็จ!"); }
        else { discountAmount = 0; alert("โค้ดไม่ถูกต้อง"); }
        calculateTotal(cart.reduce((s, i) => s + i.price * i.qty, 0));
    }

    function calculateTotal(subtotal) {
        document.getElementById('chk-discount').innerText = `-฿${discountAmount}`;
        let total = subtotal + 50 - discountAmount; // ค่าส่ง 50 บาท
        document.getElementById('chk-total').innerText = `฿${total.toLocaleString()}`;
    }

    function selectPaymentMethod(method) {
        selectedPayment = method;
        document.getElementById('pay-cod').classList.toggle('active', method === 'COD');
        document.getElementById('pay-qr').classList.toggle('active', method === 'QR');
    }

    // --- 5. Order Processing & QR Timer ---
    function processOrder() {
        if(!document.getElementById('ship-name').value || !document.getElementById('ship-address').value) return alert("กรุณากรอกข้อมูลจัดส่งให้ครบถ้วน");
        
        bootstrap.Modal.getInstance(document.getElementById('checkoutModal')).hide();
        
        if(selectedPayment === 'QR') {
            setTimeout(() => { new bootstrap.Modal(document.getElementById('qrModal')).show(); startQrTimer(); }, 400);
        } else {
            alert("สั่งซื้อสำเร็จ (เก็บเงินปลายทาง)\nสถานะ: รอแอดมินยืนยัน\nใช้เวลาจัดส่ง 2-3 วันทำการ");
            completeOrderFlow();
        }
    }

    function startQrTimer() {
        let timeLeft = 300; // 5 นาที
        document.getElementById('qr-timer').innerText = "05:00";
        qrTimerInterval = setInterval(() => {
            timeLeft--;
            let m = Math.floor(timeLeft / 60).toString().padStart(2, '0');
            let s = (timeLeft % 60).toString().padStart(2, '0');
            document.getElementById('qr-timer').innerText = `${m}:${s}`;
            
            if(timeLeft <= 0) {
                clearInterval(qrTimerInterval);
                if(confirm("หมดเวลาชำระเงิน คุณต้องการทำรายการต่อหรือไม่?")) { startQrTimer(); } 
                else { cancelQRPayment(); }
            }
        }, 1000);
    }

    function confirmQRPayment() {
        clearInterval(qrTimerInterval);
        bootstrap.Modal.getInstance(document.getElementById('qrModal')).hide();
        alert("ได้รับยอดเงินเรียบร้อยแล้ว!\nสถานะ: รอการจัดส่ง (2-3 วันทำการ)");
        completeOrderFlow();
    }

    function cancelQRPayment() {
        clearInterval(qrTimerInterval);
        bootstrap.Modal.getInstance(document.getElementById('qrModal')).hide();
        alert("ยกเลิกการสั่งซื้อแล้ว");
    }

    function completeOrderFlow() {
        cart = []; updateCartUI(); discountAmount = 0;
        document.getElementById('ship-name').value = ''; document.getElementById('ship-address').value = '';
    }

    function openOrderHistory() {
        new bootstrap.Modal(document.getElementById('orderHistoryModal')).show();
    }
</script>
</body>
</html>