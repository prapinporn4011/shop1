<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ThanJai Shop - The Ultimate Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #1a1a1a; --accent: #ffae00; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f8f9fa; }
        .navbar { background: var(--primary) !important; }
        .hero-banner { 
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1510566337590-2fc1f21d0faa?q=80&w=2070&auto=format&fit=crop') center/cover;
            height: 350px; display: flex; align-items: center; color: white; text-align: center;
        }
        .product-card { border: none; border-radius: 12px; transition: 0.3s; overflow: hidden; height: 100%; position: relative; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .product-img { height: 220px; object-fit: cover; width: 100%; cursor: pointer; }
        .btn-cart { background: #e9ecef; color: #333; font-weight: bold; border: none; }
        .btn-cart:hover { background: #dde0e3; }
        .btn-buy-now { background: var(--accent); color: var(--primary); font-weight: bold; border: none; }
        .btn-buy-now:hover { background: #e69d00; }
        .sale-badge { position: absolute; top: 10px; right: 10px; background: #dc3545; color: white; padding: 5px 10px; border-radius: 5px; font-weight: bold; font-size: 12px; z-index: 10; }
        .toast-container { z-index: 1060; }
        .payment-box { border: 2px solid #ddd; border-radius: 8px; padding: 15px; cursor: pointer; text-align: center; }
        .payment-box.active { border-color: var(--accent); background: #fffdf5; font-weight: bold; }
    </style>
</head>
<body onload="initApp()">

<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="appToast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body fw-bold" id="toastMsg">ข้อความ</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#" onclick="filterCat('ทั้งหมด')">ThanJai <span class="text-warning">Shop</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="#" onclick="filterCat('ทั้งหมด')">หน้าหลัก</a></li>
                <li class="nav-item"><a class="nav-link text-danger fw-bold" href="#" onclick="filterCat('โปรโมชั่น')">โปรโมชั่น</a></li>
            </ul>
            <div class="d-flex align-items-center gap-3">
                <input type="text" id="searchInput" class="form-control form-control-sm d-none d-md-block" placeholder="ค้นหาสินค้า..." onkeyup="searchProduct()" style="width: 200px;">
                
                <div id="guestNav"><button class="btn btn-outline-warning btn-sm fw-bold" onclick="openAuth('login')">เข้าสู่ระบบ / สมัครสมาชิก</button></div>
                
                <div id="userNav" class="d-none dropdown">
                    <a href="#" class="text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                        <img id="navProfilePic" src="https://placehold.co/35" width="35" height="35" class="rounded-circle border">
                        <span id="navUsername" class="ms-1 fw-bold">User</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profileModal"><i class="fa fa-user-cog me-2"></i>โปรไฟล์</a></li>
                        <li><a class="dropdown-item" href="#" onclick="openHistory()"><i class="fa fa-box-open me-2"></i>ประวัติคำสั่งซื้อ</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#" onclick="logout()"><i class="fa fa-sign-out-alt me-2"></i>ออกจากระบบ</a></li>
                    </ul>
                </div>

                <button class="btn btn-light position-relative" onclick="openCart()">
                    <i class="fa fa-shopping-cart"></i>
                    <span id="cartBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">0</span>
                </button>
            </div>
        </div>
    </div>
</nav>

<header class="hero-banner mb-4">
    <div class="container">
        <h1 class="display-4 fw-bold">NEW COLLECTION</h1>
        <p class="lead mb-4">เสื้อฟุตบอลฤดูกาลใหม่ล่าสุด จัดส่งไว ทันใจแน่นอน</p>
        <button class="btn btn-danger btn-lg fw-bold shadow" onclick="collectCoupon('THANJAI50')">
            <i class="fa fa-ticket-alt me-2"></i>เก็บโค้ดส่วนลด 50 บาท (THANJAI50)
        </button>
    </div>
</header>

<main class="container mb-5">
    <div class="d-flex justify-content-center gap-2 mb-4 overflow-auto pb-2" id="category-filters">
        <button class="btn btn-dark px-4 rounded-pill filter-btn active" onclick="filterCat('ทั้งหมด')">ทั้งหมด</button>
        <button class="btn btn-outline-dark px-4 rounded-pill filter-btn" onclick="filterCat('เหย้า')">ชุดเหย้า</button>
        <button class="btn btn-outline-dark px-4 rounded-pill filter-btn" onclick="filterCat('เยือน')">ชุดเยือน</button>
    </div>
    <div class="row g-4" id="productGrid"></div>
</main>

<div class="modal fade" id="authModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold" id="authTitle">เข้าสู่ระบบ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="loginForm">
                    <input type="text" id="logUser" class="form-control mb-2" placeholder="ชื่อผู้ใช้">
                    <input type="password" id="logPass" class="form-control mb-3" placeholder="รหัสผ่าน">
                    <button class="btn btn-warning w-100 fw-bold mb-2" onclick="login()">เข้าสู่ระบบ</button>
                    <p class="text-center small mb-0">ไม่มีบัญชี? <a href="#" onclick="openAuth('register')">สมัครสมาชิก</a></p>
                </div>
                <div id="regForm" class="d-none">
                    <input type="text" id="regUser" class="form-control mb-2" placeholder="ชื่อผู้ใช้">
                    <input type="email" id="regEmail" class="form-control mb-2" placeholder="อีเมล (ต้องมี @ และ .)">
                    <input type="text" id="regPhone" class="form-control mb-2" placeholder="เบอร์โทรศัพท์ (10 หลัก)" maxlength="10">
                    <input type="password" id="regPass" class="form-control mb-3" placeholder="รหัสผ่าน">
                    <button class="btn btn-success w-100 fw-bold mb-2" onclick="register()">ลงทะเบียน</button>
                    <p class="text-center small mb-0">มีบัญชีแล้ว? <a href="#" onclick="openAuth('login')">เข้าสู่ระบบ</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">รายละเอียดสินค้า</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5 text-center mb-3">
                        <img id="detImg" src="" class="img-fluid rounded shadow" style="max-height: 300px; object-fit: contain;">
                    </div>
                    <div class="col-md-7">
                        <span id="detCat" class="badge bg-secondary mb-2"></span>
                        <h4 class="fw-bold" id="detName">Name</h4>
                        <div class="mb-3"><span class="text-danger fw-bold fs-3" id="detPrice">฿0</span></div>
                        
                        <div class="row g-2 align-items-end mb-4">
                            <div class="col-8">
                                <label class="form-label small fw-bold">เลือกไซส์:</label>
                                <select id="detSize" class="form-select"><option value="S">S</option><option value="M" selected>M</option><option value="L">L</option><option value="XL">XL</option></select>
                            </div>
                            <div class="col-4">
                                <label class="form-label small fw-bold">จำนวน:</label>
                                <input type="number" id="detQty" class="form-control text-center" value="1" min="1">
                            </div>
                        </div>
                        <input type="hidden" id="detId">
                        <div class="row g-2">
                            <div class="col-6"><button class="btn btn-cart w-100 py-2" onclick="addToCart(false, true)"><i class="fa fa-cart-plus"></i> เพิ่มลงตะกร้า</button></div>
                            <div class="col-6"><button class="btn btn-buy-now w-100 py-2" onclick="addToCart(true, true)"><i class="fa fa-bolt"></i> สั่งซื้อทันที</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="cartModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold"><i class="fa fa-shopping-cart"></i> ตะกร้าและชำระเงิน</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="row g-0">
                    <div class="col-lg-6 p-4 border-end" id="cartItems"></div>
                    <div class="col-lg-6 p-4 bg-light">
                        <h6 class="fw-bold border-bottom pb-2 mb-3">ข้อมูลจัดส่ง</h6>
                        <input type="text" id="chkPhone" class="form-control form-control-sm mb-2" placeholder="เบอร์โทรศัพท์">
                        <textarea id="chkAddress" class="form-control form-control-sm mb-3" rows="2" placeholder="ที่อยู่จัดส่งแบบละเอียด..."></textarea>
                        
                        <h6 class="fw-bold border-bottom pb-2 mb-3">วิธีชำระเงิน</h6>
                        <div class="row g-2 mb-3">
                            <div class="col-6"><div class="payment-box active" id="payCOD" onclick="setPay('COD')">เก็บเงินปลายทาง</div></div>
                            <div class="col-6"><div class="payment-box" id="payQR" onclick="setPay('QR')">โอนเงิน (QR)</div></div>
                        </div>

                        <div class="card border-0 shadow-sm"><div class="card-body">
                            <div class="input-group input-group-sm mb-2">
                                <input type="text" id="couponCode" class="form-control" placeholder="รหัสคูปอง">
                                <button class="btn btn-dark" onclick="useCoupon()">ใช้คูปอง</button>
                            </div>
                            <div id="myCoupons" class="small mb-3 text-muted"></div>
                            
                            <div class="d-flex justify-content-between small"><span>รวมสินค้า:</span><span id="sumSub">฿0</span></div>
                            <div class="d-flex justify-content-between small text-success"><span>ส่วนลด:</span><span id="sumDisc">-฿0</span></div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between fw-bold fs-5 mb-3"><span>ยอดสุทธิ:</span><span id="sumTotal" class="text-danger">฿0</span></div>
                            <button class="btn btn-success w-100 fw-bold py-2" onclick="checkout()"><i class="fa fa-check"></i> ยืนยันคำสั่งซื้อ</button>
                        </div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="historyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold">ประวัติการสั่งซื้อ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body bg-light p-4" id="historyList"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="profileModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white"><h5 class="modal-title fw-bold">โปรไฟล์</h5><button class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
            <div class="modal-body text-center">
                <img id="profImgPreview" src="" class="rounded-circle mb-3 border border-warning" width="100" height="100" style="object-fit:cover;">
                <input type="text" id="profPhone" class="form-control form-control-sm mb-2" placeholder="เบอร์โทร (10 หลัก)">
                <input type="text" id="profAddress" class="form-control form-control-sm mb-3" placeholder="ที่อยู่">
                <button class="btn btn-warning w-100 fw-bold" onclick="saveProfile()">บันทึกข้อมูล</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // 1. Data & State
    const products = [
        { id: 1, name: "Buriram United Home 2024", price: 790, isSale: false, cat: "เหย้า", img: "https://placehold.co/400x400/003366/fff?text=Buriram+Home" },
        { id: 2, name: "Port FC Away Kit", price: 590, isSale: true, oldPrice: 790, cat: "เยือน", img: "https://placehold.co/400x400/ff6600/fff?text=Port+Away" },
        { id: 3, name: "Muangthong Utd Home", price: 890, isSale: false, cat: "เหย้า", img: "https://placehold.co/400x400/cc0000/fff?text=MTUTD+Home" },
        { id: 4, name: "Chonburi FC Away", price: 450, isSale: true, oldPrice: 650, cat: "เยือน", img: "https://placehold.co/400x400/0099ff/fff?text=Chonburi+Away" }
    ];

    let db = JSON.parse(localStorage.getItem('tj_db_v3')) || [];
    let user = null;
    let cart = [];
    let discount = 0;
    let payMethod = 'COD';

    // 2. Init & Toast
    function initApp() {
        renderProds(products);
        const active = localStorage.getItem('tj_active_v3');
        if(active) {
            user = db.find(u => u.username === active);
            if(user) { cart = user.cart || []; updateNav(); }
        }
    }

    function showToast(msg, type='success') {
        const t = document.getElementById('appToast');
        t.className = `toast align-items-center text-white border-0 bg-${type==='error'?'danger':type==='warning'?'warning text-dark':'success'}`;
        document.getElementById('toastMsg').innerHTML = msg;
        new bootstrap.Toast(t, {delay: 3000}).show();
    }

    function saveDB() {
        if(user) {
            user.cart = cart;
            const idx = db.findIndex(u => u.username === user.username);
            if(idx > -1) db[idx] = user; else db.push(user);
            localStorage.setItem('tj_db_v3', JSON.stringify(db));
        }
    }

    // 3. Auth System (Strict Validation)
    function openAuth(type) {
        document.getElementById('loginForm').classList.toggle('d-none', type !== 'login');
        document.getElementById('regForm').classList.toggle('d-none', type !== 'register');
        document.getElementById('authTitle').innerText = type === 'login' ? 'เข้าสู่ระบบ' : 'สมัครสมาชิก';
        new bootstrap.Modal(document.getElementById('authModal')).show();
    }

    function register() {
        const u = document.getElementById('regUser').value.trim();
        const e = document.getElementById('regEmail').value.trim();
        const p = document.getElementById('regPhone').value.trim();
        const pw = document.getElementById('regPass').value.trim();

        if(!u || !e || !p || !pw) return showToast('กรุณากรอกข้อมูลให้ครบ', 'error');
        if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(e)) return showToast('รูปแบบอีเมลไม่ถูกต้อง (ต้องมี @ และ .)', 'error');
        if(!/^\d{10}$/.test(p)) return showToast('เบอร์โทรศัพท์ต้องเป็นตัวเลข 10 หลัก', 'error');

        if(db.some(x => x.username.toLowerCase() === u.toLowerCase())) return showToast('ชื่อผู้ใช้นี้ถูกใช้งานแล้ว', 'error');
        if(db.some(x => x.email.toLowerCase() === e.toLowerCase())) return showToast('อีเมลนี้ถูกสมัครไปแล้ว', 'error');
        if(db.some(x => x.phone === p)) return showToast('เบอร์โทรศัพท์นี้ถูกใช้งานแล้ว', 'error');

        const newUser = { username: u, email: e, phone: p, password: pw, coupons: [], cart: [], orders: [], address: '', pic: 'https://placehold.co/100' };
        db.push(newUser);
        localStorage.setItem('tj_db_v3', JSON.stringify(db));
        
        showToast('สมัครสมาชิกสำเร็จ! กรุณาเข้าสู่ระบบ', 'success');
        openAuth('login');
    }

    function login() {
        const u = document.getElementById('logUser').value.trim();
        const pw = document.getElementById('logPass').value.trim();
        
        const found = db.find(x => x.username.toLowerCase() === u.toLowerCase() && x.password === pw);
        if(!found) return showToast('ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง', 'error');

        user = found; cart = user.cart || [];
        localStorage.setItem('tj_active_v3', user.username);
        bootstrap.Modal.getInstance(document.getElementById('authModal')).hide();
        updateNav(); showToast(`ยินดีต้อนรับ ${user.username}`, 'success');
    }

    function logout() {
        user = null; cart = []; localStorage.removeItem('tj_active_v3');
        updateNav(); showToast('ออกจากระบบแล้ว', 'success');
    }

    function updateNav() {
        document.getElementById('guestNav').classList.toggle('d-none', user !== null);
        document.getElementById('userNav').classList.toggle('d-none', user === null);
        if(user) {
            document.getElementById('navUsername').innerText = user.username;
            document.getElementById('navProfilePic').src = user.pic;
            document.getElementById('profImgPreview').src = user.pic;
            document.getElementById('profPhone').value = user.phone;
            document.getElementById('profAddress').value = user.address;
        }
        document.getElementById('cartBadge').innerText = cart.reduce((s, i) => s + i.qty, 0);
    }

    // Profile Save
    function saveProfile() {
        const p = document.getElementById('profPhone').value.trim();
        if(!/^\d{10}$/.test(p)) return showToast('เบอร์โทรต้องมี 10 หลัก', 'error');
        user.phone = p; user.address = document.getElementById('profAddress').value;
        saveDB(); updateNav();
        bootstrap.Modal.getInstance(document.getElementById('profileModal')).hide();
        showToast('อัปเดตโปรไฟล์แล้ว', 'success');
    }

    // 4. Products & Filter
    function filterCat(cat) {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.replace('btn-dark', 'btn-outline-dark'));
        event.target.classList.replace('btn-outline-dark', 'btn-dark');
        let f = cat === 'ทั้งหมด' ? products : cat === 'โปรโมชั่น' ? products.filter(p => p.isSale) : products.filter(p => p.cat === cat);
        renderProds(f);
    }

    function searchProduct() {
        const term = document.getElementById('searchInput').value.toLowerCase();
        renderProds(products.filter(p => p.name.toLowerCase().includes(term)));
    }

    function renderProds(list) {
        document.getElementById('productGrid').innerHTML = list.map(p => `
            <div class="col-6 col-md-3">
                <div class="card product-card">
                    ${p.isSale ? `<span class="sale-badge">SALE</span>` : ''}
                    <img src="${p.img}" class="product-img" onclick="openDetail(${p.id})">
                    <div class="card-body d-flex flex-column p-3">
                        <small class="text-muted">${p.cat}</small>
                        <h6 class="fw-bold text-truncate mb-1">${p.name}</h6>
                        <div class="mb-2"><strong class="text-danger fs-5">฿${p.price}</strong> ${p.isSale?`<s class="text-muted small">฿${p.oldPrice}</s>`:''}</div>
                        <div class="row g-1 mt-auto">
                            <div class="col-12"><button class="btn btn-cart w-100 btn-sm" onclick="quickAdd(${p.id}, false)"><i class="fa fa-cart-plus"></i> ลงตะกร้า</button></div>
                            <div class="col-12"><button class="btn btn-buy-now w-100 btn-sm" onclick="quickAdd(${p.id}, true)"><i class="fa fa-bolt"></i> ซื้อทันที</button></div>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    // 5. Product Detail & Cart Logic (The 2 Buttons)
    function openDetail(id) {
        const p = products.find(x => x.id === id);
        document.getElementById('detId').value = p.id;
        document.getElementById('detName').innerText = p.name;
        document.getElementById('detPrice').innerText = `฿${p.price}`;
        document.getElementById('detCat').innerText = p.cat;
        document.getElementById('detImg').src = p.img;
        document.getElementById('detQty').value = 1;
        new bootstrap.Modal(document.getElementById('detailModal')).show();
    }

    function quickAdd(id, isBuyNow) {
        document.getElementById('detId').value = id;
        document.getElementById('detSize').value = 'M';
        document.getElementById('detQty').value = 1;
        addToCart(isBuyNow, false);
    }

    function addToCart(isBuyNow, fromModal) {
        if(!user) {
            if(fromModal) bootstrap.Modal.getInstance(document.getElementById('detailModal')).hide();
            showToast('กรุณาเข้าสู่ระบบก่อนสั่งซื้อ', 'warning');
            return openAuth('login');
        }

        const id = parseInt(document.getElementById('detId').value);
        const size = document.getElementById('detSize').value;
        const qty = parseInt(document.getElementById('detQty').value);
        const p = products.find(x => x.id === id);

        const exist = cart.find(i => i.id === id && i.size === size);
        if(exist) exist.qty += qty; else cart.push({...p, size, qty});
        
        saveDB(); updateNav();
        if(fromModal) bootstrap.Modal.getInstance(document.getElementById('detailModal')).hide();

        if(isBuyNow) {
            setTimeout(() => openCart(), 300); // ดีเลย์นิดนึงให้ UI ไม่ซ้อน
        } else {
            showToast(`เพิ่ม ${p.name} ลงตะกร้าแล้ว!`, 'success');
        }
    }

    // 6. Cart & Checkout & Coupons
    function collectCoupon(code) {
        if(!user) return showToast('กรุณาเข้าสู่ระบบก่อนเก็บคูปอง', 'warning');
        if(!user.coupons) user.coupons = [];
        if(user.coupons.includes(code)) return showToast('คุณเก็บคูปองนี้ไปแล้ว', 'warning');
        user.coupons.push(code); saveDB();
        showToast('เก็บคูปองสำเร็จ! ใช้ได้ในหน้าตะกร้า', 'success');
    }

    function openCart() {
        if(!user) return;
        if(cart.length === 0) return showToast('ตะกร้าว่างเปล่า', 'warning');
        
        document.getElementById('chkPhone').value = user.phone;
        document.getElementById('chkAddress').value = user.address || '';
        document.getElementById('couponCode').value = '';
        discount = 0; setPay('COD');

        const cbox = document.getElementById('myCoupons');
        if(user.coupons && user.coupons.length > 0) {
            cbox.innerHTML = 'คูปองของคุณ: ' + user.coupons.map(c => `<span class="badge bg-warning text-dark mx-1" style="cursor:pointer" onclick="document.getElementById('couponCode').value='${c}'">${c}</span>`).join('');
        } else cbox.innerHTML = 'ไม่มีคูปอง';

        renderCart();
        new bootstrap.Modal(document.getElementById('cartModal')).show();
    }

    function renderCart() {
        let sub = 0;
        document.getElementById('cartItems').innerHTML = cart.map((i, idx) => {
            sub += i.price * i.qty;
            return `
            <div class="d-flex align-items-center mb-3 border-bottom pb-2">
                <img src="${i.img}" width="60" class="rounded me-3">
                <div class="flex-grow-1">
                    <h6 class="mb-0 fw-bold">${i.name}</h6>
                    <small class="text-muted">ไซส์: ${i.size} | ฿${i.price} x ${i.qty}</small>
                </div>
                <div class="text-end">
                    <div class="fw-bold">฿${i.price * i.qty}</div>
                    <button class="btn btn-sm text-danger px-0" onclick="delCart(${idx})"><i class="fa fa-trash"></i> ลบ</button>
                </div>
            </div>`;
        }).join('');
        
        document.getElementById('sumSub').innerText = `฿${sub}`;
        document.getElementById('sumDisc').innerText = `-฿${discount}`;
        document.getElementById('sumTotal').innerText = `฿${sub - discount > 0 ? sub - discount : 0}`;
    }

    function delCart(idx) {
        cart.splice(idx, 1); saveDB(); updateNav();
        if(cart.length === 0) {
            bootstrap.Modal.getInstance(document.getElementById('cartModal')).hide();
            showToast('ตะกร้าว่างเปล่า', 'warning');
        } else renderCart();
    }

    function useCoupon() {
        const code = document.getElementById('couponCode').value.toUpperCase().trim();
        if(!user.coupons || !user.coupons.includes(code)) {
            discount = 0; renderCart(); return showToast('ไม่มีคูปองนี้ในกระเป๋า', 'error');
        }
        if(code === 'THANJAI50') { discount = 50; showToast('ใช้ส่วนลด 50 บาทสำเร็จ', 'success'); }
        renderCart();
    }

    function setPay(m) {
        payMethod = m;
        document.getElementById('payCOD').className = `payment-box ${m==='COD'?'active':''}`;
        document.getElementById('payQR').className = `payment-box ${m==='QR'?'active':''}`;
    }

    function checkout() {
        const ph = document.getElementById('chkPhone').value.trim();
        const ad = document.getElementById('chkAddress').value.trim();
        
        if(!ph || !ad) return showToast('กรุณากรอกที่อยู่และเบอร์โทร', 'error');
        if(!/^\d{10}$/.test(ph)) return showToast('เบอร์โทรต้องมี 10 หลัก', 'error');

        user.phone = ph; user.address = ad;
        const code = document.getElementById('couponCode').value.toUpperCase();
        if(discount > 0) user.coupons = user.coupons.filter(c => c !== code); // ลบคูปองที่ใช้

        const total = cart.reduce((s,i)=>s+(i.price*i.qty),0) - discount;
        const order = { id: 'TJ'+Math.floor(1000+Math.random()*9000), date: new Date().toLocaleDateString('th-TH'), items: [...cart], total, method: payMethod, status: payMethod==='QR'?'รอตรวจสอบโอนเงิน':'เตรียมจัดส่ง' };
        
        if(!user.orders) user.orders = [];
        user.orders.unshift(order);
        
        cart = []; discount = 0; saveDB(); updateNav();
        bootstrap.Modal.getInstance(document.getElementById('cartModal')).hide();
        
        if(payMethod === 'QR') {
            showToast('ระบบจำลองการสร้าง QR Code... สั่งซื้อสำเร็จ!', 'success');
        } else {
            showToast('สั่งซื้อแบบเก็บเงินปลายทางสำเร็จ!', 'success');
        }
    }

    // 7. Order History
    function openHistory() {
        const c = document.getElementById('historyList');
        if(!user.orders || user.orders.length === 0) {
            c.innerHTML = '<div class="text-center text-muted py-4">ยังไม่มีประวัติการสั่งซื้อ</div>';
        } else {
            c.innerHTML = user.orders.map(o => `
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-muted small">#${o.id}</span>
                        <span class="badge ${o.method==='QR'?'bg-primary':'bg-warning text-dark'}">${o.status}</span>
                    </div>
                    <div class="card-body">
                        ${o.items.map(i => `<div class="d-flex mb-1"><img src="${i.img}" width="40" class="me-2 rounded"><small class="mb-0 flex-grow-1">${i.name} (${i.size}) x${i.qty}</small></div>`).join('')}
                        <hr class="my-2">
                        <div class="d-flex justify-content-between align-items-end">
                            <small class="text-muted">${o.date} | ${o.method}</small>
                            <strong class="text-danger">฿${o.total}</strong>
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