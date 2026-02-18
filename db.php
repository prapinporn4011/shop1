<?php
// ตั้งค่าตัวแปรสำหรับการเชื่อมต่อ
$servername = "localhost"; // ชื่อเซิร์ฟเวอร์ (ปกติใช้ localhost)
$username   = "root";      // ชื่อผู้ใช้ฐานข้อมูล (XAMPP ปกติใช้ root)
$password   = "";          // รหัสผ่าน (XAMPP ปกติจะเป็นค่าว่าง)
$dbname     = "myshop_db";   // ชื่อฐานข้อมูลที่เราเพิ่งสร้าง

// คำสั่งเชื่อมต่อ (Create connection)
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ (Check connection)
if ($conn->connect_error) {
    // ถ้าเชื่อมต่อไม่ได้ ให้แจ้งเตือนและหยุดทำงาน
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}

// ตั้งค่าภาษาไทยให้ถูกต้อง (ป้องกันภาษาต่างดาว)
$conn->set_charset("utf8");

// ถ้าหน้านี้ไม่มี Error อะไรขึ้นเลย แสดงว่าเชื่อมต่อสำเร็จ!
?>