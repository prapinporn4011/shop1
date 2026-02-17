<?php
$conn = mysqli_connect("localhost", "root", "", "myshop_db");
if (!$conn) {
    die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8");
?>