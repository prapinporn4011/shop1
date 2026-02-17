<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Admin - จัดการสต็อก</title>
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <span class="navbar-brand mb-0 h1">ระบบหลังบ้าน Admin</span>
        <a href="shop.php" class="btn btn-outline-light btn-sm"><i class="fa fa-eye"></i> ดูหน้าร้าน</a>
    </div>
</nav>

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h5 class="fw-bold mb-3">เพิ่มสินค้าใหม่</h5>
                <form action="save_product.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">ชื่อสินค้า</label>
                        <input type="text" name="p_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ราคา</label>
                        <input type="number" name="p_price" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">จำนวนสต็อก</label>
                        <input type="number" name="p_qty" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">บันทึกข้อมูล</button>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ชื่อสินค้า</th>
                            <th>ราคา</th>
                            <th>สต็อก</th>
                            <th class="text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM products ORDER BY id DESC";
                        $result = mysqli_query($conn, $query);
                        while($row = mysqli_fetch_assoc($result)) {
                        ?>
                        <tr>
                            <td><?php echo $row['p_name']; ?></td>
                            <td><?php echo number_format($row['p_price']); ?> บาท</td>
                            <td><span class="badge bg-success"><?php echo $row['p_qty']; ?></span></td>
                            <td class="text-center">
                                <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('ยืนยันการลบ?')"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>