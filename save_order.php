<?php
session_start();
include('db.php');

if (isset($_POST['confirm_order'])) {
    $username = $_SESSION['username'];
    $items = mysqli_real_escape_string($conn, $_POST['items']);
    $total = $_POST['total'];
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    $sql = "INSERT INTO orders (username, items, total, phone, address, status) 
            VALUES ('$username', '$items', '$total', '$phone', '$address', 'รอดำเนินการ')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('สั่งซื้อสำเร็จ! ขอบคุณที่ใช้บริการ'); window.location='index.php';</script>";
    } else {
        echo "ผิดพลาด: " . mysqli_error($conn);
    }
}
?>