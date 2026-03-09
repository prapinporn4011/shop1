<?php
session_start();
require_once 'db.php';

// ตั้งค่าให้ตอบกลับเป็น JSON
header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['action'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$action = $data['action'];

try {
    // ----------------สมัครสมาชิก----------------
    if ($action === 'register') {
        $user = trim($data['username']);
        $email = trim($data['email']);
        $phone = trim($data['phone']);
        $pass = $data['password'];

        // เช็คว่ามี username หรือ email ซ้ำไหม
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ? OR phone = ?");
        $stmt->execute([$user, $email, $phone]);
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'ชื่อผู้ใช้ อีเมล หรือเบอร์โทรศัพท์นี้ถูกใช้งานแล้ว']);
            exit;
        }

        // เข้ารหัสผ่านก่อนบันทึก
        $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("INSERT INTO users (username, email, phone, password, fullname) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user, $email, $phone, $hashed_password, $user]);

        // สมัครเสร็จให้ล็อกอินอัตโนมัติ
        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['username'] = $user;

        echo json_encode(['success' => true, 'user' => [
            'username' => $user, 'name' => $user, 'email' => $email, 'phone' => $phone
        ]]);
    }
    // ----------------เข้าสู่ระบบ----------------
    elseif ($action === 'login') {
        $user = trim($data['username']);
        $pass = $data['password'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$user]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        // เช็คผู้ใช้ และตรวจสอบรหัสผ่านที่เข้ารหัสไว้
        if ($userData && password_verify($pass, $userData['password'])) {
            $_SESSION['user_id'] = $userData['id'];
            $_SESSION['username'] = $userData['username'];

            echo json_encode(['success' => true, 'user' => [
                'username' => $userData['username'],
                'name' => $userData['fullname'],
                'email' => $userData['email'],
                'phone' => $userData['phone']
            ]]);
        } else {
            echo json_encode(['success' => false, 'message' => 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง']);
        }
    }
    // ----------------ออกจากระบบ----------------
    elseif ($action === 'logout') {
        session_destroy();
        echo json_encode(['success' => true]);
    }
    // ----------------เช็คสถานะล็อกอินตอนรีเฟรชหน้าเว็บ----------------
    elseif ($action === 'check_session') {
        if (isset($_SESSION['user_id'])) {
            $stmt = $pdo->prepare("SELECT username, fullname, email, phone FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($userData) {
                echo json_encode(['success' => true, 'user' => [
                    'username' => $userData['username'],
                    'name' => $userData['fullname'],
                    'email' => $userData['email'],
                    'phone' => $userData['phone']
                ]]);
                exit;
            }
        }
        echo json_encode(['success' => false]);
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>