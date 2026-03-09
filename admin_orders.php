<?php
session_start();
require_once 'db.php';

// ดึงข้อมูลออเดอร์ (เชื่อมตาราง orders เข้ากับตาราง users เพื่อเอาชื่อลูกค้า)
// หมายเหตุ: หากตาราง users คุณยังว่าง อาจจะไม่มีชื่อลูกค้าโชว์
$sql = "SELECT o.id, o.total_price, o.status, o.shipping_address, o.created_at, u.fullname as customer_name 
        FROM orders o 
        LEFT JOIN users u ON o.user_id = u.id 
        ORDER BY o.created_at DESC";
$stmt = $pdo->query($sql);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ฟังก์ชันแปลงสีสถานะออเดอร์
function getStatusBadge($status) {
    switch($status) {
        case 'pending': return '<span class="badge bg-warning text-dark">รอชำระเงิน</span>';
        case 'paid': return '<span class="badge bg-success">ชำระเงินแล้ว</span>';
        case 'shipped': return '<span class="badge bg-info text-dark">จัดส่งแล้ว</span>';
        case 'cancelled': return '<span class="badge bg-danger">ยกเลิก</span>';
        default: return '<span class="badge bg-secondary">'.$status.'</span>';
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการออเดอร์ - หลังบ้าน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <style>
        body { font-family: 'Sarabun', sans-serif; background: #f4f6f9; }
        .sidebar { min-height: 100vh; background: #1a1a1a; color: white; width: 250px; position: fixed; }
        .sidebar a { color: #ccc; text-decoration: none; padding: 15px 20px; display: block; border-bottom: 1px solid #333; }
        .sidebar a:hover, .sidebar a.active { background: #ffae00; color: #1a1a1a; font-weight: bold; }
        .main-content { margin-left: 250px; padding: 20px; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h4 class="text-center py-4 text-warning fw-bold border-bottom m-0">Admin Panel</h4>
        <a href="admin_products.php"><i class="fa fa-tshirt me-2"></i> จัดการสินค้า</a>
        <a href="admin_orders.php" class="active"><i class="fa fa-box me-2"></i> จัดการออเดอร์</a>
        <a href="#"><i class="fa fa-tags me-2"></i> จัดการประเภทสินค้า</a>
        <a href="#"><i class="fa fa-users me-2"></i> จัดการลูกค้า</a>
        <a href="indexnew.php" class="text-info mt-5"><i class="fa fa-store me-2"></i> กลับหน้าร้าน</a>
    </div>

    <div class="main-content">
        <div class="mb-4">
            <h2 class="fw-bold"><i class="fa fa-box me-2"></i>ระบบจัดการออเดอร์ (Orders)</h2>
            <p class="text-muted">ตรวจสอบรายการสั่งซื้อของลูกค้า ที่อยู่จัดส่ง และสถานะการจัดส่ง</p>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <table id="orderTable" class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>เลขที่ออเดอร์</th>
                            <th>วันที่สั่งซื้อ</th>
                            <th>ชื่อลูกค้า</th>
                            <th>ยอดรวม</th>
                            <th>สถานะ</th>
                            <th>ที่อยู่จัดส่ง</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($orders as $o): ?>
                        <tr>
                            <td class="fw-bold text-primary">ORD-<?= str_pad($o['id'], 5, '0', STR_PAD_LEFT) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($o['created_at'])) ?></td>
                            <td><?= $o['customer_name'] ? htmlspecialchars($o['customer_name']) : 'ลูกค้าทั่วไป' ?></td>
                            <td class="text-danger fw-bold">฿<?= number_format($o['total_price'], 2) ?></td>
                            <td><?= getStatusBadge($o['status']) ?></td>
                            <td><small><?= htmlspecialchars($o['shipping_address']) ?></small></td>
                            <td>
                                <button class="btn btn-sm btn-dark"><i class="fa fa-eye"></i> ดูรายละเอียด</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#orderTable').DataTable({
                "language": { "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/th.json" },
                "order": [[ 0, "desc" ]] // เรียงออเดอร์ใหม่ล่าสุดขึ้นก่อน
            });
        });
    </script>
</body>
</html>