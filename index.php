<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ThanJai shop - Complete System</title>
    <style>
        :root { --primary: #00247d; --secondary: #ed1c24; --success: #28a745; --bg: #f4f7f6; }
        body { font-family: 'Sarabun', Arial, sans-serif; margin: 0; background: var(--bg); }
        
        /* Auth Section */
        .auth-overlay { display: flex; justify-content: center; align-items: center; height: 100vh; background: var(--primary); }
        .auth-box { background: white; padding: 30px; border-radius: 15px; width: 350px; text-align: center; box-shadow: 0 10px 20px rgba(0,0,0,0.2); }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
        button { cursor: pointer; border: none; border-radius: 8px; font-weight: bold; transition: 0.3s; }
        .btn-blue { background: var(--primary); color: white; width: 100%; padding: 12px; }
        
        /* Main Content */
        #main-app { display: none; padding: 20px; }
        .container { max-width: 1000px; margin: auto; background: white; padding: 25px; border-radius: 15px; }
        header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid var(--primary); padding-bottom: 10px; }
        
        /* Shop Layout */
        .shop-section { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-top: 20px; }
        .product-grid { display: flex; gap: 15px; }
        .product-card { border: 1px solid #eee; padding: 15px; border-radius: 10px; text-align: center; flex: 1; }
        .product-card img { width: 100%; height: 200px; object-fit: cover; border-radius: 8px; }
        
        /* Order Management (Admin View) */
        .admin-section { margin-top: 40px; border-top: 5px double var(--primary); padding-top: 20px; }
        .order-card { background: #f9f9f9; border-left: 5px solid var(--success); padding: 15px; margin-bottom: 15px; border-radius: 5px; }
    </style>
</head>
<body>

<div id="auth-ui" class="auth-overlay">
    <div class="auth-box" id="login-box">
        <h2>🔒 เข้าสู่ระบบ</h2>
        <input type="text" id="user-in" placeholder="ชื่อผู้ใช้งาน">
        <input type="password" id="pass-in" placeholder="รหัสผ่าน">
        <button class="btn-blue" onclick="app.login()">เข้าสู่ระบบ</button>
        <p onclick="app.showRegister()" style="cursor:pointer; color:blue; font-size:14px; margin-top:15px;">ยังไม่มีบัญชี? สมัครสมาชิก</p>
    </div>

    <div class="auth-box" id="reg-box" style="display:none;">
        <h2>📝 สมัครสมาชิก</h2>
        <input type="text" id="user-reg" placeholder="ตั้งชื่อผู้ใช้งาน">
        <input type="password" id="pass-reg" placeholder="ตั้งรหัสผ่าน">
        <button class="btn-blue" style="background:var(--success)" onclick="app.register()">ลงชื่อเข้าใช้</button>
        <p onclick="app.showLogin()" style="cursor:pointer; color:blue; font-size:14px; margin-top:15px;">มีบัญชีแล้ว? กลับไปล็อกอิน</p>
    </div>
</div>

<div id="main-app">
    <div class="container">
        <header>
            <h1>🇹🇭 ShadowSport Shop</h1>
            <button onclick="location.reload()" style="background:red; color:white; padding:5px 10px;">ออกจากระบบ</button>
        </header>

        <div class="shop-section">
            <div>
                <h2>1. เลือกสินค้า</h2>
                <div class="product-grid">
                    <div class="product-card">
                        <img src="a.jpg" alt="Home"> <h4>เสื้อทีมชาติ (น้ำเงิน)</h4>
                        <p>790 บาท</p>
                        <button class="btn-blue" onclick="app.addToCart('เสื้อน้ำเงิน', 790)">เพิ่มลงตะกร้า</button>
                    </div>
                    <div class="product-card">
                        <img src="b.jpg" alt="Away"> <h4>เสื้อทีมชาติ (แดง)</h4>
                        <p>890 บาท</p>
                        <button class="btn-blue" onclick="app.addToCart('เสื้อแดง', 890)">เพิ่มลงตะกร้า</button>
                    </div>
                </div>
            </div>

            <div style="background:#f0f2f5; padding:15px; border-radius:10px;">
                <h2>2. สั่งซื้อ</h2>
                <div id="cart-items">ตะกร้าว่างเปล่า</div>
                <hr>
                <input type="text" id="cust-name" placeholder="ชื่อลูกค้า">
                <textarea id="cust-addr" placeholder="ที่อยู่จัดส่ง" style="width:100%; height:60px; margin-top:10px; border-radius:8px; padding:10px; border:1px solid #ddd;"></textarea>
                <p><strong>รวม: <span id="cart-total">0</span> บาท</strong></p>
                <button class="btn-blue" style="background:var(--success)" onclick="app.checkout()">ยืนยันสั่งซื้อ</button>
            </div>
        </div>

        <div class="admin-section">
            <h2>📋 4. จัดการออเดอร์ (สำหรับผู้ดูแล)</h2>
            <div id="admin-orders">
                <p style="color:#888;">ยังไม่มีออเดอร์เข้ามา</p>
            </div>
        </div>
    </div>
</div>

<script>
const app = {
    cart: [],
    orders: [],

    // ระบบสมาชิก
    showRegister: () => { document.getElementById('login-box').style.display='none'; document.getElementById('reg-box').style.display='block'; },
    showLogin: () => { document.getElementById('reg-box').style.display='none'; document.getElementById('login-box').style.display='block'; },
    
    register: () => {
        const u = document.getElementById('user-reg').value;
        const p = document.getElementById('pass-reg').value;
        if(u && p) {
            localStorage.setItem('user_'+u, p);
            alert('สมัครสำเร็จ!'); app.showLogin();
        }
    },

    login: () => {
        const u = document.getElementById('user-in').value;
        const p = document.getElementById('pass-in').value;
        const storedP = localStorage.getItem('user_'+u);
        if((u === 'admin' && p === '1234') || storedP === p) {
            document.getElementById('auth-ui').style.display='none';
            document.getElementById('main-app').style.display='block';
        } else { alert('รหัสผ่านไม่ถูกต้อง'); }
    },

    // ระบบร้านค้า
    addToCart: (name, price) => {
        app.cart.push({name, price});
        app.renderCart();
    },

    renderCart: () => {
        const div = document.getElementById('cart-items');
        const total = document.getElementById('cart-total');
        if(app.cart.length === 0) { div.innerHTML = "ตะกร้าว่างเปล่า"; total.innerText = 0; return; }
        
        let sum = 0;
        div.innerHTML = app.cart.map((item, i) => {
            sum += item.price;
            return <div>${item.name} - ${item.price}บ. <button onclick="app.removeItem(${i})">x</button></div>;
        }).join('');
        total.innerText = sum;
    },

    removeItem: (i) => { app.cart.splice(i, 1); app.renderCart(); },

    // ระบบสั่งซื้อและจัดการออเดอร์ (ตรงตาม Requirement ใบงาน)
    checkout: () => {
        const name = document.getElementById('cust-name').value;
        const addr = document.getElementById('cust-addr').value;
        if(!name || !addr || app.cart.length === 0) return alert('กรุณากรอกข้อมูลให้ครบ');

        const newOrder = {
            id: app.orders.length + 1,
            customer: name,
            address: addr,
            items: app.cart.map(i => i.name).join(', '),
            total: document.getElementById('cart-total').innerText
        };

        app.orders.push(newOrder);
        app.cart = [];
        document.getElementById('cust-name').value = '';
        document.getElementById('cust-addr').value = '';
        app.renderCart();
        app.renderAdmin();
        alert('สั่งซื้อสำเร็จ!');
    },

    renderAdmin: () => {
        const div = document.getElementById('admin-orders');
        div.innerHTML = app.orders.map(o => `
            <div class="order-card">
                <strong>📦 ออเดอร์ #${o.id}</strong><br>
                👤 <strong>ลูกค้า:</strong> ${o.customer} <br>
                📍 <strong>ที่อยู่:</strong> ${o.address} <br>
                👕 <strong>รายการ:</strong> ${o.items} <br>
                💰 <strong>ยอดรวม:</strong> ${o.total} บาท
            </div>
        `).join('') || "<p>ยังไม่มีออเดอร์เข้ามา</p>";
    }
};
</script>
</body>
</html>