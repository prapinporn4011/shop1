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
        
        /* สไตล์เพิ่มเติมสำหรับระบบตะกร้า */
        .cart-badge { font-size: 0.6rem; padding: 2px 5px; }
        #cart-items-list { max-height: 400px; overflow-y: auto; }

        /* สไตล์เพิ่มเติมสำหรับระบบชำระเงิน */
        .payment-box { border: 2px solid #ddd; border-radius: 8px; padding: 15px; cursor: pointer; transition: 0.2s; text-align: center; }
        .payment-box:hover { border-color: var(--accent); background: #fffdf5; }
        .payment-box.active { border-color: var(--accent); background: #fff8e1; font-weight: bold; }
        
        /* สไตล์สำหรับ Timeline สถานะสั่งซื้อ */
        .order-timeline { border-left: 3px solid #ddd; padding-left: 20px; margin-left: 10px; }
        .timeline-step { position: relative; margin-bottom: 20px; }
        .timeline-step::before { content: ''; position: absolute; left: -28px; top: 0; width: 14px; height: 14px; border-radius: 50%; background: #ddd; }
        .timeline-step.active::before { background: var(--accent); border: 3px solid #fff; box-shadow: 0 0 0 2px var(--accent); }
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
                <li class="nav-item"><a class="nav-link active" href="#" onclick="filterProducts('ทั้งหมด')">หน้าหลัก</a></li>
                <li class="nav-item"><a class="nav-link" href="#product-list">สินค้าทั้งหมด</a></li>
                <li class="nav-item"><a class="nav-link" href="#">โปรโมชั่น</a></li>
                <li class="nav-item"><a class="nav-link text-warning fw-bold" href="#" data-bs-toggle="modal" data-bs-target="#contactModal"><i class="fa fa-phone-alt me-1"></i>ติดต่อเรา</a></li>
            </ul>
            
            <div class="d-flex align-items-center gap-3">
                <div class="input-group input-group-sm d-none d-md-flex">
                    <input type="text" id="search-input" class="form-control" placeholder="ค้นหาชื่อทีม..." onkeyup="searchProducts()">
                </div>

                <div id="guest-zone">
                    <button class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#authModal">เข้าสู่ระบบ / สมัครสมาชิก</button>
                </div>

                <div id="user-zone" class="d-none">
                    <div class="dropdown text-white">
                        <span class="me-2 d-none d-md-inline">ยินดีต้อนรับ, <strong id="display-username">คุณธีรศิลป์</strong> <span class="member-badge">Member</span></span>
                        <a href="#" class="link-light dropdown-toggle" data-bs-toggle="dropdown">
                            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Felix" width="35" class="rounded-circle border">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profileModal"><i class="fa fa-user me-2"></i>โปรไฟล์ของฉัน</a></li>
                            <li><a class="dropdown-item" href="#" onclick="alert('กำลังพัฒนาระบบออเดอร์ย้อนหลัง')"><i class="fa fa-shopping-bag me-2"></i>ออเดอร์ของฉัน</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="#" onclick="logoutUser()"><i class="fa fa-sign-out-alt me-2"></i>ออกจากระบบ</a></li>
                        </ul>
                    </div>
                </div>
                
                <a href="#" class="text-white position-relative" data-bs-toggle="modal" data-bs-target="#cartModal">
                    <i class="fa fa-shopping-cart fs-5"></i>
                    <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-badge">0</span>
                </a>
            </div>
        </div>
    </div>
</nav>

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

    <div class="row g-4" id="store-display">
    </div>
</main>

<div class="modal fade" id="authModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <ul class="nav nav-pills w-100 justify-content-center" id="authTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active text-white" data-bs-toggle="pill" data-bs-target="#login-tab">เข้าสู่ระบบ</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link text-white" data-bs-toggle="pill" data-bs-target="#register-tab">สมัครสมาชิก</button>
                    </li>
                </ul>
                <button type="button" class="btn-close btn-close-white position-absolute end-0 me-3" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="login-tab">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Username</label>
                            <input type="text" id="login-user" class="form-control" placeholder="ชื่อผู้ใช้งาน">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Password</label>
                            <input type="password" id="login-pass" class="form-control" placeholder="รหัสผ่าน">
                        </div>
                        <button class="btn btn-warning w-100 fw-bold" onclick="processLogin()">เข้าสู่ระบบ</button>
                    </div>
                    <div class="tab-pane fade" id="register-tab">
                        <div class="mb-2">
                            <label class="form-label small fw-bold">ชื่อ Username</label>
                            <input type="text" id="reg-user" class="form-control" placeholder="ชื่อผู้ใช้งาน">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-bold">อีเมล</label>
                            <input type="email" id="reg-email" class="form-control" placeholder="email@example.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">รหัสผ่าน</label>
                            <input type="password" id="reg-pass" class="form-control" placeholder="รหัสผ่าน">
                        </div>
                        <button class="btn btn-dark w-100 fw-bold" onclick="processRegister()">ลงทะเบียน</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title fw-bold"><i class="fa fa-user-edit me-2"></i>แก้ไขข้อมูลส่วนตัว</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label small fw-bold">ชื่อ-นามสกุล</label>
                    <input type="text" id="edit-fullname" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">เบอร์โทรศัพท์</label>
                    <input type="text" id="edit-phone" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">ที่อยู่จัดส่งเริ่มต้น</label>
                    <textarea id="edit-address" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-dark" onclick="saveProfile()">บันทึกข้อมูล</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="profileModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content text-center py-4">
            <div class="modal-body">
                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Felix" width="100" class="rounded-circle border border-warning shadow-sm mb-3">
                <h4 class="fw-bold mb-1" id="profile-name">ชื่อลูกค้า</h4>
                <p class="text-muted small mb-2" id="profile-email">อีเมล: -</p>
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill shadow-sm">Member</span>
                <hr class="my-4">
                <button class="btn btn-outline-dark btn-sm w-100 mb-2" onclick="openEditProfile()">แก้ไขข้อมูลส่วนตัว</button>
                <button class="btn btn-danger btn-sm w-100" onclick="logoutUser()">ออกจากระบบ</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="orderStatusModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold"><i class="fa fa-box me-2"></i>สถานะคำสั่งซื้อของคุณ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6 class="fw-bold text-center text-success mb-4" id="order-success-msg">สั่งซื้อสำเร็จ!</h6>
                <div class="order-timeline" id="order-timeline-box">
                    <div class="timeline-step active" id="step-1">
                        <h6 class="fw-bold mb-0">รอยืนยันจากแอดมิน</h6>
                        <small class="text-muted">ระบบได้รับคำสั่งซื้อของคุณแล้ว</small>
                    </div>
                    <div class="timeline-step" id="step-2">
                        <h6 class="fw-bold mb-0">แอดมินยืนยันแล้ว / เตรียมจัดส่ง</h6>
                        <small class="text-muted d-none" id="delivery-details">
                            <strong class="text-danger" id="delivery-price">ยอดชำระ: ฿0</strong><br>
                            คาดว่าจะได้รับของภายใน: <strong>2-3 วันทำการ</strong><br>
                            เส้นทางจัดส่ง: <span id="delivery-route">กรุงเทพฯ -> ปลายทาง</span>
                        </small>
                    </div>
                </div>
                
                <div class="text-center mt-4 pt-3 border-top">
                    <small class="text-muted d-block mb-2">(ปุ่มสำหรับทดสอบระบบ)</small>
                    <button class="btn btn-outline-primary btn-sm" id="btn-mock-admin" onclick="simulateAdminConfirm()">จำลอง: แอดมินกดยืนยันออเดอร์</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="qrTimerModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content text-center">
            <div class="modal-header bg-primary text-white justify-content-center">
                <h5 class="modal-title fw-bold"><i class="fa fa-qrcode me-2"></i>สแกนเพื่อชำระเงิน</h5>
            </div>
            <div class="modal-body py-4">
                <h3 class="text-danger fw-bold mb-3" id="qr-pay-amount">฿0</h3>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=promptpay12345" alt="QR Code" class="img-thumbnail mb-3">
                <p class="small text-muted mb-1">กรุณาชำระเงินภายในเวลาที่กำหนด</p>
                <h2 class="fw-bold text-primary" id="qr-timer-display">05:00</h2>
                
                <div class="mt-4 border-top pt-3">
                    <small class="text-muted d-block mb-2">(ปุ่มสำหรับทดสอบระบบ)</small>
                    <button class="btn btn-success btn-sm w-100" onclick="simulateQRPaymentSuccess()">จำลอง: ลูกค้าโอนเงินสำเร็จ</button>
                    <button class="btn btn-outline-danger btn-sm w-100 mt-2" onclick="cancelQRPayment()">ยกเลิกการชำระเงิน</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="contactModal" tabindex="-1">... (ละไว้เพื่อความกระชับ โค้ดด้านล่างนี้มีครบ) ...</div>

<div class="modal fade" id="contactModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold"><i class="fa fa-headset me-2"></i>ช่องทางการติดต่อ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <h5 class="fw-bold mb-3">ThanJai Shop ยินดีให้บริการครับ!</h5>
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
                        <img id="detail-img" src="" class="img-fluid rounded shadow-sm" style="max-height: 350px; object-fit: contain;">
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
                                    <option value="S">S (อก 36")</option>
                                    <option value="M" selected>M (อก 38")</option>
                                    <option value="L">L (อก 40")</option>
                                    <option value="XL">XL (อก 42")</option>
                                    <option value="2XL">2XL (อก 44")</option>
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
            <div class="modal-footer border-0 pt-0">
                <input type="hidden" id="detail-id">
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" id="btn-confirm-action" class="btn btn-warning fw-bold px-4" onclick="confirmProductSelection()">
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
                <h5 class="modal-title fw-bold"><i class="fa fa-shopping-cart me-2"></i>ตะกร้าสินค้าของคุณ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="cart-items-list"></div>
                <hr>
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold">ราคารวมทั้งสิ้น:</h5>
                    <h4 class="text-danger fw-bold" id="cart-total">฿0</h4>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ช้อปต่อ</button>
                <button type="button" class="btn btn-warning fw-bold" onclick="openCheckoutModal()">ยืนยันรายการสั่งซื้อ</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="checkoutModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold"><i class="fa fa-check-circle me-2"></i>ยืนยันคำสั่งซื้อและชำระเงิน</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6 class="fw-bold border-bottom pb-2">ข้อมูลการจัดส่ง</h6>
                        <div class="mb-2">
                            <label class="form-label small fw-bold">ชื่อ-นามสกุล</label>
                            <input type="text" id="ship-name" class="form-control" placeholder="ระบุชื่อผู้รับ">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-bold">เบอร์ติดต่อ</label>
                            <input type="text" id="ship-phone" class="form-control" placeholder="08X-XXX-XXXX">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-bold">ที่อยู่จัดส่ง</label>
                            <textarea id="ship-address" class="form-control" rows="3" placeholder="บ้านเลขที่, ถนน, ตำบล, อำเภอ, จังหวัด, รหัสไปรษณีย์"></textarea>
                        </div>
                    </div>

                    <div class="col-md-6 bg-light p-3 rounded">
                        <h6 class="fw-bold border-bottom pb-2">สรุปรายการสินค้า</h6>
                        <div id="checkout-summary-list" style="max-height: 150px; overflow-y: auto; font-size: 0.9rem;" class="mb-3">
                        </div>
                        <div class="d-flex justify-content-between fw-bold mb-3">
                            <span>ยอดสุทธิที่ต้องชำระ:</span>
                            <span class="text-danger fs-5" id="checkout-total-price">฿0</span>
                        </div>

                        <h6 class="fw-bold border-bottom pb-2">เลือกวิธีชำระเงิน</h6>
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

                        <div id="qr-code-section" class="text-center mt-3 d-none">
                            <p class="small text-muted mt-2">เมื่อกดยืนยัน ระบบจะสร้าง QR Code พร้อมจับเวลา 5 นาที</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-target="#cartModal" data-bs-toggle="modal">กลับไปแก้ไขตะกร้า</button>
                <button type="button" class="btn btn-success fw-bold px-4" onclick="processOrder()">
                    <i class="fa fa-check"></i> ยืนยันคำสั่งซื้อ
                </button>
            </div>
        </div>
    </div>
</div>

<footer class="bg-dark text-white py-5 mt-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <h5 class="fw-bold text-warning">ThanJai Shop</h5>
                <p class="text-muted small">ร้านขายอุปกรณ์กีฬาและเสื้อบอลอันดับ 1 มั่นใจ ได้ของไว ถึงมือแน่นอน ประสบการณ์กว่า 10 ปี</p>
            </div>
            <div class="col-md-4">
                <h5>ติดต่อเรา</h5>
                <p class="text-muted mb-1 small"><i class="fa fa-phone me-2"></i> 081-XXX-XXXX</p>
                <p class="text-muted small"><i class="fab fa-line me-2"></i> @thanjai_shop</p>
            </div>
            <div class="col-md-4">
                <h5>ติดตามข่าวสาร</h5>
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" placeholder="อีเมลของคุณ">
                    <button class="btn btn-warning btn-sm">สมัคร</button>
                </div>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const originalProducts = [
        { id: 2, name: "Uthai Thani Home 2024", price: 790, type: "เหย้า", img: "2.jpg", desc: "เสื้อแข่งทีมอุทัยธานี เอฟซี ฤดูกาลล่าสุด ผ้าเกรดพรีเมียม" },
        { id: 4, name: "Buriram United Home", price: 690, type: "เหย้า", img: "4.jpg", desc: "เสื้อสายฟ้า ปราสาทสายฟ้า คุณภาพอันดับ 1 ของเมืองไทย" },
        { id: 5, name: "Thailand Edition", price: 590, type: "ซ้อม", img: "5.jpg", desc: "เสื้อเชียร์ทีมชาติไทย สวมใส่สบาย ระบายอากาศดี" },
        { id: 6, name: "Port FC Away Kit", price: 750, type: "เยือน", img: "6.jpg", desc: "สิงห์เจ้าท่า สีเยือนเรียบหรู พร้อมดีไซน์ทันสมัย" },
        { id: 7, name: "Training BGPU", price: 490, type: "ซ้อม", img: "7.jpg", desc: "เสื้อซ้อมคุณภาพสูงจากสโมสรบีจี ปทุม" },
        { id: 8, name: "Muangthong Utd Home", price: 790, type: "เหย้า", img: "8.jpg", desc: "กิเลนผยอง คลาสสิกสีแดงดำ ดีไซน์ทรงคุณค่า" },
        { id: 9, name: "Chonburi FC Away", price: 650, type: "เยือน", img: "9.jpg", desc: "ฉลามชล ดีไซน์สปอร์ตทันสมัย สีเยือนโดดเด่น" },
        { id: 10, name: "Special Training", price: 390, type: "ซ้อม", img: "10.jpg", desc: "เสื้อซ้อมรวมสโมสร ลิมิเต็ด อิดิชั่น" }
    ];

    let cart = [];
    let currentAction = 'cart';
    let selectedPayment = 'COD';
    let currentCheckoutTotal = 0;

    // ระบบ User & LocalStorage
    let usersDB = JSON.parse(localStorage.getItem('thanjai_users')) || [];
    let loggedInUser = JSON.parse(localStorage.getItem('thanjai_active_user')) || null;

    function initStore() {
        renderProducts(originalProducts);
        updateUserUI();
    }

    function updateUserUI() {
        if (loggedInUser) {
            document.getElementById('guest-zone').classList.add('d-none');
            document.getElementById('user-zone').classList.remove('d-none');
            
            document.getElementById('display-username').innerText = loggedInUser.user;
            document.getElementById('profile-name').innerText = loggedInUser.fullname || loggedInUser.user;
            document.getElementById('profile-email').innerText = `อีเมล: ${loggedInUser.email}`;
            
            // อัปเดตข้อมูลในช่อง Checkout อัตโนมัติ
            document.getElementById('ship-name').value = loggedInUser.fullname || '';
            document.getElementById('ship-phone').value = loggedInUser.phone || '';
            document.getElementById('ship-address').value = loggedInUser.address || '';
        } else {
            document.getElementById('guest-zone').classList.remove('d-none');
            document.getElementById('user-zone').classList.add('d-none');
        }
    }

    function processRegister() {
        const user = document.getElementById('reg-user').value.trim();
        const email = document.getElementById('reg-email').value.trim();
        const pass = document.getElementById('reg-pass').value.trim();
        
        if(!user || !email || !pass) return alert("กรุณากรอกข้อมูลให้ครบถ้วน");
        if(usersDB.find(u => u.user === user)) return alert("ชื่อผู้ใช้งานนี้มีในระบบแล้ว");

        const newUser = { user, email, pass, fullname: '', phone: '', address: '' };
        usersDB.push(newUser);
        localStorage.setItem('thanjai_users', JSON.stringify(usersDB));
        
        alert("สมัครสมาชิกสำเร็จ! กำลังเข้าสู่ระบบ...");
        loginAs(newUser);
    }

    function processLogin() {
        const user = document.getElementById('login-user').value.trim();
        const pass = document.getElementById('login-pass').value.trim();
        const found = usersDB.find(u => u.user === user && u.pass === pass);
        
        if(found) {
            loginAs(found);
        } else {
            alert("ชื่อผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง");
        }
    }

    function loginAs(userObj) {
        loggedInUser = userObj;
        localStorage.setItem('thanjai_active_user', JSON.stringify(loggedInUser));
        bootstrap.Modal.getInstance(document.getElementById('authModal')).hide();
        updateUserUI();
    }

    function logoutUser() {
        if(confirm("คุณต้องการออกจากระบบใช่หรือไม่?")) {
            loggedInUser = null;
            localStorage.removeItem('thanjai_active_user');
            updateUserUI();
            
            const profileModalEl = document.getElementById('profileModal');
            if(profileModalEl.classList.contains('show')){
                bootstrap.Modal.getInstance(profileModalEl).hide();
            }
        }
    }

    // ฟังก์ชันแก้ไขโปรไฟล์
    function openEditProfile() {
        bootstrap.Modal.getInstance(document.getElementById('profileModal')).hide();
        document.getElementById('edit-fullname').value = loggedInUser.fullname || '';
        document.getElementById('edit-phone').value = loggedInUser.phone || '';
        document.getElementById('edit-address').value = loggedInUser.address || '';
        new bootstrap.Modal(document.getElementById('editProfileModal')).show();
    }

    function saveProfile() {
        loggedInUser.fullname = document.getElementById('edit-fullname').value.trim();
        loggedInUser.phone = document.getElementById('edit-phone').value.trim();
        loggedInUser.address = document.getElementById('edit-address').value.trim();
        
        // อัปเดตใน DB หลัก
        const index = usersDB.findIndex(u => u.user === loggedInUser.user);
        if(index > -1) usersDB[index] = loggedInUser;
        
        localStorage.setItem('thanjai_users', JSON.stringify(usersDB));
        localStorage.setItem('thanjai_active_user', JSON.stringify(loggedInUser));
        
        bootstrap.Modal.getInstance(document.getElementById('editProfileModal')).hide();
        updateUserUI();
        alert("บันทึกข้อมูลเรียบร้อยแล้ว");
    }

    // ส่วนของสินค้า (คงเดิม)
    function renderProducts(items) {
        const container = document.getElementById('store-display');
        container.innerHTML = items.map(p => `
            <div class="col-6 col-md-3 mb-4">
                <div class="card product-card shadow-sm border-0">
                    <img src="${p.img}" class="card-img-top" alt="${p.name}">
                    <div class="card-body">
                        <span class="badge bg-secondary mb-2" style="font-size: 10px;">${p.type}</span>
                        <h6 class="card-title fw-bold text-dark mb-1" style="font-size: 14px;">${p.name}</h6>
                        <div class="mt-3">
                            <span class="text-danger fw-bold fs-5 d-block mb-2">฿${p.price.toLocaleString()}</span>
                            <div class="d-flex gap-1">
                                <button class="btn btn-outline-dark btn-sm flex-fill" onclick="openProductDetail(${p.id}, 'cart')" title="เพิ่มลงตะกร้า">
                                    <i class="fa fa-cart-plus"></i>
                                </button>
                                <button class="btn btn-buy btn-sm flex-fill fw-bold" onclick="openProductDetail(${p.id}, 'buy')">
                                    <i class="fa fa-bolt text-warning"></i> ซื้อเลย
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    function searchProducts() {
        const term = document.getElementById('search-input').value.toLowerCase();
        renderProducts(originalProducts.filter(p => p.name.toLowerCase().includes(term)));
    }

    function filterProducts(type) {
        renderProducts(type === 'ทั้งหมด' ? originalProducts : originalProducts.filter(p => p.type === type));
    }

    function openProductDetail(id, action) {
        currentAction = action;
        const p = originalProducts.find(x => x.id === id);
        
        document.getElementById('detail-id').value = p.id;
        document.getElementById('detail-name').innerText = p.name;
        document.getElementById('detail-price').innerText = '฿' + p.price.toLocaleString();
        document.getElementById('detail-desc').innerText = p.desc;
        document.getElementById('detail-type').innerText = p.type;
        document.getElementById('detail-img').src = p.img; 
        document.getElementById('detail-qty').value = 1;
        document.getElementById('detail-size').selectedIndex = 1; 

        const btnConfirm = document.getElementById('btn-confirm-action');
        if(action === 'buy') {
            btnConfirm.innerHTML = '<i class="fa fa-bolt"></i> สั่งซื้อทันที';
            btnConfirm.className = 'btn btn-success fw-bold px-4';
        } else {
            btnConfirm.innerHTML = '<i class="fa fa-cart-plus"></i> เพิ่มลงตะกร้า';
            btnConfirm.className = 'btn btn-warning fw-bold px-4';
        }
        new bootstrap.Modal(document.getElementById('productDetailModal')).show();
    }

    function confirmProductSelection() {
        const id = parseInt(document.getElementById('detail-id').value);
        const size = document.getElementById('detail-size').value;
        const qty = parseInt(document.getElementById('detail-qty').value);
        const product = originalProducts.find(p => p.id === id);

        const existingItemIndex = cart.findIndex(item => item.id === id && item.size === size);
        if (existingItemIndex > -1) cart[existingItemIndex].qty += qty; 
        else cart.push({ ...product, size: size, qty: qty }); 

        updateCartUI();
        bootstrap.Modal.getInstance(document.getElementById('productDetailModal')).hide();

        if(currentAction === 'buy') {
            setTimeout(() => new bootstrap.Modal(document.getElementById('cartModal')).show(), 400); 
        }
    }

    function updateCartUI() {
        document.getElementById('cart-count').innerText = cart.reduce((sum, item) => sum + item.qty, 0);
        const list = document.getElementById('cart-items-list');
        const totalElem = document.getElementById('cart-total');
        
        if (cart.length === 0) {
            list.innerHTML = '<p class="text-center text-muted my-4">ไม่มีสินค้าในตะกร้า</p>';
            totalElem.innerText = '฿0';
            return;
        }

        let totalAmount = 0;
        list.innerHTML = cart.map((item, index) => {
            const itemTotal = item.price * item.qty;
            totalAmount += itemTotal;
            return `
                <div class="d-flex align-items-center mb-3 p-2 border-bottom">
                    <img src="${item.img}" width="60" height="60" class="rounded me-3 border" style="object-fit: cover;">
                    <div class="flex-grow-1">
                        <h6 class="mb-0 fw-bold">${item.name}</h6>
                        <small class="text-muted">ไซส์: <strong>${item.size}</strong> | จำนวน: <strong>${item.qty}</strong> ตัว</small><br>
                        <small class="text-danger">฿${item.price.toLocaleString()} / ชิ้น</small>
                    </div>
                    <div class="fw-bold me-3 text-end">฿${itemTotal.toLocaleString()}</div>
                    <button class="btn btn-sm text-danger" onclick="removeFromCart(${index})"><i class="fa fa-trash"></i></button>
                </div>
            `;
        }).join('');
        totalElem.innerText = `฿${totalAmount.toLocaleString()}`;
    }

    function removeFromCart(index) {
        cart.splice(index, 1);
        updateCartUI();
    }

    function openCheckoutModal() {
        if (cart.length === 0) return alert("กรุณาเลือกสินค้าลงตะกร้าก่อนครับ");
        
        // บังคับให้เป็นสมาชิกก่อนถึงจะสั่งซื้อได้
        if (!loggedInUser) {
            const cartModalEl = document.getElementById('cartModal');
            if(cartModalEl.classList.contains('show')) bootstrap.Modal.getInstance(cartModalEl).hide();
            
            alert("กรุณาเข้าสู่ระบบหรือสมัครสมาชิกก่อนทำการสั่งซื้อครับ");
            new bootstrap.Modal(document.getElementById('authModal')).show();
            return;
        }

        bootstrap.Modal.getInstance(document.getElementById('cartModal')).hide();

        let totalAmount = 0;
        document.getElementById('checkout-summary-list').innerHTML = cart.map(item => {
            const itemTotal = item.price * item.qty;
            totalAmount += itemTotal;
            return `<div class="d-flex justify-content-between mb-2 pb-1 border-bottom border-light"><span>${item.name} (${item.size}) x ${item.qty}</span><span class="fw-bold">฿${itemTotal.toLocaleString()}</span></div>`;
        }).join('');
        
        currentCheckoutTotal = totalAmount;
        document.getElementById('checkout-total-price').innerText = `฿${totalAmount.toLocaleString()}`;

        setTimeout(() => new bootstrap.Modal(document.getElementById('checkoutModal')).show(), 400);
    }

    function selectPaymentMethod(method) {
        selectedPayment = method;
        document.getElementById('pay-cod').classList.remove('active');
        document.getElementById('pay-qr').classList.remove('active');
        
        if(method === 'COD') {
            document.getElementById('pay-cod').classList.add('active');
            document.getElementById('qr-code-section').classList.add('d-none');
        } else {
            document.getElementById('pay-qr').classList.add('active');
            document.getElementById('qr-code-section').classList.remove('d-none');
        }
    }

    // จัดการการสั่งซื้อตามวิธีชำระเงิน
    let qrTimerInterval;
    let qrTimeLeft = 300; // 5 นาที = 300 วินาที

    function processOrder() {
        const name = document.getElementById('ship-name').value.trim();
        const address = document.getElementById('ship-address').value.trim();

        if (!name || !address) return alert("กรุณากรอกข้อมูลการจัดส่งให้ครบถ้วนครับ");

        bootstrap.Modal.getInstance(document.getElementById('checkoutModal')).hide();

        if(selectedPayment === 'COD') {
            // เปิดหน้าสถานะ รอแอดมินยืนยัน
            document.getElementById('step-2').classList.remove('active');
            document.getElementById('delivery-details').classList.add('d-none');
            document.getElementById('order-success-msg').innerText = "สั่งซื้อสำเร็จ! รอการตรวจสอบ";
            document.getElementById('btn-mock-admin').classList.remove('d-none');
            
            setTimeout(() => new bootstrap.Modal(document.getElementById('orderStatusModal')).show(), 400);
            clearCart();
        } else {
            // ระบบ QR Code
            document.getElementById('qr-pay-amount').innerText = `฿${currentCheckoutTotal.toLocaleString()}`;
            setTimeout(() => {
                new bootstrap.Modal(document.getElementById('qrTimerModal')).show();
                startQRTimer();
            }, 400);
        }
    }

    // ฟังก์ชันจับเวลา QR Code
    function startQRTimer() {
        qrTimeLeft = 300; 
        clearInterval(qrTimerInterval);
        
        const display = document.getElementById('qr-timer-display');
        qrTimerInterval = setInterval(() => {
            qrTimeLeft--;
            const m = Math.floor(qrTimeLeft / 60).toString().padStart(2, '0');
            const s = (qrTimeLeft % 60).toString().padStart(2, '0');
            display.innerText = `${m}:${s}`;

            // แจ้งเตือนเมื่อเหลือ 1 นาที
            if (qrTimeLeft === 60) {
                if(!confirm("เวลาใกล้หมดแล้ว คุณต้องการดำเนินการชำระเงินต่อหรือไม่? (หากกด Cancel ระบบจะยกเลิกออเดอร์)")) {
                    cancelQRPayment();
                }
            }

            if (qrTimeLeft <= 0) {
                clearInterval(qrTimerInterval);
                alert("หมดเวลาทำรายการ ระบบได้ยกเลิกคำสั่งซื้ออัตโนมัติ");
                bootstrap.Modal.getInstance(document.getElementById('qrTimerModal')).hide();
            }
        }, 1000);
    }

    function cancelQRPayment() {
        clearInterval(qrTimerInterval);
        bootstrap.Modal.getInstance(document.getElementById('qrTimerModal')).hide();
        alert("คุณได้ยกเลิกการชำระเงิน");
    }

    // จำลองลูกค้าโอนเงินสำเร็จ
    function simulateQRPaymentSuccess() {
        clearInterval(qrTimerInterval);
        bootstrap.Modal.getInstance(document.getElementById('qrTimerModal')).hide();
        
        // ข้ามไปสถานะรอจัดส่งเลย (สมมติว่าเงินเข้าแล้ว แอดมินรับทราบทันทีผ่านระบบ Auto)
        document.getElementById('step-2').classList.add('active');
        document.getElementById('delivery-details').classList.remove('d-none');
        document.getElementById('delivery-price').innerText = "สถานะ: ชำระเงินแล้ว";
        document.getElementById('order-success-msg').innerText = "ชำระเงินสำเร็จ! ขอบคุณครับ";
        document.getElementById('btn-mock-admin').classList.add('d-none');
        
        setTimeout(() => new bootstrap.Modal(document.getElementById('orderStatusModal')).show(), 400);
        clearCart();
    }

    // จำลองแอดมินกดยืนยันออเดอร์ (สำหรับ COD)
    function simulateAdminConfirm() {
        document.getElementById('step-2').classList.add('active');
        document.getElementById('delivery-details').classList.remove('d-none');
        document.getElementById('delivery-price').innerText = `ยอดต้องชำระปลายทาง: ฿${currentCheckoutTotal.toLocaleString()}`;
        document.getElementById('btn-mock-admin').classList.add('d-none'); // ซ่อนปุ่มจำลองทิ้งไป
    }

    function clearCart() {
        cart = [];
        updateCartUI();
        selectPaymentMethod('COD');
    }
</script>
</body>
</html>