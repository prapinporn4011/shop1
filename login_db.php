<?php
session_start(); include('db.php');
if(isset($_POST['login'])){
    $u = $_POST['username']; $p = $_POST['password'];
    $res = mysqli_query($conn, "SELECT * FROM users WHERE username='$u'");
    $row = mysqli_fetch_assoc($res);
    if($row && password_verify($p, $row['password'])){
        $_SESSION['username'] = $u;
        header("Location: index.php");
    } else { echo "<script>alert('รหัสผ่านผิด!'); window.history.back();</script>"; }
}
?>