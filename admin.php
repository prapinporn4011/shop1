<?php
session_start();

// เชื่อมต่อฐานข้อมูล (รหัสผ่านถูกต้องและอยู่ในบรรทัดเดียวกันแล้ว)
$conn = new mysqli("localhost", "root", "Pw@1458800032693", "thanjai_shop");

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) { 
    die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error); 
}
$conn->set_charset("utf8mb4");

// ตรวจสอบการกดปุ่ม Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit();
}

// ตรวจสอบการกดปุ่ม Login
$login_error = '';
if (isset($_POST['login'])) {
    if ($_POST['username'] === 'admin' && $_POST['password'] === '1234') {
        $_SESSION['admin_logged_in'] = true;
    } else {
        $login_error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง!";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - ThanJai Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true): ?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4 mt-5">
                <div class="card shadow">
                    <div class="card-body">
                        <h3 class="text-center mb-4">เข้าสู่ระบบ Admin</h3>
                        <?php if ($login_error): ?><div class="alert alert-danger"><?php echo $login_error; ?></div><?php endif; ?>
                        <form method="POST">
                            <input type="text" name="username" class="form-control mb-3" required placeholder="Username (ใส่ admin)">
                            <input type="password" name="password" class="form-control mb-3" required placeholder="Password (ใส่ 1234)">
                            <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <nav class="navbar navbar-dark bg-primary px-3">
        <a class="navbar-brand" href="#">ThanJai Shop Admin</a>
        <a href="?logout=true" class="btn btn-danger btn-sm">ออกจากระบบ</a>
    </nav>
    <div class="container mt-4">
        <div class="alert alert-success">
            🎉 ยินดีด้วยครับ! เชื่อมต่อฐานข้อมูลและเข้าสู่ระบบแอดมินสำเร็จแล้ว!
        </div>
    </div>
<?php endif; ?>

</body>
</html>