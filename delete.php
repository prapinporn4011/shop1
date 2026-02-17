<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // คำสั่ง SQL สำหรับลบข้อมูล
    $sql = "DELETE FROM products WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        // ลบสำเร็จ กลับไปหน้าเดิม
        header("Location: admin.php"); 
    } else {
        echo "ลบข้อมูลไม่สำเร็จ: " . mysqli_error($conn);
    }
}
?>