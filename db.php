<?php
$host = 'localhost'; // หรือ 127.0.0.1
$dbname = 'shop1';   // ชื่อฐานข้อมูลของคุณ (อ้างอิงจากภาพที่คุณส่งมา)
$user = 'root';      // Username เริ่มต้นของ XAMPP คือ root
$pass = '';          // Password เริ่มต้นของ XAMPP คือค่าว่าง (ไม่ต้องพิมพ์อะไร)

try {
    // สร้างการเชื่อมต่อด้วย PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    
    // ตั้งค่าให้แสดง Error หากมีปัญหาเกี่ยวกับฐานข้อมูล
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // หากเชื่อมต่อสำเร็จ ระบบจะทำงานเงียบๆ เพื่อไม่ให้ข้อความไปรบกวนหน้าเว็บหลัก
    
} catch(PDOException $e) {
    // หากเชื่อมต่อไม่สำเร็จ จะแสดงข้อความ Error
    echo "การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $e->getMessage();
}
?>