<?php
session_start();

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli("localhost", "root", "Pw@1458800032693", "thanjai_shop");

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

$alert_msg = '';
$alert_type = '';

// ==========================================
// ระบบ สมัครสมาชิก (Register)
// ==========================================
if (isset($_POST['register'])) {
    $user = $_POST['reg_username'];
    $pass = $_POST['reg_password'];
    $pass_confirm = $_POST['reg_password_confirm'];

    if ($pass !== $pass_confirm) {
        $alert_msg = "รหัสผ่านไม่ตรงกัน!";
        $alert_type = "danger";
    } else {
        // เช็คว่ามี Username นี้หรือยัง
        $check = $conn->query("SELECT id FROM system_admins WHERE username = '$user'");
        if ($check->num_rows > 0) {
            $alert_msg = "ชื่อผู้ใช้นี้มีในระบบแล้ว!";
            $alert_type = "warning";
        } else {
            // เข้ารหัสผ่าน
            $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
            $sql = "INSERT INTO system_admins (username, password) VALUES ('$user', '$hashed_password')";
            
            if ($conn->query($sql) === TRUE) {
                $alert_msg = "สมัครสมาชิกแอดมินสำเร็จ! กรุณาเข้าสู่ระบบ";
                $alert_type = "success";
            } else {
                $alert_msg = "เกิดข้อผิดพลาด: " . $conn->error;
                $alert_type = "danger";
            }
        }
    }
}

// ==========================================
// ระบบ เข้าสู่ระบบ (Login)
// ==========================================
if (isset($_POST['login'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // โค้ดเดิม: รองรับ admin / 1234
    if ($user === 'admin' && $pass === '1234') {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_name'] = 'Admin (รหัสหลัก)';
    } else {
        // เช็คจากฐานข้อมูล (รหัสที่สมัครใหม่)
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

// เช็คว่าผู้ใช้กดเลือกหน้า "สมัครสมาชิก" หรือไม่
$is_register_page = isset($_GET['action']) && $_GET['action'] == 'register';
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบจัดการหลังบ้าน - ThanJai Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { 
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); 
            min-height: 100vh; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .auth-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            background: #ffffff;
            overflow: hidden;
        }
        .auth-header {
            background: #0d6efd;
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
        }
        .btn-primary {
            border-radius: 10px;
            padding: 12px;
            font-weight: bold;
        }
        .admin-navbar {
            border-radius: 0 0 15px 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>

<?php if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true): ?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5 mt-4">
                
                <?php if ($alert_msg): ?>
                    <div class="alert alert-<?php echo $alert_type; ?> alert-dismissible fade show shadow-sm text-center" role="alert">
                        <?php echo $alert_msg; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="card auth-card">
                    
                    <?php if ($is_register_page): ?>
                        <div class="auth-header bg-success">
                            <h3><i class="fas fa-user-plus mb-2"></i></h3>
                            <h4 class="mb-0">เพิ่มแอดมินใหม่</h4>
                        </div>
                        <div class="card-body p-4 p-md-5">
                            <form method="POST" action="admin.php?action=register">
                                <div class="mb-3">
                                    <label class="form-label text-muted small fw-bold">ชื่อผู้ใช้ (Username)</label>
                                    <input type="text" name="reg_username" class="form-control" required placeholder="ตั้งชื่อผู้ใช้">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted small fw-bold">รหัสผ่าน (Password)</label>
                                    <input type="password" name="reg_password" class="form-control" required placeholder="ตั้งรหัสผ่าน">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label text-muted small fw-bold">ยืนยันรหัสผ่านอีกครั้ง</label>
                                    <input type="password" name="reg_password_confirm" class="form-control" required placeholder="กรอกรหัสผ่านอีกครั้ง">
                                </div>
                                <button type="submit" name="register" class="btn btn-success w-100 mb-3 shadow-sm">บันทึกข้อมูล</button>
                                <div class="text-center">
                                    <a href="admin.php" class="text-decoration-none text-muted"><i class="fas fa-arrow-left me-1"></i> กลับไปหน้าเข้าสู่ระบบ</a>
                                </div>
                            </form>
                        </div>

                    <?php else: ?>
                        <div class="auth-header">
                            <h3><i class="fas fa-store mb-2"></i></h3>
                            <h4 class="mb-0">ThanJai Shop Admin</h4>
                        </div>
                        <div class="card-body p-4 p-md-5">
                            <form method="POST" action="admin.php">
                                <div class="mb-3">
                                    <label class="form-label text-muted small fw-bold"><i class="fas fa-user me-1"></i> ชื่อผู้ใช้</label>
                                    <input type="text" name="username" class="form-control" required placeholder="กรอก Username">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label text-muted small fw-bold"><i class="fas fa-lock me-1"></i> รหัสผ่าน</label>
                                    <input type="password" name="password" class="form-control" required placeholder="กรอก Password">
                                </div>
                                <button type="submit" name="login" class="btn btn-primary w-100 mb-4 shadow-sm">เข้าสู่ระบบ</button>
                                
                                <div class="text-center border-top pt-3">
                                    <p class="text-muted small mb-0">ต้องการเพิ่มผู้ดูแลระบบใช่ไหม?</p>
                                    <a href="?action=register" class="text-success fw-bold text-decoration-none"><i class="fas fa-user-plus me-1"></i> สมัครสมาชิกแอดมิน</a>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

<?php else: ?>
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary px-4 py-3 admin-navbar">
            <a class="navbar-brand fw-bold" href="#"><i class="fas fa-store me-2"></i>ThanJai Shop Admin</a>
            <div class="ms-auto d-flex align-items-center">
                <span class="text-white me-3"><i class="fas fa-user-circle me-1"></i> <?php echo isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : 'Admin'; ?></span>
                <a href="?logout=true" class="btn btn-light btn-sm fw-bold text-danger rounded-pill px-3 shadow-sm">ออกจากระบบ <i class="fas fa-sign-out-alt ms-1"></i></a>
            </div>
        </nav>
        
        <div class="mt-5">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-body p-5 text-center">
                    <i class="fas fa-check-circle text-success fa-4x mb-3"></i>
                    <h2 class="text-success fw-bold">เข้าสู่ระบบสำเร็จ!</h2>
                    <p class="text-muted fs-5">ยินดีต้อนรับเข้าสู่ระบบจัดการหลังบ้าน คุณสามารถเริ่มจัดการร้านค้าได้เลยครับ 🎉</p>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>