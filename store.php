<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopsport - Product Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --dark-deep: #0f0f0f;
            --dark-card: #1a1a1a;
            --primary-red: #e63946;
            --accent-red: #ff4d6d;
        }

        body {
            background-color: var(--dark-deep);
            color: #ffffff;
            font-family: 'Sarabun', sans-serif;
        }


        
        /* Navbar */
        .navbar {
            background: rgba(15, 15, 15, 0.95) !important;
            backdrop-filter: blur(10px);
            border-bottom: 2px solid var(--primary-red);
        }

        /* Product Section */
        .product-container {
            background: var(--dark-card);
            border-radius: 30px;
            border: 1px solid #333;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.5);
        }

        .main-image-wrapper {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            background: #000;
        }

        #mainImg {
            transition: 0.5s ease;
            object-fit: cover;
        }

        .thumbnail-images img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 12px;
            cursor: pointer;
            border: 2px solid transparent;
            transition: 0.3s;
            opacity: 0.6;
        }

        .thumbnail-images img:hover, .thumbnail-images img.active {
            border-color: var(--primary-red);
            opacity: 1;
            transform: scale(1.05);
        }

        /* Detail Section */
        .badge-new {
            background: var(--primary-red);
            font-size: 0.75rem;
            padding: 5px 15px;
            border-radius: 50px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .price-tag {
            font-size: 3rem;
            font-weight: 800;
            color: var(--primary-red);
            text-shadow: 0 0 20px rgba(230, 57, 70, 0.2);
        }

        .qty-input {
            background: #222 !important;
            border: 1px solid #444 !important;
            color: white !important;
            text-align: center;
            border-radius: 10px;
        }

        .btn-cart {
            background: linear-gradient(45deg, var(--primary-red), var(--accent-red));
            border: none;
            color: white;
            font-weight: 700;
            padding: 15px;
            border-radius: 15px;
            transition: 0.4s;
            text-transform: uppercase;
        }

        .btn-cart:hover {
            box-shadow: 0 0 30px rgba(230, 57, 70, 0.5);
            transform: translateY(-5px);
            color: white;
        }

        .feature-box {
            background: rgba(255,255,255,0.05);
            padding: 15px;
            border-radius: 15px;
            font-size: 0.9rem;
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.2);
            border: 1px solid #28a745;
            color: #28a745;
            border-radius: 12px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold text-uppercase" href="a.php">
            <span class="text-danger">Shopsport</span> SHOP
        </a>
        <div class="ms-auto d-flex align-items-center">
            <a class="nav-link me-3" href="a.php text-white">หน้าแรก</a>
            <a class="btn btn-sm btn-outline-danger rounded-pill px-3" href="b.php">
                <i class="fa fa-cog me-1"></i> Admin
            </a>
        </div>
    </div>
</nav>

<div class="container my-5">
    <div class="product-container p-4 p-lg-5">
        <div class="row g-5">
            <div class="col-md-6">
                <div class="main-image-wrapper mb-4 shadow-lg">
                    <img id="mainImg" src="10.jpg" alt="Product" class="img-fluid w-100">
                </div>
                <div class="thumbnail-images d-flex justify-content-center gap-3">
                    <img src="10.jpg" class="active shadow-sm" onclick="changeImage(this)">
                    <img src="9.jpg" class="shadow-sm" onclick="changeImage(this)">
                    <img src="8.jpg" class="shadow-sm" onclick="changeImage(this)">
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-2">
                    <span class="badge badge-new">New Arrival</span>
                </div>
                <h1 class="fw-bold display-6 mb-3">เสื้อฟุตบอลทีมชาติ <span class="text-danger">(Special Edition)</span></h1>
                
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="price-tag">฿1,290.00</div>
                    <del class="text-secondary small">฿1,590.00</del>
                </div>

                <p class="text-secondary mb-4 lead">สัมผัสประสบการณ์ความสบายระดับโปร ด้วยนวัตกรรมผ้า Hybrid-Mesh ระบายอากาศยอดเยี่ยม สีแดง-ดำดุดัน เอกลักษณ์ของกลอยใจ Shop</p>

                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="feature-box">
                            <i class="fa fa-shield-halved text-danger me-2"></i> ของแท้ 100%
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="feature-box">
                            <i class="fa fa-truck text-danger me-2"></i> ส่งฟรีทั่วไทย
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="fw-bold mb-2 text-secondary">จำนวนที่ต้องการสั่งซื้อ:</label>
                    <div class="d-flex align-items-center gap-3">
                        <input type="number" id="qty" class="form-control qty-input w-25 shadow-none" value="1" min="1">
                        <span class="text-secondary">ชิ้น (มีสินค้าในสต็อก 15 ชิ้น)</span>
                    </div>
                </div>

                <button onclick="addToCart()" class="btn btn-cart btn-lg w-100 py-3 shadow">
                    <i class="fa fa-shopping-cart me-2"></i> ใส่ตะกร้าเลย
                </button>

                <div id="msg"></div>
            </div>
        </div>
    </div>
</div>

<script>
    // เปลี่ยนรูปภาพ
    function changeImage(el) {
        const mainImg = document.getElementById("mainImg");
        mainImg.style.opacity = "0"; // Fade out
        
        setTimeout(() => {
            mainImg.src = el.src;
            mainImg.style.opacity = "1"; // Fade in
        }, 200);

        document.querySelectorAll('.thumbnail-images img').forEach(i => i.classList.remove('active'));
        el.classList.add('active');
    }

    // แจ้งเตือนเมื่อเพิ่มลงตะกร้า
    function addToCart() {
        let q = document.getElementById("qty").value;
        if(q < 1) return;
        
        const msgDiv = document.getElementById("msg");
        msgDiv.innerHTML = `
            <div class="alert alert-success mt-4 animate__animated animate__fadeIn">
                <i class="fa fa-check-circle me-2"></i> สำเร็จ! เพิ่มสินค้า ${q} ชิ้นลงในตะกร้าของคุณแล้ว
            </div>
        `;
        
        // หายไปเองหลังจาก 3 วินาที
        setTimeout(() => {
            msgDiv.innerHTML = "";
        }, 3000);
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>