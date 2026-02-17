<?php
$hostname = "localhost";
$username = "root";
$password = "";
$dbname   = "myshop_db"; // ชื่อฐานข้อมูลที่คุณสร้างไว้

$conn = mysqli_connect($hostname, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>