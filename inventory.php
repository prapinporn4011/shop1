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
        .product-card { border: none; border-radius: 15px; transition: 0.3s; overflow: hidden; }
        .product-card:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(0,0,0,0.1); }
        .btn-buy { background: var(--primary); color: white; border-radius: 8px; }
        .btn-buy:hover { background: var(--accent); color: var(--primary); }
        .member-badge { font-size: 0.7rem; background: var(--accent); color: var(--primary); padding: 2px 8px; border-radius: 10px; font-weight: bold; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">ThanJai <span class="text-warning">Shop</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link active" href="#">หน้าหลัก</a></li>
                <li class="nav-item"><a class="nav-link" href="#">สินค้าทั้งหมด</a></li>
                <li class="nav-item"><a class="nav-link" href="#">โปรโมชั่น</a></li>
            </ul>
            <div class="d-flex align-items-center gap-3">
                <div id="guest-zone">
                    <a href="#" class="btn btn-outline-light btn-sm">เข้าสู่ระบบ</a>
                    <a href="#" class="btn btn-warning btn-sm">สมัครสมาชิก</a>
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
                            <li><a class="dropdown-item" href="#"><i class="fa fa-heart me-2"></i>รายการโปรด</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="#"><i class="fa fa-sign-out-alt me-2"></i>ออกจากระบบ</a></li>
                        </ul>
                    </div>
                </div>
                
                <a href="#" class="text-white position-relative">
                    <i class="fa fa-shopping-cart fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">2</span>
                </a>
            </div>
        </div>
    </div>
</nav>

<header class="hero-banner text-center">
    <div class="container">
        <h1 class="display-4 fw-bold">NEW SEASON 2026</h1>
        <p class="lead">คอลเลคชั่นใหม่ล่าสุดจากสโมสรดังทั่วโลก พร้อมส่งแล้ววันนี้!</p>
        <button class="btn btn-warning btn-lg px-5 fw-bold">เลือกช้อปเลย</button>
    </div>
</header>

<main class="container my-5">
    <div class="d-flex justify-content-center gap-2 mb-5 overflow-auto pb-2">
        <button class="btn btn-dark px-4 rounded-pill">ทั้งหมด</button>
        <button class="btn btn-outline-dark px-4 rounded-pill">ไทยลีก</button>
        <button class="btn btn-outline-dark px-4 rounded-pill">พรีเมียร์ลีก</button>
        <button class="btn btn-outline-dark px-4 rounded-pill">เสื้อซ้อม</button>
    </div>

    <div class="row g-4">
        <div class="col-6 col-md-3">
            <div class="card product-card h-100 shadow-sm">
                <img src="https://via.placeholder.com/300x400" class="card-img-top" alt="Product">
                <div class="card-body">
                    <p class="text-muted small mb-1">ไทยลีก</p>
                    <h6 class="card-title fw-bold">Buriram Home 2026</h6>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-danger fw-bold fs-5">฿790</span>
                        <button class="btn btn-buy btn-sm px-3"><i class="fa fa-cart-plus"></i></button>
                    </div>
                </div>
            </div>
        </div>
        </div>
</main>

<footer class="bg-dark text-white py-5 mt-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <h5 class="fw-bold">ThanJai Shop</h5>
                <p class="text-muted">ร้านขายอุปกรณ์กีฬาและเสื้อบอลอันดับ 1 มั่นใจ ได้ของไว ถึงมือแน่นอน</p>
            </div>
            <div class="col-md-4">
                <h5>ติดต่อเรา</h5>
                <p class="text-muted mb-1"><i class="fa fa-phone me-2"></i> 081-XXX-XXXX</p>
                <p class="text-muted"><i class="fab fa-line me-2"></i> @thanjai_shop</p>
            </div>
            <div class="col-md-4">
                <h5>ติดตามข่าวสาร</h5>
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="อีเมลของคุณ">
                    <button class="btn btn-warning">สมัคร</button>
                </div>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // จำลองสถานะล็อกอิน (เมื่อทำจริง ให้ใช้ตัวแปรจาก PHP เช็ค Session)
    let isLoggedIn = true; 

    if (isLoggedIn) {
        document.getElementById('guest-zone').classList.add('d-none');
        document.getElementById('user-zone').classList.remove('d-none');
    }
</script>
</body>
</html>