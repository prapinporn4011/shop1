<?php session_start(); ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ThanJai Shop - Membership System</title>
    <style>
        /* ก๊อปปี้ CSS เดิมของคุณมาใส่ตรงนี้ได้เลยครับ */
    </style>
</head>
<body>

<div id="auth-section" class="auth-page" style="<?php echo isset($_SESSION['username']) ? 'display:none' : ''; ?>">
    <div id="login-box" class="auth-container">
        <h2>🔒 เข้าสู่ระบบ</h2>
        <form action="register_db.php" method="POST">
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
            <input type="email" name="email" placeholder="อีเมล">
            <input type="password" name="password" placeholder="ตั้งรหัสผ่าน" required>
            <button type="submit" name="register" style="background: #28a745;">สร้างบัญชีผู้ใช้</button>
        </form>
        <span class="toggle-link" onclick="toggleAuth('login')">มีบัญชีอยู่แล้ว? กลับไปหน้าเข้าสู่ระบบ</span>
    </div>
</div>

<?php if(isset($_SESSION['username'])): ?>
<div id="main-content" style="display: block;">
    <div class="container">
        <a href="logout.php" class="btn-logout" style="background:red; color:white; padding:10px; text-decoration:none; border-radius:5px; float:right;">ออกจากระบบ</a>
        <h1>Welcome, <?php echo $_SESSION['username']; ?></h1>
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