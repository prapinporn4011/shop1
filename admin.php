<?php
session_start();

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli("localhost", "root", "Pw@1458800032693", "thanjai_shop");

if ($conn->connect_error) { 
    die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error); 
}
$conn->set_charset("utf8mb4");

// --- โค้ดส่วนจัดการระบบ (Logic เพิ่มเติม) ---

// ลบข้อมูลลูกค้า/ออเดอร์
if (isset($_GET['delete_order'])) {
    $id = $_GET['delete_order'];
    $conn->query("DELETE FROM orders WHERE id = $id");
    header("Location: admin.php?view=orders");
}

// อัปเดตสถานะออเดอร์
if (isset($_POST['update_status'])) {
    $id = $_POST['order_id'];
    $status = $_POST['status'];
    $conn->query("UPDATE orders SET status = '$status' WHERE id = $id");
    $alert_msg = "อัปเดตสถานะสำเร็จ!";
    $alert_type = "success";
}

// ตรวจสอบการกดปุ่ม Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit();
}

$alert_msg = '';
$alert_type = '';

// ==========================================
// ระบบ สมัครสมาชิก (เหมือนเดิม)
// ==========================================
if (isset($_POST['register'])) {
    $user = $_POST['reg_username'];
    $pass = $_POST['reg_password'];
    $pass_confirm = $_POST['reg_password_confirm'];

    if ($pass !== $pass_confirm) {
        $alert_msg = "รหัสผ่านไม่ตรงกัน!";
        $alert_type = "danger";
    } else {
        $check = $conn->query("SELECT id FROM system_admins WHERE username = '$user'");
        if ($check->num_rows > 0) {
            $alert_msg = "ชื่อผู้ใช้นี้มีในระบบแล้ว!";
            $alert_type = "warning";
        } else {
            $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
            $sql = "INSERT INTO system_admins (username, password) VALUES ('$user', '$hashed_password')";
            if ($conn->query($sql) === TRUE) {
                $alert_msg = "สมัครสมาชิกแอดมินสำเร็จ! กรุณาเข้าสู่ระบบ";
                $alert_type = "success";
            }
        }
    }
}

// ==========================================
// ระบบ เข้าสู่ระบบ (เหมือนเดิม)
// ==========================================
if (isset($_POST['login'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    if ($user === 'admin' && $pass === '1234') {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_name'] = 'Admin (รหัสหลัก)';
    } else {
        $sql = "SELECT * FROM system_admins WHERE username = '$user'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($pass, $row['password'])) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_name'] = $row['username'];
            } else {
                $alert_msg = "รหัสผ่านไม่ถูกต้อง!";
                $alert_type = "danger";
            }
        } else {
            $alert_msg = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง!";
            $alert_type = "danger";
        }
    }
}

