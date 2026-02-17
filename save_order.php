<?php
include 'db.php'; // เชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $p_name = $_POST['p_name'];
    $p_price = $_POST['p_price'];
    $p_qty = $_POST['p_qty'];

    // คำสั่ง SQL สำหรับเพิ่มข้อมูล
    $sql = "INSERT INTO products (p_name, p_price, p_qty) VALUES ('$p_name', '$p_price', '$p_qty')";

    if (mysqli_query($conn, $sql)) {
        // บันทึกสำเร็จ ให้กลับไปหน้าเดิม (admin.php หรือชื่อไฟล์ที่คุณใช้)
        echo "<script>alert('บันทึกข้อมูลสำเร็จ'); window.location='admin.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>