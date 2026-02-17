<?php
// 1. เชื่อมต่อฐานข้อมูล
$conn = new mysqli("localhost", "root", "", "my_shop");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['product_name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    
    // 2. ตรวจสอบว่ามีการอัปโหลดรูปภาพใหม่หรือไม่
    if ($_FILES['image']['name'] != "") {
        // กรณีอัปโหลดรูปใหม่
        $image_name = $_FILES['image']['name'];
        $ext = pathinfo($image_name, PATHINFO_EXTENSION);
        $new_name = date("YmdHis") . "." . $ext;
        
        // ย้ายไฟล์ไปที่โฟลเดอร์ uploads
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $new_name);
        
        // อัปเดตข้อมูลพร้อมชื่อรูปภาพใหม่
        $sql = "UPDATE products SET product_name='$name', image='$new_name', price='$price', stock='$stock' WHERE id=$id";
    } else {
        // กรณีไม่เปลี่ยนรูปภาพ (ใช้อันเดิม)
        $sql = "UPDATE products SET product_name='$name', price='$price', stock='$stock' WHERE id=$id";
    }

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('แก้ไขข้อมูลสำเร็จ'); window.location='index.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>