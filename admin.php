<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Admin - ระบบจัดการสต็อกสมบูรณ์แบบ</title>
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark mb-4 shadow">
    <div class="container">
        <span class="navbar-brand mb-0 h1"><i class="fa fa-gears"></i> ระบบหลังบ้าน Admin</span>
        <a href="shop.php" class="btn btn-outline-light btn-sm"><i class="fa fa-eye"></i> ดูหน้าร้าน</a>
    </div>
</nav>

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h5 class="fw-bold mb-3 text-primary"><i class="fa fa-plus-circle"></i> เพิ่มสินค้าใหม่</h5>
                <form action="process.php?action=add" method="POST">
                    <div class="mb-3">
                        <label class="form-label">ชื่อสินค้า</label>
                        <input type="text" name="p_name" class="form-control" placeholder="เช่น เสื้อทีมชาติ" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ราคา (บาท)</label>
                        <input type="number" name="p_price" class="form-control" placeholder="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">จำนวนสต็อก</label>
                        <input type="number" name="p_qty" class="form-control" placeholder="0" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 shadow-sm">บันทึกข้อมูล</button>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <table class="table table-hover align-middle mb-0 text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>ชื่อสินค้า</th>
                            <th>ราคา</th>
                            <th>สต็อก</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM products ORDER BY id DESC";
                        $result = mysqli_query($conn, $query);
                        while($row = mysqli_fetch_assoc($result)) {
                        ?>
                        <tr>
                            <td class="text-start fw-bold"><?php echo $row['p_name']; ?></td>
                            <td><?php echo number_format($row['p_price']); ?> ฿</td>
                            <td>
                                <?php if($row['p_qty'] > 0): ?>
                                    <span class="badge bg-success">คงเหลือ <?php echo $row['p_qty']; ?></span>
                                <?php else: ?>
                                    <span class="badge bg-danger">สินค้าหมด</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning text-white" 
                                        onclick="openEditModal(<?php echo $row['id']; ?>, '<?php echo $row['p_name']; ?>', <?php echo $row['p_price']; ?>, <?php echo $row['p_qty']; ?>)">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <a href="process.php?action=delete&id=<?php echo $row['id']; ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('คุณแน่ใจหรือไม่ที่จะลบสินค้านี้?')">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title"><i class="fa fa-edit"></i> แก้ไขข้อมูลสินค้า</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="process.php?action=update" method="POST">
                <div class="modal-body p-4">
                    <input type="hidden" name="id" id="edit-id">
                    <div class="mb-3">
                        <label class="form-label">ชื่อสินค้า</label>
                        <input type="text" name="p_name" id="edit-name" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">ราคา</label>
                        <input type="number" name="p_price" id="edit-price" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">สต็อก</label>
                        <input type="number" name="p_qty" id="edit-qty" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-warning text-white px-4">ยืนยันการแก้ไข</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function openEditModal(id, name, price, qty) {
    document.getElementById('edit-id').value = id;
    document.getElementById('edit-name').value = name;
    document.getElementById('edit-price').value = price;
    document.getElementById('edit-qty').value = qty;
    new bootstrap.Modal(document.getElementById('editModal')).show();
}
</script>
</body>
</html>