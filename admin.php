<?php
session_start();
$conn = new mysqli("localhost", "root", "Pw@1458800032693", "thanjai_shop");
if ($conn->connect_error) { die("เชื่อมต่อฐานข้อมูลล้มเหลว"); }
$conn->set_charset("utf8mb4");

// Logic การลบ/อัปเดต (เหมือนเดิม)
if (isset($_GET['delete_order'])) {
    $id = $_GET['delete_order'];
    $conn->query("DELETE FROM orders WHERE id = $id");
    header("Location: admin.php?view=orders");
}
if (isset($_POST['update_status'])) {
    $id = $_POST['order_id'];
    $status = $_POST['status'];
    $conn->query("UPDATE orders SET status = '$status' WHERE id = $id");
    $alert_msg = "อัปเดตสถานะสำเร็จ!"; $alert_type = "success";
}
if (isset($_GET['logout'])) { session_destroy(); header("Location: admin.php"); exit(); }

$view = isset($_GET['view']) ? $_GET['view'] : 'dashboard';
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>THANJAI ADMIN - Sport Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #0f172a;
            --card-dark: #1e293b;
            --neon-blue: #0ea5e9;
            --neon-cyan: #22d3ee;
            --accent-gradient: linear-gradient(135deg, #0ea5e9 0%, #22d3ee 100%);
        }

        body { 
            background-color: var(--bg-dark); 
            color: #e2e8f0; 
            font-family: 'Kanit', sans-serif;
            overflow-x: hidden;
        }

        .sport-font { font-family: 'Orbitron', sans-serif; }

        /* การเคลื่อนไหว */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade { animation: fadeInUp 0.6s ease-out; }

        /* Navbar */
        .navbar {
            background: rgba(30, 41, 59, 0.8) !important;
            backdrop-filter: blur(10px);
            border-bottom: 2px solid var(--neon-blue);
        }

        /* Stat Cards */
        .stat-card {
            background: var(--card-dark);
            border: none;
            border-left: 5px solid var(--neon-blue);
            border-radius: 15px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            position: relative;
        }

        .stat-card:hover {
            transform: scale(1.05) translateY(-5px);
            box-shadow: 0 10px 20px rgba(14, 165, 233, 0.2);
            border-left: 8px solid var(--neon-cyan);
        }

        .icon-box {
            font-size: 2.5rem;
            opacity: 0.3;
            position: absolute;
            right: 15px;
            bottom: 10px;
            color: var(--neon-cyan);
        }

        /* Sidebar Custom */
        .nav-link-custom {
            color: #94a3b8;
            border-radius: 12px;
            margin-bottom: 8px;
            padding: 15px;
            transition: 0.3s;
            border: 1px solid transparent;
        }

        .nav-link-custom:hover, .nav-link-custom.active {
            background: var(--accent-gradient);
            color: white;
            box-shadow: 0 4px 15px rgba(14, 165, 233, 0.4);
            border: 1px solid var(--neon-cyan);
        }

        /* Table Style */
        .table-custom {
            background: var(--card-dark);
            border-radius: 15px;
            overflow: hidden;
            color: white;
        }

        .table-custom thead { background: rgba(14, 165, 233, 0.1); color: var(--neon-cyan); }
        .table td, .table th { border-color: #334155; padding: 15px; vertical-align: middle; }

        .badge-status { border-radius: 8px; padding: 5px 12px; font-weight: 400; }
    </style>
</head>
<body>

<?php if (isset($_SESSION['admin_logged_in'])): ?>
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top px-4 py-3">
        <div class="container-fluid">
            <a class="navbar-brand sport-font fw-bold" href="admin.php">
                <i class="fas fa-bolt text-info me-2"></i>THANJAI <span class="text-info">ADMIN</span>
            </a>
            <div class="ms-auto d-flex align-items-center">
                <div class="text-end me-3 d-none d-md-block">
                    <div class="small text-muted">เข้าสู่ระบบโดย</div>
                    <div class="fw-bold text-info"><?php echo $_SESSION['admin_name']; ?></div>
                </div>
                <a href="?logout=true" class="btn btn-outline-danger rounded-pill px-4 btn-sm">LOGOUT</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-4 px-md-5">
        <div class="row">
            <div class="col-lg-3 mb-4">
                <div class="card bg-transparent border-0">
                    <a href="admin.php?view=dashboard" class="nav-link nav-link-custom d-block text-decoration-none <?php echo $view == 'dashboard' ? 'active' : ''; ?>">
                        <i class="fas fa-tachometer-alt me-2"></i> แดชบอร์ดสปอร์ต
                    </a>
                    <a href="admin.php?view=orders" class="nav-link nav-link-custom d-block text-decoration-none <?php echo $view == 'orders' ? 'active' : ''; ?>">
                        <i class="fas fa-box me-2"></i> ออเดอร์ & ลูกค้า
                    </a>
                    <hr class="text-muted">
                    <div class="p-2">
                        <small class="text-muted text-uppercase">System Status</small>
                        <div class="d-flex align-items-center mt-2">
                            <span class="p-1 bg-success rounded-circle me-2"></span>
                            <small class="text-success">Server Online</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-9 animate-fade">
                
                <?php if ($view == 'dashboard'): ?>
                    <div class="row g-4 mb-5">
                        <div class="col-md-3">
                            <div class="card stat-card p-3">
                                <small class="text-muted">ยอดขายเดือนนี้</small>
                                <h3 class="sport-font mt-2 mb-0">฿45,200</h3>
                                <i class="fas fa-wallet icon-box"></i>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-card p-3" style="border-left-color: #f59e0b;">
                                <small class="text-muted">ออเดอร์ใหม่</small>
                                <h3 class="sport-font mt-2 mb-0">12</h3>
                                <i class="fas fa-shopping-cart icon-box" style="color: #f59e0b;"></i>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-card p-3" style="border-left-color: #10b981;">
                                <small class="text-muted">สินค้าในสต็อก</small>
                                <h3 class="sport-font mt-2 mb-0">1,450</h3>
                                <i class="fas fa-cubes icon-box" style="color: #10b981;"></i>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stat-card p-3" style="border-left-color: #8b5cf6;">
                                <small class="text-muted">สมาชิกทั้งหมด</small>
                                <h3 class="sport-font mt-2 mb-0">284</h3>
                                <i class="fas fa-users icon-box" style="color: #8b5cf6;"></i>
                            </div>
                        </div>
                    </div>

                    <div class="card table-custom border-0 shadow p-5 text-center">
                        <div class="mb-4">
                            <i class="fas fa-gauge-high fa-4x text-info"></i>
                        </div>
                        <h2 class="sport-font">SYSTEM <span class="text-info">READY</span></h2>
                        <p class="text-muted">ระบบวิเคราะห์ข้อมูล และเครื่องมือจัดการหลังบ้านพร้อมทำงานแล้ว</p>
                        <div class="d-flex justify-content-center gap-2 mt-3">
                            <a href="?view=orders" class="btn btn-info px-4">ดูรายการล่าสุด</a>
                        </div>
                    </div>

                <?php elseif ($view == 'orders'): ?>
                    <div class="card table-custom border-0 shadow">
                        <div class="p-4 border-bottom border-secondary d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 sport-font text-info">ORDER MANAGEMENT</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-custom mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>CUSTOMER</th>
                                        <th>TOTAL</th>
                                        <th>STATUS</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $res = $conn->query("SELECT * FROM orders ORDER BY id DESC");
                                    while($row = $res->fetch_assoc()):
                                    ?>
                                    <tr>
                                        <td class="sport-font"><?php echo $row['order_number']; ?></td>
                                        <td><?php echo $row['customer_name']; ?></td>
                                        <td class="text-info">฿<?php echo number_format($row['total_price'], 2); ?></td>
                                        <td>
                                            <form method="POST">
                                                <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                                <select name="status" class="form-select form-select-sm bg-dark text-white border-secondary" onchange="this.form.submit()">
                                                    <option value="pending" <?php if($row['status']=='pending') echo 'selected'; ?>>PENDING</option>
                                                    <option value="paid" <?php if($row['status']=='paid') echo 'selected'; ?>>PAID</option>
                                                    <option value="shipped" <?php if($row['status']=='shipped') echo 'selected'; ?>>SHIPPED</option>
                                                </select>
                                                <input type="hidden" name="update_status">
                                            </form>
                                        </td>
                                        <td>
                                            <a href="?view=detail&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-info me-1"><i class="fas fa-eye"></i></a>
                                            <a href="?delete_order=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Confirm Delete?')"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                <?php elseif ($view == 'detail'): 
                    $id = $_GET['id'];
                    $order = $conn->query("SELECT o.*, d.product_list, d.shipping_address FROM orders o LEFT JOIN order_details d ON o.id = d.order_id WHERE o.id = $id")->fetch_assoc();
                ?>
                    <div class="card table-custom border-0 p-4">
                        <h4 class="sport-font text-info"><i class="fas fa-file-invoice me-2"></i> DETAILS: <?php echo $order['order_number']; ?></h4>
                        <hr class="border-secondary">
                        <div class="row">
                            <div class="col-md-7">
                                <p class="mb-1 text-muted">Customer Name</p>
                                <h5 class="mb-3"><?php echo $order['customer_name']; ?></h5>
                                <p class="mb-1 text-muted">Items Purchased</p>
                                <div class="bg-dark p-3 rounded text-info mb-3 border border-secondary">
                                    <?php echo nl2br($order['product_list'] ?? 'No Data'); ?>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="p-3 rounded" style="background: rgba(14,165,233,0.05); border: 1px dashed var(--neon-blue);">
                                    <p class="small text-muted mb-2"><i class="fas fa-map-marker-alt me-1"></i> DELIVERY ADDRESS</p>
                                    <p class="small"><?php echo nl2br($order['shipping_address'] ?? 'N/A'); ?></p>
                                    <a href="https://line.me" target="_blank" class="btn btn-success btn-sm w-100 mt-2"><i class="fab fa-line"></i> CHAT CUSTOMER</a>
                                </div>
                            </div>
                        </div>
                        <a href="?view=orders" class="btn btn-secondary mt-4 btn-sm">BACK TO LIST</a>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

<?php else: ?>
    <div class="container mt-5 pt-5 text-center">
         <h1 class="sport-font text-info">THANJAI SHOP</h1>
         <p class="text-muted">Please Log in to continue</p>
         <a href="admin.php" class="btn btn-info px-5">LOGIN PAGE</a>
    </div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>