<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - กลอยใจ Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --admin-dark: #1a1a1a; --accent: #e63946; }
        body { background: #f4f7f6; font-family: 'Sarabun', sans-serif; }
        .sidebar { min-height: 100vh; background: var(--admin-dark); color: white; }
        .sidebar a { color: #adb5bd; text-decoration: none; padding: 15px 20px; display: block; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background: rgba(255,255,255,0.1); color: white; border-left: 4px solid var(--accent); }
        .card { border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .form-label { font-weight: 600; color: #444; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar px-0 shadow">
            <div class="p-4 text-center border-bottom border-secondary">
                <h5 class="fw-bold mb-0 text-white">GLOYJAI ADMIN</h5>
            </div>
            <div class="py-3">
                <a href="e.php"><i class="fa fa-chart-line me-2"></i> แดชบอร์ด</a>
                <a href="c.php"><i class="fa fa-box me-2"></i> จัดการสินค้า</a>
                <a href="d.php"><i class="fa fa-users me-2"></i> จัดการลูกค้า</a>
                <a href="b.php"><i class="fa fa-shopping-bag me-2"></i> ออเดอร์</a>
                <a href="f.php" class="active text-white"><i class="fa fa-cog me-2"></i> ตั้งค่าระบบ</a>
                <hr class="mx-3 opacity-25">
                <a href="a.php" class="text-info"><i class="fa fa-external-link-alt me-2"></i> ไปหน้าขายสินค้า</a>
            </div>
        </div>

        <div class="col-md-10 p-4 p-md-5">
            <h3 class="fw-bold mb-4"><i class="fa fa-tools me-2"></i> ตั้งค่าระบบและโปรไฟล์</h3>
            
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card p-4">
                        <h5 class="fw-bold mb-4 border-bottom pb-2">ข้อมูลผู้ดูแลระบบ</h5>
                        <form onsubmit="alert('บันทึกข้อมูลส่วนตัวเรียบร้อย'); return false;">
                            <div class="text-center mb-4">
                                <img src="https://ui-avatars.com/api/?name=Admin+Gloyjai&background=e63946&color=fff&size=100" class="rounded-circle shadow-sm mb-2">
                                <div><button type="button" class="btn btn-sm btn-outline-secondary">เปลี่ยนรูปโปรไฟล์</button></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">ชื่อ-นามสกุล</label>
                                <input type="text" class="form-control" value="ผู้ดูแลระบบ กลอยใจ">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">อีเมล</label>
                                <input type="email" class="form-control" value="admin@gloyjaishop.com">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">รหัสผ่านใหม่ (ปล่อยว่างถ้าไม่ต้องการเปลี่ยน)</label>
                                <input type="password" class="form-control" placeholder="****">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">บันทึกการเปลี่ยนแปลง</button>
                        </form>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card p-4">
                        <h5 class="fw-bold mb-4 border-bottom pb-2">ข้อมูลร้านค้า</h5>
                        <form onsubmit="alert('อัปเดตข้อมูลร้านค้าเรียบร้อย'); return false;">
                            <div class="mb-3">
                                <label class="form-label">ชื่อร้านค้า</label>
                                <input type="text" class="form-control" value="กลอยใจ Shop">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">เบอร์โทรศัพท์ติดต่อ</label>
                                <input type="text" class="form-control" value="081-XXX-XXXX">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">ที่อยู่ร้าน</label>
                                <textarea class="form-control" rows="3">123 หมู่ 1 ต.ในเมือง อ.เมือง จ.อุบลราชธานี</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">สกุลเงินที่ใช้</label>
                                <select class="form-select">
                                    <option selected>บาท (THB)</option>
                                    <option>ดอลลาร์ (USD)</option>
                                </select>
                            </div>
                            <div class="form-check form-switch mb-4">
                                <input class="form-check-input" type="checkbox" checked>
                                <label class="form-check-label">เปิดใช้งานระบบตะกร้าสินค้า</label>
                            </div>
                            <button type="submit" class="btn btn-dark w-100">บันทึกการตั้งค่าร้าน</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>