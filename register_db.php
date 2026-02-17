<?php
include('db.php');

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // เข้ารหัสผ่าน
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // ตรวจสอบชื่อซ้ำก่อน
    $check_user = "SELECT * FROM users WHERE username = '$username'";
    $query_check = mysqli_query($conn, $check_user);

    if (mysqli_num_rows($query_check) > 0) {
        echo "<script>alert('ชื่อผู้ใช้งานนี้มีอยู่แล้ว!'); window.history.back();</script>";
    } else {
        $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('สมัครสมาชิกสำเร็จ!'); window.location='store.php';</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>