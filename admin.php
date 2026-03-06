<?php
session_start();

// --- 1. ระบบ Authentication (เข้าสู่ระบบ) ---
// ในระบบจริงควรดึงจาก Database แต่เพื่อความง่ายในการทดสอบ ผมจะตั้ง Username/Password ไว้ตรงนี้
$admin_user = "admin";
$admin_pass = "1234";

// ตรวจสอบการ Login
if (isset($_POST['login'])) {
    if ($_POST['username'] == $admin_user && $_POST['password'] == $admin_pass) {
        $_SESSION['is_logged_in'] = true;
        header("Location: admin.php");
        exit;
    } else {
        $login_error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง!";
    }
}

// ตรวจสอบการ Logout
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: admin.php");
    exit;
}

// --- ถ้ายังไม่ Login ให้แสดงหน้าต่าง Login ---
if (!isset($_SESSION['is_logged_in'])) {
?>
    <!DOCTYPE html>
    <html lang="th">
    <head>
        <meta charset="UTF-8">
        <title>Login - ThanJai Admin</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-dark d-flex align-items-center justify-content-center" style="height: 100vh;">
        <div class="card p-4 shadow-lg" style="width: 350px; border-radius: 15px;">
            <div class="text-center mb-4">
                <h3 class="text-warning fw-bold">ThanJai Shop</h3>
                <p class="text-muted">ระบบจัดการหลังบ้าน</p>
            </div>
            <?php if(isset($login_error)) echo "<div class='alert alert-danger'>$login_error</div>"; ?>
            <form method="POST">
                <div class="mb-3"><input type="text" name="username" class="form-control" placeholder="Username (admin)" required></div>
                <div class="mb-3"><input type="password" name="password" class="form-control" placeholder="Password (1234)" required></div>
                <button type="submit" name="login" class="btn btn-warning w-100 fw-bold">เข้าสู่ระบบ</button>
            </form>
        </div>
    </body>
    </html>
<?php
    exit; // หยุดการทำงาน ไม่แสดงหน้า Dashboard ถ้ายังไม่ Login
}

// --- 2. PHP LOGIC (ระบบหลังบ้านหลัก) ---
$conn = new mysqli("localhost", "root", "", "thanjai_shop");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

