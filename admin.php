<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "thanjai_shop");
mysqli_set_charset($conn, "utf8mb4");

// --- LOGIC: ระบบล็อกอิน/ออก ---
if (isset($_POST['login'])) {
    $u = mysqli_real_escape_string($conn, $_POST['user']);
    $res = mysqli_query($conn, "SELECT * FROM admins WHERE username='$u'");
    $admin = mysqli_fetch_assoc($res);
    if ($admin && password_verify($_POST['pass'], $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['full_name'];
        header("Location: admin.php?page=dashboard");
    } else { $error = "เข้าสู่ระบบไม่สำเร็จ!"; }
}
if (isset($_GET['logout'])) { session_destroy(); header("Location: admin.php"); }

// --- LOGIC: CRUD & ACTIONS ---
if (isset($_SESSION['admin_id'])) {
    // สมัครแอดมินใหม่
    if (isset($_POST['reg_admin'])) {
        $un = $_POST['username']; $pw = password_hash($_POST['password'], PASSWORD_DEFAULT); $fn = $_POST['fullname'];
        mysqli_query($conn, "INSERT INTO admins (username, password, full_name) VALUES ('$un', '$pw', '$fn')");
        $msg = "เพิ่มแอดมินเรียบร้อย!";
    }
    // ลบสินค้า
    if (isset($_GET['del_prod'])) {
        $id = $_GET['del_prod'];
        mysqli_query($conn, "DELETE FROM products WHERE id=$id");
    }
    // อัปเดตออเดอร์
    if (isset($_POST['up_order'])) {
        $oid = $_POST['oid']; $ps = $_POST['pay_st']; $ss = $_POST['ship_st']; $tk = $_POST['track'];
        mysqli_query($conn, "UPDATE orders SET pay_status='$ps', ship_status='$ss', tracking_no='$tk' WHERE id=$oid");
    }
}

$page = $_GET['page'] ?? (isset($_SESSION['admin_id']) ? 'dashboard' : 'login');
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>THANJAI SHOP | ADMIN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&family=Orbitron:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --navy: #00122e; --navy-light: #002d62; --neon: #00d4ff; --gold: #ffcc00; }
        body { font-family: 'Kanit', sans-serif; background: radial-gradient(circle at top right, #001f3f, #000); color: white; min-height: 100vh; overflow-x: hidden; }
        
        /* Animations */
        @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate { animation: fadeUp 0.6s ease-out; }

        /* Sidebar */
        .sidebar { background: rgba(0, 18, 46, 0.9); backdrop-filter: blur(10px); min-height: 100vh; border-right: 1px solid var(--neon); position: fixed; width: 250px; }
        .sidebar a { color: #8a99af; text-decoration: none; padding: 15px 25px; display: block; transition: 0.3s; border-radius: 10px; margin: 5px 15px; }
        .sidebar a:hover, .sidebar a.active { background: var(--navy-light); color: var(--neon); transform: translateX(10px); box-shadow: 0 0 15px rgba(0, 212, 255, 0.2); }
        
        /* Cards */
        .glass-card { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 20px; padding: 25px; transition: 0.3s; }
        .glass-card:hover { border-color: var(--neon); transform: translateY(-5px); }

        .btn-neon { background: transparent; color: var(--neon); border: 1px solid var(--neon); transition: 0.3s; }
        .btn-neon:hover { background: var(--neon); color: var(--navy); box-shadow: 0 0 20px var(--neon); }
        
        .login-box { max-width: 400px; margin: 100px auto; background: rgba(0, 18, 46, 0.95); padding: 40px; border-radius: 30px; border: 1px solid var(--neon); box-shadow: 0 0 30px rgba(0, 212, 255, 0.3); }
        .form-control { background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); color: white; }
        .form-control:focus { background: rgba(0,0,0,0.5); border-color: var(--neon); color: white; box-shadow: none; }
    </style>
</head>
<body>

<?php if (!isset($_SESSION['admin_id'])): ?>
    <div class="container animate">
        <div class="login-box text-center">
            <h1 style="font-family: 'Orbitron'; color: var(--gold);">THANJAI</h1>
            <p class="text-muted">ADMIN AUTHENTICATION</p>
            <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
            <form method="POST">
                <input type="text" name="user" class="form-control mb-3" placeholder="Username" required>
                <input type="password" name="pass" class="form-control mb-4" placeholder="Password" required>
                <button type="submit" name="login" class="btn btn-neon w-100 py-2">LOGIN</button>
            </form>
        </div>
    </div>
<?php else: ?>
    <div class="sidebar">
        <div class="text-center py-5">
            <h3 style="font-family: 'Orbitron'; color: var(--gold);">THANJAI</h3>
            <span class="badge bg-info">ADMIN MODE</span>
        </div>
        <a href="?page=dashboard" class="<?= $page=='dashboard'?'active':'' ?>"><i class="fa fa-home me-2"></i> แดชบอร์ด</a>
        <a href="?page=products" class="<?= $page=='products'?'active':'' ?>"><i class="fa fa-tshirt me-2"></i> คลังสินค้า</a>
        <a href="?page=orders" class="<?= $page=='orders'?'active':'' ?>"><i class="fa fa-truck me-2"></i> คำสั่งซื้อ</a>
        <a href="?page=customers" class="<?= $page=='customers'?'active':'' ?>"><i class="fa fa-users me-2"></i> ลูกค้า</a>
        <a href="?page=register" class="<?= $page=='register'?'active':'' ?>"><i class="fa fa-user-plus me-2"></i> เพิ่มแอดมิน</a>
        <a href="?logout=1" class="text-danger mt-5"><i class="fa fa-power-off me-2"></i> ออกจากระบบ</a>
    </div>

    <div class="main-content" style="margin-left: 250px; padding: 40px;">
        
        <?php if ($page == 'dashboard'): ?>
            <h2 class="animate">Overview System</h2>
            <div class="row g-4 mt-2">
                <div class="col-md-4 animate">
                    <div class="glass-card">
                        <h6>ยอดขายรวม</h6>
                        <h2 class="text-info">฿ <?= number_format(158400, 2) ?></h2>
                    </div>
                </div>
                <div class="col-md-4 animate">
                    <div class="glass-card">
                        <h6>ออเดอร์ใหม่</h6>
                        <h2 class="text-warning">12 รายการ</h2>
                    </div>
                </div>
            </div>

        <?php elseif ($page == 'products'): ?>
            <div class="d-flex justify-content-between mb-4">
                <h2>คลังสินค้า</h2>
                <button class="btn btn-neon">+ เพิ่มสินค้า</button>
            </div>
            <div class="glass-card animate">
                <table class="table table-dark table-hover">
                    <thead><tr><th>ชื่อสินค้า</th><th>ราคา</th><th>สต็อก</th><th>หมวดหมู่</th><th>จัดการ</th></tr></thead>
                    <tbody>
                        <?php $res = mysqli_query($conn, "SELECT * FROM products"); while($row = mysqli_fetch_assoc($res)): ?>
                        <tr>
                            <td><?= $row['name'] ?></td>
                            <td><?= $row['price'] ?></td>
                            <td><?= $row['stock'] ?></td>
                            <td><?= $row['category'] ?></td>
                            <td>
                                <button class="btn btn-sm btn-info text-white"><i class="fa fa-edit"></i></button>
                                <a href="?page=products&del_prod=<?= $row['id'] ?>" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        <?php elseif ($page == 'orders'): ?>
            <h2>การจัดการคำสั่งซื้อ</h2>
            <div class="glass-card mt-4 animate">
                <table class="table table-dark">
                    <thead><tr><th>ID</th><th>ลูกค้า</th><th>ชำระเงิน</th><th>การส่ง</th><th>Tracking</th><th>Action</th></tr></thead>
                    <tbody>
                        <?php $res = mysqli_query($conn, "SELECT orders.*, customers.cus_name FROM orders JOIN customers ON orders.customer_id = customers.id"); 
                        while($o = mysqli_fetch_assoc($res)): ?>
                        <form method="POST">
                        <input type="hidden" name="oid" value="<?= $o['id'] ?>">
                        <tr>
                            <td>#<?= $o['id'] ?></td>
                            <td><?= $o['cus_name'] ?></td>
                            <td>
                                <select name="pay_st" class="form-select bg-dark text-white border-0">
                                    <option value="Pending" <?= $o['pay_status']=='Pending'?'selected':'' ?>>รอชำระ</option>
                                    <option value="Paid" <?= $o['pay_status']=='Paid'?'selected':'' ?>>ชำระแล้ว</option>
                                </select>
                            </td>
                            <td>
                                <select name="ship_st" class="form-select bg-dark text-white border-0">
                                    <option value="Processing" <?= $o['ship_status']=='Processing'?'selected':'' ?>>เตรียมส่ง</option>
                                    <option value="Shipped" <?= $o['ship_status']=='Shipped'?'selected':'' ?>>ส่งแล้ว</option>
                                </select>
                            </td>
                            <td><input type="text" name="track" class="form-control" value="<?= $o['tracking_no'] ?>"></td>
                            <td><button type="submit" name="up_order" class="btn btn-neon btn-sm">บันทึก</button></td>
                        </tr>
                        </form>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        <?php elseif ($page == 'customers'): ?>
            <h2>ข้อมูลลูกค้า</h2>
            <div class="glass-card mt-4 animate">
                <table class="table table-dark table-striped">
                    <thead><tr><th>ชื่อ</th><th>เบอร์โทร</th><th>ยอดซื้อสะสม</th></tr></thead>
                    <tbody>
                        <?php $res = mysqli_query($conn, "SELECT * FROM customers"); while($c = mysqli_fetch_assoc($res)): ?>
                        <tr><td><?= $c['cus_name'] ?></td><td><?= $c['cus_phone'] ?></td><td class="text-success">฿<?= number_format($c['total_spent'],2) ?></td></tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        <?php elseif ($page == 'register'): ?>
            <div class="animate" style="max-width: 500px;">
                <h2>สมัครสมาชิกแอดมิน</h2>
                <?php if(isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>
                <div class="glass-card mt-4">
                    <form method="POST">
                        <div class="mb-3"><label>ชื่อจริง</label><input type="text" name="fullname" class="form-control" required></div>
                        <div class="mb-3"><label>Username</label><input type="text" name="username" class="form-control" required></div>
                        <div class="mb-4"><label>Password</label><input type="password" name="password" class="form-control" required></div>
                        <button type="submit" name="reg_admin" class="btn btn-neon w-100">ลงทะเบียนแอดมิน</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

    </div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>