<?php
$host = 'localhost';
$dbname = 'shop1'; // ชื่อฐานข้อมูลตามในรูปของคุณ
$username = 'root'; // <-- ตรงนี้อาจจะต้องเปลี่ยนเป็น user ที่คุณสร้างไว้
$password = 'ใส่รหัสผ่านฐานข้อมูลของคุณที่นี่'; // <-- ต้องใส่รหัสผ่านให้ถูกต้อง

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // ตั้งค่า Error Mode
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $e->getMessage());
}
?>