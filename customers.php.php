<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>หลังร้าน - จัดการออเดอร์</title>
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
                <a href="d.php" class="list-group-item list-group-item-action">👥 จัดการลูกค้า</a>
                <a href="b.php" class="list-group-item list-group-item-action active">📝 รายการสั่งซื้อ (ออเดอร์)</a>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card border-0 shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4><i class="fa fa-shopping-cart text-primary"></i> รายการสั่งซื้อ (Order Management)</h4>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>เลขที่ใบสั่งซื้อ</th>
                                <th>ชื่อลูกค้า</th>
                                <th>วันที่สั่งซื้อ</th>
                                <th>สถานะ</th>
                                <th>ยอดรวม</th>
                                <th>จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>#ORD-001</strong></td>
                                <td>วีรเทพ ป้อมพันธุ์</td>
                                <td>06/03/2026</td>
                                <td><span class="badge bg-warning text-dark">รอการจัดส่ง</span></td>
                                <td>฿1,250.00</td>
                                <td>
                                    <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#orderDetailModal">
                                        <i class="fa fa-eye"></i> ดูรายละเอียด
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="orderDetailModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">รายละเอียดการสั่งซื้อ #ORD-001</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <h6><strong>ข้อมูลลูกค้า:</strong></h6>
                <p>ชื่อ-นามสกุล: วีรเทพ ป้อมพันธุ์<br>อีเมล: weerathep@mail.com<br>เบอร์โทร: 081-234-5678</p>
            </div>
            <div class="col-md-6">
                <h6><strong>ที่อยู่จัดส่ง:</strong></h6>
                <p class="text-muted">
                    <i class="fa fa-map-marker-alt text-danger"></i> 
                    123/45 หมู่บ้านสุขใจ ถ.สุขุมวิท แขวงคลองเตย เขตคลองเตย กรุงเทพฯ 10110
                </p>
            </div>
        </div>
        <hr>
        <h6><strong>รายการสินค้าที่สั่ง:</strong></h6>
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>สินค้า</th>
                    <th class="text-center">จำนวน</th>
                    <th class="text-end">ราคา</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>เสื้อยืดสีขาว Cotton 100%</td>
                    <td class="text-center">2</td>
                    <td class="text-end">฿500.00</td>
                </tr>
                <tr>
                    <td>กางเกงยีนส์ขากระบอก</td>
                    <td class="text-center">1</td>
                    <td class="text-end">฿750.00</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2" class="text-end">ยอดรวมทั้งสิ้น:</th>
                    <th class="text-end text-primary">฿1,250.00</th>
                </tr>
            </tfoot>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิดหน้าต่าง</button>
        <button type="button" class="btn btn-success"><i class="fa fa-check"></i> ยืนยันการจัดส่ง</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>