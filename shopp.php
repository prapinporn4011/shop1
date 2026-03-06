<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ThanJai Shop - ระบบร้านค้าออนไลน์แบบครบวงจร</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary-color: #1a1a1a; --secondary-color: #ffae00; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f6f9; }
        .navbar { background-color: var(--primary-color) !important; }
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1510566337590-2fc1f21d0faa?q=80&w=2070&auto=format&fit=crop') center/cover;
            color: white; padding: 60px 0; text-align: center;
        }
        .product-card { border: none; border-radius: 12px; transition: transform 0.2s, box-shadow 0.2s; overflow: hidden; height: 100%; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .product-img { height: 200px; object-fit: cover; width: 100%; }
        .btn-add-cart { background-color: #e9ecef; color: #333; font-weight: bold; border: none; }
        .btn-add-cart:hover { background-color: #dde0e3; }
        .btn-buy-now { background-color: var(--secondary-color); color: var(--primary-color); font-weight: bold; border: none; }
        .btn-buy-now:hover { background-color: #e69c00; }
        .coupon-banner { background-color: #ffe8e8; border: 2px dashed #ff6b6b; border-radius: 10px; padding: 15px; text-align: center; margin-bottom: 30px; }
        .toast-container { z-index: 1060; }
    </style>
</head>
<body>

<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="systemToast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="toastMessage">ข้อความแจ้งเตือน</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">ThanJai <span style="color: var(--secondary-color);">Shop</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link active" href="#">หน้าหลัก</a></li>
                <li class="nav-item"><a class="nav-link" href="#products-section">สินค้าทั้งหมด</a></li>
            </ul>
            <div class="d-flex align-items-center gap-3">
                <div id="guest-menu">
                    <button class="btn btn-outline-light btn-sm" onclick="showAuthModal('login')">เข้าสู่ระบบ / สมัครสมาชิก</button>
                </div>
                <div id="user-menu" class="d-none text-white">
                    <span class="me-3"><i class="fa fa-user-circle"></i> ยินดีต้อนรับ, <strong id="display-username">User</strong></span>
                    <button class="btn btn-danger btn-sm" onclick="logout()">ออกจากระบบ</button>
                </div>
                <button class="btn btn-light position-relative" onclick="openCartModal()">
                    <i class="fa fa-shopping-cart"></i> ตะกร้า
                    <span id="cart-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">0</span>
                </button>
            </div>
        </div>
    </div>
</nav>

<header class="hero-section mb-4">
    <div class="container">
        <h1 class="display-5 fw-bold">ยินดีต้อนรับสู่ ThanJai Shop</h1>
        <p class="lead">แหล่งรวมสินค้าคุณภาพ สั่งซื้อง่าย สะดวก รวดเร็ว ทันใจ</p>
    </div>
</header>

<main class="container">
    <div class="coupon-banner shadow-sm">
        <h4 class="text-danger fw-bold"><i class="fa fa-ticket-alt"></i> แจกโค้ดส่วนลดพิเศษ!</h4>
        <p class="mb-2">รับส่วนลดทันที 50 บาท เมื่อใช้โค้ด <strong>WELCOME50</strong></p>
        <button class="btn btn-danger btn-sm px-4 fw-bold" onclick="collectCoupon('WELCOME50')">เก็บคูปอง</button>
    </div>

    <h3 class="fw-bold border-bottom pb-2 mb-4" id="products-section">รายการสินค้าของเรา</h3>
    <div class="row g-4" id="product-list">
        </div>
</main>

<div class="modal fade" id="authModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="authModalTitle">เข้าสู่ระบบ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="login-form">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">ชื่อผู้ใช้ (Username)</label>
                        <input type="text" id="login-username" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">รหัสผ่าน</label>
                        <input type="password" id="login-password" class="form-control">
                    </div>
                    <button class="btn btn-primary w-100 fw-bold mb-2" onclick="processLogin()">เข้าสู่ระบบ</button>
                    <p class="text-center small mb-0">ยังไม่มีบัญชี? <a href="#" onclick="showAuthModal('register')">สมัครสมาชิกใหม่</a></p>
                </div>

                <div id="register-form" class="d-none">
                    <div class="mb-2">
                        <label class="form-label small fw-bold">ตั้งชื่อผู้ใช้ (Username)</label>
                        <input type="text" id="reg-username" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-bold">อีเมล (ต้องมี @ และ .)</label>
                        <input type="email" id="reg-email" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-bold">เบอร์โทรศัพท์ (10 หลัก)</label>
                        <input type="text" id="reg-phone" class="form-control" maxlength="10">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">ตั้งรหัสผ่าน</label>
                        <input type="password" id="reg-password" class="form-control">
                    </div>
                    <button class="btn btn-success w-100 fw-bold mb-2" onclick="processRegister()">ยืนยันการสมัคร</button>
                    <p class="text-center small mb-0">มีบัญชีแล้ว? <a href="#" onclick="showAuthModal('login')">เข้าสู่ระบบเลย</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="cartModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class="fa fa-shopping-cart"></i> ตะกร้าสินค้าของคุณ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7 border-end" id="cart-items-container">
                        </div>
                    
                    <div class="col-md-5 bg-light p-3 rounded">
                        <h6 class="fw-bold mb-3">สรุปคำสั่งซื้อ</h6>
                        
                        <div class="input-group input-group-sm mb-2">
                            <input type="text" id="coupon-input" class="form-control" placeholder="กรอกโค้ดส่วนลด">
                            <button class="btn btn-dark" onclick="applyCoupon()">ใช้คูปอง</button>
                        </div>
                        <div id="user-coupons-display" class="small mb-3 text-muted"></div>

                        <div class="d-flex justify-content-between small mb-1">
                            <span>รวมราคาสินค้า:</span><span id="summary-subtotal">฿0</span>
                        </div>
                        <div class="d-flex justify-content-between small mb-1 text-success">
                            <span>ส่วนลดคูปอง:</span><span id="summary-discount">-฿0</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-5 mb-4">
                            <span>ยอดสุทธิ:</span><span id="summary-total" class="text-danger">฿0</span>
                        </div>
                        
                        <h6 class="fw-bold mb-2 small">ข้อมูลจัดส่ง (ดึงจากบัญชี)</h6>
                        <input type="text" id="checkout-phone" class="form-control form-control-sm mb-2" placeholder="เบอร์ติดต่อ" disabled>
                        <textarea id="checkout-address" class="form-control form-control-sm mb-3" rows="2" placeholder="กรุณาระบุที่อยู่จัดส่งให้ชัดเจน..."></textarea>
                        
                        <button class="btn btn-success w-100 fw-bold py-2" onclick="confirmOrder()">ยืนยันคำสั่งซื้อ</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // ------------------------------------------------------------------
    // 1. ฐานข้อมูลจำลองและตัวแปรหลัก
    // ------------------------------------------------------------------
    const products = [
        { id: 1, name: "เสื้อเชิ้ตลายสก็อต", price: 350, img: "https://placehold.co/400x400/e9ecef/495057?text=Shirt" },
        { id: 2, name: "กางเกงยีนส์แฟชั่น", price: 590, img: "https://placehold.co/400x400/e9ecef/495057?text=Jeans" },
        { id: 3, name: "รองเท้าผ้าใบสไตล์เกาหลี", price: 890, img: "https://placehold.co/400x400/e9ecef/495057?text=Sneakers" },
        { id: 4, name: "กระเป๋าสะพายข้าง", price: 450, img: "https://placehold.co/400x400/e9ecef/495057?text=Bag" }
    ];

    let usersDB = JSON.parse(localStorage.getItem('thanjai_users')) || [];
    let loggedInUser = null;
    let cart = [];
    let currentDiscount = 0;

    // ------------------------------------------------------------------
    // 2. ฟังก์ชันเริ่มต้นและการแจ้งเตือน
    // ------------------------------------------------------------------
    window.onload = () => {
        renderProducts();
        checkLoginStatus();
    };

    function showNotification(msg, type = 'success') {
        const toastEl = document.getElementById('systemToast');
        const toastMsg = document.getElementById('toastMessage');
        
        toastEl.classList.remove('bg-success', 'bg-danger', 'bg-warning');
        if(type === 'success') toastEl.classList.add('bg-success');
        if(type === 'error') toastEl.classList.add('bg-danger');
        if(type === 'warning') toastEl.classList.add('bg-warning', 'text-dark');
        
        toastMsg.innerHTML = msg;
        new bootstrap.Toast(toastEl, { delay: 3000 }).show();
    }

    // ------------------------------------------------------------------
    // 3. ระบบแสดงสินค้า
    // ------------------------------------------------------------------
    function renderProducts() {
        const productList = document.getElementById('product-list');
        productList.innerHTML = products.map(p => `
            <div class="col-6 col-md-3">
                <div class="card product-card">
                    <img src="${p.img}" class="product-img" alt="${p.name}">
                    <div class="card-body d-flex flex-column">
                        <h6 class="fw-bold text-truncate">${p.name}</h6>
                        <p class="text-danger fw-bold mb-3 fs-5">฿${p.price}</p>
                        <div class="mt-auto row g-1">
                            <div class="col-12 mb-1">
                                <button class="btn btn-add-cart w-100 btn-sm" onclick="addToCart(${p.id}, false)">
                                    <i class="fa fa-cart-plus"></i> เพิ่มลงตะกร้า
                                </button>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-buy-now w-100 btn-sm" onclick="addToCart(${p.id}, true)">
                                    <i class="fa fa-bolt"></i> สั่งซื้อทันที
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    // ------------------------------------------------------------------
    // 4. ระบบสมาชิก (Login / Register / Validation)
    // ------------------------------------------------------------------
    function showAuthModal(type) {
        document.getElementById('login-form').classList.toggle('d-none', type !== 'login');
        document.getElementById('register-form').classList.toggle('d-none', type !== 'register');
        document.getElementById('authModalTitle').innerText = type === 'login' ? 'เข้าสู่ระบบ' : 'สมัครสมาชิกใหม่';
        new bootstrap.Modal(document.getElementById('authModal')).show();
    }

    function processRegister() {
        const username = document.getElementById('reg-username').value.trim();
        const email = document.getElementById('reg-email').value.trim();
        const phone = document.getElementById('reg-phone').value.trim();
        const password = document.getElementById('reg-password').value.trim();

        // เช็คช่องว่าง
        if(!username || !email || !phone || !password) return showNotification('กรุณากรอกข้อมูลให้ครบทุกช่อง', 'error');

        // Validation: รูปแบบอีเมล
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if(!emailPattern.test(email)) return showNotification('รูปแบบอีเมลไม่ถูกต้อง (ต้องมี @ และ .)', 'error');

        // Validation: เบอร์โทรศัพท์ต้องเป็นตัวเลข 10 หลัก
        const phonePattern = /^\d{10}$/;
        if(!phonePattern.test(phone)) return showNotification('เบอร์โทรศัพท์ต้องเป็นตัวเลข 10 หลักเท่านั้น', 'error');

        // Validation: เช็คข้อมูลซ้ำในระบบ
        const isUserDuplicate = usersDB.some(u => u.username.toLowerCase() === username.toLowerCase());
        const isEmailDuplicate = usersDB.some(u => u.email.toLowerCase() === email.toLowerCase());
        const isPhoneDuplicate = usersDB.some(u => u.phone === phone);

        if(isUserDuplicate) return showNotification('มีผู้ใช้งานชื่อนี้ในระบบแล้ว กรุณาใช้ชื่ออื่น', 'error');
        if(isEmailDuplicate) return showNotification('อีเมลนี้ถูกใช้สมัครสมาชิกไปแล้ว', 'error');
        if(isPhoneDuplicate) return showNotification('เบอร์โทรศัพท์นี้มีในระบบแล้ว', 'error');

        // บันทึกบัญชีใหม่
        const newUser = { username, email, phone, password, coupons: [], cart: [] };
        usersDB.push(newUser);
        localStorage.setItem('thanjai_users', JSON.stringify(usersDB));
        
        showNotification('สมัครสมาชิกสำเร็จ! กรุณาเข้าสู่ระบบ', 'success');
        showAuthModal('login'); // สลับไปหน้าล็อกอิน
    }

    function processLogin() {
        const username = document.getElementById('login-username').value.trim();
        const password = document.getElementById('login-password').value.trim();

        if(!username || !password) return showNotification('กรุณากรอกชื่อผู้ใช้และรหัสผ่าน', 'warning');

        // ค้นหาบัญชี
        const user = usersDB.find(u => u.username.toLowerCase() === username.toLowerCase() && u.password === password);
        
        if(user) {
            loggedInUser = user;
            cart = loggedInUser.cart || [];
            localStorage.setItem('thanjai_active_user', loggedInUser.username);
            
            bootstrap.Modal.getInstance(document.getElementById('authModal')).hide();
            updateUIAfterLogin();
            showNotification(`ยินดีต้อนรับ ${loggedInUser.username}!`, 'success');
        } else {
            showNotification('ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง', 'error');
        }
    }

    function checkLoginStatus() {
        const activeUsername = localStorage.getItem('thanjai_active_user');
        if(activeUsername) {
            loggedInUser = usersDB.find(u => u.username === activeUsername);
            if(loggedInUser) {
                cart = loggedInUser.cart || [];
                updateUIAfterLogin();
            }
        }
    }

    function updateUIAfterLogin() {
        document.getElementById('guest-menu').classList.add('d-none');
        document.getElementById('user-menu').classList.remove('d-none');
        document.getElementById('display-username').innerText = loggedInUser.username;
        updateCartBadge();
    }

    function logout() {
        loggedInUser = null;
        cart = [];
        localStorage.removeItem('thanjai_active_user');
        
        document.getElementById('guest-menu').classList.remove('d-none');
        document.getElementById('user-menu').classList.add('d-none');
        updateCartBadge();
        showNotification('ออกจากระบบเรียบร้อย', 'success');
    }

    // ฟังก์ชันช่วยบันทึกข้อมูลของ User ปัจจุบันลง LocalStorage
    function saveUserData() {
        if(loggedInUser) {
            loggedInUser.cart = cart;
            const index = usersDB.findIndex(u => u.username === loggedInUser.username);
            if(index !== -1) usersDB[index] = loggedInUser;
            localStorage.setItem('thanjai_users', JSON.stringify(usersDB));
        }
    }

    // ------------------------------------------------------------------
    // 5. ระบบคูปอง (เก็บและใช้งาน)
    // ------------------------------------------------------------------
    function collectCoupon(code) {
        if(!loggedInUser) {
            showNotification('กรุณาเข้าสู่ระบบก่อนเก็บคูปอง', 'warning');
            return showAuthModal('login');
        }

        if(!loggedInUser.coupons) loggedInUser.coupons = [];
        
        if(loggedInUser.coupons.includes(code)) {
            showNotification('คุณมีคูปองนี้อยู่ในระบบแล้ว', 'warning');
        } else {
            loggedInUser.coupons.push(code);
            saveUserData();
            showNotification(`เก็บคูปอง ${code} สำเร็จ! นำไปใช้ในหน้าชำระเงินได้เลย`, 'success');
        }
    }

    // ------------------------------------------------------------------
    // 6. ระบบตะกร้าสินค้า และสั่งซื้อ
    // ------------------------------------------------------------------
    function addToCart(productId, isBuyNow) {
        if(!loggedInUser) {
            showNotification('กรุณาเข้าสู่ระบบก่อนเลือกซื้อสินค้า', 'warning');
            return showAuthModal('login');
        }

        const product = products.find(p => p.id === productId);
        const existingItem = cart.find(item => item.id === productId);

        if(existingItem) {
            existingItem.qty += 1;
        } else {
            cart.push({ ...product, qty: 1 });
        }

        saveUserData();
        updateCartBadge();

        if(isBuyNow) {
            // ถ้ากด "สั่งซื้อทันที" เปิดหน้าตะกร้าเลย
            openCartModal();
        } else {
            // ถ้ากด "เพิ่มลงตะกร้า" แค่แจ้งเตือนแล้วให้เลือกซื้อต่อได้
            showNotification(`เพิ่ม "${product.name}" ลงตะกร้าแล้ว ช้อปต่อได้เลย!`, 'success');
        }
    }

    function updateCartBadge() {
        const totalItems = cart.reduce((sum, item) => sum + item.qty, 0);
        document.getElementById('cart-badge').innerText = totalItems;
    }

    function openCartModal() {
        if(!loggedInUser) return showAuthModal('login');
        if(cart.length === 0) return showNotification('ตะกร้าสินค้าว่างเปล่า กรุณาเลือกสินค้าก่อน', 'warning');

        // ดึงข้อมูลที่อยู่/เบอร์โทรมาใส่ฟอร์ม
        document.getElementById('checkout-phone').value = loggedInUser.phone;
        document.getElementById('checkout-address').value = loggedInUser.address || '';
        document.getElementById('coupon-input').value = '';
        currentDiscount = 0;

        // แสดงคูปองที่ผู้ใช้มี
        const couponDisplay = document.getElementById('user-coupons-display');
        if(loggedInUser.coupons && loggedInUser.coupons.length > 0) {
            couponDisplay.innerHTML = `คูปองของคุณ: ` + loggedInUser.coupons.map(c => 
                `<span class="badge bg-warning text-dark me-1" style="cursor:pointer" onclick="document.getElementById('coupon-input').value='${c}'">${c}</span>`
            ).join('');
        } else {
            couponDisplay.innerHTML = 'คุณยังไม่มีคูปองส่วนลด';
        }

        renderCartItems();
        new bootstrap.Modal(document.getElementById('cartModal')).show();
    }

    function renderCartItems() {
        const container = document.getElementById('cart-items-container');
        let subtotal = 0;

        container.innerHTML = cart.map((item, index) => {
            const itemTotal = item.price * item.qty;
            subtotal += itemTotal;
            return `
                <div class="d-flex align-items-center mb-3 border-bottom pb-2">
                    <img src="${item.img}" width="50" class="rounded me-3">
                    <div class="flex-grow-1">
                        <h6 class="mb-0 fw-bold">${item.name}</h6>
                        <small class="text-muted">฿${item.price} x ${item.qty}</small>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold">฿${itemTotal}</div>
                        <button class="btn btn-sm btn-outline-danger py-0 px-2 mt-1" onclick="removeFromCart(${index})">ลบ</button>
                    </div>
                </div>
            `;
        }).join('');

        updateSummary(subtotal);
    }

    function removeFromCart(index) {
        cart.splice(index, 1);
        saveUserData();
        updateCartBadge();
        
        if(cart.length === 0) {
            bootstrap.Modal.getInstance(document.getElementById('cartModal')).hide();
            showNotification('นำสินค้าทั้งหมดออกจากตะกร้าแล้ว', 'warning');
        } else {
            renderCartItems();
        }
    }

    function applyCoupon() {
        const code = document.getElementById('coupon-input').value.toUpperCase().trim();
        
        // เช็คว่ามีคูปองนี้ในกระเป๋าไหม
        if(!loggedInUser.coupons || !loggedInUser.coupons.includes(code)) {
            currentDiscount = 0;
            renderCartItems();
            return showNotification('รหัสคูปองไม่ถูกต้อง หรือคุณยังไม่ได้เก็บคูปองนี้', 'error');
        }

        if(code === 'WELCOME50') {
            currentDiscount = 50;
            showNotification('ใช้งานคูปองสำเร็จ! ลดราคา 50 บาท', 'success');
        }
        
        renderCartItems(); // อัปเดตยอดเงิน
    }

    function updateSummary(subtotal) {
        document.getElementById('summary-subtotal').innerText = `฿${subtotal}`;
        document.getElementById('summary-discount').innerText = `-฿${currentDiscount}`;
        
        const total = subtotal - currentDiscount;
        document.getElementById('summary-total').innerText = `฿${total > 0 ? total : 0}`;
    }

    function confirmOrder() {
        const address = document.getElementById('checkout-address').value.trim();
        if(!address) return showNotification('กรุณาระบุที่อยู่สำหรับจัดส่ง', 'error');

        // บันทึกที่อยู่ไว้ใช้ครั้งถัดไป
        loggedInUser.address = address;

        // ลบคูปองที่ใช้แล้วออกจากบัญชี
        const usedCoupon = document.getElementById('coupon-input').value.toUpperCase().trim();
        if(currentDiscount > 0) {
            loggedInUser.coupons = loggedInUser.coupons.filter(c => c !== usedCoupon);
        }

        // เคลียร์ตะกร้าเมื่อสั่งซื้อสำเร็จ
        cart = [];
        saveUserData();
        updateCartBadge();
        
        bootstrap.Modal.getInstance(document.getElementById('cartModal')).hide();
        showNotification('🎉 สั่งซื้อสินค้าสำเร็จ! ระบบกำลังดำเนินการจัดส่ง', 'success');
    }
</script>
</body>
</html>