<?php
$host = 'localhost';
$dbname = 'shop1'; // ชื่อฐานข้อมูลของคุณ
$user = 'root';
$pass = ''; // รหัสผ่าน XAMPP/MAMP ปกติจะว่าง

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>