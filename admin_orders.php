<?php
session_start();
require_once 'db.php';

// เช็คสิทธิ์การเข้าถึง (ต้องล็อกอินและเป็น admin เท่านั้น)
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('❌ คุณไม่มีสิทธิ์เข้าถึงระบบหลังบ้าน!'); window.location.href='indexnew.php';</script>";
    exit;
}

if (isset($_GET['action']) && $_GET['action'] == 'get_order_details') {
    header('Content-Type: application/json');
    $order_id = $_GET['order_id'];
    $stmt = $pdo->prepare("SELECT oi.*, p.name, p.image FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
    $stmt->execute([$order_id]);
    echo json_encode(['success' => true, 'items' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$new_status, $order_id]);
    header("Location: admin_orders.php?msg=status_updated");
    exit;
}

$sql = "SELECT o.id, o.total_price, o.status, o.shipping_address, o.created_at, u.fullname as customer_name, u.phone 
        FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC";
$stmt = $pdo->query($sql);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

function getStatusBadge($status) {
    switch(trim(strtolower($status))) {
        case 'pending': return '<span class="badge bg-warning text-dark">รอชำระเงิน / COD</span>';
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
        .sidebar { min-height: 100vh; background: #1a1a1a; color: white; width: 250px; position: fixed; z-index: 1000; }
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
        <a href="admin_categories.php"><i class="fa fa-tags me-2"></i> จัดการประเภทสินค้า</a>
        <a href="#"><i class="fa fa-users me-2"></i> จัดการลูกค้า</a>
        <a href="indexnew.php" class="text-info mt-5"><i class="fa fa-store me-2"></i> กลับหน้าร้าน</a>
    </div>

    <div class="main-content">
        <div class="mb-4">
            <h2 class="fw-bold"><i class="fa fa-box me-2"></i>ระบบจัดการออเดอร์ (Orders)</h2>
            <p class="text-muted">ตรวจสอบรายการสั่งซื้อของลูกค้า ที่อยู่จัดส่ง และปรับเปลี่ยนสถานะ</p>
        </div>

        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'status_updated'): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fa fa-check-circle"></i> อัปเดตสถานะออเดอร์เรียบร้อยแล้ว
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm">
            <div class="card-body table-responsive">
                <table id="orderTable" class="table table-striped table-hover align-middle w-100">
                    <thead class="table-dark">
                        <tr><th>เลขที่ออเดอร์</th><th>วันที่สั่งซื้อ</th><th>ลูกค้า (เบอร์โทร)</th><th>ยอดรวม</th><th>สถานะ</th><th>จัดการ</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($orders as $o): ?>
                        <tr>
                            <td class="fw-bold text-primary">ORD-<?= str_pad($o['id'], 5, '0', STR_PAD_LEFT) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($o['created_at'])) ?></td>
                            <td><?= $o['customer_name'] ? htmlspecialchars($o['customer_name']) : 'ลูกค้าทั่วไป' ?><br><small class="text-muted"><i class="fa fa-phone"></i> <?= $o['phone'] ? htmlspecialchars($o['phone']) : '-' ?></small></td>
                            <td class="text-danger fw-bold">฿<?= number_format($o['total_price'], 2) ?></td>
                            <td><?= getStatusBadge($o['status']) ?></td>
                            <td><button class="btn btn-sm btn-dark" onclick="viewOrderDetails(<?= $o['id'] ?>, '<?= $o['status'] ?>', '<?= htmlspecialchars($o['shipping_address']) ?>')"><i class="fa fa-eye"></i> รายละเอียด</button></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="orderDetailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold"><i class="fa fa-receipt me-2"></i>รายละเอียดออเดอร์ <span id="modal-order-id" class="text-warning"></span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h6 class="fw-bold border-bottom pb-2">รายการสินค้า</h6>
                    <div id="order-items-container" class="mb-4"></div>
                    <h6 class="fw-bold border-bottom pb-2">ที่อยู่สำหรับจัดส่ง</h6>
                    <p id="modal-address" class="bg-light p-3 rounded border"></p>

                    <form action="admin_orders.php" method="POST" class="mt-4 bg-light p-3 border rounded shadow-sm">
                        <input type="hidden" name="action" value="update_status">
                        <input type="hidden" name="order_id" id="form-order-id">
                        <h6 class="fw-bold mb-3"><i class="fa fa-edit me-1"></i>อัปเดตสถานะการจัดส่ง</h6>
                        <div class="input-group">
                            <select name="status" id="modal-status" class="form-select border-dark">
                                <option value="pending">รอชำระเงิน / COD</option>
                                <option value="paid">ชำระเงินแล้ว (รอกล่อง)</option>
                                <option value="shipped">จัดส่งสินค้าเรียบร้อยแล้ว</option>
                                <option value="cancelled">ยกเลิกคำสั่งซื้อ</option>
                            </select>
                            <button type="submit" class="btn btn-warning fw-bold px-4">บันทึกสถานะ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() { $('#orderTable').DataTable({ "language": { "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/th.json" }, "order": [[ 0, "desc" ]] }); });

        function viewOrderDetails(orderId, currentStatus, address) {
            document.getElementById('modal-order-id').innerText = 'ORD-' + String(orderId).padStart(5, '0');
            document.getElementById('form-order-id').value = orderId;
            document.getElementById('modal-status').value = currentStatus;
            document.getElementById('modal-address').innerText = address || 'ไม่มีข้อมูลที่อยู่จัดส่ง';

            new bootstrap.Modal(document.getElementById('orderDetailModal')).show();
            const container = document.getElementById('order-items-container');
            container.innerHTML = '<div class="text-center py-3"><div class="spinner-border text-primary"></div></div>';

            fetch('admin_orders.php?action=get_order_details&order_id=' + orderId).then(res => res.json()).then(data => {
                if(data.success && data.items.length > 0) {
                    let html = '<table class="table table-bordered align-middle"><thead><tr class="bg-light"><th>สินค้า</th><th>ไซส์</th><th>จำนวน</th><th>ราคา/ชิ้น</th></tr></thead><tbody>';
                    data.items.forEach(item => {
                        let imgUrl = item.image ? (item.image.startsWith('http') || item.image.startsWith('data:') ? item.image : 'uploads/' + item.image) : 'default.jpg';
                        html += `<tr><td><div class="d-flex align-items-center"><img src="${imgUrl}" onerror="this.src='https://via.placeholder.com/150?text=No+Image'" width="40" height="40" style="object-fit:cover;" class="rounded me-2 border bg-white">${item.name || 'สินค้า'}</div></td><td class="text-center">${item.size}</td><td class="text-center">${item.qty}</td><td class="text-end">฿${parseFloat(item.price).toLocaleString()}</td></tr>`;
                    });
                    html += '</tbody></table>'; container.innerHTML = html;
                } else { container.innerHTML = '<div class="alert alert-warning">ไม่พบรายการสินค้าในออเดอร์นี้</div>'; }
            });
        }
    </script>
</body>
</html>