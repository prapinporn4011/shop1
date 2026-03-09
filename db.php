<?php
$host = 'localhost';
$dbname = 'shop1'; // อ้างอิงชื่อฐานข้อมูลจากรูปของคุณ
$user = 'root'; // ชื่อผู้ใช้เริ่มต้นของ XAMPP
$pass = ''; // รหัสผ่านเริ่มต้นของ XAMPP (ปล่อยว่างไว้)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    // ตั้งค่าให้แสดง Error หากมีปัญหา
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $e->getMessage());
}
?>