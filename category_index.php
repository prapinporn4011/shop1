<?php
$conn = new mysqli("localhost", "root", "", "my_shop");
$result = $conn->query("SELECT * FROM categories");
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include('navbar.php'); ?>
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-dark text-white d-flex justify-content-between">
                <h4>2. จัดการประเภทสินค้า</h4>
                <a href="category_add.php" class="btn btn-light btn-sm">+ เพิ่มประเภท</a>
            </div>
            <div class="card-body">
                <table id="catTable" class="table table-hover">
                    <thead>
                        <tr>
                            <th>รหัส</th>
                            <th>ชื่อประเภทสินค้า</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['cat_id']; ?></td>
                            <td><?php echo $row['cat_name']; ?></td>
                            <td>
                                <a href="#" class="btn btn-warning btn-sm">แก้ไข</a>
                                <a href="#" class="btn btn-danger btn-sm">ลบ</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>$(document).ready(function() { $('#catTable').DataTable(); });</script>
</body>
</html>