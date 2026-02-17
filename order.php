<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KloyJai Admin - Order Management</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --dark-bg: #0b0b0b;
            --sidebar-bg: #141414;
            --primary-red: #e63946;
            --accent-red: #ff4d6d;
            --text-gray: #a0a0a0;
            --card-glass: rgba(255, 255, 255, 0.05);
        }

        body {
            font-family: 'Sarabun', sans-serif;
            background-color: var(--dark-bg);
            color: #ffffff;
            overflow-x: hidden;
        }

        /* Sidebar Design */
        .sidebar {
            background-color: var(--sidebar-bg);
            min-height: 100vh;
            border-right: 1px solid #222;
            padding: 30px 20px;
            position: fixed;
            width: inherit;
        }

        .sidebar-brand {
            color: var(--primary-red);
            font-size: 1.5rem;
            font-weight: 800;
            text-align: center;
            margin-bottom: 40px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .sidebar-nav a {
            color: var(--text-gray);
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 12px 20px;
            border-radius: 12px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .sidebar-nav a i {
            margin-right: 15px;
            font-size: 1.1rem;
        }

        .sidebar-nav a:hover, .sidebar-nav a.active {
            background: linear-gradient(45deg, var(--primary-red), var(--accent-red));
            color: white;
            box-shadow: 0 4px 15px rgba(230, 57, 70, 0.3);
            transform: translateX(5px);
        }

        /* Main Content */
        .main-content {
            margin-left: 16.666667%; /* Offset for col-md-2 */
            padding: 40px;
        }

        .content-header h2 {
            font-weight: 700;
            letter-spacing: -0.5px;
            border-left: 5px solid var(--primary-red);
            padding-left: 15px;
        }

        /* Stat Cards */
        .stat-card {
            background: var(--sidebar-bg);
            border: 1px solid #222;
            border-radius: 20px;
            padding: 25px;
            transition: 0.4s;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            border-color: var(--primary-red);
            transform: translateY(-10px);
        }

        .stat-card h6 {
            color: var(--text-gray);
            font-size: 0.9rem;
            text-transform: uppercase;
        }

        .stat-card h2 {
            font-weight: 800;
            margin-top: 10px;
            color: #fff;
        }

        .stat-icon {
            position: absolute;
            right: -10px;
            bottom: -10px;
            font-size: 4rem;
            color: rgba(230, 57, 70, 0.1);
        }

        /* Table Customization */
        .table-container {
            background: var(--sidebar-bg);
            border-radius: 20px;
            padding: 20px;
            border: 1px solid #222;
        }

        .table {
            color: #fff;
            border-color: #222;
        }

        .table thead th {
            background-color: transparent;
            color: var(--text-gray);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            border-bottom: 2px solid #222;
            padding: 15px;
        }

        .table tbody tr {
            transition: 0.3s;
            border-bottom: 1px solid #222;
        }

        .table tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.02);
        }

        .table td {
            padding: 18px 15px;
            vertical-align: middle;
        }

        /* Badges */
        .badge-status {
            padding: 8px 15px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .bg-pending { background-color: rgba(230, 57, 70, 0.15); color: #ff4d6d; }
        .bg-shipped { background-color: rgba(40, 167, 69, 0.15); color: #28a745; }

        .price-tag {
            color: var(--accent-red);
            font-weight: 700;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--dark-bg); }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--primary-red); }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <div class="col-md-2 p-0">
            <div class="sidebar">
                <div class="sidebar-brand">
                    GLOYJAI <span style="color:white">ADMIN</span>
                </div>
                
                <nav class="sidebar-nav">
                    <a href="a.php"><i class="fa-solid fa-store"></i> หน้าร้าน</a>
                    <a href="b.php" class="active"><i class="fa-solid fa-box-open"></i> จัดการออเดอร์</a>
                    <a href="c.php"><i class="fa-solid fa-shirt"></i> จัดการสินค้า</a>
                    <hr style="border-color: #333; margin: 20px 0;">
                    <a href="#" class="text-danger mt-4"><i class="fa-solid fa-right-from-bracket"></i> ออกจากระบบ</a>
                </nav>
            </div>
        </div>

        <div class="col-md-10 main-content">
            
            <div class="content-header mb-5">
                <h2 class="text-white">จัดการรายการสั่งซื้อ</h2>
                <p class="text-secondary">ยินดีต้อนรับกลับ, ตรวจสอบและอัปเดตสถานะการส่งสินค้าของคุณที่นี่</p>
            </div>

            <div class="row mb-5">
                <div class="col-md-4">
                    <div class="card stat-card">
                        <h6>ยอดขายรวมทั้งหมด</h6>
                        <h2>฿3,570.00</h2>
                        <i class="fa-solid fa-chart-line stat-icon"></i>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card">
                        <h6>รายการที่ส่งแล้ว</h6>
                        <h2>฿1,290.00</h2>
                        <i class="fa-solid fa-truck-fast stat-icon"></i>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card">
                        <h6>รอการจัดส่ง</h6>
                        <h2 style="color: var(--accent-red);">฿2,280.00</h2>
                        <i class="fa-solid fa-clock stat-icon"></i>
                    </div>
                </div>
            </div>

            <div class="table-container shadow-lg">
                <div class="d-flex justify-content-between align-items-center mb-4 px-3">
                    <h5 class="mb-0 fw-bold">รายการสั่งซื้อล่าสุด</h5>
                    <button class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-filter me-2"></i>กรองข้อมูล</button>
                </div>
                
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>ชื่อลูกค้า</th>
                                <th>รายละเอียดสินค้า</th>
                                <th>ยอดชำระ</th>
                                <th>สถานะการจัดส่ง</th>
                                <th class="text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-bold">#JSY-001</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-secondary rounded-circle me-2" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 0.8rem;">ว</div>
                                        คุณวีรเทพ
                                    </div>
                                </td>
                                <td>Home Kit <span class="badge bg-dark">Size L</span></td>
                                <td class="price-tag">1,290.-</td>
                                <td><span class="badge-status bg-pending">● รอการจัดส่ง</span></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-danger px-3 rounded-pill">อัปเดต</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">#JSY-002</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-secondary rounded-circle me-2" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 0.8rem;">ป</div>
                                        คุณปิยะพร
                                    </div>
                                </td>
                                <td>Away Kit <span class="badge bg-dark">Size M</span></td>
                                <td class="price-tag">1,280.-</td>
                                <td><span class="badge-status bg-shipped">● จัดส่งสำเร็จ</span></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-light px-3 rounded-pill">ดูรายละเอียด</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div> </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>