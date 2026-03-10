<?php
session_start(); require_once 'db.php';
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { echo "<script>alert('❌ คุณไม่มีสิทธิ์!'); window.location.href='indexnew.php';</script>"; exit; }
$stmtAdmin = $pdo->prepare("SELECT fullname, phone, profile_pic FROM users WHERE id = ?"); $stmtAdmin->execute([$_SESSION['user_id']]); $adminData = $stmtAdmin->fetch();
$adminName = $adminData['fullname'] ?: $_SESSION['username']; $adminPhone = $adminData['phone'] ?: '';
$adminPic = (!empty($adminData['profile_pic']) && strpos($adminData['profile_pic'], 'data:image') === 0) ? $adminData['profile_pic'] : 'https://dummyimage.com/100x100/ffb800/000.png&text='.urlencode(mb_substr($adminName,0,2));

if (isset($_GET['action']) && $_GET['action'] == 'get_order_details') { header('Content-Type: application/json'); $stmt = $pdo->prepare("SELECT oi.*, p.name, p.image FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?"); $stmt->execute([$_GET['order_id']]); echo json_encode(['success' => true, 'items' => $stmt->fetchAll(PDO::FETCH_ASSOC)]); exit; }
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') { $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?"); $stmt->execute([$_POST['status'], $_POST['order_id']]); header("Location: admin_orders.php?msg=status_updated"); exit; }

