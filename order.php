<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Champs Sports - Admin System</title>
<style>
    body { font-family: 'Sarabun', Arial, sans-serif; margin: 0; background-color: #f0f2f5; }
    
    /* สไตล์หน้า Login */
    #login-page {
        display: flex; justify-content: center; align-items: center;
        height: 100vh; background: #00247d;
    }
    .login-container {
        background: white; padding: 30px; border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2); text-align: center; width: 300px;
    }
    .login-container input {
        width: 100%; padding: 10px; margin: 10px 0;
        border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;
    }
    
    /* สไตล์หน้าจัดการหลัก (ซ่อนไว้ก่อน Login) */
    #admin-content { display: none; padding: 20px; }
    .container { max-width: 900px; margin: auto; background: white; padding: 25px; border-radius: 15px; }
    
    .product-grid { display: flex; gap: 20px; justify-content: center; margin-bottom: 30px; }
    .product { border: 1px solid #ddd; padding: 15px; width: 200px; text-align: center; border-radius: 10px; }
    .product img { width: 100%; height: 180px; object-fit: cover; border-radius: 5px; }
    
    .order-box { 
        border-left: 5px solid #28a745; background: #f4fff4; 
        padding: 15px; margin-top: 10px; border-radius: 5px; 
    }
    
    button { 
        cursor: pointer; padding: 10px 20px; border: none; 
        border-radius: 5px; font-weight: bold; background: #00247d; color: white;
    }
    .btn-logout { background: #ff4d4d; float: right; }
</style>
</head>
<body>

<div id="login-page">
    <div class="login-container">
        <h2>🔒 Admin Login</h2>
        <input type="text" id="user" placeholder="ชื่อผู้ใช้งาน">
        <input type="password" id="pass" placeholder="รหัสผ่าน">
        <button onclick="checkLogin()" style="width: 100%;">เข้าสู่ระบบ</button>
        <p id="error" style="color: red; display: none;">ข้อมูลไม่ถูกต้อง!</p>
    </div>
</div>

<div id="admin-content">
    <div class="container">
        <button class="btn-logout" onclick="logout()">ออกจากระบบ</button>
        <h1>🇹🇭 ระบบจัดการออเดอร์ ShadowSport</h1>
        <hr>

        <h3>📦 รายการสินค้า (ไฟล์ a.jpg และ b.jpg)</h3>
        <div class="product-grid">
            <div class="product">
                <img src="a.jpg" alt="Blue Shirt">
                <p>เสื้อทีมชาติ (น้ำเงิน)</p>
                <button onclick="addOrder('เสื้อน้ำเงิน', 790)">จำลองการสั่งซื้อ</button>
            </div>
            <div class="product">
                <img src="b.jpg" alt="Red Shirt">
                <p>เสื้อทีมชาติ (แดง)</p>
                <button onclick="addOrder('เสื้อแดง', 890)">จำลองการสั่งซื้อ</button>
            </div>
        </div>

        <h3>📋 รายการออเดอร์ที่ต้องจัดส่ง</h3>
        <div id="order-list">
            <p style="color: #888;">ยังไม่มีข้อมูลการสั่งซื้อ</p>
        </div>
    </div>
</div>

<script>
// ข้อมูล Login (คุณสามารถเปลี่ยนตรงนี้ได้)
const ADMIN_USER = "admin";
const ADMIN_PASS = "1234";

function checkLogin() {
    const user = document.getElementById("user").value;
    const pass = document.getElementById("pass").value;

    if (user === ADMIN_USER && pass === ADMIN_PASS) {
        document.getElementById("login-page").style.display = "none";
        document.getElementById("admin-content").style.display = "block";
    } else {
        document.getElementById("error").style.display = "block";
    }
}

function logout() {
    location.reload(); // รีเฟรชหน้าเพื่อกลับไปหน้า Login และล้างข้อมูล
}

// ระบบจัดการออเดอร์ (ตัวอย่างข้อมูลลูกค้าตาม Requirement)
let orders = [
    { id: 1, customer: "คุณวีรเทพ", address: "กรุงเทพฯ", item: "เสื้อน้ำเงิน", price: 790 },
];

function renderOrders() {
    const list = document.getElementById("order-list");
    let html = "";
    
    orders.forEach(order => {
        html += `
        <div class="order-box">
            <strong>ออเดอร์ #${order.id}</strong><br>
            👤 <strong>ลูกค้า:</strong> ${order.customer} <br>
            📍 <strong>ที่อยู่จัดส่ง:</strong> ${order.address} <br>
            👕 <strong>สินค้า:</strong> ${order.item} - ${order.price} บาท
        </div>
        `;
    });
    list.innerHTML = html;
}

// ฟังก์ชันเพิ่มออเดอร์ตัวอย่าง
function addOrder(name, price) {
    orders.push({
        id: orders.length + 1,
        customer: "ลูกค้าใหม่",
        address: "ระบุภายหลัง",
        item: name,
        price: price
    });
    renderOrders();
}

// แสดงข้อมูลเริ่มต้น
renderOrders();
</script>

</body>
</html>