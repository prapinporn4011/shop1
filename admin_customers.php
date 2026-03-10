<?php
session_start();
require_once 'db.php';

// เช็คสิทธิ์การเข้าถึง (ต้องล็อกอินและเป็น admin เท่านั้น)
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('❌ คุณไม่มีสิทธิ์เข้าถึงระบบหลังบ้าน!'); window.location.href='indexnew.php';</script>";
    exit;
}

// ---------------------------------------------------------
// 1. ระบบแก้ไขข้อมูลลูกค้า (Edit)
// ---------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = $_POST['id'];
    $fullname = trim($_POST['fullname']);
    $phone = trim($_POST['phone']);
    $role = $_POST['role'];
    
    $stmt = $pdo->prepare("UPDATE users SET fullname=?, phone=?, role=? WHERE id=?");
    $stmt->execute([$fullname, $phone, $role, $id]);
    
    header("Location: admin_customers.php?status=edited");
    exit;
}

// ---------------------------------------------------------
// 2. ระบบลบข้อมูลลูกค้า (Delete)
// ---------------------------------------------------------
if (isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];
    
    // ป้องกันไม่ให้แอดมินลบบัญชีตัวเองที่กำลังล็อกอินอยู่
    if ($id !== (int)$_SESSION['user_id']) {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: admin_customers.php?status=deleted");
    } else {
        header("Location: admin_customers.php?status=error_self");
    }
    exit;
}

