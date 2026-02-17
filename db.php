<?php
$hostname = "localhost"; // หรือลองเปลี่ยนเป็น "127.0.0.1"
$username = "ชื่อผู้ใช้ที่คุณสร้างในโฮสติ้ง"; 
$password = "รหัสผ่านฐานข้อมูลของคุณ"; 
$dbname   = "myshop_db"; 

$conn = mysqli_connect($hostname, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>