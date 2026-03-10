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
        body { font-family: 'Sarabun', sans-serif; background: #f8fafc; }
        h1, h2, h3, h4, h5, h6, .btn, th, .sport-font { font-family: 'Kanit', sans-serif; }
        
        /* เปลี่ยน Sidebar ให้เป็นภาพพื้นหลัง CR7 โทนดาร์ค */
        /* เปลี่ยน Sidebar ให้เป็นภาพพื้นหลัง CR7 โทนดาร์ค */
        /* เปลี่ยน Sidebar ให้เป็นภาพพื้นหลัง CR7 โทนดาร์ค */
        .sidebar { 
            min-height: 100vh; 
            background: linear-gradient(to bottom, rgba(10, 10, 10, 0.95), rgba(30, 41, 59, 0.95)), url('https://upload.wikimedia.org/wikipedia/commons/thumb/8/8c/Cristiano_Ronaldo_2018.jpg/800px-Cristiano_Ronaldo_2018.jpg');
            background-size: cover; background-position: center;
            color: white; width: 260px; position: fixed; z-index: 1000; 
            box-shadow: 4px 0 20px rgba(0,0,0,0.3);
        }
        
        /* ส่วนหัวโลโก้ Sidebar */
        .sidebar-header {
            text-align: center; padding: 30px 20px 20px; border-bottom: 1px solid rgba(255,255,255,0.1); background: rgba(0,0,0,0.3); backdrop-filter: blur(5px);
        }
        .sidebar-header img { width: 90px; height: 90px; border-radius: 50%; border: 3px solid #FBBF24; margin-bottom: 15px; box-shadow: 0 0 15px rgba(251, 191, 36, 0.5); object-fit: cover; object-position: top;}
        
        /* ปุ่มเมนู */
        .sidebar a { color: #cbd5e1; text-decoration: none; padding: 15px 25px; display: block; border-bottom: 1px solid rgba(255,255,255,0.05); font-family: 'Kanit', sans-serif; font-size: 1.05rem; letter-spacing: 0.5px; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background: rgba(251, 191, 36, 0.15); color: #FBBF24; border-left: 5px solid #FBBF24; padding-left: 20px; font-weight: 500; }
        
        /* พื้นที่เนื้อหาหลัก */
        .main-content { margin-left: 260px; padding: 30px; }
        .card { border-radius: 10px; border: none; box-shadow: 0 4px 10px -2px rgba(0, 0, 0, 0.1); }
        .table-dark { background-color: #0a0a0a !important; color: #fff; }
        .btn-warning { background-color: #FBBF24; font-weight: 600; color: #000; }
        .text-warning { color: #FBBF24 !important; }
    </style>
</head>
<body>

    <div class="sidebar-header">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/8c/Cristiano_Ronaldo_2018.jpg/400px-Cristiano_Ronaldo_2018.jpg" alt="Admin Pro">
            <h4 class="text-warning fw-bold m-0 sport-font text-uppercase">Admin Panel</h4>
            <small class="text-light opacity-75">Premium Sports Store</small>
        </div>
        
        <a href="admin_products.php"><i class="fa fa-tshirt me-2"></i> จัดการสินค้า</a>
        <a href="admin_orders.php"><i class="fa fa-box me-2"></i> จัดการออเดอร์</a>
        <a href="admin_categories.php"><i class="fa fa-tags me-2"></i> จัดการประเภทสินค้า</a>
        <a href="admin_customers.php"><i class="fa fa-users me-2"></i> จัดการลูกค้า</a>
        <a href="indexnew.php" class="text-info mt-5" style="border-top: 1px solid rgba(255,255,255,0.1);"><i class="fa fa-store me-2"></i> กลับหน้าร้าน</a>
    </div>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold"><i class="fa fa-users me-2"></i>ระบบจัดการลูกค้า</h2>
        </div>

        <?php if(isset($_GET['status'])): ?>
            <div class="alert alert-<?php echo $_GET['status'] == 'error_self' ? 'danger' : 'success'; ?> alert-dismissible fade show">
                <i class="fa fa-info-circle"></i> 
                <?php 
                    if($_GET['status'] == 'edited') echo "อัปเดตข้อมูลลูกค้าเรียบร้อยแล้ว!"; 
                    elseif($_GET['status'] == 'deleted') echo "ลบบัญชีลูกค้าเรียบร้อยแล้ว!"; 
                    elseif($_GET['status'] == 'error_self') echo "ไม่สามารถลบบัญชีของตัวเองที่กำลังใช้งานอยู่ได้!"; 
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm">
            <div class="card-body table-responsive">
                <table id="customerTable" class="table table-striped table-hover align-middle w-100">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>โปรไฟล์</th>
                            <th>ชื่อผู้ใช้ (Username)</th>
                            <th>ชื่อแสดงผล / อีเมล</th>
                            <th>เบอร์โทรศัพท์</th>
                            <th>สิทธิ์ (Role)</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($customers as $c): ?>
                        <tr>
                            <td><?= $c['id'] ?></td>
                            <td>
                                <?php 
                                    // เช็คว่ามีรูปโปรไฟล์แบบ Base64 ไหม ถ้าไม่มีให้ใช้รูปการ์ตูน
                                    $pic = (!empty($c['profile_pic']) && strpos($c['profile_pic'], 'data:image') === 0) 
                                            ? $c['profile_pic'] 
                                            : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . $c['username'];
                                ?>
                                <img src="<?= htmlspecialchars($pic) ?>" width="40" height="40" class="rounded-circle border bg-white" style="object-fit: cover;">
                            </td>
                            <td class="fw-bold"><?= htmlspecialchars($c['username']) ?></td>
                            <td>
                                <div><?= htmlspecialchars($c['fullname']) ?></div>
                                <small class="text-muted"><?= htmlspecialchars($c['email']) ?></small>
                            </td>
                            <td><?= htmlspecialchars($c['phone'] ?: '-') ?></td>
                            <td>
                                <?php if($c['role'] == 'admin'): ?>
                                    <span class="badge bg-danger">แอดมิน (Admin)</span>
                                <?php else: ?>
                                    <span class="badge bg-primary">ลูกค้า (User)</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info text-white" onclick="editCustomer(<?= $c['id'] ?>, '<?= htmlspecialchars(addslashes($c['fullname'])) ?>', '<?= htmlspecialchars(addslashes($c['phone'])) ?>', '<?= $c['role'] ?>')"><i class="fa fa-edit"></i> แก้ไข</button>
                                <?php if($c['id'] != $_SESSION['user_id']): // ซ่อนปุ่มลบถ้าเป็นบัญชีตัวเอง ?>
                                    <a href="admin_customers.php?delete_id=<?= $c['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('ยืนยันการลบบัญชีผู้ใช้นี้อย่างถาวร?');"><i class="fa fa-trash"></i> ลบ</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editCustomerModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title fw-bold"><i class="fa fa-user-edit"></i> แก้ไขข้อมูลลูกค้า</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="admin_customers.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" id="edit-id">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small">ชื่อแสดงผล</label>
                            <input type="text" name="fullname" id="edit-fullname" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">เบอร์โทรศัพท์</label>
                            <input type="text" name="phone" id="edit-phone" class="form-control" maxlength="10">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">สิทธิ์การใช้งาน (Role)</label>
                            <select name="role" id="edit-role" class="form-select border-dark">
                                <option value="user">ลูกค้าทั่วไป (User)</option>
                                <option value="admin" class="text-danger fw-bold">แอดมิน (Admin)</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-info text-white fw-bold">บันทึกข้อมูล</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() { 
            $('#customerTable').DataTable({ 
                "language": { "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/th.json" }, 
                "order": [[ 0, "desc" ]] // เรียงลูกค้าใหม่ล่าสุดขึ้นก่อน
            }); 
        });
        
        function editCustomer(id, fullname, phone, role) {
            document.getElementById('edit-id').value = id; 
            document.getElementById('edit-fullname').value = fullname;
            document.getElementById('edit-phone').value = phone;
            document.getElementById('edit-role').value = role;
            new bootstrap.Modal(document.getElementById('editCustomerModal')).show();
        }
    </script>
</body>
</html>