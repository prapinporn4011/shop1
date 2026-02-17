<?php
session_start(); include('db.php');
if(isset($_SESSION['username'])){
    $user = $_SESSION['username'];
    $item = $_POST['item_name'];
    $total = $_POST['item_price'];
    $phone = $_POST['phone'];
    $addr = $_POST['address'];
    
    $sql = "INSERT INTO orders (username, phone, address, items, total) VALUES ('$user', '$phone', '$addr', '$item', '$total')";
    mysqli_query($conn, $sql);
    echo "<script>alert('สั่งซื้อสำเร็จ! เราจะรีบจัดส่งให้คุณ'); window.location='index.php';</script>";
}
?>