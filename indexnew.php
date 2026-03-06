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
        .product-card { border: none; border-radius: 15px; transition: 0.3s; overflow: hidden; height: 100%; position: relative; }
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
        .sale-badge { position: absolute; top: 10px; right: 10px; background: #dc3545; color: white; padding: 5px 12px; border-radius: 5px; font-weight: bold; font-size: 12px; z-index: 10; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
        .profile-img-preview { width: 100px; height: 100px; object-fit: cover; border-radius: 50%; border: 3px solid var(--accent); cursor: pointer; }
        .star-rating i { color: #ddd; cursor: pointer; }
        .star-rating i.active { color: #ffc107; }
    </style>
</head>
<body onload="initStore()">

<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
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
                <li class="nav-item"><a class="nav-link text-warning fw-bold" href="#" data-bs-toggle="modal" data-bs-target="#contactModal"><i class="fa fa-phone-alt me-1"></i>ติดต่อแอดมิน</a></li>
            </ul>
            
            <div class="d-flex align-items-center gap-3">
                <div class="input-group input-group-sm d-none d-md-flex">
                    <input type="text" id="search-input" class="form-control" placeholder="ค้นหาชื่อทีม..." onkeyup="searchProducts()">
                </div>

                <div id="guest-zone">
                    <button class="btn btn-outline-warning btn-sm fw-bold" onclick="openAuthModal('login')">เข้าสู่ระบบ</button>
                </div>

                <div id="user-zone" class="d-none">
                    <div class="dropdown text-white">
                        <span class="me-2 d-none d-md-inline">ยินดีต้อนรับ, <strong id="nav-username">ผู้ใช้</strong> <span class="member-badge">Member</span></span>
                        <a href="#" class="link-light dropdown-toggle" data-bs-toggle="dropdown">
                            <img id="nav-profile-pic" src="https://api.dicebear.com/7.x/avataaars/svg?seed=User" width="35" height="35" class="rounded-circle border" style="object-fit: cover;">
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

<div class="container mt-4 d-none" id="promo-banner">
    <div class="alert alert-danger shadow-sm border-0 rounded-3 text-center position-relative overflow-hidden">
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: url('https://www.transparenttextures.com/patterns/cubes.png'); opacity: 0.1;"></div>
        <h3 class="fw-bold mb-2 position-relative"><i class="fa fa-gift me-2"></i> โปรโมชั่นพิเศษสำหรับลูกค้าใหม่!</h3>
        <p class="mb-0 fs-5 position-relative">เก็บโค้ด <span class="badge bg-warning text-dark fs-5 px-3 py-2 border border-dark">NEWBIE50</span> เพื่อใช้เป็นส่วนลด 50 บาท ทันที!</p>
    </div>
</div>

<main class="container my-5" id="product-list">
    <div class="d-flex justify-content-center gap-2 mb-5 overflow-auto pb-2" id="category-filters">
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
                    <p class="text-center small mt-2 mb-0">ยังไม่มีบัญชี? <a href="#" onclick="toggleAuth('register')">สมัครสมาชิก</a></p>
                </div>
                <div id="register-section" class="d-none">
                    <input type="text" id="reg-user" class="form-control mb-2" placeholder="ชื่อผู้ใช้ (Username)">
                    <input type="email" id="reg-email" class="form-control mb-2" placeholder="อีเมล">
                    <input type="password" id="reg-pass" class="form-control mb-3" placeholder="รหัสผ่าน">
                    <button class="btn btn-success w-100 fw-bold mb-2" onclick="register()">สมัครสมาชิก</button>
                    <p class="text-center small mt-2 mb-0">มีบัญชีอยู่แล้ว? <a href="#" onclick="toggleAuth('login')">เข้าสู่ระบบ</a></p>
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
                <p class="small text-muted mb-4">คลิกที่รูปเพื่อเปลี่ยนภาพโปรไฟล์</p>
                
                <div class="text-start">
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label small fw-bold">ชื่อแสดงผล / ชื่อจริง</label>
                            <input type="text" id="set-name" class="form-control form-control-sm">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">เบอร์โทรศัพท์</label>
                            <input type="text" id="set-phone" class="form-control form-control-sm">
                        </div>
                    </div>
                    <label class="form-label small fw-bold">อีเมล</label>
                    <input type="email" id="set-email" class="form-control form-control-sm mb-2">
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
                        <div class="row g-3 align-items-end">
                            <div class="col-8">
                                <label class="form-label fw-bold small">เลือกไซส์ (Size):</label>
                                <select id="detail-size" class="form-select border-dark">
                                    <option value="S">S (อก 36")</option>
                                    <option value="M" selected>M (อก 38")</option>
                                    <option value="L">L (อก 40")</option>
                                    <option value="XL">XL (อก 42")</option>
                                    <option value="2XL">2XL (อก 44")</option>
                                    <option value="3XL">3XL (อก 46")</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <label class="form-label fw-bold small">จำนวน:</label>
                                <input type="number" id="detail-qty" class="form-control border-dark text-center" value="1" min="1">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <input type="hidden" id="detail-id">
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-warning fw-bold px-4" onclick="addToCart()">
                    <i class="fa fa-cart-plus me-1"></i> สั่งซื้อสินค้า
                </button>
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
                            <div class="col-sm-6"><input type="text" id="ship-phone" class="form-control form-control-sm" placeholder="เบอร์โทรศัพท์"></div>
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
                                    <div class="small">QR PromptPay</div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3">สรุปคำสั่งซื้อ</h6>
                                <div class="input-group input-group-sm mb-3">
                                    <input type="text" id="coupon-input" class="form-control" placeholder="รหัสคูปองส่วนลด">
                                    <button class="btn btn-dark" onclick="applyCoupon()">ใช้คูปอง</button>
                                </div>
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
                <button class="btn btn-outline-danger w-100" onclick="qrPaymentCancel()">ยกเลิกการสั่งซื้อ</button>
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
            <div class="modal-body p-4" id="history-container">
                </div>
        </div>
    </div>
</div>

<div class="modal fade" id="contactModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title fw-bold"><i class="fa fa-headset me-2"></i>ช่องทางการติดต่อแอดมิน</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <img src="https://images.unsplash.com/photo-1534536281715-e28d76689b4d?q=80&w=2070&auto=format&fit=crop" width="100" height="100" class="rounded-circle mb-3 shadow" style="object-fit:cover;">
                <h5 class="fw-bold mb-4">ThanJai Shop Support</h5>
                <a href="#" class="btn btn-outline-success w-100 mb-2 text-start px-4"><i class="fab fa-line fa-lg me-3"></i> <strong>Line Official:</strong> @thanjai_shop</a>
                <a href="#" class="btn btn-outline-primary w-100 mb-2 text-start px-4"><i class="fab fa-facebook fa-lg me-3"></i> <strong>Facebook:</strong> ThanJai Shop</a>
                <a href="#" class="btn btn-outline-dark w-100 text-start px-4"><i class="fa fa-phone fa-lg me-3"></i> <strong>เบอร์โทร:</strong> 081-999-9999</a>
            </div>
        </div>
    </div>
</div>

<footer class="bg-dark text-white py-5 mt-5">
    <div class="container">
        <div class="row g-4 text-center text-md-start">
            <div class="col-md-4">
                <h5 class="fw-bold text-warning">ThanJai Shop</h5>
                <p class="text-muted small">ร้านขายเสื้อบอลอันดับ 1 มั่นใจ ได้ของไว ประสบการณ์กว่า 10 ปี</p>
            </div>
            <div class="col-md-4">
                <h5>ติดต่อเรา</h5>
                <p class="text-muted mb-1 small"><i class="fa fa-phone me-2"></i> 081-999-9999</p>
                <p class="text-muted small"><i class="fab fa-line me-2"></i> @thanjai_shop</p>
            </div>
            <div class="col-md-4">
                <h5>ติดตามข่าวสาร</h5>
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" placeholder="อีเมลของคุณ">
                    <button class="btn btn-warning fw-bold">สมัคร</button>
                </div>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // --------------------------------------------------------
    // ข้อมูลสินค้าแบบเต็ม
    // --------------------------------------------------------
    const products = [
        { id: 1, name: "Uthai Thani Home 2024", price: 790, isSale: false, type: "เหย้า", img: "2.jpg", desc: "เสื้อแข่งทีมอุทัยธานี เอฟซี ฤดูกาลล่าสุด ผ้าเกรดพรีเมียม ระบายเหงื่อได้ดี" },
        { id: 2, name: "Buriram United Home", price: 690, oldPrice: 890, isSale: true, type: "เหย้า", img: "4.jpg", desc: "เสื้อสายฟ้า ปราสาทสายฟ้า ทรงเข้ารูป ใส่กระชับ" },
        { id: 3, name: "Thailand Edition", price: 590, isSale: false, type: "ซ้อม", img: "5.jpg", desc: "เสื้อเชียร์ทีมชาติไทย สวมใส่สบาย เหมาะสำหรับใส่ออกกำลังกาย" },
        { id: 4, name: "Port FC Away Kit", price: 550, oldPrice: 750, isSale: true, type: "เยือน", img: "6.jpg", desc: "สิงห์เจ้าท่า สีเยือนเรียบหรู ดีไซน์คอปกคลาสสิก" },
        { id: 5, name: "Training BGPU", price: 490, isSale: false, type: "ซ้อม", img: "7.jpg", desc: "เสื้อซ้อมคุณภาพสูงจากสโมสรบีจี ปทุม ยูไนเต็ด" },
        { id: 6, name: "Muangthong Utd Home", price: 790, isSale: false, type: "เหย้า", img: "8.jpg", desc: "กิเลนผยอง คลาสสิกสีแดงดำ เนื้อผ้าทอพิเศษเบาสบาย" },
        { id: 7, name: "Chonburi FC Away", price: 450, oldPrice: 650, isSale: true, type: "เยือน", img: "9.jpg", desc: "ฉลามชล ดีไซน์สปอร์ตทันสมัย สีขาวตัดฟ้า" },
        { id: 8, name: "Special Training Limited", price: 390, isSale: false, type: "ซ้อม", img: "10.jpg", desc: "เสื้อซ้อมรุ่นลิมิเต็ด ผลิตจำนวนจำกัด หมดแล้วหมดเลย" }
    ];

    // --------------------------------------------------------
    // ตัวแปรระบบ
    // --------------------------------------------------------
    let currentUser = null;
    let cart = [];
    let discount = 0;
    let paymentMethod = 'COD';
    let qrTimerInterval;

    // --------------------------------------------------------
    // เริ่มต้นระบบ
    // --------------------------------------------------------
    function initStore() {
        renderProducts(products);
        loadUserFromStorage();
    }

    // --------------------------------------------------------
    // ระบบสมาชิก (Authentication)
    // --------------------------------------------------------
    function loadUserFromStorage() {
        const storedUser = localStorage.getItem('thanjai_user');
        if (storedUser) {
            currentUser = JSON.parse(storedUser);
            // โหลดตะกร้าของ user นี้
            cart = currentUser.cart || [];
            updateNavUI();
            updateCartBadge();
        }
    }

    function saveUserToStorage() {
        if(currentUser) {
            currentUser.cart = cart; // ผูกตะกร้าไว้กับไอดี
            localStorage.setItem('thanjai_user', JSON.stringify(currentUser));
        }
    }

    function openAuthModal(type) {
        toggleAuth(type);
        new bootstrap.Modal(document.getElementById('authModal')).show();
    }

    function toggleAuth(type) {
        document.getElementById('login-section').classList.toggle('d-none', type !== 'login');
        document.getElementById('register-section').classList.toggle('d-none', type !== 'register');
        document.getElementById('authModalTitle').innerText = type === 'login' ? 'เข้าสู่ระบบ' : 'สมัครสมาชิก';
    }

    function register() {
        const user = document.getElementById('reg-user').value.trim();
        const email = document.getElementById('reg-email').value.trim();
        const pass = document.getElementById('reg-pass').value.trim();

        if(!user || !email || !pass) return alert('กรุณากรอกข้อมูลให้ครบถ้วน');

        currentUser = {
            username: user, name: user, email: email, phone: '', password: pass,
            profilePic: 'https://api.dicebear.com/7.x/avataaars/svg?seed=' + user,
            cart: [], orders: []
        };
        
        saveUserToStorage();
        bootstrap.Modal.getInstance(document.getElementById('authModal')).hide();
        updateNavUI();
        alert('สมัครสมาชิกและเข้าสู่ระบบสำเร็จ! ช้อปปิ้งต่อได้เลยครับ');
    }

    function login() {
        const user = document.getElementById('login-user').value.trim();
        if(!user) return alert('กรุณากรอกชื่อผู้ใช้');

        // จำลองการดึงจาก DB โดยเช็ค LocalStorage ถ้ามีเอาของเก่ามา ถ้าไม่มีสร้างใหม่ให้เนียนๆ
        const storedUser = JSON.parse(localStorage.getItem('thanjai_user'));
        
        if(storedUser && storedUser.username === user) {
            currentUser = storedUser;
        } else {
            currentUser = {
                username: user, name: user, email: user+'@email.com', phone: '',
                profilePic: 'https://api.dicebear.com/7.x/avataaars/svg?seed=' + user,
                cart: [], orders: []
            };
        }
        cart = currentUser.cart;
        saveUserToStorage();
        bootstrap.Modal.getInstance(document.getElementById('authModal')).hide();
        updateNavUI();
        updateCartBadge();
    }

    function logoutUser() {
        if(confirm("ต้องการออกจากระบบใช่หรือไม่?")) {
            localStorage.removeItem('thanjai_user');
            currentUser = null;
            cart = [];
            updateNavUI();
            updateCartBadge();
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
            // ยังไม่เซฟจนกว่าจะกดบันทึก
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    function saveProfile() {
        if(!currentUser) return;
        currentUser.name = document.getElementById('set-name').value;
        currentUser.phone = document.getElementById('set-phone').value;
        currentUser.email = document.getElementById('set-email').value;
        currentUser.profilePic = document.getElementById('setting-profile-pic').src;
        
        const newPass = document.getElementById('set-password').value;
        if(newPass) currentUser.password = newPass;

        saveUserToStorage();
        updateNavUI();
        bootstrap.Modal.getInstance(document.getElementById('profileModal')).hide();
        alert('อัปเดตข้อมูลโปรไฟล์เรียบร้อยแล้ว');
    }

    // --------------------------------------------------------
    // ระบบแสดงสินค้า
    // --------------------------------------------------------
    function filterProducts(type) {
        // จัดการแบนเนอร์
        document.getElementById('hero-banner').classList.toggle('d-none', type === 'โปรโมชั่น');
        document.getElementById('promo-banner').classList.toggle('d-none', type !== 'โปรโมชั่น');

        // จัดการปุ่ม Filter
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
                            <button class="btn btn-buy btn-sm fw-bold w-100 mt-2" onclick="openProduct(${p.id})">
                                <i class="fa fa-shopping-bag text-warning me-1"></i> ดูรายละเอียด
                            </button>
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
    // ระบบตะกร้า & Checkout
    // --------------------------------------------------------
    function addToCart() {
        if(!currentUser) {
            bootstrap.Modal.getInstance(document.getElementById('productDetailModal')).hide();
            alert("กรุณาเข้าสู่ระบบก่อนทำการสั่งซื้อสินค้าครับ");
            return openAuthModal('login');
        }

        const id = parseInt(document.getElementById('detail-id').value);
        const size = document.getElementById('detail-size').value;
        const qty = parseInt(document.getElementById('detail-qty').value);
        const prod = products.find(p => p.id === id);

        const existing = cart.find(item => item.id === id && item.size === size);
        if(existing) existing.qty += qty;
        else cart.push({ ...prod, size, qty });

        saveUserToStorage();
        updateCartBadge();
        bootstrap.Modal.getInstance(document.getElementById('productDetailModal')).hide();
        
        setTimeout(() => openCart(), 400);
    }

    function updateCartBadge() {
        document.getElementById('cart-count').innerText = cart.reduce((sum, item) => sum + item.qty, 0);
    }

    function openCart() {
        if(!currentUser) return alert("กรุณาเข้าสู่ระบบก่อนครับ"), openAuthModal('login');
        if(cart.length === 0) return alert("ยังไม่มีสินค้าในตะกร้าครับ เลือกช้อปได้เลย!");

        // โหลดข้อมูลที่อยู่ลงฟอร์ม
        document.getElementById('ship-name').value = currentUser.name;
        document.getElementById('ship-phone').value = currentUser.phone;
        document.getElementById('ship-address').value = currentUser.address || '';
        document.getElementById('coupon-input').value = '';
        discount = 0;

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
        saveUserToStorage();
        updateCartBadge();
        if(cart.length === 0) {
            bootstrap.Modal.getInstance(document.getElementById('checkoutModal')).hide();
        } else {
            renderCartItems();
        }
    }

    function applyCoupon() {
        const code = document.getElementById('coupon-input').value.toUpperCase().trim();
        if(code === 'NEWBIE50') {
            discount = 50;
            alert("ใช้งานคูปองส่วนลด 50 บาทสำเร็จ!");
        } else {
            discount = 0;
            alert("รหัสคูปองไม่ถูกต้อง หรือหมดอายุแล้ว");
        }
        renderCartItems(); // Re-calculate
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

        if(!name || !phone || !address) return alert("กรุณากรอกข้อมูลการจัดส่งให้ครบถ้วนครับ");

        // เซฟที่อยู่ไว้ใช้ครั้งหน้า
        currentUser.address = address;
        currentUser.phone = phone;
        saveUserToStorage();

        bootstrap.Modal.getInstance(document.getElementById('checkoutModal')).hide();

        if(paymentMethod === 'QR') {
            setTimeout(() => {
                new bootstrap.Modal(document.getElementById('qrModal')).show();
                startQrTimer();
            }, 400);
        } else {
            finalizeOrder('รอยืนยันจากแอดมิน');
            alert("สั่งซื้อสำเร็จ (เก็บเงินปลายทาง)\nระบบกำลังรอแอดมินยืนยันคำสั่งซื้อครับ");
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
                    startQrTimer(); // ต่อเวลา
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
        alert("ได้รับยอดโอนเรียบร้อยแล้ว!\nระบบได้ยืนยันสถานะและเข้าสู่ขั้นตอนรอการจัดส่งครับ");
    }

    function qrPaymentCancel() {
        clearInterval(qrTimerInterval);
        bootstrap.Modal.getInstance(document.getElementById('qrModal')).hide();
        alert("ยกเลิกการสั่งซื้อแล้วครับ สินค้ายังอยู่ในตะกร้าของคุณ");
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
        currentUser.orders.unshift(newOrder); // ใส่ไว้บนสุด
        
        cart = []; // เคลียร์ตะกร้า
        discount = 0;
        saveUserToStorage();
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
                                <img src="${item.img}" width="50" height="50" class="rounded me-3 border" style="object-fit:cover">
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
                                ${o.status === 'รอการจัดส่ง' ? `<button class="btn btn-sm btn-outline-info mb-1" onclick="simDelivery(${idx})"><i class="fa fa-truck"></i> จำลองการจัดส่ง</button>` : ''}
                                ${o.status === 'จัดส่งสำเร็จ' && !o.isReviewed ? `
                                    <div class="mt-2 bg-light p-2 rounded text-start border">
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
                                ${o.isReviewed ? `<span class="badge bg-success"><i class="fa fa-check"></i> รีวิวแล้ว</span>` : ''}
                            </div>
                        </div>
                        
                        ${o.status === 'รอการจัดส่ง' ? `
                            <div class="alert alert-info py-2 mt-3 mb-0 small">
                                <strong><i class="fa fa-info-circle"></i> แจ้งจัดส่ง:</strong> สินค้าเตรียมจัดส่งผ่าน Flash Express คาดว่าจะถึงภายใน 2-3 วัน
                            </div>
                        ` : ''}
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

    // ฟังก์ชันจำลองการทำงานของแอดมินและขนส่ง (เพื่อให้เห็น Flow)
    function simAdminConfirm(idx) {
        currentUser.orders[idx].status = 'รอการจัดส่ง';
        saveUserToStorage();
        openOrderHistory(); // รีเฟรชหน้า
    }
    
    function simDelivery(idx) {
        currentUser.orders[idx].status = 'จัดส่งสำเร็จ';
        saveUserToStorage();
        openOrderHistory(); // รีเฟรชหน้า
    }

    // ระบบรีวิว
    function setStar(idx, rating) {
        const stars = document.querySelectorAll(`#stars-${idx} i`);
        stars.forEach((star, i) => {
            if(i < rating) star.classList.add('active');
            else star.classList.remove('active');
        });
    }

    function submitReview(idx) {
        const text = document.getElementById(`review-${idx}`).value;
        if(!text) return alert('กรุณาเขียนรีวิวด้วยครับ');
        
        currentUser.orders[idx].isReviewed = true;
        saveUserToStorage();
        alert('ขอบคุณสำหรับรีวิวครับ!');
        openOrderHistory(); // รีเฟรชหน้า
    }
</script>
</body>
</html>