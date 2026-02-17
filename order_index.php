<?php
$conn = new mysqli("localhost", "root", "", "my_shop");
// ใช้ SQL JOIN เพื่อดึงชื่อลูกค้ามาจากตาราง users
$sql = "SELECT orders.*, users.fullname 
        FROM orders 
        JOIN users ON orders.user_id = users.user_id 
        ORDER BY orders.order_date DESC";
$result = $conn->query($sql);
?>
<body class="bg-light">
    <?php include('navbar.php'); ?>
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-danger text-white">
                <h4>4. จัดการออเดอร์</h4>
            </div>
            <div class="card-body">
                <table id="orderTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>เลขที่สั่งซื้อ</th>
                            <th>วันที่</th>
                            <th>ชื่อลูกค้า (ผู้สั่ง)</th> <th>ราคารวม</th>
                            <th>ที่อยู่จัดส่ง</th> <th>รายละเอียด</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($result): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo $row['order_id']; ?></td>
                            <td><?php echo $row['order_date']; ?></td>
                            <td><?php echo $row['fullname']; ?></td>
                            <td><?php echo number_format($row['total_price'], 2); ?></td>
                            <td><?php echo $row['shipping_address']; ?></td>
                            <td>
                                <a href="order_detail.php?id=<?php echo $row['order_id']; ?>" class="btn btn-primary btn-sm">ดูรายละเอียด</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>