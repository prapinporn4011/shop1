<?php
session_start();
include('db.php');

if (isset($_POST['login'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = '$user'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        // ตรวจสอบรหัสผ่านที่เข้ารหัสไว้
        if (password_verify($pass, $row['password'])) {
            $_SESSION['username'] = $row['username'];
            header("Location: shop.php"); // เข้าสู่ระบบสำเร็จ ไปหน้า shop.php
        } else {
            echo "<script>alert('รหัสผ่านไม่ถูกต้อง'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('ไม่พบชื่อผู้ใช้งานนี้'); window.history.back();</script>";
    }
}
?>