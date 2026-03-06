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
                            <li><a class="dropdown-item" href="#"><i class="fa fa-user me-2"></i>โปรไฟล์ของฉัน</a></li>
                            <li><a class="dropdown-item" href="b.php"><i class="fa fa-shopping-bag me-2"></i>ออเดอร์ของฉัน</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="#" onclick="location.reload()"><i class="fa fa-sign-out-alt me-2"></i>ออกจากระบบ</a></li>
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

<div class="modal fade" id="cartModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold"><i class="fa fa-shopping-cart me-2"></i>ตะกร้าสินค้าของคุณ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="cart-items-list">
                    </div>
                <hr>
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold">ราคารวมทั้งสิ้น:</h5>
                    <h4 class="text-danger fw-bold" id="cart-total">฿0</h4>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ช้อปต่อ</button>
                <button type="button" class="btn btn-warning fw-bold" onclick="alert('กำลังไปหน้าชำระเงิน...')">ยืนยันรายการสั่งซื้อ</button>
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
    // ข้อมูลสินค้า ดึงมาจากไฟล์ index.html
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

    function initStore() {
        renderProducts(originalProducts);
        
        if (isLoggedIn) {
            document.getElementById('guest-zone').classList.add('d-none');
            document.getElementById('user-zone').classList.remove('d-none');
        }
    }

    // ฟังก์ชันแสดงสินค้า
    function renderProducts(items) {
        const container = document.getElementById('store-display');
        container.innerHTML = items.map(p => `
            <div class="col-6 col-md-3 mb-4">
                <div class="card product-card shadow-sm border-0">
                    <img src="${p.img}" class="card-img-top" alt="${p.name}" onerror="this.src='https://via.placeholder.com/300x400?text=${p.name}'">
                    <div class="card-body">
                        <span class="badge bg-secondary mb-2" style="font-size: 10px;">${p.type}</span>
                        <h6 class="card-title fw-bold text-dark mb-1" style="font-size: 14px;">${p.name}</h6>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="text-danger fw-bold fs-5">฿${p.price.toLocaleString()}</span>
                            <button class="btn btn-buy btn-sm px-3" onclick="addToCart(${p.id})">
                                <i class="fa fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    // ระบบค้นหา
    function searchProducts() {
        const term = document.getElementById('search-input').value.toLowerCase();
        const filtered = originalProducts.filter(p => p.name.toLowerCase().includes(term));
        renderProducts(filtered);
    }

    // ระบบกรองหมวดหมู่
    function filterProducts(type) {
        const filtered = type === 'ทั้งหมด' 
            ? originalProducts 
            : originalProducts.filter(p => p.type === type);
        renderProducts(filtered);
    }

    // เพิ่มสินค้าลงตะกร้า
    function addToCart(id) {
        const product = originalProducts.find(p => p.id === id);
        cart.push(product);
        updateCartUI();
        
        // เอฟเฟกต์แจ้งเตือนเล็กน้อย
        const btn = event.currentTarget;
        const originalIcon = btn.innerHTML;
        btn.innerHTML = '<i class="fa fa-check text-warning"></i>';
        setTimeout(() => btn.innerHTML = originalIcon, 1000);
    }

    // อัปเดต UI ตะกร้า
    function updateCartUI() {
        document.getElementById('cart-count').innerText = cart.length;
        const list = document.getElementById('cart-items-list');
        const totalElem = document.getElementById('cart-total');
        
        if (cart.length === 0) {
            list.innerHTML = '<p class="text-center text-muted my-4">ไม่มีสินค้าในตะกร้า</p>';
            totalElem.innerText = '฿0';
            return;
        }

        let total = 0;
        list.innerHTML = cart.map((item, index) => {
            total += item.price;
            return `
                <div class="d-flex align-items-center mb-3 p-2 border-bottom">
                    <img src="${item.img}" width="60" height="60" class="rounded me-3" style="object-fit: cover;">
                    <div class="flex-grow-1">
                        <h6 class="mb-0 fw-bold">${item.name}</h6>
                        <small class="text-danger">฿${item.price.toLocaleString()}</small>
                    </div>
                    <button class="btn btn-sm text-danger" onclick="removeFromCart(${index})">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            `;
        }).join('');
        
        totalElem.innerText = `฿${total.toLocaleString()}`;
    }

    function removeFromCart(index) {
        cart.splice(index, 1);
        updateCartUI();
    }
</script>
</body>
</html>