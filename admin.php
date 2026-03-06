<?php
session_start();
// --- 1. การเชื่อมต่อฐานข้อมูล ---
$host = "localhost";
$user = "root";
$pass = "";
$db   = "thanjai_shop";
$conn = mysqli_connect($host, $user, $pass, $db);
mysqli_set_charset($conn, "utf8");

// --- 2. ระบบจัดการ Session & Login/Logout ---
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
}

if (isset($_POST['login'])) {
    $u = $_POST['user'];
    $p = $_POST['pass'];
    $res = mysqli_query($conn, "SELECT * FROM admins WHERE username='$u'");
    $admin = mysqli_fetch_assoc($res);
    if ($admin && password_verify($p, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['full_name'];
        header("Location: admin.php?page=dashboard");
    } else { $error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง!"; }
}

// เช็คสิทธิ์การเข้าถึง
$isLoggedIn = isset($_SESSION['admin_id']);
$page = $_GET['page'] ?? 'login';

// --- 3. ส่วนการจัดการข้อมูล (CRUD Logic) ---
if ($isLoggedIn) {
    // ลบข้อมูล
    if (isset($_GET['delete_prod'])) {
        $id = $_GET['delete_prod'];
        mysqli_query($conn, "DELETE FROM products WHERE id=$id");
    }
    // อัปเดตสถานะการส่ง/ชำระเงิน
    if (isset($_POST['update_order'])) {
        $oid = $_POST['order_id'];
        $p_st = $_POST['pay_status'];
        $s_st = $_POST['ship_status'];
        $track = $_POST['track_no'];
        mysqli_query($conn, "UPDATE orders SET pay_status='$p_st', ship_status='$s_st', tracking_no='$track' WHERE id=$oid");
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanjai Shop Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --navy: #001f3f; --navy-light: #08345c; --accent: #ffcc00; }
        body { font-family: 'Kanit', sans-serif; background: #f0f2f5; overflow-x: hidden; }
        
        /* Animations */
        @keyframes slideIn { from { transform: translateX(-100%); } to { transform: translateX(0); } }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        
        .sidebar { background: var(--navy); min-height: 100vh; color: white; animation: slideIn 0.5s ease-out; }
        .sidebar a { color: #ccc; text-decoration: none; padding: 15px; display: block; transition: 0.3s; border-left: 4px solid transparent; }
        .sidebar a:hover, .sidebar a.active { background: var(--navy-light); color: var(--accent); border-left: 4px solid var(--accent); padding-left: 25px; }
        
        .main-content { animation: fadeIn 0.8s ease-in-out; }
        .card { border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: 0.3s; }
        .card:hover { transform: translateY(-5px); }
        .bg-navy { background-color: var(--navy) !important; color: white; }
        .btn-navy { background: var(--navy); color: white; border-radius: 8px; }
        .btn-navy:hover { background: var(--navy-light); color: var(--accent); }
        
        /* Login Page */
        .login-box { max-width: 400px; margin: 100px auto; background: white; padding: 30px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
    </style>
</head>
<body>

<?php if (!$isLoggedIn): ?>
    <!-- หน้าเข้าสู่ระบบ -->
    <div class="container">
        <div class="login-box animate-fade">
            <h2 class="text-center mb-4" style="color:var(--navy)">THANJAI SHOP</h2>
            <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
            <form method="POST">
                <div class="mb-3">
                    <label>Username</label>
                    <input type="text" name="user" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="pass" class="form-control" required>
                </div>
                <button type="submit" name="login" class="btn btn-navy w-100 py-2">เข้าสู่ระบบหลังบ้าน</button>
            </form>
        </div>
    </div>
<?php else: ?>
    <!-- หน้าหลักหลัง Login -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 p-0 sidebar position-fixed">
                <div class="text-center py-4">
                    <h4 class="text-warning">THANJAI SHOP</h4>
                    <small>Admin Management</small>
                </div>
                <a href="?page=dashboard" class="<?= $page=='dashboard'?'active':'' ?>"><i class="fa fa-chart-line me-2"></i> แดชบอร์ด</a>
                <a href="?page=products" class="<?= $page=='products'?'active':'' ?>"><i class="fa fa-tshirt me-2"></i> จัดการสินค้า</a>
                <a href="?page=orders" class="<?= $page=='orders'?'active':'' ?>"><i class="fa fa-shopping-cart me-2"></i> คำสั่งซื้อ & ขนส่ง</a>
                <a href="?page=customers" class="<?= $page=='customers'?'active':'' ?>"><i class="fa fa-users me-2"></i> ข้อมูลลูกค้า</a>
                <a href="?page=register" class="<?= $page=='register'?'active':'' ?>"><i class="fa fa-user-plus me-2"></i> เพิ่มแอดมิน</a>
                <div class="mt-5 p-3">
                    <a href="?logout=1" class="text-danger border-0"><i class="fa fa-sign-out-alt me-2"></i> ออกจากระบบ</a>
                </div>
            </div>

            <!-- Content Area -->
            <div class="col-md-10 offset-md-2 p-4 main-content">
                
                <?php if ($page == 'dashboard'): ?>
                    <h2 class="mb-4">ภาพรวมระบบ (Dashboard)</h2>
                    <div class="row g-4">
                        <div class="col-md-3">
                            <div class="card p-3 bg-navy">
                                <h6>ยอดขายวันนี้</h6>
                                <h3>฿12,400</h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card p-3 bg-white border-start border-primary border-4">
                                <h6>ออเดอร์รอจัดส่ง</h6>
                                <h3 class="text-primary">8 รายการ</h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card p-3 bg-white border-start border-warning border-4">
                                <h6>สินค้าสต็อกต่ำ</h6>
                                <h3 class="text-warning">3 รายการ</h3>
                            </div>
                        </div>
                    </div>

                <?php elseif ($page == 'products'): ?>
                    <div class="d-flex justify-content-between mb-4">
                        <h2>จัดการสินค้า</h2>
                        <button class="btn btn-navy"><i class="fa fa-plus"></i> เพิ่มสินค้าใหม่</button>
                    </div>
                    <div class="card p-3">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>รูป</th><th>ชื่อสินค้า</th><th>หมวดหมู่</th><th>ราคา</th><th>สต็อก</th><th>สถานะ</th><th>จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $res = mysqli_query($conn, "SELECT * FROM products");
                                while($row = mysqli_fetch_assoc($res)) {
                                    echo "<tr>
                                        <td><img src='{$row['image_url']}' width='40' class='rounded'></td>
                                        <td>{$row['name']}</td>
                                        <td>{$row['category']}</td>
                                        <td>{$row['price']}</td>
                                        <td>{$row['stock']}</td>
                                        <td><span class='badge bg-success'>{$row['status']}</span></td>
                                        <td>
                                            <button class='btn btn-sm btn-info text-white'><i class='fa fa-edit'></i></button>
                                            <a href='?page=products&delete_prod={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"ลบหรือไม่?\")'><i class='fa fa-trash'></i></a>
                                        </td>
                                    </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                <?php elseif ($page == 'orders'): ?>
                    <h2>การจัดการคำสั่งซื้อ & สถานะชำระเงิน</h2>
                    <div class="card p-3 mt-4">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Order ID</th><th>ลูกค้า</th><th>ยอดรวม</th><th>ชำระเงิน</th><th>การขนส่ง</th><th>Tracking No.</th><th>ดำเนินการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $orders = mysqli_query($conn, "SELECT orders.*, customers.cus_name FROM orders JOIN customers ON orders.customer_id = customers.id");
                                while($o = mysqli_fetch_assoc($orders)): ?>
                                <form method="POST">
                                    <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                                    <tr>
                                        <td>#<?= $o['id'] ?></td>
                                        <td><?= $o['cus_name'] ?></td>
                                        <td>฿<?= number_format($o['total_amount'],2) ?></td>
                                        <td>
                                            <select name="pay_status" class="form-select form-select-sm">
                                                <option value="Pending" <?= $o['pay_status']=='Pending'?'selected':'' ?>>รอชำระ</option>
                                                <option value="Paid" <?= $o['pay_status']=='Paid'?'selected':'' ?>>ชำระแล้ว</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="ship_status" class="form-select form-select-sm">
                                                <option value="Processing" <?= $o['ship_status']=='Processing'?'selected':'' ?>>กำลังเตรียม</option>
                                                <option value="Shipped" <?= $o['ship_status']=='Shipped'?'selected':'' ?>>ส่งแล้ว</option>
                                                <option value="Delivered" <?= $o['ship_status']=='Delivered'?'selected':'' ?>>ถึงมือผู้รับ</option>
                                            </select>
                                        </td>
                                        <td><input type="text" name="track_no" class="form-control form-control-sm" value="<?= $o['tracking_no'] ?>"></td>
                                        <td><button type="submit" name="update_order" class="btn btn-sm btn-success">บันทึก</button></td>
                                    </tr>
                                </form>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>

                <?php elseif ($page == 'customers'): ?>
                    <h2>รายชื่อลูกค้า</h2>
                    <div class="card p-3 mt-4 animate-fade">
                        <table class="table table-striped">
                            <thead><tr><th>ID</th><th>ชื่อ</th><th>เบอร์โทร</th><th>ที่อยู่</th><th>ยอดซื้อสะสม</th></tr></thead>
                            <tbody>
                                <?php
                                $customers = mysqli_query($conn, "SELECT * FROM customers");
                                while($c = mysqli_fetch_assoc($customers)) {
                                    echo "<tr>
                                        <td>#{$c['id']}</td>
                                        <td>{$c['cus_name']}</td>
                                        <td>{$c['cus_phone']}</td>
                                        <td>{$c['cus_address']}</td>
                                        <td class='text-success fw-bold'>฿".number_format($c['total_spent'],2)."</td>
                                    </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                <?php elseif ($page == 'register'): ?>
                    <div class="card p-4 mx-auto" style="max-width: 500px;">
                        <h4>สมัครสมาชิกแอดมินใหม่</h4>
                        <form method="POST" action="?page=dashboard"> <!-- ในระบบจริงควรมี logic insert -->
                            <div class="mb-3"><label>ชื่อ-นามสกุล</label><input type="text" class="form-control"></div>
                            <div class="mb-3"><label>Username</label><input type="text" class="form-control"></div>
                            <div class="mb-3"><label>Password</label><input type="password" class="form-control"></div>
                            <button type="submit" class="btn btn-navy w-100">ลงทะเบียน</button>
                        </form>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>