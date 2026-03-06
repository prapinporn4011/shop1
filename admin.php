-- 1. สร้างและเลือกฐานข้อมูล
CREATE DATABASE IF NOT EXISTS thanjai_shop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE thanjai_shop;

-- 2. เคลียร์ข้อมูลเก่า (เรียงลำดับป้องกัน Error Foreign Key)
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS customers;
DROP TABLE IF EXISTS admins;
SET FOREIGN_KEY_CHECKS = 1;

-- 3. ตารางแอดมิน (รองรับระบบสมัครสมาชิกใหม่)
CREATE TABLE admins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    role ENUM('SuperAdmin', 'Editor') DEFAULT 'Editor',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 4. ตารางลูกค้า (รองรับยอดซื้อสะสม)
CREATE TABLE customers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cus_name VARCHAR(100) NOT NULL,
    cus_email VARCHAR(100),
    cus_phone VARCHAR(20),
    cus_address TEXT,
    total_spent DECIMAL(10,2) DEFAULT 0.00
) ENGINE=InnoDB;

-- 5. ตารางสินค้า
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    category VARCHAR(50),
    price DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    image_url VARCHAR(255) DEFAULT 'https://via.placeholder.com/150',
    status ENUM('Available', 'Out of Stock') DEFAULT 'Available'
) ENGINE=InnoDB;

-- 6. ตารางคำสั่งซื้อ (รองรับสถานะชำระเงินและเลขพัสดุ)
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT,
    total_amount DECIMAL(10,2),
    pay_status ENUM('Pending', 'Paid', 'Cancelled') DEFAULT 'Pending',
    ship_status ENUM('Processing', 'Shipped', 'Delivered') DEFAULT 'Processing',
    tracking_no VARCHAR(100) DEFAULT '-',
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_cust FOREIGN KEY (customer_id) REFERENCES customers (id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- --------------------------------------------------------
-- เพิ่มข้อมูลตัวอย่าง (เพื่อเทสความเท่ของระบบ)
-- --------------------------------------------------------

-- แอดมินหลัก (User: admin / Pass: 1234)
INSERT INTO admins (username, password, full_name, role) 
VALUES ('admin', '$2y$10$8Wv6yMpsH1/n66T1p5I8UuI9xY.pY6xX6Z6X6Z6X6Z6X6Z6X6Z6', 'ผู้ดูแลระบบ Thanjai', 'SuperAdmin');

-- ข้อมูลลูกค้าตัวอย่าง
INSERT INTO customers (cus_name, cus_phone, cus_address, total_spent) VALUES 
('นายเท่ สปอร์ต', '089-999-8888', '99/1 ถ.สปอร์ต แขวงลมโชย กรุงเทพฯ', 12500.00),
('คุณใจดี มีสุข', '081-222-3333', '1/2 หมู่บ้านรักดี จ.เชียงใหม่', 450.00);

-- ข้อมูลสินค้าตัวอย่าง
INSERT INTO products (name, category, price, stock, status) VALUES 
('เสื้อทีมชาติไทย Navy Edition', 'Jersey', 850.00, 45, 'Available'),
('กางเกงวิ่งขาสั้น Pro-Run', 'Apparel', 350.00, 10, 'Available'),
('รองเท้าสตั๊ด Cyber-Blue', 'Shoes', 2500.00, 0, 'Out of Stock');

-- ข้อมูลออเดอร์ตัวอย่าง
INSERT INTO orders (customer_id, total_amount, pay_status, ship_status, tracking_no) VALUES 
(1, 1200.00, 'Paid', 'Shipped', 'TH123456789'),
(2, 450.00, 'Pending', 'Processing', '-');
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
