<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ThanJai Shop - Store (Pro Version)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #001233; --accent: #ffae00; }
        body { font-family: 'Kanit', sans-serif; background: #f4f7f6; color: #1a1a1a; }
        .navbar { background: var(--primary) !important; }
        .hero-banner {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1510566337590-2fc1f21d0faa?q=80&w=2070&auto=format&fit=crop');
            background-size: cover; background-position: center; height: 380px; display: flex; align-items: center; color: white;
        }
        .product-card { border: none; border-radius: 15px; transition: 0.3s; overflow: hidden; height: 100%; position: relative; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .product-card:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(0,0,0,0.1); }
        .product-card img { height: 260px; object-fit: cover; cursor: pointer; }
        .btn-cart { background: #f1f1f1; color: var(--primary); border-radius: 8px; font-weight: bold; }
        .btn-cart:hover { background: #e2e2e2; }
        .btn-buy-now { background: var(--accent); color: var(--primary); border-radius: 8px; font-weight: bold; }
        .btn-buy-now:hover { background: #e59c00; }
        .member-badge { font-size: 0.7rem; background: var(--accent); color: var(--primary); padding: 2px 8px; border-radius: 10px; font-weight: bold; }
        .cart-badge { font-size: 0.65rem; padding: 3px 6px; }
        .payment-box { border: 2px solid #ddd; border-radius: 10px; padding: 15px; cursor: pointer; transition: 0.2s; text-align: center; background: white; }
        .payment-box:hover { border-color: var(--accent); background: #fffdf5; }
        .payment-box.active { border-color: var(--accent); background: #fff8e1; font-weight: bold; }
        .sale-badge { position: absolute; top: 12px; right: 12px; background: #d90429; color: white; padding: 5px 12px; border-radius: 8px; font-weight: bold; font-size: 12px; z-index: 10; }
        .avatar-preview { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; cursor: pointer; border: 3px solid transparent; transition: 0.3s; }
        .avatar-preview.active { border-color: var(--accent); transform: scale(1.1); }
        .toast-container { z-index: 9999; }
        .toast { border-radius: 10px; font-weight: bold; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
        .notification-dot { width: 10px; height: 10px; background-color: #d90429; border-radius: 50%; position: absolute; top: -2px; right: -2px; border: 2px solid var(--primary); }
    </style>
</head>
<body onload="initStore()">

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="liveToast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toast-msg">ข้อความ</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-dark sticky-top shadow">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4" href="#" onclick="filterProducts('ทั้งหมด')">ThanJai <span style="color:var(--accent);">Shop</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link active" href="#" onclick="filterProducts('ทั้งหมด')"><i class="fa fa-home me-1"></i>หน้าหลัก</a></li>
                    <li class="nav-item"><a class="nav-link" href="#product-list" onclick="filterProducts('ทั้งหมด')"><i class="fa fa-list me-1"></i>สินค้าทั้งหมด</a></li>
                    <li class="nav-item"><a class="nav-link text-warning fw-bold" href="#" onclick="filterProducts('โปรโมชั่น')"><i class="fa fa-tags me-1"></i>โปรโมชั่นลดราคา</a></li>
                    <li class="nav-item"><a class="nav-link text-info fw-bold" href="#" onclick="alert('📌 ติดต่อเรา ThanJai Shop\\n\\n📱 Line: @thanjai_shop\\n📞 โทร: 081-XXX-XXXX\\n🌐 Facebook: ThanJai Sport')"><i class="fa fa-phone-alt me-1"></i>ติดต่อ</a></li>
                </ul>
                <div class="d-flex align-items-center gap-3 mt-2 mt-lg-0">
                    <div class="input-group input-group-sm d-none d-md-flex" style="max-width: 200px;">
                        <input type="text" id="search-input" class="form-control" placeholder="ค้นหาชื่อทีม..." onkeyup="searchProducts()">
                    </div>
                    
                    <div id="guest-zone">
                        <button class="btn btn-warning btn-sm fw-bold px-3" onclick="openAuthModal('login')">เข้าสู่ระบบ / สมัครสมาชิก</button>
                    </div>

                    <div id="user-zone" class="d-none">
                        <div class="dropdown text-white d-flex align-items-center">
                            <span class="me-3 d-none d-md-inline">สวัสดี, <strong id="nav-username">User</strong> <span class="member-badge">Member</span></span>
                            <a href="#" class="link-light dropdown-toggle text-decoration-none position-relative" data-bs-toggle="dropdown">
                                <i class="fa fa-bars fs-3 align-middle me-2"></i>
                                <img id="nav-profile-pic" src="" width="35" height="35" class="rounded-circle border border-2 border-warning" style="object-fit: cover;">
                                <span id="nav-noti-dot" class="notification-dot d-none"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                                <li><a class="dropdown-item py-2" href="#" data-bs-toggle="modal" data-bs-target="#profileModal"><i class="fa fa-cog me-2 text-muted"></i>ตั้งค่าบัญชี</a></li>
                                <li><a class="dropdown-item py-2 d-flex justify-content-between align-items-center" href="#" onclick="openOrderHistory()">
                                    <span><i class="fa fa-box-open me-2 text-muted"></i>ประวัติสั่งซื้อ</span>
                                    <span class="badge bg-danger rounded-pill d-none" id="menu-unpaid-count">0</span>
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger py-2" href="#" onclick="logoutUser()"><i class="fa fa-sign-out-alt me-2"></i>ออกจากระบบ</a></li>
                            </ul>
                        </div>
                    </div>

                    <a href="#" class="text-white position-relative ms-2" onclick="openCart()">
                        <i class="fa fa-shopping-cart fs-4"></i>
                        <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-badge">0</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <header class="hero-banner text-center" id="hero-banner">
        <div class="container">
            <h1 class="display-4 fw-bold text-uppercase" style="text-shadow: 2px 2px 10px rgba(0,0,0,0.5);">NEW SEASON 2026</h1>
            <p class="lead fw-light mb-4" style="text-shadow: 1px 1px 5px rgba(0,0,0,0.5);">คอลเลคชั่นใหม่ล่าสุดจากสโมสรดังทั่วโลก พร้อมส่งแล้ววันนี้!</p>
            <a href="#product-list" class="btn btn-warning btn-lg px-5 fw-bold shadow">เลือกช้อปเลย</a>
        </div>
    </header>

    <main class="container my-5" id="product-list">
        <div class="d-flex justify-content-center gap-2 mb-4 overflow-auto pb-2">
            <button class="btn btn-dark px-4 rounded-pill filter-btn active" onclick="filterProducts('ทั้งหมด', this)">ทั้งหมด</button>
            <button class="btn btn-outline-dark px-4 rounded-pill filter-btn" onclick="filterProducts('เหย้า', this)">เหย้า</button>
            <button class="btn btn-outline-dark px-4 rounded-pill filter-btn" onclick="filterProducts('เยือน', this)">เยือน</button>
            <button class="btn btn-outline-dark px-4 rounded-pill filter-btn" onclick="filterProducts('ซ้อม', this)">เสื้อซ้อม</button>
        </div>
        
        <div class="row g-4" id="store-display">
            </div>

        <div class="d-flex justify-content-end mt-5 pt-4 border-top">
            <button class="btn btn-outline-dark rounded-pill px-4 py-2 fw-bold" onclick="filterProducts('ทั้งหมด', document.querySelector('.filter-btn'))">
                สินค้าทั้งหมด <i class="fa fa-arrow-right ms-2"></i>
            </button>
        </div>
    </main>

    <div class="modal fade" id="authModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-dark text-white border-0">
                    <h5 class="modal-title fw-bold" id="authModalTitle">เข้าสู่ระบบก่อนสั่งซื้อ</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <div id="login-section">
                        <input type="text" id="login-user" class="form-control mb-3 text-center" placeholder="ชื่อผู้ใช้ (Username)">
                        <input type="password" id="login-pass" class="form-control mb-3 text-center" placeholder="รหัสผ่าน">
                        <button class="btn btn-warning w-100 fw-bold py-2 mb-3 shadow-sm" onclick="login()">ยืนยันเข้าสู่ระบบ</button>
                        <p class="small mb-0">ยังไม่มีบัญชี? <a href="#" onclick="toggleAuth('register')" class="text-primary fw-bold text-decoration-none">สมัครสมาชิกใหม่</a></p>
                    </div>
                    <div id="register-section" class="d-none">
                        <input type="text" id="reg-user" class="form-control mb-2 text-center" placeholder="ตั้งชื่อผู้ใช้ (Username)">
                        <input type="email" id="reg-email" class="form-control mb-2 text-center" placeholder="อีเมล (Email)">
                        <input type="text" id="reg-phone" class="form-control mb-2 text-center" placeholder="เบอร์โทรศัพท์" maxlength="10">
                        <input type="password" id="reg-pass" class="form-control mb-3 text-center" placeholder="ตั้งรหัสผ่าน">
                        <button class="btn btn-success w-100 fw-bold py-2 mb-3 shadow-sm" onclick="register()">ลงทะเบียน</button>
                        <p class="small mb-0">มีบัญชีอยู่แล้ว? <a href="#" onclick="toggleAuth('login')" class="text-primary fw-bold text-decoration-none">กลับไปเข้าสู่ระบบ</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="productDetailModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light border-0">
                    <h5 class="modal-title fw-bold">รายละเอียดสินค้า</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <div class="col-md-5 text-center position-relative">
                            <span id="detail-sale-badge" class="sale-badge d-none">ลดราคา!</span>
                            <img id="detail-img" src="" class="img-fluid rounded shadow-sm" style="max-height: 380px; object-fit: contain;">
                        </div>
                        <div class="col-md-7">
                            <span id="detail-type" class="badge bg-secondary mb-2"></span>
                            <h3 class="fw-bold" id="detail-name">ชื่อสินค้า</h3>
                            <div class="mb-3">
                                <span class="text-danger fw-bold fs-3" id="detail-price">฿0</span>
                                <span class="text-muted text-decoration-line-through ms-2 d-none" id="detail-old-price">฿0</span>
                            </div>
                            <p class="text-muted" id="detail-desc">รายละเอียดสินค้า...</p>
                            
                            <hr class="my-4">
                            
                            <div class="row g-3 align-items-end mb-4 bg-light p-3 rounded border">
                                <div class="col-8">
                                    <label class="form-label fw-bold small text-primary"><i class="fa fa-ruler me-1"></i> เลือกไซส์ (Size):</label>
                                    <select id="detail-size" class="form-select border-primary shadow-sm">
                                        <option value="S">S (รอบอก 36 นิ้ว)</option>
                                        <option value="M" selected>M (รอบอก 38 นิ้ว)</option>
                                        <option value="L">L (รอบอก 40 นิ้ว)</option>
                                        <option value="XL">XL (รอบอก 42 นิ้ว)</option>
                                        <option value="2XL">2XL (รอบอก 44 นิ้ว)</option>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <label class="form-label fw-bold small text-primary"><i class="fa fa-hashtag me-1"></i> จำนวน (ตัว):</label>
                                    <input type="number" id="detail-qty" class="form-control border-primary shadow-sm text-center" value="1" min="1">
                                </div>
                            </div>
                            
                            <input type="hidden" id="detail-id">
                            <div class="row g-2">
                                <div class="col-6">
                                    <button type="button" class="btn btn-cart w-100 py-3" onclick="addToCart(false)">
                                        <i class="fa fa-cart-plus me-1"></i> เพิ่มลงตะกร้า
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-buy-now w-100 py-3 shadow-sm" onclick="addToCart(true)">
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
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-dark text-white border-0">
                    <h5 class="modal-title fw-bold"><i class="fa fa-shopping-cart me-2"></i>สรุปรายการและชำระเงิน</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="row g-0">
                        <div class="col-lg-6 p-4 border-end bg-white">
                            <h5 class="fw-bold border-bottom pb-2 mb-3">รายการสินค้าของคุณ</h5>
                            <div id="cart-items-container" style="max-height: 400px; overflow-y: auto;"></div>
                        </div>
                        <div class="col-lg-6 p-4 bg-light">
                            <h5 class="fw-bold border-bottom pb-2 mb-3">เลือกวิธีชำระเงิน</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-6">
                                    <div class="payment-box active shadow-sm" id="pay-cod" onclick="selectPayment('COD')">
                                        <i class="fa fa-truck fa-2x mb-2 text-dark"></i>
                                        <div>เก็บเงินปลายทาง</div>
                                        <small class="text-muted">(รอรับที่บ้าน)</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="payment-box shadow-sm" id="pay-qr" onclick="selectPayment('QR')">
                                        <i class="fa fa-qrcode fa-2x mb-2 text-primary"></i>
                                        <div>โอนผ่านธนาคาร</div>
                                        <small class="text-muted">(สแกน QR Code)</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-white p-3 rounded border mb-3">
                                <div class="d-flex justify-content-between mb-1"><span>รวมค่าสินค้า:</span> <span id="summary-subtotal">฿0</span></div>
                                <div class="d-flex justify-content-between mb-1"><span>ค่าจัดส่ง: </span> <span>฿50</span></div>
                                <hr>
                                <div class="d-flex justify-content-between fw-bold fs-4"><span>ยอดสุทธิที่ต้องชำระ:</span> <span class="text-danger" id="summary-total">฿0</span></div>
                            </div>
                            
                            <button type="button" class="btn btn-warning w-100 py-3 fw-bold fs-5 shadow" onclick="confirmCheckout()">
                                ยืนยันคำสั่งซื้อ
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="historyModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg bg-light">
                <div class="modal-header bg-dark text-white border-0">
                    <h5 class="modal-title fw-bold"><i class="fa fa-box-open me-2"></i>ประวัติคำสั่งซื้อของคุณ</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4" id="history-list">
                    </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="profileModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-dark text-white border-0">
                    <h5 class="modal-title fw-bold"><i class="fa fa-user-cog me-2"></i>ตั้งค่าบัญชี</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <div class="d-flex justify-content-center gap-2 mb-4">
                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=A" class="avatar-preview active" onclick="selectAvatar(this)">
                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=B" class="avatar-preview" onclick="selectAvatar(this)">
                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=C" class="avatar-preview" onclick="selectAvatar(this)">
                    </div>
                    <div class="text-start">
                        <label class="form-label small fw-bold">ชื่อแสดงผล</label>
                        <input type="text" id="set-name" class="form-control mb-2 text-center">
                        <label class="form-label small fw-bold">เบอร์โทรศัพท์</label>
                        <input type="text" id="set-phone" class="form-control mb-4 text-center">
                    </div>
                    <button class="btn btn-warning w-100 fw-bold py-2 shadow-sm" onclick="saveProfile()">บันทึกข้อมูล</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ข้อมูลสินค้าหลัก (ใช้ชื่อรูปภาพ 2.jpg - 10.jpg ของคุณ)
        const products = [
            { id: 2, name: "Uthai Thani Home 2024", price: 790, isSale: false, type: "เหย้า", img: "2.jpg", desc: "เสื้อแข่งทีมอุทัยธานี เอฟซี ฤดูกาลล่าสุด ผ้าเกรดพรีเมียม" },
            { id: 4, name: "Buriram United Home", price: 690, oldPrice: 890, isSale: true, type: "เหย้า", img: "4.jpg", desc: "เสื้อสายฟ้า ปราสาทสายฟ้า คุณภาพอันดับ 1 ของเมืองไทย" },
            { id: 5, name: "Thailand Edition", price: 590, isSale: false, type: "ซ้อม", img: "5.jpg", desc: "เสื้อเชียร์ทีมชาติไทย สวมใส่สบาย ระบายอากาศดี" },
            { id: 6, name: "Port FC Away Kit", price: 750, oldPrice: 850, isSale: true, type: "เยือน", img: "6.jpg", desc: "สิงห์เจ้าท่า สีเยือนเรียบหรู พร้อมดีไซน์ทันสมัย" },
            { id: 7, name: "Training BGPU", price: 490, isSale: false, type: "ซ้อม", img: "7.jpg", desc: "เสื้อซ้อมคุณภาพสูงจากสโมสรบีจี ปทุม" },
            { id: 8, name: "Muangthong Utd Home", price: 790, isSale: false, type: "เหย้า", img: "8.jpg", desc: "กิเลนผยอง คลาสสิกสีแดงดำ ดีไซน์ทรงคุณค่า" },
            { id: 9, name: "Chonburi FC Away", price: 650, isSale: false, type: "เยือน", img: "9.jpg", desc: "ฉลามชล ดีไซน์สปอร์ตทันสมัย สีเยือนโดดเด่น" },
            { id: 10, name: "Special Training", price: 390, isSale: false, type: "ซ้อม", img: "10.jpg", desc: "เสื้อซ้อมรวมสโมสร ลิมิเต็ด อิดิชั่น" }
        ];

        // ระบบสำรองรูปภาพ หากไฟล์ในเครื่องหาไม่เจอเว็บจะได้ไม่พัง
        const fallbackImages = {
            2: "https://images.unsplash.com/photo-1589487391730-58f20eb2c308?w=500&h=600&fit=crop", 
            4: "https://images.unsplash.com/photo-1577223625816-7546f13df25d?w=500&h=600&fit=crop",
            5: "https://images.unsplash.com/photo-1599305090598-fe179d501227?w=500&h=600&fit=crop",
            6: "https://images.unsplash.com/photo-1614631446501-abcf76949eca?w=500&h=600&fit=crop",
            7: "https://images.unsplash.com/photo-1560272564-c83b66b1ad12?w=500&h=600&fit=crop",
            8: "https://images.unsplash.com/photo-1608248543803-ba4f8c70ae0b?w=500&h=600&fit=crop",
            9: "https://images.unsplash.com/photo-1522778119026-d647f0596c20?w=500&h=600&fit=crop",
            10: "https://images.unsplash.com/photo-1558222218-b7b54eede3f3?w=500&h=600&fit=crop"
        };
        function getFallbackImage(id) { return fallbackImages[id] || 'https://placehold.co/300x400/1a1a1a/ffae00?text=No+Image'; }

        // ตัวแปรระบบ
        let usersDB = [];
        let currentUser = null;
        let cart = [];
        let paymentMethod = 'COD';

        // 1. เริ่มต้นระบบ
        function initStore() {
            renderProducts(products);
            const storedDB = localStorage.getItem('thanjai_pro_db');
            if(storedDB) usersDB = JSON.parse(storedDB);
            
            const activeSession = localStorage.getItem('thanjai_session');
            if (activeSession) {
                const foundUser = usersDB.find(u => u.username === activeSession);
                if (foundUser) {
                    currentUser = foundUser;
                    cart = currentUser.cart || [];
                    updateNavUI();
                    checkUnpaidOrders(); // เช็คเตือนทันทีที่เข้าเว็บ
                }
            }
        }

        // ระบบแจ้งเตือน (Toast)
        function showToast(message, type = 'success') {
            const toastEl = document.getElementById('liveToast');
            toastEl.classList.remove('bg-success', 'bg-danger', 'bg-warning', 'text-dark');
            if(type === 'success') toastEl.classList.add('bg-success');
            else if(type === 'error') toastEl.classList.add('bg-danger');
            else if(type === 'warning') toastEl.classList.add('bg-warning', 'text-dark');
            document.getElementById('toast-msg').innerHTML = `<i class="fa fa-info-circle me-2"></i> ${message}`;
            new bootstrap.Toast(toastEl, { delay: 3500 }).show();
        }

        function saveDatabase() {
            if(currentUser) {
                const index = usersDB.findIndex(u => u.username === currentUser.username);
                currentUser.cart = cart;
                if(index !== -1) usersDB[index] = currentUser;
                else usersDB.push(currentUser);
                localStorage.setItem('thanjai_pro_db', JSON.stringify(usersDB));
                localStorage.setItem('thanjai_session', currentUser.username);
            }
        }

        // 2. ระบบสมาชิก
        function openAuthModal(type) {
            toggleAuth(type);
            new bootstrap.Modal(document.getElementById('authModal')).show();
        }

        function toggleAuth(type) {
            document.getElementById('login-section').classList.toggle('d-none', type !== 'login');
            document.getElementById('register-section').classList.toggle('d-none', type !== 'register');
            document.getElementById('authModalTitle').innerText = type === 'login' ? 'เข้าสู่ระบบก่อนสั่งซื้อ' : 'สมัครสมาชิกใหม่';
        }

        function register() {
            const user = document.getElementById('reg-user').value.trim();
            const email = document.getElementById('reg-email').value.trim();
            const phone = document.getElementById('reg-phone').value.trim();
            const pass = document.getElementById('reg-pass').value.trim();
            if(!user || !pass) return showToast('กรุณากรอกข้อมูลให้ครบถ้วน', 'error');
            if(usersDB.some(u => u.username === user)) return showToast('ชื่อผู้ใช้นี้ถูกใช้งานแล้ว', 'error');
            
            currentUser = { 
                username: user, name: user, email: email, phone: phone, password: pass, 
                profilePic: 'https://api.dicebear.com/7.x/avataaars/svg?seed='+user, 
                orders: [], cart: [] 
            };
            cart = []; saveDatabase();
            bootstrap.Modal.getInstance(document.getElementById('authModal')).hide();
            updateNavUI();
            showToast('🎉 สมัครและเข้าสู่ระบบสำเร็จ!', 'success');
        }

        function login() {
            const user = document.getElementById('login-user').value.trim();
            const pass = document.getElementById('login-pass').value.trim();
            if(!user || !pass) return showToast('กรุณากรอกข้อมูลให้ครบ', 'warning');
            const foundUser = usersDB.find(u => u.username === user);
            if(!foundUser || foundUser.password !== pass) return showToast('❌ Username หรือ Password ไม่ถูกต้อง', 'error');
            
            currentUser = foundUser;
            cart = currentUser.cart || [];
            if(!currentUser.orders) currentUser.orders = [];
            saveDatabase();
            bootstrap.Modal.getInstance(document.getElementById('authModal')).hide();
            updateNavUI();
            checkUnpaidOrders(); // เช็คเตือนค้างชำระ
            showToast(`ยินดีต้อนรับกลับมาครับคุณ ${currentUser.name}!`, 'success');
        }

        function logoutUser() {
            if(confirm("ต้องการออกจากระบบใช่หรือไม่?")) {
                localStorage.removeItem('thanjai_session');
                currentUser = null; cart = [];
                updateNavUI();
                showToast('ออกจากระบบเรียบร้อยแล้ว', 'success');
            }
        }

        function updateNavUI() {
            const guestZone = document.getElementById('guest-zone');
            const userZone = document.getElementById('user-zone');
            
            if(currentUser) {
                guestZone.classList.add('d-none');
                userZone.classList.remove('d-none');
                document.getElementById('nav-username').innerText = currentUser.name;
                document.getElementById('nav-profile-pic').src = currentUser.profilePic;
                document.getElementById('set-name').value = currentUser.name;
                document.getElementById('set-phone').value = currentUser.phone || '';
            } else {
                guestZone.classList.remove('d-none');
                userZone.classList.add('d-none');
            }
            updateCartBadge();
        }

        function selectAvatar(el) {
            document.querySelectorAll('.avatar-preview').forEach(i => i.classList.remove('active'));
            el.classList.add('active');
            if(currentUser) currentUser.profilePic = el.src;
        }

        function saveProfile() {
            if(!currentUser) return;
            currentUser.name = document.getElementById('set-name').value;
            currentUser.phone = document.getElementById('set-phone').value;
            saveDatabase(); updateNavUI();
            bootstrap.Modal.getInstance(document.getElementById('profileModal')).hide();
            showToast('✅ อัปเดตโปรไฟล์เรียบร้อย!', 'success');
        }

        // 3. ระบบสินค้าและตะกร้า
        function renderProducts(items) {
            const container = document.getElementById('store-display');
            container.innerHTML = items.map(p => `
                <div class="col-6 col-md-3 mb-4">
                    <div class="card product-card bg-white">
                        ${p.isSale ? `<span class="sale-badge">ลดราคา!</span>` : ''}
                        <img src="${p.img}" class="card-img-top" alt="${p.name}" onclick="viewDetail(${p.id})" onerror="this.onerror=null; this.src=getFallbackImage(${p.id})">
                        <div class="card-body">
                            <span class="badge bg-secondary mb-2" style="font-size: 10px;">${p.type}</span>
                            <h6 class="card-title fw-bold text-dark mb-1" style="font-size: 14px;">${p.name}</h6>
                            <div class="mt-3">
                                <span class="text-danger fw-bold fs-5">฿${p.price.toLocaleString()}</span>
                                ${p.isSale ? `<span class="text-muted text-decoration-line-through small ms-1">฿${p.oldPrice}</span>` : ''}
                                <button class="btn btn-dark w-100 mt-2 fw-bold" onclick="viewDetail(${p.id})">ดูรายละเอียด / สั่งซื้อ</button>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function filterProducts(type, btnElement) {
            if(btnElement) {
                document.querySelectorAll('.filter-btn').forEach(b => { b.classList.remove('active', 'btn-dark'); b.classList.add('btn-outline-dark'); });
                btnElement.classList.remove('btn-outline-dark'); btnElement.classList.add('btn-dark', 'active');
            }
            if(type === 'ทั้งหมด') renderProducts(products);
            else if(type === 'โปรโมชั่น') renderProducts(products.filter(p => p.isSale));
            else renderProducts(products.filter(p => p.type === type));
        }

        function searchProducts() {
            const val = document.getElementById('search-input').value.toLowerCase();
            renderProducts(products.filter(p => p.name.toLowerCase().includes(val)));
        }

        function viewDetail(id) {
            // บังคับล็อกอินก่อนดู/ซื้อ
            if(!currentUser) {
                openAuthModal('login');
                return showToast('กรุณาเข้าสู่ระบบก่อนทำการเลือกซื้อสินค้าครับ', 'warning');
            }

            const p = products.find(x => x.id === id);
            document.getElementById('detail-id').value = p.id;
            document.getElementById('detail-name').innerText = p.name;
            document.getElementById('detail-price').innerText = '฿' + p.price.toLocaleString();
            document.getElementById('detail-desc').innerText = p.desc;
            document.getElementById('detail-type').innerText = p.type;
            
            const imgEl = document.getElementById('detail-img');
            imgEl.src = p.img;
            imgEl.onerror = function() { this.src = getFallbackImage(p.id); };

            if(p.isSale) {
                document.getElementById('detail-old-price').innerText = '฿' + p.oldPrice.toLocaleString();
                document.getElementById('detail-old-price').classList.remove('d-none');
                document.getElementById('detail-sale-badge').classList.remove('d-none');
            } else {
                document.getElementById('detail-old-price').classList.add('d-none');
                document.getElementById('detail-sale-badge').classList.add('d-none');
            }
            
            // รีเซ็ตค่าเริ่มต้น
            document.getElementById('detail-qty').value = 1;
            document.getElementById('detail-size').selectedIndex = 1; 
            new bootstrap.Modal(document.getElementById('productDetailModal')).show();
        }

        function addToCart(isBuyNow) {
            const id = parseInt(document.getElementById('detail-id').value);
            const size = document.getElementById('detail-size').value; // เก็บค่าไซส์
            const qty = parseInt(document.getElementById('detail-qty').value); // เก็บค่าจำนวน
            const product = products.find(p => p.id === id);

            const index = cart.findIndex(item => item.id === id && item.size === size);
            if(index > -1) cart[index].qty += qty;
            else cart.push({ ...product, size: size, qty: qty });

            saveDatabase(); updateCartBadge();
            bootstrap.Modal.getInstance(document.getElementById('productDetailModal')).hide();
            
            if(isBuyNow) {
                setTimeout(openCart, 400); // ถ้ากดซื้อเลย ให้เปิดตะกร้าต่อทันที
            } else {
                showToast(`เพิ่ม ${product.name} (ไซส์ ${size}) ลงตะกร้าแล้ว`, 'success');
            }
        }

        function updateCartBadge() {
            document.getElementById('cart-count').innerText = cart.reduce((sum, i) => sum + i.qty, 0);
        }

        function openCart() {
            if(!currentUser) {
                openAuthModal('login');
                return showToast('กรุณาเข้าสู่ระบบก่อนเข้าดูตะกร้า', 'warning');
            }
            
            const container = document.getElementById('cart-items-container');
            if(cart.length === 0) {
                container.innerHTML = '<div class="text-center text-muted my-5 py-5"><i class="fa fa-shopping-basket fa-3x mb-3"></i><p>ไม่มีสินค้าในตะกร้า</p></div>';
                document.getElementById('summary-subtotal').innerText = '฿0';
                document.getElementById('summary-total').innerText = '฿0';
            } else {
                let subtotal = 0;
                container.innerHTML = cart.map((item, index) => {
                    const total = item.price * item.qty; subtotal += total;
                    return `
                        <div class="d-flex align-items-center mb-3 p-3 border rounded shadow-sm bg-white">
                            <img src="${item.img}" width="70" height="70" class="rounded me-3 border" style="object-fit: cover;" onerror="this.src=getFallbackImage(${item.id})">
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-bold text-dark">${item.name}</h6>
                                <small class="text-primary bg-light px-2 py-1 rounded border">ไซส์: <strong>${item.size}</strong> | จำนวน: <strong>${item.qty} ตัว</strong></small>
                                <div class="text-danger mt-1 small">ราคา ฿${item.price.toLocaleString()} / ชิ้น</div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold fs-5 text-dark mb-1">฿${total.toLocaleString()}</div>
                                <button class="btn btn-sm btn-outline-danger" onclick="removeCartItem(${index})"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                    `;
                }).join('');
                document.getElementById('summary-subtotal').innerText = '฿' + subtotal.toLocaleString();
                document.getElementById('summary-total').innerText = '฿' + (subtotal + 50).toLocaleString(); // รวมส่ง 50 บาท
            }
            new bootstrap.Modal(document.getElementById('checkoutModal')).show();
        }

        function removeCartItem(index) {
            cart.splice(index, 1);
            saveDatabase(); updateCartBadge(); openCart();
        }

        // 4. ชำระเงิน & ประวัติคำสั่งซื้อ
        function selectPayment(method) {
            paymentMethod = method;
            document.getElementById('pay-cod').classList.remove('active');
            document.getElementById('pay-qr').classList.remove('active');
            if(method === 'COD') document.getElementById('pay-cod').classList.add('active');
            if(method === 'QR') document.getElementById('pay-qr').classList.add('active');
        }

        function confirmCheckout() {
            if(cart.length === 0) return showToast('กรุณาเลือกสินค้าลงตะกร้าก่อนครับ', 'error');
            bootstrap.Modal.getInstance(document.getElementById('checkoutModal')).hide();
            
            // ประมวลผลสร้างออเดอร์
            const subtotal = cart.reduce((sum, i) => sum + (i.price * i.qty), 0);
            const isQR = (paymentMethod === 'QR');
            
            const newOrder = {
                orderId: "TJ-" + Math.floor(Math.random() * 90000 + 10000),
                items: [...cart],
                method: isQR ? 'โอนเงินบัญชีธนาคาร (รอแจ้งโอน)' : 'เก็บเงินปลายทาง (COD)',
                status: isQR ? 'รอชำระเงิน' : 'เตรียมจัดส่ง', // ++ แยกสถานะตามที่คุณขอ ++
                total: subtotal + 50,
                date: new Date().toLocaleDateString('th-TH')
            };
            
            if(!currentUser.orders) currentUser.orders = [];
            currentUser.orders.unshift(newOrder); // ใส่ไว้บนสุด
            cart = []; saveDatabase(); updateCartBadge();
            
            if(isQR) {
                showToast('สั่งซื้อสำเร็จ! กรุณาโอนเงินและแจ้งสลิปนะครับ', 'warning');
            } else {
                showToast('สั่งซื้อสำเร็จ! ทางร้านจะรีบจัดส่งให้เร็วที่สุดครับ', 'success');
            }
            
            setTimeout(openOrderHistory, 600);
            checkUnpaidOrders(); // อัปเดตแจ้งเตือนสีแดง
        }

        function openOrderHistory() {
            if(!currentUser) return;
            const container = document.getElementById('history-list');
            const orders = currentUser.orders || [];
            
            if(orders.length === 0) {
                container.innerHTML = '<div class="text-center py-5 text-muted"><i class="fa fa-box-open fa-3x mb-3"></i><p>ยังไม่มีประวัติการสั่งซื้อ</p></div>';
            } else {
                container.innerHTML = orders.map(o => {
                    const isUnpaid = o.status === 'รอชำระเงิน';
                    return `
                    <div class="bg-white p-4 rounded shadow-sm mb-4 border position-relative">
                        <div class="d-flex justify-content-between border-bottom pb-2 mb-3">
                            <span class="fw-bold fs-5 text-dark"><i class="fa fa-receipt text-muted me-2"></i>ออเดอร์: ${o.orderId}</span>
                            <span class="badge ${isUnpaid ? 'bg-danger px-3 py-2' : 'bg-success px-3 py-2'} fs-6">${o.status}</span>
                        </div>
                        ${o.items.map(item => `
                            <div class="d-flex align-items-center mb-3 bg-light p-2 rounded">
                                <img src="${item.img}" width="60" height="60" class="rounded border border-secondary me-3" style="object-fit: cover;" onerror="this.src=getFallbackImage(${item.id})">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold text-dark">${item.name}</h6>
                                    <small class="text-primary bg-white px-2 py-1 rounded border">ไซส์: <strong>${item.size}</strong> | จำนวน: <strong>${item.qty} ตัว</strong></small>
                                </div>
                            </div>
                        `).join('')}
                        <div class="d-flex justify-content-between align-items-end pt-3 mt-3 border-top">
                            <div>
                                <small class="text-muted d-block mb-1"><i class="fa fa-calendar-alt me-1"></i> สั่งเมื่อ: ${o.date}</small>
                                <small class="text-muted d-block"><i class="fa fa-credit-card me-1"></i> ชำระผ่าน: <strong class="text-dark">${o.method}</strong></small>
                            </div>
                            <div class="text-end">
                                <strong class="text-danger fs-4 d-block mb-2">ยอดสุทธิ: ฿${o.total.toLocaleString()}</strong>
                                ${isUnpaid ? `<button class="btn btn-sm btn-outline-danger fw-bold" onclick="alert('กรุณาโอนเงินเข้าบัญชี กสิกรไทย 123-4-56789-0 และแจ้งสลิปที่ Line: @thanjai_shop')"><i class="fa fa-qrcode me-1"></i> แจ้งชำระเงิน</button>` : ''}
                            </div>
                        </div>
                    </div>
                `}).join('');
            }
            new bootstrap.Modal(document.getElementById('historyModal')).show();
        }

        // ระบบเช็คออเดอร์ค้างชำระ (เพื่อแสดงจุดแดงแจ้งเตือน)
        function checkUnpaidOrders() {
            if(!currentUser) return;
            const orders = currentUser.orders || [];
            const unpaidCount = orders.filter(o => o.status === 'รอชำระเงิน').length;
            
            const dot = document.getElementById('nav-noti-dot');
            const menuBadge = document.getElementById('menu-unpaid-count');
            
            if(unpaidCount > 0) {
                dot.classList.remove('d-none');
                menuBadge.classList.remove('d-none');
                menuBadge.innerText = unpaidCount;
                
                // แจ้งเตือนเด้งให้รู้ตัวเมื่อเข้ามา
                setTimeout(() => {
                    showToast(`คุณมีออเดอร์ค้างชำระ ${unpaidCount} รายการ กรุณาตรวจสอบที่ประวัติการสั่งซื้อครับ`, 'warning');
                }, 1000);
            } else {
                dot.classList.add('d-none');
                menuBadge.classList.add('d-none');
            }
        }
    </script>
</body>
</html>