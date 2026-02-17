<?php
include 'db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$action = $data['action'] ?? '';

if ($action == 'register') {
    $fullname = $data['name'];
    $username = $data['user'];
    $password = $data['pass'];
    $sql = "INSERT INTO user_accounts (name, username, pass) VALUES ('$fullname', '$username', '$password')";
    if(mysqli_query($conn, $sql)) echo json_encode(['success' => true]);
} 
else if ($action == 'login') {
    $username = $data['user'];
    $password = $data['pass'];
    $sql = "SELECT * FROM user_accounts WHERE username='$username' AND pass='$password'";
    $result = mysqli_query($conn, $sql);
    if($row = mysqli_fetch_assoc($result)) {
        echo json_encode(['success' => true, 'user' => $row]);
    } else {
        echo json_encode(['success' => false]);
    }
}
else if ($action == 'update_profile') {
    $username = $data['user'];
    $name = $data['name'];
    $phone = $data['phone'];
    $address = $data['address'];
    $pass = $data['pass'];
    
    $query = "UPDATE user_accounts SET name='$name', phone='$phone', address='$address'";
    if(!empty($pass)) $query .= ", pass='$pass'";
    $query .= " WHERE username='$username'";
    
    if(mysqli_query($conn, $query)) echo json_encode(['success' => true]);
}
?>