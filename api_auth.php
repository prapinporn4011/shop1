<?php
session_start();
require_once 'db.php';

// ตั้งค่าให้ตอบกลับเป็นรูปแบบ JSON เสมอ
header('Content-Type: application/json');

// รับข้อมูล JSON ที่ส่งมาจาก JavaScript
$data = json_decode(file_get_contents("php://input"), true);

// ตรวจสอบว่ามีการส่ง action มาหรือไม่
if (!$data || !isset($data['action'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$action = $data['action'];

try {
    // ---------------- 1. สมัครสมาชิก (Register) ----------------
    if ($action === 'register') {
        $user = trim($data['username']);
        $email = trim($data['email']);
        $phone = trim($data['phone']);
        $pass = $data['password'];

        // เช็คว่ามี username, email หรือ phone ซ้ำในระบบหรือไม่
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ? OR phone = ?");
        $stmt->execute([$user, $email, $phone]);
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'ชื่อผู้ใช้ อีเมล หรือเบอร์โทรศัพท์นี้ถูกใช้งานแล้ว']);
            exit;
        }

        // เข้ารหัสผ่านก่อนบันทึกลงฐานข้อมูลเพื่อความปลอดภัย
        $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("INSERT INTO users (username, email, phone, password, fullname) VALUES (?, ?, ?, ?, ?)");
        // ตั้งให้ fullname เริ่มต้นมีค่าเท่ากับ username ไปก่อน
        $stmt->execute([$user, $email, $phone, $hashed_password, $user]);

        // สมัครเสร็จให้ล็อกอินอัตโนมัติทันที
        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['username'] = $user;

        echo json_encode(['success' => true, 'user' => [
            'username' => $user, 
            'name' => $user, 
            'email' => $email, 
            'phone' => $phone
        ]]);
    }
    
    // ---------------- 2. เข้าสู่ระบบ (Login) ----------------
    elseif ($action === 'login') {
        $user = trim($data['username']);
        $pass = $data['password'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$user]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        // ตรวจสอบว่าพบผู้ใช้ และรหัสผ่านตรงกับที่เข้ารหัสไว้หรือไม่
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
    
    // ---------------- 3. ออกจากระบบ (Logout) ----------------
    elseif ($action === 'logout') {
        session_destroy();
        echo json_encode(['success' => true]);
    }
    
    // ---------------- 4. เช็คสถานะล็อกอิน (Check Session) ----------------
    // ใช้สำหรับตอนผู้ใช้รีเฟรชหน้าเว็บ เพื่อให้ยังคงล็อกอินอยู่
    elseif ($action === 'check_session') {
        if (isset($_SESSION['user_id'])) {
            $stmt = $pdo->prepare("SELECT username, fullname, email, phone FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($userData) {
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

    // ---------------- 5. อัปเดตโปรไฟล์ (Update Profile) ----------------
    elseif ($action === 'update_profile') {
        // ต้องล็อกอินก่อนถึงจะแก้โปรไฟล์ได้
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'กรุณาเข้าสู่ระบบก่อนทำรายการ']);
            exit;
        }

        $user_id = $_SESSION['user_id'];
        $fullname = trim($data['fullname']);
        $phone = trim($data['phone']);
        $new_password = isset($data['password']) ? trim($data['password']) : '';

        // เช็คว่าส่งข้อมูลสำคัญมาครบไหม
        if (empty($fullname) || empty($phone)) {
            echo json_encode(['success' => false, 'message' => 'กรุณากรอกข้อมูลชื่อและเบอร์โทรให้ครบถ้วน']);
            exit;
        }

        if (!empty($new_password)) {
            // กรณีที่มีการกรอกรหัสผ่านใหม่เข้ามาด้วย (ต้องเข้ารหัสก่อนบันทึก)
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET fullname = ?, phone = ?, password = ? WHERE id = ?");
            $stmt->execute([$fullname, $phone, $hashed_password, $user_id]);
        } else {
            // กรณีเปลี่ยนแค่ชื่อกับเบอร์โทร (ไม่เปลี่ยนรหัสผ่าน)
            $stmt = $pdo->prepare("UPDATE users SET fullname = ?, phone = ? WHERE id = ?");
            $stmt->execute([$fullname, $phone, $user_id]);
        }

        echo json_encode(['success' => true]);
    }

} catch (PDOException $e) {
    // ดักจับ Error เผื่อฐานข้อมูลมีปัญหา
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>