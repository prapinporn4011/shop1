<?php
session_start();
require_once 'db.php';

// เช็คสิทธิ์การเข้าถึง (ต้องล็อกอินและเป็น admin เท่านั้น)
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('❌ คุณไม่มีสิทธิ์เข้าถึงระบบหลังบ้าน!'); window.location.href='indexnew.php';</script>";
    exit;
}

// ---------------------------------------------------------
// 1. ระบบเพิ่มประเภทสินค้า (Add)
// ---------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = trim($_POST['name']);
    
    if (!empty($name)) {
        $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->execute([$name]);
    }
    header("Location: admin_categories.php?status=success");
    exit;
}

// ---------------------------------------------------------
// 2. ระบบแก้ไขประเภทสินค้า (Edit)
// ---------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = $_POST['id'];
    $name = trim($_POST['name']);
    
    if (!empty($name)) {
        $stmt = $pdo->prepare("UPDATE categories SET name=? WHERE id=?");
        $stmt->execute([$name, $id]);
    }
    header("Location: admin_categories.php?status=edited");
    exit;
}

// ---------------------------------------------------------
// 3. ระบบลบประเภทสินค้า (Delete)
// ---------------------------------------------------------
if (isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];
    
    // ลบข้อมูล
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    
    header("Location: admin_categories.php?status=deleted");
    exit;
}

// ดึงข้อมูลประเภทสินค้าทั้งหมดมาแสดง
$stmt = $pdo->query("SELECT * FROM categories ORDER BY id DESC");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            <h2 class="fw-bold"><i class="fa fa-tags me-2"></i>ระบบจัดการประเภทสินค้า</h2>
            <button class="btn btn-warning fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal"><i class="fa fa-plus"></i> เพิ่มประเภทใหม่</button>
        </div>

        <?php if(isset($_GET['status'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fa fa-check-circle"></i> 
                <?php if($_GET['status'] == 'success') echo "เพิ่มประเภทสินค้าเรียบร้อยแล้ว!"; elseif($_GET['status'] == 'edited') echo "แก้ไขประเภทสินค้าเรียบร้อยแล้ว!"; elseif($_GET['status'] == 'deleted') echo "ลบประเภทสินค้าเรียบร้อยแล้ว!"; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body table-responsive">
                        <table id="categoryTable" class="table table-striped table-hover align-middle w-100">
                            <thead class="table-dark"><tr><th>ID</th><th>ชื่อประเภทสินค้า</th><th>จัดการ</th></tr></thead>
                            <tbody>
                                <?php foreach($categories as $c): ?>
                                <tr>
                                    <td><?= $c['id'] ?></td>
                                    <td class="fw-bold text-primary"><?= htmlspecialchars($c['name']) ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-info text-white" onclick="editCategory(<?= $c['id'] ?>, '<?= htmlspecialchars(addslashes($c['name'])) ?>')"><i class="fa fa-edit"></i> แก้ไข</button>
                                        <a href="admin_categories.php?delete_id=<?= $c['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('ยืนยันการลบประเภทสินค้านี้?');"><i class="fa fa-trash"></i> ลบ</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-warning"><h5 class="modal-title fw-bold">เพิ่มประเภทใหม่</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <form action="admin_categories.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add">
                        <div class="mb-3">
                            <label class="form-label fw-bold">ชื่อประเภทสินค้า (เช่น เสื้อผู้รักษาประตู)</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-dark">บันทึกข้อมูล</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editCategoryModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-info text-white"><h5 class="modal-title fw-bold"><i class="fa fa-edit"></i> แก้ไขประเภทสินค้า</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
                <form action="admin_categories.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" id="edit-id">
                        <div class="mb-3">
                            <label class="form-label fw-bold">ชื่อประเภทสินค้า</label>
                            <input type="text" name="name" id="edit-name" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-info text-white fw-bold">อัปเดตข้อมูล</button>
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
        $(document).ready(function() { $('#categoryTable').DataTable({ "language": { "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/th.json" }, "order": [[ 0, "desc" ]] }); });
        
        function editCategory(id, name) {
            document.getElementById('edit-id').value = id; 
            document.getElementById('edit-name').value = name;
            new bootstrap.Modal(document.getElementById('editCategoryModal')).show();
        }
    </script>
</body>
</html>