// Logic: อัปเดตสถานะ & เลขพัสดุ
if (isset($_POST['update_order'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $tracking = $_POST['tracking_number'];
    
    $stmt = $conn->prepare("UPDATE orders SET status = ?, tracking_number = ? WHERE id = ?");
    $stmt->bind_param("ssi", $status, $tracking, $order_id);
    $stmt->execute();
    header("Location: admin.php?msg=updated"); exit;
}

// Logic: แก้ไขข้อมูลลูกค้า
if (isset($_POST['edit_customer'])) {
    $order_id = $_POST['order_id'];
    $name = $_POST['customer_name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    
    $stmt = $conn->prepare("UPDATE orders SET customer_name = ?, phone = ?, address = ? WHERE id = ?");
    $stmt->bind_param("sssi", $name, $phone, $address, $order_id);
    $stmt->execute();
    header("Location: admin.php?msg=edited"); exit;
}

// Logic: ลบออเดอร์
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: admin.php?msg=deleted"); exit;
}

$result = $conn->query("SELECT * FROM orders ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - ThanJai Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; }
        .sidebar { min-height: 100vh; background-color: #212529; color: white; transition: 0.3s; }
        .sidebar a { color: #adb5bd; text-decoration: none; padding: 10px 15px; display: block; border-radius: 5px; margin-bottom: 5px; }
        .sidebar a:hover, .sidebar a.active { background-color: #ffcc00; color: #212529; font-weight: bold; }
        .main-content { padding: 20px; }
        .status-Pending { background-color: #ffc107; color: #000; }
        .status-Paid { background-color: #198754; color: #fff; }
        .status-Shipped { background-color: #0d6efd; color: #fff; }
    </style>
</head>
<body>

<div class="container-fluid p-0">
    <div class="row g-0">
        <div class="col-md-2 sidebar p-3 d-none d-md-block">
            <h4 class="text-warning text-center fw-bold mb-4"><i class="fas fa-shopping-cart"></i> ThanJai Admin</h4>
            <a href="#" class="active"><i class="fas fa-box"></i> จัดการออเดอร์</a>
            <a href="#"><i class="fas fa-warehouse"></i> จัดการสต็อก</a>
            <a href="#"><i class="fas fa-users"></i> ลูกค้าสัมพันธ์</a>
            <hr class="text-secondary">
            <a href="?action=logout" class="text-danger"><i class="fas fa-sign-out-alt"></i> ออกจากระบบ</a>
        </div>

        <div class="col-md-10 main-content">
            <div class="d-flex justify-content-between align-items-center bg-white p-3 rounded shadow-sm mb-4">
                <h4 class="mb-0 fw-bold">รายการสั่งซื้อล่าสุด</h4>
                <div>
                    <span class="me-3"><i class="fas fa-user-circle"></i> แอดมินหลัก</span>
                    <a href="?action=logout" class="btn btn-sm btn-outline-danger d-md-none">Logout</a>
                </div>
            </div>

            <?php if(isset($_GET['msg'])) { 
                $msgs = ['updated'=>'อัปเดตสถานะสำเร็จ', 'edited'=>'แก้ไขข้อมูลสำเร็จ', 'deleted'=>'ลบข้อมูลสำเร็จ'];
                echo "<div class='alert alert-success alert-dismissible fade show'>
                        <i class='fas fa-check-circle'></i> " . $msgs[$_GET['msg']] . "
                        <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                      </div>";
            } ?>

            <div class="card shadow-sm border-0">
                <div class="card-body table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>ข้อมูลลูกค้า</th>
                                <th>ยอดชำระ</th>
                                <th>สถานะ / เลขพัสดุ</th>
                                <th class="text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><strong>#<?php echo $row['id']; ?></strong></td>
                                <td>
                                    <div class="fw-bold"><i class="fas fa-user"></i> <?php echo $row['customer_name']; ?></div>
                                    <div class="text-muted small"><i class="fas fa-phone"></i> <?php echo $row['phone'] ?: '-'; ?></div>
                                    <div class="text-muted small"><i class="fas fa-map-marker-alt"></i> <?php echo mb_strimwidth($row['address'], 0, 30, '...'); ?></div>
                                </td>
                                <td>฿<?php echo number_format($row['total_price'], 2); ?></td>
                                <td>
                                    <span class="badge status-<?php echo $row['status']; ?> mb-1">
                                        <?php echo $row['status']; ?>
                                    </span><br>
                                    <small class="text-info"><i class="fas fa-truck"></i> <?php echo $row['tracking_number'] ?: 'ยังไม่มีเลขพัสดุ'; ?></small>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-warning mb-1" data-bs-toggle="modal" data-bs-target="#statusModal<?php echo $row['id']; ?>" title="จัดการสถานะ">
                                        <i class="fas fa-truck-fast"></i>
                                    </button>
                                    
                                    <button class="btn btn-sm btn-info mb-1" data-bs-toggle="modal" data-bs-target="#editCustomerModal<?php echo $row['id']; ?>" title="แก้ไขข้อมูลลูกค้า">
                                        <i class="fas fa-user-edit"></i>
                                    </button>

                                    <?php if($row['phone']): ?>
                                        <a href="tel:<?php echo $row['phone']; ?>" class="btn btn-sm btn-success mb-1" title="โทรหาลูกค้า"><i class="fas fa-phone-alt"></i></a>
                                    <?php endif; ?>

                                    <button onclick="window.print()" class="btn btn-sm btn-secondary mb-1" title="พิมพ์ใบปะหน้า"><i class="fas fa-print"></i></button>

                                    <a href="?delete_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger mb-1" onclick="return confirm('ยืนยันการลบออเดอร์ #<?php echo $row['id']; ?>?')" title="ลบข้อมูล">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>

                            <div class="modal fade" id="statusModal<?php echo $row['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <form method="POST" class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">อัปเดตสถานะ ออเดอร์ #<?php echo $row['id']; ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                            <div class="mb-3">
                                                <label class="form-label">สถานะ</label>
                                                <select name="status" class="form-select">
                                                    <option value="Pending" <?php if($row['status']=='Pending') echo 'selected'; ?>>รอชำระเงิน</option>
                                                    <option value="Paid" <?php if($row['status']=='Paid') echo 'selected'; ?>>ชำระเงินแล้ว</option>
                                                    <option value="Shipped" <?php if($row['status']=='Shipped') echo 'selected'; ?>>จัดส่งแล้ว</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">เลขพัสดุ (Tracking Number)</label>
                                                <input type="text" name="tracking_number" class="form-control" value="<?php echo $row['tracking_number']; ?>" placeholder="เช่น TH123456789">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" name="update_order" class="btn btn-primary">บันทึกข้อมูล</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="modal fade" id="editCustomerModal<?php echo $row['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <form method="POST" class="modal-content">
                                        <div class="modal-header bg-info text-dark">
                                            <h5 class="modal-title">แก้ไขข้อมูลลูกค้า #<?php echo $row['id']; ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                            <div class="mb-3">
                                                <label class="form-label">ชื่อลูกค้า</label>
                                                <input type="text" name="customer_name" class="form-control" value="<?php echo $row['customer_name']; ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">เบอร์โทรศัพท์</label>
                                                <input type="text" name="phone" class="form-control" value="<?php echo $row['phone']; ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">ที่อยู่จัดส่ง</label>
                                                <textarea name="address" class="form-control" rows="3" required><?php echo $row['address']; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" name="edit_customer" class="btn btn-success">บันทึกการแก้ไข</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>