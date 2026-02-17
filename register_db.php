<?php
include('db.php');
if(isset($_POST['register'])){
    $u = $_POST['username'];
    $p = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $e = $_POST['email'];
    mysqli_query($conn, "INSERT INTO users (username, password, email) VALUES ('$u', '$p', '$e')");
    echo "<script>alert('สมัครสำเร็จ!'); window.location='index.php';</script>";
}
?>