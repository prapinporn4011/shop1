<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - ThanJai Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <style>
        :root { --primary: #1a1a1a; --accent: #ffae00; }
        body { font-family: 'Sarabun', sans-serif; background: #f4f6f9; }
        .sidebar { min-height: 100vh; background: var(--primary); color: white; }
        .sidebar a { color: #ccc; text-decoration: none; padding: 10px 20px; display: block; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background: var(--accent); color: var(--primary); font-weight: bold; }
        .content { padding: 20px; width: 100%; }
    </style>
</head>
<body class="d-flex">

    <div class="sidebar d-flex flex-column" style="width: 250px;">
        <h3 class="text-center py-4 text-warning fw-bold border-bottom border-secondary">
            ThanJai <span class="text-white">Admin</span>
        </h3>
        <a href="admin_dashboard.php"><i class="fa fa-chart-line me-2"></i> ภาพรวม (Dashboard)</a>
        <a href="admin_orders.php"><i class="fa fa-box me-2"></i> จัดการออเดอร์</a>
        <a href="admin_products.php" class="active"><i class="fa fa-tshirt me-2"></i> จัดการสินค้า</a>
        <a href="admin_categories.php"><i class="fa fa-tags me-2"></i> จัดการประเภทสินค้า</a>
        <a href="admin_users.php"><i class="fa fa-users me-2"></i> จัดการลูกค้า</a>
        <div class="mt-auto mb-3">
            <a href="logout.php" class="text-danger"><i class="fa fa-sign-out-alt me-2"></i> ออกจากระบบ</a>
        </div>
    </div>

    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">จัดการสินค้า (Products)</h2>
            <button class="btn btn-warning fw-bold" data-bs-toggle="modal" data-bs-target="#addProductModal">
                <i class="fa fa-plus"></i> เพิ่มสินค้าใหม่
            </button>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <table id="productsTable" class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>รูปภาพ</th>
                            <th>ชื่อสินค้า</th>
                            <th>ประเภท</th>
                            <th>ราคา</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td><img src="2.jpg" width="50" class="rounded shadow-sm"></td>
                            <td>Uthai Thani Home 2024</td>
                            <td><span class="badge bg-secondary">เหย้า</span></td>
                            <td>฿790</td>
                            <td>
                                <button class="btn btn-sm btn-info text-white"><i class="fa fa-edit"></i> แก้ไข</button>
                                <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> ลบ</button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td><img src="4.jpg" width="50" class="rounded shadow-sm"></td>
                            <td>Buriram United Home</td>
                            <td><span class="badge bg-secondary">เหย้า</span></td>
                            <td>฿690</td>
                            <td>
                                <button class="btn btn-sm btn-info text-white"><i class="fa fa-edit"></i> แก้ไข</button>
                                <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> ลบ</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addProductModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title fw-bold">เพิ่มสินค้าใหม่</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="api/add_product.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">ชื่อสินค้า</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">ประเภท</label>
                                <select name="category_id" class="form-select">
                                    <option value="1">เหย้า</option>
                                    <option value="2">เยือน</option>
                                    <option value="3">ซ้อม</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">ราคา</label>
                                <input type="number" name="price" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">รายละเอียด</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">อัปโหลดรูปภาพ</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-dark w-100">บันทึกข้อมูล</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            // เรียกใช้งาน DataTables
            $('#productsTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/th.json" // แปลภาษาไทยให้ DataTables
                }
            });
        });
    </script>
</body>
</html>