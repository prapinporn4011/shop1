<?php
// 1. เรียกใช้ไฟล์เชื่อมต่อที่เราทำไว้
require_once 'db.php'; 

// 2. เขียนคำสั่ง SQL (ภาษาที่ใช้คุยกับฐานข้อมูล)
$sql = "SELECT * FROM products";

// 3. สั่งรันคำสั่ง SQL ผ่านตัวแปร $conn
$result = $conn->query($sql);

// 4. เช็คว่าดึงข้อมูลมาได้ไหม
if ($result->num_rows > 0) {
    // วนลูปเอาข้อมูลออกมาทีละแถว
    while($row = $result->fetch_assoc()) {
        echo "ชื่อสินค้า: " . $row['product_name'] . "<br>";
        echo "ราคา: " . $row['price'] . " บาท<br><hr>";
    }
} else {
    echo "ไม่พบข้อมูลสินค้า";
}

// 5. ปิดการเชื่อมต่อเมื่อใช้เสร็จ (Optional แต่ทำไว้ก็ดี)
$conn->close();
?>