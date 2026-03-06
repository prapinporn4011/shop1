<?php
// --- PART 1: PHP LOGIC (ระบบหลังบ้าน) ---
$conn = new mysqli("localhost", "root", "", "thanjai_shop");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

// อัปเดตสถานะออเดอร์
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $order_id);
    $stmt->execute();
    header("Location: admin.php"); // Refresh หน้า
}

$result = $conn->query("SELECT * FROM orders ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - ThanJai Shop Management</title>
    <style>
        :root { --main-gold: #FFCC00; --dark-bg: #1a1a1a; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f7f6; margin: 0; display: flex; }
        
        /* Sidebar Styles */
        .sidebar { width: 250px; background: var(--dark-bg); color: white; height: 100vh; padding: 20px; position: fixed; }
        .sidebar h2 { color: var(--main-gold); font-size: 22px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .menu-item { padding: 15px 0; border-bottom: 1px solid #333; cursor: pointer; transition: 0.3s; }
        .menu-item:hover { color: var(--main-gold); }

        /* Main Content */
        .main-content { margin-left: 290px; padding: 30px; width: 100%; }
        .header-card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 20px; }
        
        /* Table Styles */
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        th { background: #333; color: white; padding: 15px; text-align: left; }
        td { padding: 15px; border-bottom: 1px solid #eee; }
        tr:hover { background-color: #f9f9f9; }

        /* Status Badges */
        .badge { padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .Pending { background: #fff3cd; color: #856404; }
        .Paid { background: #d1e7dd; color: #0f5132; }
        .Shipped { background: #cfe2ff; color: #084298; }

        .btn-update { background: var(--main-gold); border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; font-weight: bold; }
        select { padding: 7px; border-radius: 5px; border: 1px solid #ddd; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>ThanJai Admin</h2>
        <div class="menu-item">📊 แดชบอร์ดสรุปผล</div>
        <div class="menu-item" style="color: var(--main-gold);">📦 จัดการออเดอร์</div>
        <div class="menu-item">👕 จัดการสต็อกสินค้า</div>
        <div class="menu-item">👥 รายชื่อลูกค้า</div>
        <div class="menu-item">⚙️ ตั้งค่าระบบ</div>
    </div>

    <div class="main-content">
        <div class="header-card">
            <h1>รายการคำสั่งซื้อล่าสุด</h1>
            <p>ยินดีต้อนรับกลับมา, คุณแอดมิน! วันนี้มีออเดอร์ใหม่รอดำเนินการอยู่.</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>ลูกค้า</th>
                    <th>ยอดชำระ</th>
                    <th>สถานะปัจจุบัน</th>
                    <th>ปรับปรุงสถานะ</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><strong>#<?php echo $row['id']; ?></strong></td>
                    <td>
                        <?php echo $row['customer_name']; ?><br>
                        <small style="color: #888;"><?php echo $row['address']; ?></small>
                    </td>
                    <td>฿<?php echo number_format($row['total_price'], 2); ?></td>
                    <td>
                        <span class="badge <?php echo $row['status']; ?>">
                            <?php echo ($row['status'] == 'Pending') ? '⏳ รอชำระเงิน' : (($row['status'] == 'Paid') ? '✅ ชำระแล้ว' : '🚚 ส่งแล้ว'); ?>
                        </span>
                    </td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                            <select name="status">
                                <option value="Pending" <?php if($row['status']=='Pending') echo 'selected'; ?>>รอชำระเงิน</option>
                                <option value="Paid" <?php if($row['status']=='Paid') echo 'selected'; ?>>ชำระเงินแล้ว</option>
                                <option value="Shipped" <?php if($row['status']=='Shipped') echo 'selected'; ?>>จัดส่งแล้ว</option>
                            </select>
                            <button type="submit" name="update_status" class="btn-update">บันทึก</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>