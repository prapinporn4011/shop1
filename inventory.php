<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - กลอยใจ Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --admin-dark: #1a1a1a; --accent: #e63946; }
        body { background: #f0f2f5; font-family: 'Sarabun', sans-serif; }
        .sidebar { min-height: 100vh; background: var(--admin-dark); color: white; }
        .sidebar a { color: #adb5bd; text-decoration: none; padding: 15px 20px; display: block; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background: rgba(255,255,255,0.1); color: white; border-left: 4px solid var(--accent); }
        .stat-card { border: none; border-radius: 15px; transition: 0.3s; }
        .stat-card:hover { transform: translateY(-5px); }
        .icon-box { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; }
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
                <a href="e.php" class="active"><i class="fa fa-chart-line me-2"></i> แดชบอร์ดสรุปผล</a>
                <a href="c.php"><i class="fa fa-box me-2"></i> จัดการสินค้า</a>
                <a href="d.php"><i class="fa fa-users me-2"></i> จัดการลูกค้า</a>
                <a href="b.php"><i class="fa fa-shopping-bag me-2"></i> จัดการออเดอร์</a>
                <hr class="mx-3 opacity-25">
                <a href="a.php" class="text-info"><i class="fa fa-external-link-alt me-2"></i> ไปหน้าขายสินค้า</a>
                <a href="#" class="text-danger mt-5"><i class="fa fa-sign-out-alt me-2"></i> ออกจากระบบ</a>
            </div>
        </div>

        <div class="col-md-10 p-4 p-md-5">
            <h3 class="fw-bold mb-4 text-dark">ภาพรวมระบบ (Overview)</h3>
            
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="card stat-card shadow-sm p-4">
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-primary text-white me-3"><i class="fa fa-wallet"></i></div>
                            <div>
                                <h6 class="text-muted mb-0">ยอดขายรวม</h6>
                                <h2 class="fw-bold mb-0">฿45,290</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card shadow-sm p-4">
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-success text-white me-3"><i class="fa fa-shopping-cart"></i></div>
                            <div>
                                <h6 class="text-muted mb-0">ออเดอร์ใหม่</h6>
                                <h2 class="fw-bold mb-0">12 รายการ</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card shadow-sm p-4">
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-warning text-dark me-3"><i class="fa fa-user-friends"></i></div>
                            <div>
                                <h6 class="text-muted mb-0">ลูกค้าทั้งหมด</h6>
                                <h2 class="fw-bold mb-0">158 คน</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                        <h5 class="fw-bold mb-4">ออเดอร์ล่าสุด</h5>
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr><th>วันที่</th><th>ชื่อลูกค้า</th><th>ยอดเงิน</th><th>สถานะ</th></tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>14 ก.พ. 67</td>
                                    <td>คุณวีรเทพ</td>
                                    <td class="fw-bold">1,290.-</td>
                                    <td><span class="badge bg-warning text-dark">รอดำเนินการ</span></td>
                                </tr>
                                <tr>
                                    <td>13 ก.พ. 67</td>
                                    <td>คุณธีรศิลป์</td>
                                    <td class="fw-bold">2,580.-</td>
                                    <td><span class="badge bg-success">ส่งแล้ว</span></td>
                                </tr>
                            </tbody>
                        </table>
                        <a href="b.php" class="btn btn-link text-decoration-none">ดูทั้งหมด -></a>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 text-center">
                        <h5 class="fw-bold mb-3">ทางลัดการจัดการ</h5>
                        <div class="d-grid gap-2">
                            <a href="c.php" class="btn btn-outline-dark"><i class="fa fa-plus-circle me-1"></i> เพิ่มสินค้าใหม่</a>
                            <a href="d.php" class="btn btn-outline-dark"><i class="fa fa-search me-1"></i> ค้นหาข้อมูลลูกค้า</a>
                            <a href="a.php" target="_blank" class="btn btn-primary"><i class="fa fa-eye me-1"></i> ดูหน้าร้านจริง</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>