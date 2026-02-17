<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("localhost", "root", "", "my_shop");
    $cat_name = $_POST['cat_name'];
    $sql = "INSERT INTO categories (cat_name) VALUES ('$cat_name')";
    if ($conn->query($sql) === TRUE) {
        header("Location: category_index.php");
    }
}
?>
<div class="container mt-5">
    <?php include('navbar.php'); ?>
    <h3>เพิ่มประเภทสินค้า</h3>
    <form method="POST">
        <div class="mb-3">
            <label>ชื่อประเภทสินค้า</label>
            <input type="text" name="cat_name" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">บันทึก</button>
    </form>
</div>