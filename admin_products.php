<?php
session_start();
require_once 'db.php';

// ระบบเพิ่มสินค้า (เมื่อมีการกดปุ่ม Submit จาก Modal)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    
    // ระบบอัปโหลดรูปภาพเบื้องต้น (ถ้าไม่มีให้อิงภาพ default)
    $image = 'default.jpg'; 
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], $image); // บันทึกรูปลงโฟลเดอร์ปัจจุบัน
    }

    $stmt = $pdo->prepare("INSERT INTO products (category_id, name, price, description, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$category_id, $name, $price, $description, $image]);
    
    header("Location: admin_products.php?status=success");
    exit;
}

// ระบบลบสินค้า
if (isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];
    $pdo->query("DELETE FROM products WHERE id = $id");
    header("Location: admin_products.php?status=deleted");
    exit;
}

// ดึงข้อมูลสินค้าทั้งหมด
$stmt = $pdo->query("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ดึงข้อมูลประเภทสินค้าสำหรับใช้ใน Dropdown ตอนเพิ่มสินค้า
$catStmt = $pdo->query("SELECT * FROM categories");
$categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการสินค้า - หลังบ้าน</title>
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
        <a href="admin_products.php" class="active"><i class="fa fa-tshirt me-2"></i> จัดการสินค้า</a>
        <a href="admin_orders.php"><i class="fa fa-box me-2"></i> จัดการออเดอร์</a>
        <a href="#"><i class="fa fa-tags me-2"></i> จัดการประเภทสินค้า</a>
        <a href="#"><i class="fa fa-users me-2"></i> จัดการลูกค้า</a>
        <a href="indexnew.php" class="text-info mt-5"><i class="fa fa-store me-2"></i> กลับหน้าร้าน</a>
    </div>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold"><i class="fa fa-tshirt me-2"></i>ระบบจัดการสินค้า</h2>
            <button class="btn btn-warning fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addProductModal">
                <i class="fa fa-plus"></i> เพิ่มสินค้าใหม่
            </button>
        </div>

        <?php if(isset($_GET['status']) && $_GET['status'] == 'success'): ?>
            <div class="alert alert-success"><i class="fa fa-check-circle"></i> บันทึกข้อมูลสินค้าเรียบร้อยแล้ว!</div>
        <?php endif; ?>
        <?php if(isset($_GET['status']) && $_GET['status'] == 'deleted'): ?>
            <div class="alert alert-danger"><i class="fa fa-trash"></i> ลบข้อมูลสินค้าเรียบร้อยแล้ว!</div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <table id="productTable" class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>รูปภาพ</th>
                            <th>ชื่อสินค้า</th>
                            <th>ประเภท</th>
                            <th>ราคา</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($products as $p): ?>
                        <tr>
                            <td><?= $p['id'] ?></td>
                            <td><img src="<?= htmlspecialchars($p['image']) ?>" width="50" class="rounded border"></td>
                            <td><?= htmlspecialchars($p['name']) ?></td>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($p['category_name']) ?></span></td>
                            <td class="text-danger fw-bold">฿<?= number_format($p['price'], 2) ?></td>
                            <td>
                                <a href="#" class="btn btn-sm btn-info text-white"><i class="fa fa-edit"></i> แก้ไข</a>
                                <a href="admin_products.php?delete_id=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('ยืนยันการลบสินค้าชิ้นนี้?');"><i class="fa fa-trash"></i> ลบ</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addProductModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title fw-bold">เพิ่มสินค้าใหม่</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="admin_products.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">ชื่อสินค้า</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label fw-bold">ประเภท</label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">-- เลือกประเภท --</option>
                                    <?php foreach($categories as $c): ?>
                                        <option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label fw-bold">ราคา (บาท)</label>
                                <input type="number" name="price" class="form-control" step="0.01" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">รายละเอียด</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">รูปภาพ</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
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

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            // เรียกใช้งาน DataTables พร้อมรองรับการค้นหา (ตามโจทย์)
            $('#productTable').DataTable({
                "language": { "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/th.json" }
            });
        });
    </script>
</body>
</html>