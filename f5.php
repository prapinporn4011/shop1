<?php
$conn = new mysqli("localhost", "root", "", "your_database_name");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // คำสั่งลบข้อมูลตาม ID
    $sql = "DELETE FROM products WHERE id = $id";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php"); // ลบเสร็จส่งกลับหน้าหลัก
    } else {
        echo "เกิดข้อผิดพลาดในการลบ: " . $conn->error;
    }
}
?>