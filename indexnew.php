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
                    <a href="#" class="btn btn-outline-light btn-sm">เข้าสู่ระบบ</a>
                </div>

                <div id="user-zone" class="d-none">
                    <div class="dropdown text-white">
                        <span class="me-2 d-none d-md-inline">ยินดีต้อนรับ, <strong>คุณธีรศิลป์</strong> <span class="member-badge">Gold Member</span></span>
                        <a href="#" class="link-light dropdown-toggle" data-bs-toggle="dropdown">
                            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Felix" width="35" class="rounded-circle border">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profileModal"><i class="fa fa-user me-2"></i>โปรไฟล์ของฉัน</a></li>
                            <li><a class="dropdown-item" href="b.php"><i class="fa fa-shopping-bag me-2"></i>ออเดอร์ของฉัน</a></li>
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

<div class="modal fade" id="profileModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content text-center py-4">
            <div class="modal-body">
                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Felix" width="100" class="rounded-circle border border-warning shadow-sm mb-3">
                <h4 class="fw-bold mb-1">คุณธีรศิลป์ แดงดา</h4>
                <p class="text-muted small mb-2">อีเมล: teerasil@example.com</p>
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill shadow-sm">Gold Member (สะสม 1,250 แต้ม)</span>
                <hr class="my-4">
                <button class="btn btn-outline-dark btn-sm w-100 mb-2" onclick="alert('ฟังก์ชันแก้ไขโปรไฟล์กำลังพัฒนา...')">แก้ไขข้อมูลส่วนตัว</button>
                <button class="btn btn-danger btn-sm w-100" onclick="logoutUser()">ออกจากระบบ</button>
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
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=081xxxxxxx" alt="QR Code" class="img-thumbnail">
                            <p class="small text-muted mt-2">สแกนเพื่อชำระเงิน แล้วกดปุ่มยืนยันด้านล่าง</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-target="#cartModal" data-bs-toggle="modal">กลับไปแก้ไขตะกร้า</button>
                <button type="button" class="btn btn-success fw-bold px-4" onclick="processOrder()">
                    <i class="fa fa-check"></i> สั่งซื้อเลย
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
    // ข้อมูลสินค้า ดึงไฟล์รูปภาพเสื้อกีฬาของคุณ (2.jpg - 10.jpg) จากโฟลเดอร์โดยตรง (คงไว้เหมือนเดิมเป๊ะ)
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
    let isLoggedIn = true; // จำลองสถานะ
    let currentAction = 'cart';
    let selectedPayment = 'COD'; // ค่าเริ่มต้นชำระเงินปลายทาง

    function initStore() {
        renderProducts(originalProducts);
        
        if (isLoggedIn) {
            document.getElementById('guest-zone').classList.add('d-none');
            document.getElementById('user-zone').classList.remove('d-none');
        }
    }

    // ฟังก์ชันออกจากระบบ
    function logoutUser() {
        if(confirm("คุณต้องการออกจากระบบใช่หรือไม่?")) {
            // ซ่อนเมนูผู้ใช้ แสดงปุ่มเข้าสู่ระบบแทน (หรือจะใช้ location.reload() ก็ได้)
            isLoggedIn = false;
            document.getElementById('user-zone').classList.add('d-none');
            document.getElementById('guest-zone').classList.remove('d-none');
            
            // ปิด Modal โปรไฟล์ถ้าเปิดอยู่
            const profileModalEl = document.getElementById('profileModal');
            if(profileModalEl.classList.contains('show')){
                bootstrap.Modal.getInstance(profileModalEl).hide();
            }
        }
    }

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
        const filtered = originalProducts.filter(p => p.name.toLowerCase().includes(term));
        renderProducts(filtered);
    }

    function filterProducts(type) {
        const filtered = type === 'ทั้งหมด' 
            ? originalProducts 
            : originalProducts.filter(p => p.type === type);
        renderProducts(filtered);
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

        const modal = new bootstrap.Modal(document.getElementById('productDetailModal'));
        modal.show();
    }

    function confirmProductSelection() {
        const id = parseInt(document.getElementById('detail-id').value);
        const size = document.getElementById('detail-size').value;
        const qty = parseInt(document.getElementById('detail-qty').value);
        const product = originalProducts.find(p => p.id === id);

        const existingItemIndex = cart.findIndex(item => item.id === id && item.size === size);
        
        if (existingItemIndex > -1) {
            cart[existingItemIndex].qty += qty; 
        } else {
            cart.push({ ...product, size: size, qty: qty }); 
        }

        updateCartUI();

        const detailModalEl = document.getElementById('productDetailModal');
        const detailModal = bootstrap.Modal.getInstance(detailModalEl);
        detailModal.hide();

        if(currentAction === 'buy') {
            setTimeout(() => {
                const cartModal = new bootstrap.Modal(document.getElementById('cartModal'));
                cartModal.show();
            }, 400); 
        }
    }

    function updateCartUI() {
        const totalItems = cart.reduce((sum, item) => sum + item.qty, 0);
        document.getElementById('cart-count').innerText = totalItems;
        
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
                    <div class="fw-bold me-3 text-end">
                        ฿${itemTotal.toLocaleString()}
                    </div>
                    <button class="btn btn-sm text-danger" onclick="removeFromCart(${index})">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            `;
        }).join('');
        
        totalElem.innerText = `฿${totalAmount.toLocaleString()}`;
    }

    function removeFromCart(index) {
        cart.splice(index, 1);
        updateCartUI();
    }

    // ฟังก์ชันเปิดหน้าชำระเงิน / Checkout
    function openCheckoutModal() {
        if (cart.length === 0) {
            alert("กรุณาเลือกสินค้าลงตะกร้าก่อนครับ");
            return;
        }

        // ปิดตะกร้าเดิมก่อน
        const cartModalEl = document.getElementById('cartModal');
        bootstrap.Modal.getInstance(cartModalEl).hide();

        // นำข้อมูลตะกร้าไปใส่ในรายการสรุปยอด
        const summaryList = document.getElementById('checkout-summary-list');
        let totalAmount = 0;
        
        summaryList.innerHTML = cart.map(item => {
            const itemTotal = item.price * item.qty;
            totalAmount += itemTotal;
            return `
                <div class="d-flex justify-content-between mb-2 pb-1 border-bottom border-light">
                    <span>${item.name} (${item.size}) x ${item.qty}</span>
                    <span class="fw-bold">฿${itemTotal.toLocaleString()}</span>
                </div>
            `;
        }).join('');

        document.getElementById('checkout-total-price').innerText = `฿${totalAmount.toLocaleString()}`;

        // เปิด Modal ชำระเงิน
        setTimeout(() => {
            const checkoutModal = new bootstrap.Modal(document.getElementById('checkoutModal'));
            checkoutModal.show();
        }, 400);
    }

    // ฟังก์ชันเปลี่ยนวิธีชำระเงิน
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

    // ฟังก์ชันกดยืนยันการสั่งซื้อขั้นสุดท้าย
    function processOrder() {
        const name = document.getElementById('ship-name').value.trim();
        const phone = document.getElementById('ship-phone').value.trim();
        const address = document.getElementById('ship-address').value.trim();

        if (!name || !phone || !address) {
            alert("กรุณากรอกข้อมูลการจัดส่งให้ครบถ้วนครับ");
            return;
        }

        // หากครบถ้วน ให้จำลองว่าสั่งซื้อสำเร็จ
        alert(`สั่งซื้อสำเร็จ! ขอบคุณที่อุดหนุน ThanJai Shop ครับ\n\nจัดส่งถึง: ${name}\nวิธีชำระเงิน: ${selectedPayment === 'COD' ? 'เก็บเงินปลายทาง' : 'QR PromptPay'}`);
        
        // ล้างตะกร้าและเคลียร์ฟอร์ม
        cart = [];
        updateCartUI();
        document.getElementById('ship-name').value = '';
        document.getElementById('ship-phone').value = '';
        document.getElementById('ship-address').value = '';
        selectPaymentMethod('COD'); // กลับไปตั้งค่าเริ่มต้น
        
        // ปิด Modal
        const checkoutModalEl = document.getElementById('checkoutModal');
        bootstrap.Modal.getInstance(checkoutModalEl).hide();
    }
</script>
</body>
</html>