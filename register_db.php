<?php
include('db.php');
if (isset($_POST['register'])) {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (fullname, username, password) VALUES ('$fullname', '$username', '$password')";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('สมัครสมาชิกสำเร็จ!'); window.location='index.php';</script>";
    } else {
        echo "เกิดข้อผิดพลาด: " . mysqli_error($conn);
    }
}
?>