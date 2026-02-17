<?php
$hostname = "localhost";
$username = "root"; 
$password = ""; // *** ถ้าอยู่บน Server จริง ตรงนี้มักจะไม่ว่าง ให้ใส่รหัสผ่าน DB ของคุณ ***
$dbname   = "myshop_db"; 

$conn = mysqli_connect($hostname, $username, $password, $dbname);

if (!$conn) {
    // แสดงข้อความ Error แบบละเอียดเพื่อช่วยวิเคราะห์
    die("Connection failed: " . mysqli_connect_error());
}
?>