<?php
$conn = new mysqli("localhost", "root", "", "my_shop");
$id = $_GET['id'];

// ดึงชื่อไฟล์รูปมาลบออกจากโฟลเดอร์ uploads ด้วย
$res = $conn->query("SELECT image FROM products WHERE id = $id");
$row = $res->fetch_assoc();
if($row['image'] != "") {
    unlink("uploads/" . $row['image']);
}

// สั่งลบข้อมูลใน Database
$sql = "DELETE FROM products WHERE id = $id";
if ($conn->query($sql) === TRUE) {
    header("Location: index.php"); // ลบเสร็จแล้วกลับหน้าหลัก
}
?>