$stmt = $pdo->query("SELECT o.id, o.total_price, o.status, o.shipping_address, o.created_at, u.fullname as customer_name, u.phone FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC"); $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
$current_page = basename($_SERVER['PHP_SELF']);
function getStatusBadge($s) {
    switch(trim(strtolower($s))) { case 'pending': return '<span class="badge bg-dark">รอชำระเงิน / COD</span>'; case 'paid': return '<span class="badge bg-success">ชำระเงินแล้ว</span>'; case 'shipped': return '<span class="badge bg-primary">จัดส่งแล้ว</span>'; case 'cancelled': return '<span class="badge bg-danger">ยกเลิก</span>'; default: return '<span class="badge bg-secondary">'.$s.'</span>'; }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Premium Sports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&family=Sarabun:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #111111; --accent: #ffb800; } body { font-family: 'Sarabun', sans-serif; background: #f8fafc; } h1, h2, h3, h4, h5, h6, .btn, th, .sport-font { font-family: 'Kanit', sans-serif; }
        .sidebar { min-height: 100vh; background: linear-gradient(to bottom, rgba(17, 17, 17, 0.95), rgba(17, 17, 17, 0.98)), url('https://upload.wikimedia.org/wikipedia/commons/thumb/8/8c/Cristiano_Ronaldo_2018.jpg/800px-Cristiano_Ronaldo_2018.jpg'); background-size: cover; background-position: center; color: white; width: 260px; position: fixed; z-index: 1000; box-shadow: 4px 0 20px rgba(0,0,0,0.1); }
        .sidebar-header { text-align: center; padding: 30px 20px 20px; border-bottom: 1px solid rgba(255,255,255,0.1); } .sidebar-header img { width: 90px; height: 90px; border-radius: 50%; border: 3px solid var(--accent); margin-bottom: 10px; object-fit: cover; background: #fff;} .edit-profile-btn { font-size: 12px; color: #aaa; text-decoration: underline; cursor: pointer; } .edit-profile-btn:hover { color: var(--accent); }
        .sidebar a.menu-link { color: #cbd5e1; text-decoration: none; padding: 15px 25px; display: block; border-bottom: 1px solid rgba(255,255,255,0.05); font-family: 'Kanit', sans-serif; font-size: 1.05rem; transition: 0.3s; } .sidebar a.menu-link:hover, .sidebar a.menu-link.active { background: rgba(255, 184, 0, 0.1); color: var(--accent); border-left: 4px solid var(--accent); padding-left: 21px; font-weight: 500; }
        .main-content { margin-left: 260px; padding: 30px; } .card { border-radius: 0; border: 1px solid #eee; box-shadow: none; } .table-dark { background-color: var(--primary) !important; color: #fff; } .btn-warning { background-color: var(--accent); font-weight: 600; color: #000; border-radius: 0; } .btn-dark { border-radius: 0; } .text-warning { color: var(--accent) !important; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header"><img id="sb-profile-pic" src="<?= htmlspecialchars($adminPic) ?>" alt="Admin Profile"><h5 class="text-white fw-bold m-0 sport-font" id="sb-admin-name"><?= htmlspecialchars($adminName) ?></h5><span class="badge bg-danger mb-2">Admin</span><br><span class="edit-profile-btn sport-font" data-bs-toggle="modal" data-bs-target="#adminProfileModal"><i class="fa fa-edit"></i> แก้ไขโปรไฟล์</span></div>
        <a href="admin_products.php" class="menu-link <?= $current_page == 'admin_products.php' ? 'active' : '' ?>"><i class="fa fa-tshirt me-2"></i> จัดการสินค้า</a>
        <a href="admin_orders.php" class="menu-link <?= $current_page == 'admin_orders.php' ? 'active' : '' ?>"><i class="fa fa-box me-2"></i> จัดการออเดอร์</a>
        <a href="admin_categories.php" class="menu-link <?= $current_page == 'admin_categories.php' ? 'active' : '' ?>"><i class="fa fa-tags me-2"></i> จัดการประเภทสินค้า</a>
        <a href="admin_customers.php" class="menu-link <?= $current_page == 'admin_customers.php' ? 'active' : '' ?>"><i class="fa fa-users me-2"></i> จัดการลูกค้า</a>
        <a href="indexnew.php" class="menu-link text-info mt-5" style="border-top: 1px solid rgba(255,255,255,0.1);"><i class="fa fa-store me-2"></i> กลับหน้าร้าน</a>
    </div>

    <div class="main-content">
        <h2 class="fw-bold mb-4 sport-font"><i class="fa fa-box me-2"></i>ระบบจัดการออเดอร์</h2>
        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'status_updated'): ?><div class="alert alert-success alert-dismissible fade show sport-font"><i class="fa fa-check-circle"></i> อัปเดตสถานะออเดอร์เรียบร้อยแล้ว<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
        <div class="card border-0 shadow-sm sport-font">
            <div class="card-body table-responsive">
                <table id="orderTable" class="table table-striped table-hover align-middle w-100">
                    <thead class="table-dark"><tr><th>เลขที่ออเดอร์</th><th>วันที่สั่งซื้อ</th><th>ลูกค้า (เบอร์โทร)</th><th>ยอดรวม</th><th>สถานะ</th><th>จัดการ</th></tr></thead>
                    <tbody>
                        <?php foreach($orders as $o): ?>
                        <tr>
                            <td class="fw-bold text-primary">ORD-<?= str_pad($o['id'], 5, '0', STR_PAD_LEFT) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($o['created_at'])) ?></td>
                            <td><?= $o['customer_name'] ?: 'ลูกค้าทั่วไป' ?><br><small class="text-muted"><i class="fa fa-phone"></i> <?= $o['phone'] ?: '-' ?></small></td>
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

    <div class="modal fade" id="orderDetailModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content rounded-0"><div class="modal-header bg-dark text-white rounded-0"><h5 class="modal-title fw-bold sport-font"><i class="fa fa-receipt me-2 text-warning"></i>รายละเอียดออเดอร์ <span id="modal-order-id" class="text-warning"></span></h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div><div class="modal-body sport-font"><h6 class="fw-bold border-bottom pb-2">รายการสินค้า</h6><div id="order-items-container" class="mb-4"></div><h6 class="fw-bold border-bottom pb-2">ที่อยู่สำหรับจัดส่ง</h6><p id="modal-address" class="bg-light p-3 border"></p><form action="admin_orders.php" method="POST" class="mt-4 bg-light p-3 border"><input type="hidden" name="action" value="update_status"><input type="hidden" name="order_id" id="form-order-id"><h6 class="fw-bold mb-3"><i class="fa fa-edit me-1"></i>อัปเดตสถานะการจัดส่ง</h6><div class="input-group"><select name="status" id="modal-status" class="form-select rounded-0 border-dark"><option value="pending">รอชำระเงิน / COD</option><option value="paid">ชำระเงินแล้ว (รอกล่อง)</option><option value="shipped">จัดส่งสินค้าเรียบร้อยแล้ว</option><option value="cancelled">ยกเลิกคำสั่งซื้อ</option></select><button type="submit" class="btn btn-warning fw-bold px-4 rounded-0">บันทึกสถานะ</button></div></form></div></div></div></div>
    
    <div class="modal fade" id="adminProfileModal" tabindex="-1"><div class="modal-dialog modal-dialog-centered modal-sm"><div class="modal-content rounded-0"><div class="modal-header bg-dark text-white rounded-0"><h5 class="modal-title fw-bold sport-font">ตั้งค่าโปรไฟล์แอดมิน</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div><div class="modal-body text-center sport-font"><label for="admin-profile-upload" class="position-relative d-inline-block mb-3"><img id="admin-setting-pic" src="<?= htmlspecialchars($adminPic) ?>" style="width:100px; height:100px; object-fit:cover; border-radius:50%; border:2px solid #ffb800; cursor:pointer;"><div class="position-absolute bottom-0 end-0 bg-warning rounded-circle p-2 border border-dark"><i class="fa fa-camera text-dark"></i></div></label><input type="file" id="admin-profile-upload" class="d-none" accept="image/*" onchange="const f=this.files[0]; if(f){ const r=new FileReader(); r.onload=e=>document.getElementById('admin-setting-pic').src=e.target.result; r.readAsDataURL(f); }"><div class="text-start"><label class="form-label small fw-bold">ชื่อแสดงผล</label><input type="text" id="admin-set-name" class="form-control rounded-0 mb-2" value="<?= htmlspecialchars($adminName) ?>"><label class="form-label small fw-bold">เบอร์โทรศัพท์ (10 หลัก)</label><input type="text" id="admin-set-phone" class="form-control rounded-0 mb-2" value="<?= htmlspecialchars($adminPhone) ?>" maxlength="10"><label class="form-label small fw-bold">รหัสผ่านใหม่ <span class="text-danger">(ไม่เปลี่ยนปล่อยว่าง)</span></label><input type="password" id="admin-set-pass" class="form-control rounded-0 mb-3"></div><button class="btn btn-warning w-100 fw-bold rounded-0" onclick="saveAdminProfile()">บันทึกข้อมูล</button></div></div></div></div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script> <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() { $('#orderTable').DataTable({ "language": { "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/th.json" }, "order": [[ 0, "desc" ]] }); });
        function viewOrderDetails(orderId, currentStatus, address) {
            document.getElementById('modal-order-id').innerText = 'ORD-' + String(orderId).padStart(5, '0'); document.getElementById('form-order-id').value = orderId; document.getElementById('modal-status').value = currentStatus; document.getElementById('modal-address').innerText = address || 'ไม่มีข้อมูล';
            new bootstrap.Modal(document.getElementById('orderDetailModal')).show();
            const container = document.getElementById('order-items-container'); container.innerHTML = '<div class="text-center py-3"><div class="spinner-border text-dark"></div></div>';
            fetch('admin_orders.php?action=get_order_details&order_id=' + orderId).then(res => res.json()).then(data => {
                if(data.success && data.items.length > 0) {
                    let html = '<table class="table table-bordered align-middle"><thead><tr class="bg-light"><th>สินค้า</th><th>ไซส์</th><th>จำนวน</th><th>ราคา/ชิ้น</th></tr></thead><tbody>';
                    data.items.forEach(item => { let imgUrl = item.image ? (item.image.startsWith('http') || item.image.startsWith('data:') ? item.image : 'uploads/' + item.image) : 'default.jpg'; html += `<tr><td><div class="d-flex align-items-center"><img src="${imgUrl}" onerror="this.src='https://dummyimage.com/150x200/f5f5f5/a3a3a3.png&text=No+Image'" width="40" height="50" style="object-fit:cover;" class="me-2 border bg-white">${item.name || 'สินค้า'}</div></td><td class="text-center">${item.size}</td><td class="text-center">${item.qty}</td><td class="text-end">฿${parseFloat(item.price).toLocaleString()}</td></tr>`; });
                    html += '</tbody></table>'; container.innerHTML = html;
                } else container.innerHTML = '<div class="alert alert-warning">ไม่พบรายการสินค้า</div>';
            });
        }
        function saveAdminProfile() { const name = document.getElementById('admin-set-name').value.trim(); const phone = document.getElementById('admin-set-phone').value.trim(); const pass = document.getElementById('admin-set-pass').value.trim(); const pic = document.getElementById('admin-setting-pic').src; if(!name || !phone) return alert('กรุณากรอกชื่อและเบอร์โทรศัพท์'); fetch('api_auth.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ action: 'update_profile', fullname: name, phone: phone, password: pass, profile_pic: pic.startsWith('data:image') ? pic : '' }) }).then(r=>r.json()).then(d=>{ if(d.success) location.reload(); else alert('Error: '+d.message); }); }
    </script>
</body>
</html>