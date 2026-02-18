<?php
include 'db.php'; // ดึงไฟล์เชื่อมต่อ

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. สร้างเลขที่ใบสั่งซื้อแบบสุ่ม (ตัวอย่าง: ORD + เวลาปัจจุบัน)
    $order_number = "ORD" . date("Ymd") . rand(1000, 9999); 
    
    $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $total_price = $_POST['total_price'];

    // 2. บันทึกทั้ง order_number และข้อมูลอื่นๆ
    $sql = "INSERT INTO orders (order_number, customer_name, total_price, status) 
            VALUES ('$order_number', '$customer_name', '$total_price', 'รอตรวจสอบยอด')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('สั่งซื้อสำเร็จ! เลขที่ของคุณคือ: $order_number'); window.location='order.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>