<?php
// ส่วนการเชื่อมต่อฐานข้อมูล (ปรับแก้ตามจริงของคุณ)
$host = "localhost";
$user = "root";
$pass = "";
$db   = "your_database_name"; // ใส่ชื่อ DB ของคุณ

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

// ดึงข้อมูลออเดอร์ทั้งหมด
$sql = "SELECT * FROM orders ORDER BY order_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>หลังร้าน - จัดการออเดอร์</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark shadow-sm">
    <div class="container">
        <span class="navbar-brand fw-bold"> ThanJai shop</span>
        <a href="a.php" class="btn btn-outline-light btn-sm">ไปที่หน้าร้าน</a>
    </div>
</nav>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="list-group shadow-sm">
                <a href="c.php" class="list-group-item list-group-item-action">📦 จัดการสินค้า</a>
                <a href="d.php" class="list-group-item list-group-item-action">👥 จัดการลูกค้า</a>
                <a href="b.php" class="list-group-item list-group-item-action active">📝 รายการสั่งซื้อ (ออเดอร์)</a>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card border-0 shadow-sm p-4">
                <h4><i class="fa fa-clipboard-list text-primary"></i> จัดการออเดอร์</h4>
                <hr>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>ชื่อลูกค้า</th>
                                <th>ยอดรวม</th>
                                <th>สถานะ</th>
                                <th>จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo $row['order_id']; ?></td>
                                <td><?php echo $row['customer_name']; ?></td>
                                <td>฿<?php echo number_format($row['total_amount'], 2); ?></td>
                                <td>
                                    <?php if($row['status'] == 'pending'): ?>
                                        <span class="badge bg-warning text-dark">รอส่ง</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">ส่งแล้ว</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" 
                                            onclick="showDetail(<?php echo htmlspecialchars(json_encode($row)); ?>)">
                                        <i class="fa fa-search"></i> ดูรายละเอียด
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">ข้อมูลการจัดส่ง</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>สั่งซื้อโดย:</strong> <span id="m_name"></span></p>
                <p><strong>อีเมล:</strong> <span id="m_email"></span></p>
                <hr>
                <p><strong>ที่อยู่ที่ต้องจัดส่งไปที่ไหน:</strong></p>
                <div class="p-3 bg-light border rounded" id="m_address"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// ฟังก์ชัน Javascript สำหรับส่งค่าจาก PHP เข้า Modal
function showDetail(data) {
    document.getElementById('m_name').innerText = data.customer_name;
    document.getElementById('m_email').innerText = data.customer_email;
    document.getElementById('m_address').innerText = data.shipping_address;
    
    var myModal = new bootstrap.Modal(document.getElementById('detailModal'));
    myModal.show();
}
</script>

</body>
</html>