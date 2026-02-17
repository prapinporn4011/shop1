<?php
include('db.php');
if (isset($_POST['register'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT); // เข้ารหัสลับ
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');

    $sql = "INSERT INTO users (username, password, email) VALUES ('$user', '$pass', '$email')";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('สมัครสมาชิกสำเร็จ!'); window.location='store.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>