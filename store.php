<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ThanJai Shop - Membership System</title>
<style>
    body { font-family: 'Sarabun', Arial, sans-serif; margin: 0; background-color: #f0f2f5; }
    
    /* สไตล์หน้า Login & Register */
    .auth-page {
        display: flex; justify-content: center; align-items: center;
        height: 100vh; background: linear-gradient(135deg, #00247d 0%, #001a5c 100%);
    }
    .auth-container {
        background: white; padding: 30px; border-radius: 15px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2); text-align: center; width: 350px;
    }
    .auth-container h2 { color: #00247d; margin-bottom: 20px; }
    .auth-container input {
        width: 100%; padding: 12px; margin: 10px 0;
        border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box;
    }
    .auth-container button {
        width: 100%; padding: 12px; margin-top: 15px;
        background: #00247d; color: white; border: none; border-radius: 8px;
        font-weight: bold; cursor: pointer; font-size: 16px;
    }
    .auth-container .toggle-link {
        margin-top: 15px; display: block; color: #0056b3;
        text-decoration: none; font-size: 14px; cursor: pointer;
    }
    
    /* สไตล์หน้าจัดการหลัก (ซ่อนไว้ก่อน) */
    #main-content { display: none; padding: 20px; }
    .container { max-width: 900px; margin: auto; background: white; padding: 25px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
    
    .product-grid { display: flex; gap: 20px; justify-content: center; margin: 20px 0; }
    .product { border: 1px solid #eee; padding: 15px; width: 220px; text-align: center; border-radius: 12px; background: #fff; }
    .product img { width: 100%; height: 200px; object-fit: cover; border-radius: 8px; }
    
    .order-box { border-left: 6px solid #28a745; background: #f8fff8; padding: 15px; margin-top: 12px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
    .btn-logout { background: #ff4d4d !important; width: auto !important; float: right; }
    .status-msg { margin-top: 10px; font-size: 14px; }
</style>
</head>
<body>

<div id="auth-section" class="auth-page">
    <div id="login-box" class="auth-container">
        <h2>🔒 เข้าสู่ระบบ</h2>
        <input type="text" id="login-user" placeholder="ชื่อผู้ใช้งาน">
        <input type="password" id="login-pass" placeholder="รหัสผ่าน">
        <button onclick="handleLogin()">เข้าสู่ระบบ</button>
        <div id="login-error" class="status-msg" style="color: red; display: none;">ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง!</div>
        <span class="toggle-link" onclick="toggleAuth('register')">ยังไม่มีบัญชี? สมัครสมาชิกที่นี่</span>
    </div>

    <div id="register-box" class="auth-container" style="display: none;">
        <h2>📝 สมัครสมาชิกใหม่</h2>
        <input type="text" id="reg-user" placeholder="ตั้งชื่อผู้ใช้งาน">
        <input type="password" id="reg-pass" placeholder="ตั้งรหัสผ่าน">
        <input type="password" id="reg-confirm" placeholder="ยืนยันรหัสผ่านอีกครั้ง">
        <button onclick="handleRegister()" style="background: #28a745;">สร้างบัญชีผู้ใช้</button>
        <div id="reg-msg" class="status-msg"></div>
        <span class="toggle-link" onclick="toggleAuth('login')">มีบัญชีอยู่แล้ว? กลับไปหน้าเข้าสู่ระบบ</span>
    </div>
</div>

<div id="main-content">
    <div class="container">
        <button class="btn-logout" onclick="logout()">ออกจากระบบ</button>
        <h1 style="color: #00247d;">🇹🇭 ระบบจัดการหลังบ้าน ShadowSport</h1>
        <p>ยินดีต้อนรับคุณ: <strong id="display-user"></strong></p>
        <hr>

        <h3>📦 รายการสินค้าแนะนำ (a.jpg / b.jpg)</h3>
        <div class="product-grid">
            <div class="product">
                <img src="a.jpg" alt="Home Jersey">
                <h4>เสื้อเหย้า - สีน้ำเงิน</h4>
                <p>790 บาท</p>
            </div>
            <div class="product">
                <img src="b.jpg" alt="Away Jersey">
                <h4>เสื้อเยือน - สีแดง</h4>
                <p>890 บาท</p>
            </div>
        </div>

        <h3>📋 รายการสั่งซื้อของลูกค้า</h3>
        <div id="order-list">
            <div class="order-box">
                <strong>ออเดอร์ #001 (ตัวอย่าง)</strong><br>
                👤 ลูกค้า: คุณชนาธิป <br>
                📍 ที่อยู่: 123 มหาสารคาม <br>
                👕 สินค้า: เสื้อน้ำเงิน 1 ตัว
            </div>
        </div>
    </div>
</div>

<script>
    // --- ระบบสลับหน้าจอ ---
    function toggleAuth(target) {
        document.getElementById('login-box').style.display = target === 'login' ? 'block' : 'none';
        document.getElementById('register-box').style.display = target === 'register' ? 'block' : 'none';
        document.getElementById('login-error').style.display = 'none';
        document.getElementById('reg-msg').innerText = '';
    }

    // --- ระบบสมัครสมาชิก ---
    function handleRegister() {
        const user = document.getElementById('reg-user').value;
        const pass = document.getElementById('reg-pass').value;
        const confirm = document.getElementById('reg-confirm').value;
        const msg = document.getElementById('reg-msg');

        if (!user || !pass) {
            msg.style.color = 'red'; msg.innerText = 'กรุณากรอกข้อมูลให้ครบ!'; return;
        }
        if (pass !== confirm) {
            msg.style.color = 'red'; msg.innerText = 'รหัสผ่านไม่ตรงกัน!'; return;
        }

        // ดึงข้อมูลผู้ใช้ที่มีอยู่แล้วในเบราว์เซอร์
        let users = JSON.parse(localStorage.getItem('users')) || [];

        if (users.some(u => u.username === user)) {
            msg.style.color = 'red'; msg.innerText = 'ชื่อผู้ใช้นี้ถูกใช้ไปแล้ว!'; return;
        }

        // บันทึกยูสเซอร์ใหม่
        users.push({ username: user, password: pass });
        localStorage.setItem('users', JSON.stringify(users));

        msg.style.color = 'green'; msg.innerText = 'สมัครสมาชิกสำเร็จ! กำลังไปหน้า Login...';
        setTimeout(() => toggleAuth('login'), 1500);
    }

    // --- ระบบเข้าสู่ระบบ ---
    function handleLogin() {
        const user = document.getElementById('login-user').value;
        const pass = document.getElementById('login-pass').value;
        const error = document.getElementById('login-error');

        // ตรวจสอบค่าเริ่มต้น (admin/1234) หรือจากที่สมัครไว้
        let users = JSON.parse(localStorage.getItem('users')) || [];
        const foundUser = users.find(u => u.username === user && u.password === pass) || (user === "admin" && pass === "1234");

        if (foundUser) {
            document.getElementById('auth-section').style.display = 'none';
            document.getElementById('main-content').style.display = 'block';
            document.getElementById('display-user').innerText = user;
        } else {
            error.style.display = 'block';
        }
    }

    function logout() {
        location.reload();
    }
</script>

</body>
</html>