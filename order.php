<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShadowSport | Admin Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #00247d;
            --secondary-color: #28a745;
            --danger-color: #ff4d4d;
            --bg-color: #f4f7f6;
        }

        body { 
            font-family: 'Sarabun', sans-serif; 
            margin: 0; 
            background-color: var(--bg-color); 
            color: #333;
        }

        /* --- Login Page --- */
        #login-page {
            display: flex; justify-content: center; align-items: center;
            height: 100vh; background: linear-gradient(135deg, #00247d 0%, #00154b 100%);
        }
        .login-card {
            background: white; padding: 40px; border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3); text-align: center; width: 350px;
        }
        .login-card h2 { margin-bottom: 25px; color: var(--primary-color); }
        .login-card input {
            width: 100%; padding: 12px; margin: 10px 0;
            border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box;
            font-size: 16px;
        }

        /* --- Admin Content --- */
        #admin-content { display: none; padding: 30px; }
        .header {
            display: flex; justify-content: space-between; align-items: center;
            max-width: 1000px; margin: 0 auto 20px;
        }
        .container { 
            max-width: 1000px; margin: auto; background: white; 
            padding: 30px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        /* Product Grid */
        .product-grid { 
            display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); 
            gap: 20px; margin-bottom: 40px; 
        }
        .product-card { 
            border: 1px solid #eee; padding: 15px; text-align: center; 
            border-radius: 12px; transition: transform 0.2s;
        }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .product-card img { width: 100%; height: 200px; object-fit: cover; border-radius: 8px; margin-bottom: 10px; }
        .product-card p { font-weight: bold; margin: 10px 0; }

        /* Order List */
        .order-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .order-table th, .order-table td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        .order-table th { background-color: #f8f9fa; }
        
        .status-badge {
            padding: 4px 10px; border-radius: 20px; font-size: 12px; color: white;
        }
        .status-pending { background: #ffc107; color: #000; }
        .status-shipped { background: var(--secondary-color); }

        /* Buttons */
        button { 
            cursor: pointer; padding: 10px 15px; border: none; 
            border-radius: 6px; font-weight: bold; transition: 0.3s;
        }
        .btn-primary { background: var(--primary-color); color: white; width: 100%; }
        .btn-add { background: var(--secondary-color); color: white; width: 100%; }
        .btn-outline-danger { background: transparent; color: var(--danger-color); border: 1px solid var(--danger-color); padding: 5px 10px; }
        .btn-outline-danger:hover { background: var(--danger-color); color: white; }
        .btn-logout { background: var(--danger-color); color: white; }

        hr { border: 0; border-top: 1px solid #eee; margin: 20px 0; }
    </style>
</head>
<body>

<div id="login-page">
    <div class="login-card">
        <h2>🔵 ShadowSport Admin</h2>
        <input type="text" id="user" placeholder="Username">
        <input type="password" id="pass" placeholder="Password">
        <button class="btn-primary" onclick="checkLogin()">เข้าสู่ระบบ</button>
        <p id="error" style="color: red; display: none; margin-top: 15px;">❌ ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง</p>
    </div>
</div>

<div id="admin-content">
    <div class="header">
        <h1>📦 ระบบจัดการออเดอร์</h1>
        <button class="btn-logout" onclick="logout()">ออกจากระบบ</button>
    </div>

    <div class="container">
        <h3>🛍️ เลือกสินค้าเพื่อจำลองออเดอร์</h3>
        <div class="product-grid">
            <div class="product-card">
                <img src="a.jpg" alt="Blue Shirt" onerror="this.src='https://via.placeholder.com/200x200?text=No+Image'">
                <p>เสื้อทีมชาติ (น้ำเงิน)</p>
                <span style="display:block; margin-bottom: 10px;">790 THB</span>
                <button class="btn-add" onclick="addOrder('เสื้อน้ำเงิน', 790)">+ เพิ่มออเดอร์</button>
            </div>
            <div class="product-card">
                <img src="b.jpg" alt="Red Shirt" onerror="this.src='https://via.placeholder.com/200x200?text=No+Image'">
                <p>เสื้อทีมชาติ (แดง)</p>
                <span style="display:block; margin-bottom: 10px;">890 THB</span>
                <button class="btn-add" onclick="addOrder('เสื้อแดง', 890)">+ เพิ่มออเดอร์</button>
            </div>
        </div>

        <hr>

        <h3>📋 รายการคำสั่งซื้อทั้งหมด</h3>
        <table class="order-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ลูกค้า</th>
                    <th>สินค้า</th>
                    <th>ยอดชำระ</th>
                    <th>สถานะ</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody id="order-rows">
                </tbody>
        </table>
    </div>
</div>

<script>
    const ADMIN_USER = "admin";
    const ADMIN_PASS = "1234";

    // ดึงข้อมูลจาก LocalStorage หรือถ้าไม่มีให้ใช้ค่าเริ่มต้น
    let orders = JSON.parse(localStorage.getItem('orders')) || [
        { id: 101, customer: "คุณวีรเทพ", item: "เสื้อน้ำเงิน", price: 790, status: "pending" }
    ];

    function checkLogin() {
        const u = document.getElementById("user").value;
        const p = document.getElementById("pass").value;
        if (u === ADMIN_USER && p === ADMIN_PASS) {
            document.getElementById("login-page").style.display = "none";
            document.getElementById("admin-content").style.display = "block";
            renderOrders();
        } else {
            document.getElementById("error").style.display = "block";
        }
    }

    function logout() {
        location.reload();
    }

    function addOrder(itemName, price) {
        const newOrder = {
            id: Math.floor(100 + Math.random() * 900), // สุ่มเลข ID
            customer: "ลูกค้าใหม่ #" + (orders.length + 1),
            item: itemName,
            price: price,
            status: "pending"
        };
        orders.push(newOrder);
        saveAndRender();
    }

    function deleteOrder(index) {
        if(confirm('ยืนยันการลบออเดอร์นี้?')) {
            orders.splice(index, 1);
            saveAndRender();
        }
    }

    function toggleStatus(index) {
        orders[index].status = orders[index].status === "pending" ? "shipped" : "pending";
        saveAndRender();
    }

    function saveAndRender() {
        localStorage.setItem('orders', JSON.stringify(orders));
        renderOrders();
    }

    function renderOrders() {
        const tbody = document.getElementById("order-rows");
        if (orders.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" style="text-align:center; color:#888;">ไม่มีข้อมูลออเดอร์</td></tr>`;
            return;
        }

        tbody.innerHTML = orders.map((order, index) => `
            <tr>
                <td>#${order.id}</td>
                <td><strong>${order.customer}</strong></td>
                <td>${order.item}</td>
                <td>${order.price.toLocaleString()} ฿</td>
                <td>
                    <span class="status-badge ${order.status === 'pending' ? 'status-pending' : 'status-shipped'} cursor-pointer" 
                          onclick="toggleStatus(${index})" style="cursor:pointer">
                        ${order.status === 'pending' ? '⏳ รอดำเนินการ' : '✅ จัดส่งแล้ว'}
                    </span>
                </td>
                <td>
                    <button class="btn-outline-danger" onclick="deleteOrder(${index})">ลบ</button>
                </td>
            </tr>
        `).join('');
    }
</script>

</body>
</html>