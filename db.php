<?php
$host = "localhost"; // หรือบางโฮสต์อาจใช้ IP หรือชื่อเฉพาะ
$user = "ชื่อผู้ใช้ที่โฮสต์สร้างให้";  // มักจะไม่ใช่ root
$pass = "รหัสผ่านฐานข้อมูล";       // ต้องใส่รหัสผ่านที่ตั้งไว้
$db   = "ชื่อฐานข้อมูล";          // ชื่อ DB ที่คุณสร้างบนโฮสต์

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    // แสดงข้อความ Error แบบละเอียดเพื่อช่วยวิเคราะห์
    die("เชื่อมต่อล้มเหลว: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");
?>