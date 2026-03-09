<?php
$host = "localhost"; // เปลี่ยนจาก 45.91.135.100 เป็น localhost
$user = "root";
$pwd = "Pw@1458800032693";
$db = "shop1";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pwd);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $e->getMessage());
}
?>