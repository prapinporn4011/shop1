<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>หลังร้าน - จัดการลูกค้า</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark shadow-sm">
    <div class="container">
        <span class="navbar-brand fw-bold"> ThanJai shop</span>
        <a href="a.php" class="btn btn-outline-light btn-sm">ไปที่หน้าร้าน</a>
    </div>
</nav>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="list-group shadow-sm">
                <a href="c.php" class="list-group-item list-group-item-action">📦 จัดการสินค้า</a>
                <a href="d.php" class="list-group-item list-group-item-action active">👥 จัดการลูกค้า</a>
                <a href="b.php" class="list-group-item list-group-item-action">📝 รายการสั่งซื้อ (ออเดอร์)</a>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card border-0 shadow-sm p-4">
                <h4>จัดการข้อมูลสมาชิก</h4>
                <div id="customerTableContainer">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr><th>ID</th><th>ชื่อ</th><th>อีเมล</th><th>จัดการ</th></tr>
                        </thead>
                        <tbody id="customerTableBody">
                            <tr>
                                <td>1</td><td>วีรเทพ ป้อมพันธุ์</td><td>weerathep@mail.com</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" onclick="alert('แก้ไข')"><i class="fa fa-edit"></i></button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="alert('ลบ')"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>