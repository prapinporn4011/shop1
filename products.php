<?php
require 'db.php'; // ดึงไฟล์เชื่อมต่อฐานข้อมูล

// -------------------------------------------------------------------------
// 1. ส่วนจัดการข้อมูล (เพิ่ม / แก้ไข / ลบ) จะทำงานก่อนโหลดหน้าเว็บ
// -------------------------------------------------------------------------

// --- กรณี: ลบข้อมูล ---
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    try {
        $stmt = $conn->prepare("DELETE FROM products WHERE id = :id");
        $stmt->execute([':id' => $_GET['id']]);
        echo "<script>alert('ลบสินค้าสำเร็จ!'); window.location='manage_products.php';</script>";
        exit();
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// --- กรณี: เพิ่ม หรือ แก้ไข ข้อมูล (รับค่าจาก Form) ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $name = $_POST['name'];
    $detail = $_POST['detail'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    try {
        if ($_POST['action'] == 'add') {
            // โค้ดเพิ่มข้อมูล
            $sql = "INSERT INTO products (name, detail, price, stock) VALUES (:name, :detail, :price, :stock)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':name' => $name, ':detail' => $detail, ':price' => $price, ':stock' => $stock]);
            echo "<script>alert('เพิ่มสินค้าเรียบร้อย!'); window.location='manage_products.php';</script>";
            
        } elseif ($_POST['action'] == 'edit') {
            // โค้ดแก้ไขข้อมูล
            $id = $_POST['id'];
            $sql = "UPDATE products SET name = :name, detail = :detail, price = :price, stock = :stock WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':name' => $name, ':detail' => $detail, ':price' => $price, ':stock' => $stock, ':id' => $id]);
            echo "<script>alert('อัปเดตข้อมูลสำเร็จ!'); window.location='manage_products.php';</script>";
        }
        exit();
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// -------------------------------------------------------------------------
// 2. ดึงข้อมูลสินค้าทั้งหมดมาแสดงผล
// -------------------------------------------------------------------------
$stmt = $conn->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการสินค้า - หน้าเดียวจบ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5 bg-white p-4 rounded shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📦 จัดการสินค้า (หลังร้าน)</h2>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">
            + เพิ่มสินค้าใหม่
        </button>
    </div>

    <div class="table-responsive">
        <table id="productTable" class="table table-striped table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>รหัส</th>
                    <th>ชื่อสินค้า</th>
                    <th>รายละเอียด</th>
                    <th>ราคา</th>
                    <th>สต๊อก</th>
                    <th class="text-center">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($products as $row): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['detail']) ?></td>
                    <td><?= number_format($row['price'], 2) ?> ฿</td>
                    <td><?= $row['stock'] ?></td>
                    <td class="text-center">
                        <button class="btn btn-warning btn-sm" onclick="openEditModal(<?= $row['id'] ?>, '<?= htmlspecialchars(addslashes($row['name'])) ?>', '<?= htmlspecialchars(addslashes($row['detail'])) ?>', <?= $row['price'] ?>, <?= $row['stock'] ?>)">
                            แก้ไข
                        </button>
                        <a href="?action=delete&id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบสินค้านี้?');">ลบ</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="" method="POST">
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title">เพิ่มสินค้าใหม่</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="action" value="add"> <div class="mb-3">
                <label class="form-label">ชื่อสินค้า</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">รายละเอียด</label>
                <textarea name="detail" class="form-control" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">ราคา</label>
                <input type="number" step="0.01" name="price" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">จำนวนสต๊อก</label>
                <input type="number" name="stock" class="form-control" value="0" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
            <button type="submit" class="btn btn-success">บันทึกสินค้า</button>
          </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="" method="POST">
          <div class="modal-header bg-warning">
            <h5 class="modal-title">แก้ไขสินค้า</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="action" value="edit"> <input type="hidden" name="id" id="edit_id"> <div class="mb-3">
                <label class="form-label">ชื่อสินค้า</label>
                <input type="text" name="name" id="edit_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">รายละเอียด</label>
                <textarea name="detail" id="edit_detail" class="form-control" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">ราคา</label>
                <input type="number" step="0.01" name="price" id="edit_price" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">จำนวนสต๊อก</label>
                <input type="number" name="stock" id="edit_stock" class="form-control" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
            <button type="submit" class="btn btn-warning">บันทึกการแก้ไข</button>
          </div>
      </form>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // เปิดใช้งาน DataTables 
    $(document).ready(function() {
        $('#productTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/th.json"
            }
        });
    });

    // ฟังก์ชันสำหรับเปิด Modal แก้ไขและดึงข้อมูลเดิมมาใส่ในช่องกรอก
    function openEditModal(id, name, detail, price, stock) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_detail').value = detail;
        document.getElementById('edit_price').value = price;
        document.getElementById('edit_stock').value = stock;
        
        // สั่งเปิด Modal
        var editModal = new bootstrap.Modal(document.getElementById('editModal'));
        editModal.show();
    }
</script>

</body>
</html>