$is_register_page = isset($_GET['action']) && $_GET['action'] == 'register';
$view = isset($_GET['view']) ? $_GET['view'] : 'dashboard';
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - ThanJai Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f4f7f6; font-family: 'Kanit', sans-serif; }
        .auth-card { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); background: #ffffff; overflow: hidden; }
        .auth-header { background: #0d6efd; color: white; padding: 30px 20px; text-align: center; }
        .admin-navbar { border-radius: 0 0 15px 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .nav-link-custom { border-radius: 10px; margin-bottom: 5px; color: #333; transition: 0.3s; }
        .nav-link-custom:hover, .nav-link-custom.active { background: #0d6efd; color: white; }
    </style>
</head>
<body>

<?php if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true): ?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5 mt-4">
                <?php if ($alert_msg): ?><div class="alert alert-<?php echo $alert_type; ?> text-center"><?php echo $alert_msg; ?></div><?php endif; ?>
                <div class="card auth-card">
                    <?php if ($is_register_page): ?>
                        <div class="auth-header bg-success"><h4>เพิ่มแอดมินใหม่</h4></div>
                        <div class="card-body p-5">
                            <form method="POST">
                                <input type="text" name="reg_username" class="form-control mb-3" required placeholder="ชื่อผู้ใช้">
                                <input type="password" name="reg_password" class="form-control mb-3" required placeholder="รหัสผ่าน">
                                <input type="password" name="reg_password_confirm" class="form-control mb-4" required placeholder="ยืนยันรหัสผ่าน">
                                <button type="submit" name="register" class="btn btn-success w-100 mb-3">บันทึก</button>
                                <div class="text-center"><a href="admin.php" class="text-muted small">กลับไปเข้าสู่ระบบ</a></div>
                            </form>
                        </div>
                    <?php else: ?>
                        <div class="auth-header"><h4>ThanJai Shop Admin</h4></div>
                        <div class="card-body p-5">
                            <form method="POST">
                                <input type="text" name="username" class="form-control mb-3" required placeholder="ชื่อผู้ใช้">
                                <input type="password" name="password" class="form-control mb-4" required placeholder="รหัสผ่าน">
                                <button type="submit" name="login" class="btn btn-primary w-100 mb-4">เข้าสู่ระบบ</button>
                                <div class="text-center"><a href="?action=register" class="text-success small">สมัครสมาชิกแอดมิน</a></div>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<?php else: ?>
    <div class="container py-4">
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary px-4 py-3 admin-navbar mb-4">
            <a class="navbar-brand fw-bold" href="admin.php">THANJAI ADMIN</a>
            <div class="ms-auto">
                <span class="text-white me-3 small">สวัสดี, <?php echo $_SESSION['admin_name']; ?></span>
                <a href="?logout=true" class="btn btn-danger btn-sm rounded-pill">ออกจากระบบ</a>
            </div>
        </nav>

        <div class="row">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm p-3">
                    <a href="admin.php?view=dashboard" class="nav-link nav-link-custom p-3 d-block text-decoration-none <?php echo $view == 'dashboard' ? 'active' : ''; ?>"><i class="fas fa-chart-line me-2"></i> หน้าหลัก</a>
                    <a href="admin.php?view=orders" class="nav-link nav-link-custom p-3 d-block text-decoration-none <?php echo $view == 'orders' ? 'active' : ''; ?>"><i class="fas fa-shopping-basket me-2"></i> จัดการออเดอร์/ลูกค้า</a>
                </div>
            </div>

            <div class="col-md-9">
                <?php if ($view == 'dashboard'): ?>
                    <div class="card border-0 shadow-sm p-5 text-center">
                        <i class="fas fa-check-circle text-success fa-4x mb-3"></i>
                        <h2 class="text-success">ระบบพร้อมใช้งาน</h2>
                        <p class="text-muted">ยินดีต้อนรับเข้าสู่ระบบจัดการข้อมูลลูกค้าและรายการสั่งซื้อ</p>
                    </div>

                <?php elseif ($view == 'orders'): ?>
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3"><h5><i class="fas fa-users text-primary me-2"></i> รายชื่อลูกค้าและรายการสั่งซื้อ</h5></div>
                        <div class="card-body p-0">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>เลขที่ออเดอร์</th>
                                        <th>ลูกค้า</th>
                                        <th>ยอดรวม</th>
                                        <th>สถานะ</th>
                                        <th>จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $res = $conn->query("SELECT * FROM orders ORDER BY id DESC");
                                    while($row = $res->fetch_assoc()):
                                    ?>
                                    <tr>
                                        <td class="fw-bold"><?php echo $row['order_number']; ?></td>
                                        <td><?php echo $row['customer_name']; ?></td>
                                        <td>฿<?php echo number_format($row['total_price'], 2); ?></td>
                                        <td>
                                            <form method="POST" class="d-flex">
                                                <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                                <select name="status" class="form-select form-select-sm me-1" onchange="this.form.submit()">
                                                    <option value="pending" <?php if($row['status']=='pending') echo 'selected'; ?>>รอชำระ</option>
                                                    <option value="paid" <?php if($row['status']=='paid') echo 'selected'; ?>>ชำระแล้ว</option>
                                                    <option value="shipped" <?php if($row['status']=='shipped') echo 'selected'; ?>>ส่งแล้ว</option>
                                                </select>
                                                <input type="hidden" name="update_status">
                                            </form>
                                        </td>
                                        <td>
                                            <a href="?view=detail&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info text-white"><i class="fas fa-eye"></i></a>
                                            <a href="?delete_order=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('ยืนยันการลบ?')"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                <?php elseif ($view == 'detail'): 
                    $id = $_GET['id'];
                    $order = $conn->query("SELECT o.*, d.product_list, d.shipping_address, d.contact_channel FROM orders o LEFT JOIN order_details d ON o.id = d.order_id WHERE o.id = $id")->fetch_assoc();
                ?>
                    <div class="card border-0 shadow-sm p-4">
                        <h4><i class="fas fa-info-circle text-primary"></i> รายละเอียดออเดอร์: <?php echo $order['order_number']; ?></h4>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>ชื่อลูกค้า:</strong> <?php echo $order['customer_name']; ?></p>
                                <p><strong>ยอดสั่งซื้อ:</strong> ฿<?php echo number_format($order['total_price'], 2); ?></p>
                                <p><strong>รายการสินค้า:</strong> <br> <span class="text-primary"><?php echo nl2br($order['product_list'] ?? 'ไม่ได้ระบุ'); ?></span></p>
                            </div>
                            <div class="col-md-6 bg-light p-3 rounded">
                                <p><strong><i class="fas fa-truck"></i> ที่อยู่จัดส่ง:</strong><br><?php echo nl2br($order['shipping_address'] ?? 'ไม่มีข้อมูลที่อยู่'); ?></p>
                                <p><strong><i class="fas fa-comment-dots"></i> ช่องทางติดต่อ:</strong><br><span class="badge bg-dark"><?php echo $order['contact_channel'] ?? 'Line / Facebook'; ?></span></p>
                                <a href="https://line.me" target="_blank" class="btn btn-success btn-sm w-100 mt-2"><i class="fab fa-line"></i> ทักแชทลูกค้า</a>
                            </div>
                        </div>
                        <a href="?view=orders" class="btn btn-secondary mt-4">กลับไปหน้ารวม</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>