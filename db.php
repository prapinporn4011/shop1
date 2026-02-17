<?php
$host = "localhost";
$user = "root";      // ปกติคือ root
$pass = "";          // ปกติคือว่างไว้ (ถ้าไม่ได้ตั้งรหัสผ่าน)
$db   = "ชื่อฐานข้อมูลของคุณ"; 

$conn = mysqli_connect($host, $user, $pass, $db);

// ตรวจสอบการเชื่อมต่อ
if (!$conn) {
    die("เชื่อมต่อล้มเหลว: " . mysqli_connect_error());
}

// ตั้งค่าให้รองรับภาษาไทย
mysqli_set_charset($conn, "utf8");
?>