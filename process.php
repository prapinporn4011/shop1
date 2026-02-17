<?php
include 'db.php';

$action = $_GET['action'];

// 1. ส่วนของการเพิ่มสินค้า
if ($action == 'add') {
    $p_name = $_POST['p_name'];
    $p_price = $_POST['p_price'];
    $p_qty = $_POST['p_qty'];
    
    $sql = "INSERT INTO products (p_name, p_price, p_qty) VALUES ('$p_name', '$p_price', '$p_qty')";
    mysqli_query($conn, $sql);
}

// 2. ส่วนของการแก้ไขสินค้า
else if ($action == 'update') {
    $id = $_POST['id'];
    $p_name = $_POST['p_name'];
    $p_price = $_POST['p_price'];
    $p_qty = $_POST['p_qty'];
    
    $sql = "UPDATE products SET p_name='$p_name', p_price='$p_price', p_qty='$p_qty' WHERE id=$id";
    mysqli_query($conn, $sql);
}

// 3. ส่วนของการลบสินค้า
else if ($action == 'delete') {
    $id = $_GET['id'];
    $sql = "DELETE FROM products WHERE id=$id";
    mysqli_query($conn, $sql);
}

// เมื่อทำงานเสร็จทุอย่าง ให้กลับไปที่หน้า admin.php
header("Location: admin.php");
exit();
?>