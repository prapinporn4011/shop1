<?php
$hostname = "localhost";
$username = "ชื่อผู้ใช้ DB ของคุณ"; // ดูจาก Hosting
$password = "รหัสผ่าน DB ของคุณ"; // ดูจาก Hosting
$dbname   = "myshop_db"; 

$conn = mysqli_connect($hostname, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>