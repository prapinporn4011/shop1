<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ThanJai shop - Product Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --dark: #121212; --red: #e63946; }
        body { font-family: 'Sarabun', sans-serif; background: #f8f9fa; }
        .sidebar { background: var(--dark); min-height: 100vh; color: white; padding: 20px; }
        .sidebar a { color: #aaa; text-decoration: none; display: block; padding: 12px; border-radius: 8px; margin-bottom: 5px; }
        .sidebar a:hover, .sidebar a.active { background: var(--red); color: white; }
        .product-img { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar">
            <h4 class="fw-bold text-center mb-4">ThanJai shop</h4>
            <a href="a.php">🏠 ไปหน้าร้าน</a>
            <hr>
            <a href="b.php">📦 จัดการออเดอร์</a>
            <a href="c.php" class="active">👕 จัดการสินค้า</a>
        </div>
        
        <div class="col-md-10 p-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">คลังสินค้าปัจจุบัน</h2>
                <button class="btn btn-dark shadow-sm px-4"><i class="fa fa-plus me-2"></i>เพิ่มสินค้าใหม่</button>
            </div>
            
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>รูปภาพ</th>
                            <th>ชื่อสินค้า</th>
                            <th>ประเภท</th>
                            <th>ราคา</th>
                            <th>การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><img src="10.jpg" class="product-img"></td>
                            <td>เสื้อฟุตบอลทีมชาติ</td>
                            <td>เสื้อผ้ากีฬา</td>
                            <td class="fw-bold">฿1,290.00</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary me-2"><i class="fa fa-edit"></i> แก้ไข</button>
                                <button class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i> ลบ</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>