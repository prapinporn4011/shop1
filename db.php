<?php
$host = "localhost";
$user = "root"; // หรือ User ที่โฮสต์ให้มา
$pass = "";     // หรือรหัสผ่านที่ตั้งไว้
$db   = "thanjai_shop"; 

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("เชื่อมต่อล้มเหลว: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");
?>