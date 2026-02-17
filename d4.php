<?php
$conn = new mysqli("localhost", "root", "", "your_database_name");

// 1. ดึงข้อมูลเดิมมาแสดงใน Form
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $res = $conn->query("SELECT * FROM products WHERE id = $id");
    $data = $res->fetch_assoc();
}

// 2. ส่วนของการ Update เมื่อกดปุ่มบันทึก
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['product_name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $sql = "UPDATE products SET product_name='$name', price='$price', stock='$stock' WHERE id=$id";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php"); // แก้ไขเสร็จกลับไปหน้าหลัก
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>แก้ไขสินค้า</title>
</head>
<body class="bg-light">
    <?php include('navbar.php'); ?>

<div class="container mt-5">
    <div class="card shadow mx-auto" style="max-width: 500px;">
        <div class="card-header bg-warning"><h4>แก้ไขข้อมูลสินค้า</h4></div>
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                <div class="mb-3">
                    <label>ชื่อสินค้า</label>
                    <input type="text" name="product_name" class="form-control" value="<?php echo $data['product_name']; ?>" required>
                </div>
                <div class="mb-3">
                    <label>ราคา</label>
                    <input type="number" name="price" step="0.01" class="form-control" value="<?php echo $data['price']; ?>" required>
                </div>
                <div class="mb-3">
                    <label>จำนวนสต็อก</label>
                    <input type="number" name="stock" class="form-control" value="<?php echo $data['stock']; ?>" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">บันทึกการแก้ไข</button>
                <a href="index.php" class="btn btn-secondary w-100 mt-2">ยกเลิก</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>