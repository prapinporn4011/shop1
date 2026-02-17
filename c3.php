<?php
$conn = new mysqli("localhost", "root", "", "my_shop");
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM products WHERE id = $id");
$row = $result->fetch_assoc();
?>

<div class="container mt-4">
    <?php include('navbar.php'); ?>
    <h3>แก้ไขสินค้า: <?php echo $row['product_name']; ?></h3>
    <form action="update_product.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        
        <div class="mb-3">
            <label class="form-label">ชื่อสินค้า</label>
            <input type="text" name="product_name" class="form-control" value="<?php echo $row['product_name']; ?>" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label">รูปภาพปัจจุบัน</label><br>
            <img src="uploads/<?php echo $row['image']; ?>" width="100" class="mb-2 border">
            <input type="file" name="image" class="form-control">
            <small class="text-muted">*เลือกไฟล์ใหม่หากต้องการเปลี่ยนรูป</small>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">ราคา</label>
                <input type="number" name="price" class="form-control" value="<?php echo $row['price']; ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">สต็อก</label>
                <input type="number" name="stock" class="form-control" value="<?php echo $row['stock']; ?>" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">บันทึกการแก้ไข</button>
        <a href="index.php" class="btn btn-secondary">ยกเลิก</a>
    </form>
</div>