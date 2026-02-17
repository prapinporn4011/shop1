<?php session_start(); ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ThanJai Shop - PHP System</title>
    <style>
        /* ... (Copy CSS เดิมของคุณมาใส่ตรงนี้) ... */
        .auth-container form { display: flex; flex-direction: column; }
    </style>
</head>
<body>

<?php if (!isset($_SESSION['username'])): ?>
    <div id="auth-section" class="auth-page">
        <div id="login-box" class="auth-container">
            <h2>🔒 เข้าสู่ระบบ</h2>
            <form action="login_db.php" method="POST">
                <input type="text" name="username" placeholder="ชื่อผู้ใช้งาน" required>
                <input type="password" name="password" placeholder="รหัสผ่าน" required>
                <button type="submit" name="login">เข้าสู่ระบบ</button>
            </form>
            <span class="toggle-link" onclick="toggleAuth('register')">ยังไม่มีบัญชี? สมัครสมาชิกที่นี่</span>
        </div>

        <div id="register-box" class="auth-container" style="display: none;">
            <h2>📝 สมัครสมาชิกใหม่</h2>
            <form action="register_db.php" method="POST">
                <input type="text" name="username" placeholder="ตั้งชื่อผู้ใช้งาน" required>
                <input type="email" name="email" placeholder="อีเมล (ถ้ามี)">
                <input type="password" name="password" placeholder="ตั้งรหัสผ่าน" required>
                <button type="submit" name="register" style="background: #28a745;">สร้างบัญชีผู้ใช้</button>
            </form>
            <span class="toggle-link" onclick="toggleAuth('login')">มีบัญชีอยู่แล้ว? กลับไปหน้าเข้าสู่ระบบ</span>
        </div>
    </div>
<?php else: ?>
    <div id="main-content" style="display: block;">
        <div class="container">
            <a href="logout.php" class="btn-logout" style="text-decoration:none; color:white; background:red; padding:10px; border-radius:5px;">ออกจากระบบ</a>
            <h1 style="color: #00247d;">🇹🇭 ระบบจัดการหลังบ้าน ThanJai Shop</h1>
            <p>ยินดีต้อนรับคุณ: <strong><?php echo $_SESSION['username']; ?></strong></p>
            <hr>
            </div>
    </div>
<?php endif; ?>

<script>
    function toggleAuth(target) {
        document.getElementById('login-box').style.display = target === 'login' ? 'block' : 'none';
        document.getElementById('register-box').style.display = target === 'register' ? 'block' : 'none';
    }
</script>
</body>
</html>