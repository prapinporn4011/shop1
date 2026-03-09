<?php
$host = "45.91.135.100";
$user = "root";
$pwd = "Pw@1458800032693";
$db = "shop1";

try {
    // แก้ไขชื่อตัวแปรให้ตรงกับที่ตั้งไว้ด้านบน
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pwd);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $e->getMessage());
}
?>