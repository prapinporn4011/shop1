<?php
include 'db.php'; // เชื่อมต่อฐานข้อมูลจากไฟล์ db.php ในรูปที่ 6

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. รับค่าจากฟอร์ม
    $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $total_price = $_POST['total_price'];
    
    // 2. สร้างเลขที่ใบสั่งซื้อแบบสุ่ม (เช่น ORD20260218...)
    $order_number = "ORD" . date("YmdHis") . rand(10, 99);
    $status = "รอตรวจสอบยอด";

    // 3. คำสั่ง SQL สำหรับเพิ่มข้อมูลลงตาราง orders (ตามโครงสร้างในรูปที่ 1)
    $sql = "INSERT INTO orders (order_number, customer_name, total_price, status) 
            VALUES ('$order_number', '$customer_name', '$total_price', '$status')";

    if (mysqli_query($conn, $sql)) {
        // บันทึกสำเร็จ ให้เด้งเตือนและไปหน้าประวัติการสั่งซื้อ (รูปที่ 3)
        echo "<script>
                alert('สั่งซื้อสำเร็จ! เลขที่ใบสั่งซื้อของคุณคือ: $order_number');
                window.location='order.php'; 
              </script>";
    } else {
        echo "เกิดข้อผิดพลาด: " . mysqli_error($conn);
    }
}
?>