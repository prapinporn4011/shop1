<?php
session_start(); require_once 'db.php';
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { echo "<script>alert('❌ คุณไม่มีสิทธิ์!'); window.location.href='indexnew.php';</script>"; exit; }
$stmtAdmin = $pdo->prepare("SELECT fullname, phone, profile_pic FROM users WHERE id = ?"); $stmtAdmin->execute([$_SESSION['user_id']]); $adminData = $stmtAdmin->fetch();
$adminName = $adminData['fullname'] ?: $_SESSION['username']; $adminPhone = $adminData['phone'] ?: '';
$adminPic = (!empty($adminData['profile_pic']) && strpos($adminData['profile_pic'], 'data:image') === 0) ? $adminData['profile_pic'] : 'https://dummyimage.com/100x100/ffb800/000.png&text='.urlencode(mb_substr($adminName,0,2));

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') { $stmt = $pdo->prepare("UPDATE users SET fullname=?, phone=?, role=? WHERE id=?"); $stmt->execute([trim($_POST['fullname']), trim($_POST['phone']), $_POST['role'], $_POST['id']]); header("Location: admin_customers.php?status=edited"); exit; }
if (isset($_GET['delete_id'])) { $id = (int)$_GET['delete_id']; if ($id !== (int)$_SESSION['user_id']) { $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?"); $stmt->execute([$id]); header("Location: admin_customers.php?status=deleted"); } else header("Location: admin_customers.php?status=error_self"); exit; }

$stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC"); $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
$current_page = basename($_SERVER['PHP_SELF']);
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
        <h2 class="fw-bold mb-4 sport-font"><i class="fa fa-users me-2"></i>ระบบจัดการลูกค้า</h2>
        <?php if(isset($_GET['status'])): ?><div class="alert alert-<?= $_GET['status'] == 'error_self' ? 'danger' : 'success'; ?> alert-dismissible fade show sport-font"><i class="fa fa-info-circle"></i> <?php if($_GET['status'] == 'edited') echo "อัปเดตข้อมูลลูกค้าเรียบร้อยแล้ว!"; elseif($_GET['status'] == 'deleted') echo "ลบบัญชีลูกค้าเรียบร้อยแล้ว!"; elseif($_GET['status'] == 'error_self') echo "ไม่สามารถลบบัญชีของตัวเองที่กำลังใช้งานอยู่ได้!"; ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
        <div class="card border-0 shadow-sm sport-font">
            <div class="card-body table-responsive">
                <table id="customerTable" class="table table-striped table-hover align-middle w-100">
                    <thead class="table-dark"><tr><th>ID</th><th>โปรไฟล์</th><th>ชื่อผู้ใช้ (Username)</th><th>ชื่อแสดงผล / อีเมล</th><th>เบอร์โทรศัพท์</th><th>สิทธิ์ (Role)</th><th>จัดการ</th></tr></thead>
                    <tbody>
                        <?php foreach($customers as $c): ?>
                        <tr>
                            <td><?= $c['id'] ?></td>
                            <td><?php $pic = (!empty($c['profile_pic']) && strpos($c['profile_pic'], 'data:image') === 0) ? $c['profile_pic'] : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . $c['username']; ?><img src="<?= htmlspecialchars($pic) ?>" width="40" height="40" class="rounded-circle border bg-white" style="object-fit: cover;"></td>
                            <td class="fw-bold"><?= htmlspecialchars($c['username']) ?></td>
                            <td><div><?= htmlspecialchars($c['fullname']) ?></div><small class="text-muted"><?= htmlspecialchars($c['email']) ?></small></td>
                            <td><?= htmlspecialchars($c['phone'] ?: '-') ?></td>
                            <td><?php if($c['role'] == 'admin'): ?><span class="badge bg-danger rounded-0">แอดมิน (Admin)</span><?php else: ?><span class="badge bg-dark rounded-0">ลูกค้า (User)</span><?php endif; ?></td>
                            <td>
                                <button class="btn btn-sm btn-dark" onclick="editCustomer(<?= $c['id'] ?>, '<?= htmlspecialchars(addslashes($c['fullname'])) ?>', '<?= htmlspecialchars(addslashes($c['phone'])) ?>', '<?= $c['role'] ?>')"><i class="fa fa-edit"></i> แก้ไข</button>
                                <?php if($c['id'] != $_SESSION['user_id']): ?><a href="admin_customers.php?delete_id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-danger rounded-0" onclick="return confirm('ยืนยันการลบบัญชีผู้ใช้นี้อย่างถาวร?');"><i class="fa fa-trash"></i> ลบ</a><?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editCustomerModal" tabindex="-1"><div class="modal-dialog modal-dialog-centered modal-sm"><div class="modal-content rounded-0"><div class="modal-header bg-dark text-white rounded-0"><h5 class="modal-title fw-bold sport-font"><i class="fa fa-user-edit"></i> แก้ไขข้อมูลลูกค้า</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div><form action="admin_customers.php" method="POST"><div class="modal-body sport-font"><input type="hidden" name="action" value="edit"><input type="hidden" name="id" id="edit-id"><div class="mb-3"><label class="form-label fw-bold small">ชื่อแสดงผล</label><input type="text" name="fullname" id="edit-fullname" class="form-control rounded-0" required></div><div class="mb-3"><label class="form-label fw-bold small">เบอร์โทรศัพท์</label><input type="text" name="phone" id="edit-phone" class="form-control rounded-0" maxlength="10"></div><div class="mb-3"><label class="form-label fw-bold small">สิทธิ์การใช้งาน (Role)</label><select name="role" id="edit-role" class="form-select rounded-0 border-dark"><option value="user">ลูกค้าทั่วไป (User)</option><option value="admin" class="text-danger fw-bold">แอดมิน (Admin)</option></select></div></div><div class="modal-footer"><button type="button" class="btn btn-secondary rounded-0" data-bs-dismiss="modal">ยกเลิก</button><button type="submit" class="btn btn-warning rounded-0 fw-bold">บันทึกข้อมูล</button></div></form></div></div></div>
    
    <div class="modal fade" id="adminProfileModal" tabindex="-1"><div class="modal-dialog modal-dialog-centered modal-sm"><div class="modal-content rounded-0"><div class="modal-header bg-dark text-white rounded-0"><h5 class="modal-title fw-bold sport-font">ตั้งค่าโปรไฟล์แอดมิน</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div><div class="modal-body text-center sport-font"><label for="admin-profile-upload" class="position-relative d-inline-block mb-3"><img id="admin-setting-pic" src="<?= htmlspecialchars($adminPic) ?>" style="width:100px; height:100px; object-fit:cover; border-radius:50%; border:2px solid #ffb800; cursor:pointer;"><div class="position-absolute bottom-0 end-0 bg-warning rounded-circle p-2 border border-dark"><i class="fa fa-camera text-dark"></i></div></label><input type="file" id="admin-profile-upload" class="d-none" accept="image/*" onchange="const f=this.files[0]; if(f){ const r=new FileReader(); r.onload=e=>document.getElementById('admin-setting-pic').src=e.target.result; r.readAsDataURL(f); }"><div class="text-start"><label class="form-label small fw-bold">ชื่อแสดงผล</label><input type="text" id="admin-set-name" class="form-control rounded-0 mb-2" value="<?= htmlspecialchars($adminName) ?>"><label class="form-label small fw-bold">เบอร์โทรศัพท์ (10 หลัก)</label><input type="text" id="admin-set-phone" class="form-control rounded-0 mb-2" value="<?= htmlspecialchars($adminPhone) ?>" maxlength="10"><label class="form-label small fw-bold">รหัสผ่านใหม่ <span class="text-danger">(ไม่เปลี่ยนปล่อยว่าง)</span></label><input type="password" id="admin-set-pass" class="form-control rounded-0 mb-3"></div><button class="btn btn-warning w-100 fw-bold rounded-0" onclick="saveAdminProfile()">บันทึกข้อมูล</button></div></div></div></div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script> <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() { $('#customerTable').DataTable({ "language": { "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/th.json" }, "order": [[ 0, "desc" ]] }); });
        function editCustomer(id, fullname, phone, role) { document.getElementById('edit-id').value = id; document.getElementById('edit-fullname').value = fullname; document.getElementById('edit-phone').value = phone; document.getElementById('edit-role').value = role; new bootstrap.Modal(document.getElementById('editCustomerModal')).show(); }
        function saveAdminProfile() { const name = document.getElementById('admin-set-name').value.trim(); const phone = document.getElementById('admin-set-phone').value.trim(); const pass = document.getElementById('admin-set-pass').value.trim(); const pic = document.getElementById('admin-setting-pic').src; if(!name || !phone) return alert('กรุณากรอกชื่อและเบอร์โทรศัพท์'); fetch('api_auth.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ action: 'update_profile', fullname: name, phone: phone, password: pass, profile_pic: pic.startsWith('data:image') ? pic : '' }) }).then(r=>r.json()).then(d=>{ if(d.success) location.reload(); else alert('Error: '+d.message); }); }
    </script>
</body>
</html>