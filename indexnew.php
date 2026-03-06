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
        .product-card { border: none; border-radius: 15px; transition: 0.3s; overflow: hidden; height: 100%; }
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
        
        /* สไตล์ใหม่ที่เพิ่มขึ้น */
        .promo-banner { background: #dc3545; color: white; padding: 20px; border-radius: 10px; margin-bottom: 20px; text-align: center;}
        .coupon-card { border: 2px dashed #ffae00; background: #fff; padding: 15px; border-radius: 10px; text-align: center; cursor: pointer;}
        #qr-timer { font-size: 2rem; font-weight: bold; color: #dc3545; }
        .profile-img-edit { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid var(--accent); cursor: pointer; }
        .hidden-file-input { display: none; }
    </style>
</head>
<body onload="initStore()">

<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#" onclick="switchPage('home')">ThanJai <span class="text-warning">Shop</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="#" onclick="switchPage('home')">หน้าหลัก</a></li>
                <li class="nav-item"><a class="nav-link" href="#" onclick="switchPage('promo')">โปรโมชั่น <span class="badge bg-danger">ลดราคา!</span></a></li>
                <li class="nav-item"><a class="nav-link text-warning fw-bold" href="#" data-bs-toggle="modal" data-bs-target="#contactModal"><i class="fa fa-phone-alt me-1"></i>ติดต่อเรา</a></li>
            </ul>
            
            <div class="d-flex align-items-center gap-3">
                <div class="input-group input-group-sm d-none d-md-flex" id="search-bar-nav">
                    <input type="text" id="search-input" class="form-control" placeholder="ค้นหาชื่อทีม..." onkeyup="searchProducts()">
                </div>

                <div id="guest-zone">
                    <button class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#authModal">เข้าสู่ระบบ / สมัครสมาชิก</button>
                </div>

                <div id="user-zone" class="d-none">
                    <div class="dropdown text-white">
                        <span class="me-2 d-none d-md-inline">ยินดีต้อนรับ, <strong id="nav-username">ผู้ใช้</strong></span>
                        <a href="#" class="link-light dropdown-toggle" data-bs-toggle="dropdown">
                            <img id="nav-avatar" src="https://api.dicebear.com/7.x/avataaars/svg?seed=Guest" width="35" height="35" class="rounded-circle border" style="object-fit:cover;">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profileModal"><i class="fa fa-user me-2"></i>จัดการโปรไฟล์</a></li>
                            <li><a class="dropdown-item" href="#" onclick="alert('แสดงสถานะ: รอการจัดส่ง')"><i class="fa fa-shopping-bag me-2"></i>ออเดอร์ของฉัน</a></li>
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

<div id="home-page">
    <header class="hero-banner text-center">
        <div class="container">
            <h1 class="display-4 fw-bold">NEW SEASON 2026</h1>
            <p class="lead">คอลเลคชั่นใหม่ล่าสุดจากสโมสรดังทั่วโลก พร้อมส่งแล้ววันนี้!</p>
            <a href="#product-list" class="btn btn-warning btn-lg px-5 fw-bold">เลือกช้อปเลย</a>
        </div>
    </header>

    <main class="container my-5" id="product-list">
        <div class="d-flex justify-content-center gap-2 mb-5 overflow-auto pb-2">
            <button class="btn btn-dark px-4 rounded-pill filter-btn" onclick="filterProducts('ทั้งหมด')">ทั้งหมด</button>
            <button class="btn btn-outline-dark px-4 rounded-pill filter-btn" onclick="filterProducts('เหย้า')">เหย้า</button>
            <button class="btn btn-outline-dark px-4 rounded-pill filter-btn" onclick="filterProducts('เยือน')">เยือน</button>
            <button class="btn btn-outline-dark px-4 rounded-pill filter-btn" onclick="filterProducts('ซ้อม')">เสื้อซ้อม</button>
        </div>
        <div class="row g-4" id="store-display"></div>
    </main>
</div>

<div id="promo-page" class="container my-5 d-none">
    <div class="promo-banner shadow">
        <h2>🔥 โค้ดส่วนลดลูกค้าใหม่ 🔥</h2>
        <p>เก็บโค้ดคูปองด้านล่างเพื่อใช้เป็นส่วนลดในหน้าชำระเงิน</p>
        <div class="coupon-card d-inline-block mt-2" onclick="collectCoupon('NEWUSER50')">
            <h3 class="text-warning mb-0 fw-bold">NEWUSER50</h3>
            <small class="text-muted">ลดทันที 50 บาท! (คลิกเพื่อเก็บ)</small>
        </div>
    </div>
    <h3 class="fw-bold mb-4">สินค้าจัดโปรโมชั่น</h3>
    <div class="row g-4" id="promo-display"></div>
</div>

<div class="modal fade" id="authModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                    <li class="nav-item"><a class="nav-link active text-warning" id="login-tab" data-bs-toggle="tab" href="#login-pane">เข้าสู่ระบบ</a></li>
                    <li class="nav-item"><a class="nav-link text-white" id="reg-tab" data-bs-toggle="tab" href="#reg-pane">สมัครสมาชิก</a></li>
                </ul>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="login-pane">
                        <div class="mb-3">
                            <label class="form-label">ชื่อผู้ใช้ (Username)</label>
                            <input type="text" id="login-user" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">รหัสผ่าน</label>
                            <input type="password" id="login-pass" class="form-control">
                        </div>
                        <button class="btn btn-warning w-100 fw-bold" onclick="login()">เข้าสู่ระบบ</button>
                    </div>
                    <div class="tab-pane fade" id="reg-pane">
                        <div class="mb-3">
                            <label class="form-label">ชื่อ-นามสกุล</label>
                            <input type="text" id="reg-name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">อีเมล</label>
                            <input type="email" id="reg-email" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ชื่อผู้ใช้ (Username)</label>
                            <input type="text" id="reg-user" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">รหัสผ่าน</label>
                            <input type="password" id="reg-pass" class="form-control">
                        </div>
                        <button class="btn btn-success w-100 fw-bold" onclick="register()">สมัครสมาชิก</button>
                    </div>
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
            <div class="modal-body">
                <div class="text-center mb-4">
                    <img id="profile-edit-img" src="" class="profile-img-edit shadow-sm" onclick="document.getElementById('avatar-upload').click()" title="คลิกเพื่อเปลี่ยนรูป">
                    <input type="file" id="avatar-upload" class="hidden-file-input" accept="image/*" onchange="previewAvatar(event)">
                    <p class="small text-muted mt-2">คลิกที่รูปเพื่อเปลี่ยนรูปโปรไฟล์</p>
                </div>
                <div class="mb-2">
                    <label class="form-label small fw-bold">ชื่อ-นามสกุล</label>
                    <input type="text" id="edit-name" class="form-control">
                </div>
                <div class="mb-2">
                    <label class="form-label small fw-bold">อีเมล</label>
                    <input type="email" id="edit-email" class="form-control">
                </div>
                <div class="mb-2">
                    <label class="form-label small fw-bold">เบอร์โทรศัพท์</label>
                    <input type="text" id="edit-phone" class="form-control">
                </div>
                <div class="mb-2">
                    <label class="form-label small fw-bold">เปลี่ยนรหัสผ่านใหม่ (ปล่อยว่างถ้าไม่เปลี่ยน)</label>
                    <input type="password" id="edit-pass" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning w-100 fw-bold" onclick="saveProfile()">บันทึกข้อมูล</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="contactModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold"><i class="fa fa-headset me-2"></i>ช่องทางการติดต่อแอดมิน</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <p class="mb-2"><i class="fa fa-phone-alt text-success fs-5 me-2"></i> <strong>โทร:</strong> 081-XXX-XXXX</p>
                <p class="mb-2"><i class="fab fa-line text-success fs-5 me-2"></i> <strong>Line ID:</strong> @thanjai_shop</p>
                <p class="mb-0"><i class="fab fa-facebook text-primary fs-5 me-2"></i> <strong>Facebook:</strong> ThanJai Shop Official</p>
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
                    <div class="col-md-5 text-center mb-3 mb-md-0">
                        <img id="detail-img" src="" class="img-fluid rounded shadow-sm" style="max-height: 350px;">
                    </div>
                    <div class="col-md-7">
                        <span id="detail-type" class="badge bg-secondary mb-2"></span>
                        <h4 class="fw-bold" id="detail-name">ชื่อสินค้า</h4>
                        <h3 class="text-danger fw-bold mb-3" id="detail-price">฿0</h3>
                        <p class="text-muted" id="detail-desc">รายละเอียดสินค้า...</p>
                        <hr>
                        <div class="row g-3 align-items-end">
                            <div class="col-6">
                                <label class="form-label fw-bold">เลือกไซส์ (Size):</label>
                                <select id="detail-size" class="form-select border-dark">
                                    <option value="S">S (อก 36")</option><option value="M" selected>M (อก 38")</option>
                                    <option value="L">L (อก 40")</option><option value="XL">XL (อก 42")</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold">จำนวน (Qty):</label>
                                <input type="number" id="detail-qty" class="form-control border-dark" value="1" min="1">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="detail-id">
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" id="btn-confirm-action" class="btn btn-warning fw-bold px-4" onclick="confirmProductSelection()">ยืนยัน</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="cartModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold">ตะกร้าสินค้าของคุณ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="cart-items-list"></div>
                <hr>
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold">ราคารวม:</h5>
                    <h4 class="text-danger fw-bold" id="cart-total">฿0</h4>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ช้อปต่อ</button>
                <button type="button" class="btn btn-warning fw-bold" onclick="openCheckoutModal()">ดำเนินการชำระเงิน</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="checkoutModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold">ยืนยันคำสั่งซื้อและชำระเงิน</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6 class="fw-bold border-bottom pb-2 d-flex justify-content-between">
                            ข้อมูลการจัดส่ง 
                            <button class="btn btn-sm btn-outline-primary" onclick="saveAddressToProfile()">บันทึกที่อยู่</button>
                        </h6>
                        <div class="mb-2"><input type="text" id="ship-name" class="form-control" placeholder="ชื่อ-นามสกุลผู้รับ"></div>
                        <div class="mb-2"><input type="text" id="ship-phone" class="form-control" placeholder="เบอร์โทรศัพท์"></div>
                        <div class="mb-2"><textarea id="ship-address" class="form-control" rows="3" placeholder="ที่อยู่จัดส่งแบบละเอียด"></textarea></div>
                        
                        <h6 class="fw-bold border-bottom pb-2 mt-4">รหัสส่วนลด (ถ้ามี)</h6>
                        <div class="input-group">
                            <input type="text" id="coupon-code" class="form-control" placeholder="กรอกรหัสคูปอง">
                            <button class="btn btn-dark" onclick="applyCoupon()">ใช้คูปอง</button>
                        </div>
                        <small id="coupon-msg" class="text-success d-none mt-1"></small>
                    </div>

                    <div class="col-md-6 bg-light p-3 rounded">
                        <h6 class="fw-bold border-bottom pb-2">สรุปรายการสินค้า</h6>
                        <div id="checkout-summary-list" style="max-height: 120px; overflow-y: auto; font-size: 0.9rem;" class="mb-2"></div>
                        
                        <div class="d-flex justify-content-between small"><span>รวมค่าสินค้า:</span><span id="chk-subtotal">฿0</span></div>
                        <div class="d-flex justify-content-between small"><span>ค่าจัดส่ง (คงที่):</span><span>฿50</span></div>
                        <div class="d-flex justify-content-between small text-success"><span>ส่วนลดคูปอง:</span><span id="chk-discount">-฿0</span></div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold mb-3"><span>ยอดสุทธิที่ต้องชำระ:</span><span class="text-danger fs-5" id="checkout-total-price">฿0</span></div>

                        <h6 class="fw-bold border-bottom pb-2">เลือกวิธีชำระเงิน</h6>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="payment-box active" id="pay-cod" onclick="selectPaymentMethod('COD')">
                                    <i class="fa fa-truck fa-2x mb-2 text-dark"></i><div class="small">เก็บเงินปลายทาง</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="payment-box" id="pay-qr" onclick="selectPaymentMethod('QR')">
                                    <i class="fa fa-qrcode fa-2x mb-2 text-primary"></i><div class="small">QR PromptPay</div>
                                </div>
                            </div>
                        </div>

                        <div id="qr-code-section" class="text-center mt-3 d-none bg-white p-3 border rounded">
                            <p class="fw-bold text-danger mb-1">กรุณาชำระเงินภายใน <span id="qr-timer">05:00</span></p>
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=081xxxxxxx" alt="QR Code">
                            <p class="small text-muted mt-2">สแกนเพื่อโอนเงิน ระบบจะยืนยันอัตโนมัติ(จำลอง)</p>
                            <button id="btn-mock-pay" class="btn btn-sm btn-outline-success mt-2" onclick="mockQRSuccess()">จำลองว่าโอนสำเร็จแล้ว</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-target="#cartModal" data-bs-toggle="modal">กลับไปแก้ไขตะกร้า</button>
                <button type="button" id="btn-confirm-order" class="btn btn-success fw-bold px-4" onclick="processOrder()">
                    <i class="fa fa-check"></i> ยืนยันคำสั่งซื้อ
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // --- ฐานข้อมูลจำลอง ---
    const originalProducts = [
        { id: 2, name: "Uthai Thani Home 2024", price: 790, type: "เหย้า", img: "2.jpg", desc: "เสื้อแข่งทีมอุทัยธานี เอฟซี" },
        { id: 4, name: "Buriram United Home", price: 690, type: "เหย้า", img: "4.jpg", desc: "เสื้อสายฟ้า ปราสาทสายฟ้า" },
        { id: 5, name: "Thailand Edition", price: 590, type: "ซ้อม", img: "5.jpg", desc: "เสื้อเชียร์ทีมชาติไทย" },
        { id: 6, name: "Port FC Away Kit", price: 750, type: "เยือน", img: "6.jpg", desc: "สิงห์เจ้าท่า สีเยือนเรียบหรู" }
    ];
    
    // สินค้าโปรโมชั่น
    const promoProducts = [
        { id: 101, name: "Muangthong Utd (Clearance)", price: 390, type: "เหย้า", img: "8.jpg", desc: "ลดล้างสต๊อก!" },
        { id: 102, name: "Chonburi FC (Sale)", price: 450, type: "เยือน", img: "9.jpg", desc: "โปรโมชั่นพิเศษ" }
    ];

    let cart = [];
    let currentUser = null; // เก็บข้อมูลผู้ใช้ที่ล็อกอิน
    let currentAction = 'cart';
    let selectedPayment = 'COD';
    let discountAmt = 0;
    let qrInterval;

    // --- ระบบ Initialize (โหลดข้อมูลจาก localStorage) ---
    function initStore() {
        // ตรวจสอบ LocalStorage ว่ามี User ล็อกอินค้างไว้ไหม
        const savedUser = localStorage.getItem('thanjai_session');
        if (savedUser) {
            currentUser = JSON.parse(savedUser);
            updateNavForUser();
        }

        renderProducts(originalProducts, 'store-display');
        renderProducts(promoProducts, 'promo-display');
        updateCartUI();
    }

    // --- ระบบสมาชิก (Auth) ---
    function register() {
        const name = document.getElementById('reg-name').value;
        const email = document.getElementById('reg-email').value;
        const user = document.getElementById('reg-user').value;
        const pass = document.getElementById('reg-pass').value;

        if(!name || !email || !user || !pass) return alert('กรุณากรอกข้อมูลให้ครบถ้วน');

        // โหลดข้อมูลเก่าจากจำลอง DB
        let usersDB = JSON.parse(localStorage.getItem('thanjai_users')) || [];
        if(usersDB.find(u => u.username === user)) return alert('ชื่อผู้ใช้นี้มีในระบบแล้ว!');

        const newUser = { 
            username: user, password: pass, name: name, email: email, phone: '',
            avatar: 'https://api.dicebear.com/7.x/avataaars/svg?seed='+user,
            address: ''
        };
        usersDB.push(newUser);
        localStorage.setItem('thanjai_users', JSON.stringify(usersDB));
        
        alert('สมัครสมาชิกสำเร็จ! กรุณาเข้าสู่ระบบ');
        bootstrap.Tab.getInstance(document.getElementById('login-tab')).show();
    }

    function login() {
        const user = document.getElementById('login-user').value;
        const pass = document.getElementById('login-pass').value;
        
        let usersDB = JSON.parse(localStorage.getItem('thanjai_users')) || [];
        const foundUser = usersDB.find(u => u.username === user && u.password === pass);

        if (foundUser) {
            currentUser = foundUser;
            localStorage.setItem('thanjai_session', JSON.stringify(currentUser));
            updateNavForUser();
            bootstrap.Modal.getInstance(document.getElementById('authModal')).hide();
        } else {
            alert('ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง หรือคุณยังไม่สมัครสมาชิก!');
            bootstrap.Tab.getInstance(document.getElementById('reg-tab')).show();
        }
    }

    function logoutUser() {
        if(confirm("ออกจากระบบใช่หรือไม่?")) {
            currentUser = null;
            localStorage.removeItem('thanjai_session');
            document.getElementById('user-zone').classList.add('d-none');
            document.getElementById('guest-zone').classList.remove('d-none');
        }
    }

    function updateNavForUser() {
        document.getElementById('guest-zone').classList.add('d-none');
        document.getElementById('user-zone').classList.remove('d-none');
        document.getElementById('nav-username').innerText = currentUser.name;
        document.getElementById('nav-avatar').src = currentUser.avatar;
    }

    function requireLogin() {
        if (!currentUser) {
            alert("กรุณาเข้าสู่ระบบหรือสมัครสมาชิกก่อนทำรายการครับ");
            new bootstrap.Modal(document.getElementById('authModal')).show();
            return false;
        }
        return true;
    }

    // --- ระบบโปรไฟล์ (แก้ไขข้อมูล & รูปภาพ) ---
    // เมื่อเปิด Modal โปรไฟล์ ให้ดึงข้อมูลมาแสดง
    document.getElementById('profileModal').addEventListener('show.bs.modal', function () {
        if(currentUser) {
            document.getElementById('profile-edit-img').src = currentUser.avatar;
            document.getElementById('edit-name').value = currentUser.name;
            document.getElementById('edit-email').value = currentUser.email;
            document.getElementById('edit-phone').value = currentUser.phone || '';
        }
    });

    function previewAvatar(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profile-edit-img').src = e.target.result;
            }
            reader.readAsDataURL(file); // แปลงเป็น Base64 เพื่อเซฟลง LocalStorage ได้
        }
    }

    function saveProfile() {
        currentUser.name = document.getElementById('edit-name').value;
        currentUser.email = document.getElementById('edit-email').value;
        currentUser.phone = document.getElementById('edit-phone').value;
        currentUser.avatar = document.getElementById('profile-edit-img').src;
        
        const newPass = document.getElementById('edit-pass').value;
        if(newPass) currentUser.password = newPass;

        // บันทึกลง DB (localStorage)
        let usersDB = JSON.parse(localStorage.getItem('thanjai_users')) || [];
        const index = usersDB.findIndex(u => u.username === currentUser.username);
        if(index !== -1) usersDB[index] = currentUser;
        localStorage.setItem('thanjai_users', JSON.stringify(usersDB));
        localStorage.setItem('thanjai_session', JSON.stringify(currentUser));

        updateNavForUser();
        alert('อัปเดตข้อมูลเรียบร้อย!');
        bootstrap.Modal.getInstance(document.getElementById('profileModal')).hide();
    }

    // --- สลับหน้าหลัก / โปรโมชั่น ---
    function switchPage(page) {
        if(page === 'home') {
            document.getElementById('home-page').classList.remove('d-none');
            document.getElementById('promo-page').classList.add('d-none');
            document.getElementById('search-bar-nav').classList.remove('d-none');
        } else {
            document.getElementById('home-page').classList.add('d-none');
            document.getElementById('promo-page').classList.remove('d-none');
            document.getElementById('search-bar-nav').classList.add('d-none');
        }
    }

    function collectCoupon(code) {
        if(!requireLogin()) return;
        localStorage.setItem('saved_coupon', code);
        alert('เก็บคูปอง ' + code + ' เรียบร้อยแล้ว! นำไปใช้ในหน้าชำระเงินได้เลย');
    }

    // --- ระบบสินค้า & ตะกร้า ---
    function renderProducts(items, targetId) {
        const container = document.getElementById(targetId);
        container.innerHTML = items.map(p => `
            <div class="col-6 col-md-3 mb-4">
                <div class="card product-card shadow-sm border-0">
                    <img src="${p.img}" class="card-img-top" alt="${p.name}" onerror="this.src='https://via.placeholder.com/250x250?text=No+Image'">
                    <div class="card-body">
                        <span class="badge bg-secondary mb-2" style="font-size: 10px;">${p.type}</span>
                        <h6 class="card-title fw-bold text-dark mb-1" style="font-size: 14px;">${p.name}</h6>
                        <div class="mt-3">
                            <span class="text-danger fw-bold fs-5 d-block mb-2">฿${p.price.toLocaleString()}</span>
                            <div class="d-flex gap-1">
                                <button class="btn btn-outline-dark btn-sm flex-fill" onclick="openProductDetail(${p.id}, 'cart')" title="เพิ่มลงตะกร้า"><i class="fa fa-cart-plus"></i></button>
                                <button class="btn btn-buy btn-sm flex-fill fw-bold" onclick="openProductDetail(${p.id}, 'buy')">ซื้อเลย</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    function filterProducts(type) {
        const filtered = type === 'ทั้งหมด' ? originalProducts : originalProducts.filter(p => p.type === type);
        renderProducts(filtered, 'store-display');
    }

    function openProductDetail(id, action) {
        if(!requireLogin()) return; // เช็คก่อนกดสั่งซื้อ
        
        currentAction = action;
        let p = originalProducts.find(x => x.id === id) || promoProducts.find(x => x.id === id);
        
        document.getElementById('detail-id').value = p.id;
        document.getElementById('detail-name').innerText = p.name;
        document.getElementById('detail-price').innerText = '฿' + p.price.toLocaleString();
        document.getElementById('detail-desc').innerText = p.desc;
        document.getElementById('detail-type').innerText = p.type;
        document.getElementById('detail-img').src = p.img; 

        new bootstrap.Modal(document.getElementById('productDetailModal')).show();
    }

    function confirmProductSelection() {
        const id = parseInt(document.getElementById('detail-id').value);
        const size = document.getElementById('detail-size').value;
        const qty = parseInt(document.getElementById('detail-qty').value);
        let product = originalProducts.find(p => p.id === id) || promoProducts.find(p => p.id === id);

        const existIdx = cart.findIndex(item => item.id === id && item.size === size);
        if (existIdx > -1) cart[existIdx].qty += qty; 
        else cart.push({ ...product, size: size, qty: qty }); 

        updateCartUI();
        bootstrap.Modal.getInstance(document.getElementById('productDetailModal')).hide();

        if(currentAction === 'buy') {
            setTimeout(() => openCheckoutModal(), 400); 
        }
    }

    function openCart() {
        if(!requireLogin()) return;
        new bootstrap.Modal(document.getElementById('cartModal')).show();
    }

    function updateCartUI() {
        document.getElementById('cart-count').innerText = cart.reduce((sum, item) => sum + item.qty, 0);
        const list = document.getElementById('cart-items-list');
        
        if (cart.length === 0) {
            list.innerHTML = '<p class="text-center text-muted my-4">ไม่มีสินค้าในตะกร้า</p>';
            document.getElementById('cart-total').innerText = '฿0';
            return;
        }

        let totalAmount = 0;
        list.innerHTML = cart.map((item, index) => {
            const itemTotal = item.price * item.qty;
            totalAmount += itemTotal;
            return `<div class="d-flex align-items-center mb-3 p-2 border-bottom">
                <div class="flex-grow-1">
                    <h6 class="mb-0 fw-bold">${item.name}</h6>
                    <small>ไซส์: ${item.size} | จำนวน: ${item.qty}</small>
                </div>
                <div class="fw-bold me-3">฿${itemTotal.toLocaleString()}</div>
                <button class="btn btn-sm text-danger" onclick="removeFromCart(${index})"><i class="fa fa-trash"></i></button>
            </div>`;
        }).join('');
        
        document.getElementById('cart-total').innerText = `฿${totalAmount.toLocaleString()}`;
    }

    function removeFromCart(index) { cart.splice(index, 1); updateCartUI(); }

    // --- ระบบชำระเงิน (Checkout & Payment Flow) ---
    function openCheckoutModal() {
        if (cart.length === 0) return alert("กรุณาเลือกสินค้าลงตะกร้าก่อนครับ");
        
        // ดึงที่อยู่เก่ามาใส่
        document.getElementById('ship-name').value = currentUser.name;
        document.getElementById('ship-phone').value = currentUser.phone || '';
        document.getElementById('ship-address').value = currentUser.address || '';

        // ถ้ามีตะกร้าเปิดอยู่ให้ปิด
        const cartModalEl = document.getElementById('cartModal');
        if(cartModalEl.classList.contains('show')) bootstrap.Modal.getInstance(cartModalEl).hide();

        calculateTotal();

        // กรอกคูปองออโต้ถ้าเคยเก็บไว้
        const savedCoupon = localStorage.getItem('saved_coupon');
        if(savedCoupon) {
            document.getElementById('coupon-code').value = savedCoupon;
            applyCoupon();
        }

        setTimeout(() => new bootstrap.Modal(document.getElementById('checkoutModal')).show(), 400);
    }

    function saveAddressToProfile() {
        currentUser.address = document.getElementById('ship-address').value;
        localStorage.setItem('thanjai_session', JSON.stringify(currentUser));
        alert('บันทึกที่อยู่เป็นค่าเริ่มต้นเรียบร้อย');
    }

    function calculateTotal() {
        let subtotal = 0;
        const summaryList = document.getElementById('checkout-summary-list');
        summaryList.innerHTML = cart.map(item => {
            const itemTotal = item.price * item.qty;
            subtotal += itemTotal;
            return `<div class="d-flex justify-content-between mb-2"><span>${item.name} (${item.size}) x${item.qty}</span><b>฿${itemTotal.toLocaleString()}</b></div>`;
        }).join('');

        document.getElementById('chk-subtotal').innerText = `฿${subtotal.toLocaleString()}`;
        document.getElementById('chk-discount').innerText = `-฿${discountAmt.toLocaleString()}`;
        
        const shipping = 50;
        const netTotal = (subtotal + shipping) - discountAmt;
        document.getElementById('checkout-total-price').innerText = `฿${Math.max(0, netTotal).toLocaleString()}`;
    }

    function applyCoupon() {
        const code = document.getElementById('coupon-code').value.toUpperCase();
        if(code === 'NEWUSER50') {
            discountAmt = 50;
            document.getElementById('coupon-msg').innerText = "ใช้คูปองส่วนลด 50 บาทสำเร็จ!";
            document.getElementById('coupon-msg').classList.remove('d-none');
            calculateTotal();
        } else {
            alert('คูปองไม่ถูกต้อง');
        }
    }

    function selectPaymentMethod(method) {
        selectedPayment = method;
        document.getElementById('pay-cod').classList.remove('active');
        document.getElementById('pay-qr').classList.remove('active');
        
        if(method === 'COD') {
            document.getElementById('pay-cod').classList.add('active');
            document.getElementById('qr-code-section').classList.add('d-none');
            document.getElementById('btn-confirm-order').classList.remove('d-none');
            clearInterval(qrInterval);
        } else {
            document.getElementById('pay-qr').classList.add('active');
            document.getElementById('qr-code-section').classList.remove('d-none');
            document.getElementById('btn-confirm-order').classList.add('d-none'); // ซ่อนปุ่มยืนยัน เพราะต้องจ่ายผ่าน QR
            startQRTimer();
        }
    }

    // ระบบจับเวลา QR (5 นาที)
    function startQRTimer() {
        clearInterval(qrInterval);
        let timeLeft = 5 * 60; 
        const timerDisplay = document.getElementById('qr-timer');
        
        qrInterval = setInterval(() => {
            let m = Math.floor(timeLeft / 60);
            let s = timeLeft % 60;
            timerDisplay.innerText = `${m < 10 ? '0':''}${m}:${s < 10 ? '0':''}${s}`;
            
            if (timeLeft <= 0) {
                clearInterval(qrInterval);
                if(confirm("หมดเวลาการชำระเงิน ต้องการทำรายการต่อหรือไม่?")) {
                    startQRTimer();
                } else {
                    bootstrap.Modal.getInstance(document.getElementById('checkoutModal')).hide();
                    selectPaymentMethod('COD'); // รีเซ็ต
                }
            }
            timeLeft -= 1;
        }, 1000);
    }

    // จำลองเมื่อลูกค้าโอนเงินผ่าน QR สำเร็จ
    function mockQRSuccess() {
        clearInterval(qrInterval);
        alert(`รับยอดเงินเรียบร้อยแล้ว!\n\nสถานะ: ยืนยันการสั่งซื้อ รอการจัดส่ง\nระยะเวลาจัดส่ง: 2-3 วันทำการ\nเตรียมรอรับสินค้าได้เลยครับ`);
        finishOrder();
    }

    // ยืนยันคำสั่งซื้อ (สำหรับ COD)
    function processOrder() {
        const name = document.getElementById('ship-name').value;
        const address = document.getElementById('ship-address').value;
        if (!name || !address) return alert("กรุณากรอกข้อมูลการจัดส่งให้ครบถ้วนครับ");

        alert(`สั่งซื้อสำเร็จ (เก็บเงินปลายทาง)!\n\nสถานะ: รอยืนยันจากแอดมิน\nเมื่อแอดมินยืนยันแล้ว จะใช้เวลาจัดส่ง 2-3 วันทำการครับ`);
        finishOrder();
    }

    function finishOrder() {
        cart = [];
        discountAmt = 0;
        localStorage.removeItem('saved_coupon');
        updateCartUI();
        bootstrap.Modal.getInstance(document.getElementById('checkoutModal')).hide();
        selectPaymentMethod('COD');
    }

</script>
</body>
</html>