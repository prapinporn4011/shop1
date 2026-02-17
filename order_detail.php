<?php
$conn = new mysqli("localhost", "root", "", "my_shop");
$order_id = $_GET['id'];

// ดึงข้อมูลสินค้าที่อยู่ในออเดอร์นี้ โดย JOIN กับตาราง products เพื่อเอาชื่อสินค้ามาโชว์
$sql = "SELECT order_details.*, products.product_name 
        FROM order_details 
        JOIN products ON order_details.product_id = products.id 
        WHERE order_details.order_id = $order_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include('navbar.php'); ?>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4>รายละเอียดออเดอร์ #<?php echo $order_id; ?></h4>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ชื่อสินค้า</th>
                            <th>จำนวน</th>
                            <th>ราคาต่อชิ้น</th>
                            <th>รวม</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['product_name']; ?></td>
                            <td><?php echo $row['qty']; ?></td>
                            <td><?php echo number_format($row['price'], 2); ?></td>
                            <td><?php echo number_format($row['qty'] * $row['price'], 2); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <a href="order_index.php" class="btn btn-secondary">กลับหน้าหลัก</a>
            </div>
        </div>
    </div>
</body>
</html>