// ดึงข้อมูลผู้ใช้งานทั้งหมดมาแสดง
$stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php
// เพิ่มส่วนนี้เพื่อดึงข้อมูลแอดมินจาก Database มาแสดงที่ Sidebar
$stmtAdmin = $pdo->prepare("SELECT fullname, profile_pic FROM users WHERE id = ?");
$stmtAdmin->execute([$_SESSION['user_id']]);
$adminData = $stmtAdmin->fetch();
$adminName = $adminData['fullname'] ?: $_SESSION['username'];
$adminPic = (!empty($adminData['profile_pic']) && strpos($adminData['profile_pic'], 'data:image') === 0) 
            ? $adminData['profile_pic'] 
            : 'https://ui-avatars.com/api/?name='.urlencode($adminName).'&background=ffb800&color=000';
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
        :root { --primary: #111111; --accent: #ffb800; }
        body { font-family: 'Sarabun', sans-serif; background: #f8fafc; }
        h1, h2, h3, h4, h5, h6, .btn, th, .sport-font { font-family: 'Kanit', sans-serif; }
        
        .sidebar { 
            min-height: 100vh; 
            background: linear-gradient(to bottom, rgba(17, 17, 17, 0.95), rgba(17, 17, 17, 0.98)), url('https://upload.wikimedia.org/wikipedia/commons/thumb/8/8c/Cristiano_Ronaldo_2018.jpg/800px-Cristiano_Ronaldo_2018.jpg');
            background-size: cover; background-position: center;
            color: white; width: 260px; position: fixed; z-index: 1000; 
            box-shadow: 4px 0 20px rgba(0,0,0,0.1);
        }
        
        .sidebar-header { text-align: center; padding: 30px 20px 20px; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-header img { width: 90px; height: 90px; border-radius: 50%; border: 3px solid var(--accent); margin-bottom: 10px; object-fit: cover; background: #fff;}
        .edit-profile-btn { font-size: 12px; color: #aaa; text-decoration: underline; cursor: pointer; }
        .edit-profile-btn:hover { color: var(--accent); }
        
        .sidebar a.menu-link { color: #cbd5e1; text-decoration: none; padding: 15px 25px; display: block; border-bottom: 1px solid rgba(255,255,255,0.05); font-family: 'Kanit', sans-serif; font-size: 1.05rem; transition: 0.3s; }
        .sidebar a.menu-link:hover, .sidebar a.menu-link.active { background: rgba(255, 184, 0, 0.1); color: var(--accent); border-left: 4px solid var(--accent); padding-left: 21px; font-weight: 500; }
        
        .main-content { margin-left: 260px; padding: 30px; }
        .card { border-radius: 0; border: 1px solid #eee; box-shadow: none; }
        .table-dark { background-color: var(--primary) !important; color: #fff; }
        .btn-warning { background-color: var(--accent); font-weight: 600; color: #000; border-radius: 0; }
        .btn-dark { border-radius: 0; }
        .text-warning { color: var(--accent) !important; }
        
        /* Modal Profile Admin */
        .profile-img-preview { width: 100px; height: 100px; object-fit: cover; border-radius: 50%; border: 2px solid var(--accent); cursor: pointer; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header">
            <img id="sb-profile-pic" src="<?= htmlspecialchars($adminPic) ?>" alt="Admin Profile">
            <h5 class="text-white fw-bold m-0 sport-font" id="sb-admin-name"><?= htmlspecialchars($adminName) ?></h5>
            <span class="badge bg-danger mb-2">Admin</span><br>
            <span class="edit-profile-btn sport-font" data-bs-toggle="modal" data-bs-target="#adminProfileModal"><i class="fa fa-edit"></i> แก้ไขโปรไฟล์</span>
        </div>
        
        <a href="admin_products.php" class="menu-link"><i class="fa fa-tshirt me-2"></i> จัดการสินค้า</a>
        <a href="admin_orders.php" class="menu-link"><i class="fa fa-box me-2"></i> จัดการออเดอร์</a>
        <a href="admin_categories.php" class="menu-link"><i class="fa fa-tags me-2"></i> จัดการประเภทสินค้า</a>
        <a href="admin_customers.php" class="menu-link"><i class="fa fa-users me-2"></i> จัดการลูกค้า</a>
        <a href="indexnew.php" class="menu-link text-info mt-5" style="border-top: 1px solid rgba(255,255,255,0.1);"><i class="fa fa-store me-2"></i> กลับหน้าร้าน</a>
    </div>

    <div class="modal fade" id="adminProfileModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content rounded-0">
                <div class="modal-header bg-dark text-white rounded-0">
                    <h5 class="modal-title fw-bold sport-font">แก้ไขโปรไฟล์แอดมิน</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center sport-font">
                    <label for="admin-profile-upload" class="position-relative d-inline-block mb-3">
                        <img id="admin-setting-pic" src="<?= htmlspecialchars($adminPic) ?>" class="profile-img-preview shadow-sm">
                        <div class="position-absolute bottom-0 end-0 bg-warning rounded-circle p-2 border border-dark" style="cursor: pointer;"><i class="fa fa-camera small text-dark"></i></div>
                    </label>
                    <input type="file" id="admin-profile-upload" class="d-none" accept="image/*" onchange="uploadAdminPic(event)">
                    
                    <div class="text-start">
                        <label class="form-label small fw-bold">ชื่อแสดงผล</label>
                        <input type="text" id="admin-set-name" class="form-control rounded-0 mb-3" value="<?= htmlspecialchars($adminName) ?>">
                        <label class="form-label small fw-bold">รหัสผ่านใหม่ <span class="text-danger">(ไม่เปลี่ยนปล่อยว่าง)</span></label>
                        <input type="password" id="admin-set-pass" class="form-control rounded-0 mb-3">
                    </div>
                    <button class="btn btn-warning w-100 fw-bold rounded-0" onclick="saveAdminProfile()">บันทึกข้อมูล</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function uploadAdminPic(event) {
            const file = event.target.files[0];
            if(file) {
                const reader = new FileReader();
                reader.onload = function(e) { document.getElementById('admin-setting-pic').src = e.target.result; }
                reader.readAsDataURL(file);
            }
        }

        function saveAdminProfile() {
            const newName = document.getElementById('admin-set-name').value.trim();
            const newPass = document.getElementById('admin-set-pass').value.trim();
            const newPic = document.getElementById('admin-setting-pic').src;
            
            if(!newName) return alert('กรุณากรอกชื่อแสดงผล');

            let picData = newPic.startsWith('data:image') ? newPic : '';

            fetch('api_auth.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'update_profile', fullname: newName, phone: '0000000000', password: newPass, profile_pic: picData })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    alert('อัปเดตโปรไฟล์เรียบร้อยแล้ว');
                    location.reload(); // รีเฟรชหน้าเพื่อแสดงข้อมูลใหม่
                } else {
                    alert('เกิดข้อผิดพลาด: ' + data.message);
                }
            });
        }
    </script>
</body>
</html>