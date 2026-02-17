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
        if (password_verify($pass, $row['password'])) {
            $_SESSION['username'] = $row['username'];
            header("Location: store.php");
        } else {
            echo "<script>alert('รหัสผ่านไม่ถูกต้อง'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('ไม่พบผู้ใช้'); window.history.back();</script>";
    }
}
?>