<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['action'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$action = $data['action'];

try {
    // ---------------- 1. สมัครสมาชิก ----------------
    if ($action === 'register') {
        $user = trim($data['username']);
        $email = trim($data['email']);
        $phone = trim($data['phone']);
        $pass = $data['password'];

        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ? OR phone = ?");
        $stmt->execute([$user, $email, $phone]);
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'ชื่อผู้ใช้ อีเมล หรือเบอร์โทรศัพท์นี้ถูกใช้งานแล้ว']);
            exit;
        }

        $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, phone, password, fullname) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user, $email, $phone, $hashed_password, $user]);

        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['username'] = $user;

        echo json_encode(['success' => true, 'user' => [
            'username' => $user, 'name' => $user, 'email' => $email, 'phone' => $phone, 'profilePic' => null
        ]]);
    }
    
    // ---------------- 2. เข้าสู่ระบบ ----------------
    elseif ($action === 'login') {
        $user = trim($data['username']);
        $pass = $data['password'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$user]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userData && password_verify($pass, $userData['password'])) {
            $_SESSION['user_id'] = $userData['id'];
            $_SESSION['username'] = $userData['username'];

            echo json_encode(['success' => true, 'user' => [
                'username' => $userData['username'],
                'name' => $userData['fullname'],
                'email' => $userData['email'],
                'phone' => $userData['phone'],
                'profilePic' => $userData['profile_pic'] // ส่งรูปกลับไปด้วย
            ]]);
        } else {
            echo json_encode(['success' => false, 'message' => 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง']);
        }
    }
    
    // ---------------- 3. ออกจากระบบ ----------------
    elseif ($action === 'logout') {
        session_destroy();
        echo json_encode(['success' => true]);
    }
    
    // ---------------- 4. เช็คสถานะล็อกอิน ----------------
    elseif ($action === 'check_session') {
        if (isset($_SESSION['user_id'])) {
            $stmt = $pdo->prepare("SELECT username, fullname, email, phone, profile_pic FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($userData) {
                echo json_encode(['success' => true, 'user' => [
                    'username' => $userData['username'],
                    'name' => $userData['fullname'],
                    'email' => $userData['email'],
                    'phone' => $userData['phone'],
                    'profilePic' => $userData['profile_pic'] // ส่งรูปกลับไปด้วย
                ]]);
                exit;
            }
        }
        echo json_encode(['success' => false]);
    }

    // ---------------- 5. อัปเดตโปรไฟล์ (อัปโหลดรูป) ----------------
    elseif ($action === 'update_profile') {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'กรุณาเข้าสู่ระบบก่อนทำรายการ']);
            exit;
        }

        $user_id = $_SESSION['user_id'];
        $fullname = trim($data['fullname']);
        $phone = trim($data['phone']);
        $new_password = isset($data['password']) ? trim($data['password']) : '';
        $profile_pic_base64 = isset($data['profile_pic']) ? $data['profile_pic'] : '';

        if (empty($fullname) || empty($phone)) {
            echo json_encode(['success' => false, 'message' => 'กรุณากรอกข้อมูลให้ครบถ้วน']);
            exit;
        }

        // ระบบแปลง Base64 เป็นไฟล์รูปภาพ
        $profile_pic_path = null;
        if (!empty($profile_pic_base64) && strpos($profile_pic_base64, 'data:image') === 0) {
            $image_parts = explode(";base64,", $profile_pic_base64);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);

            // สร้างโฟลเดอร์ uploads ถ้ายังไม่มี
            if (!is_dir('uploads')) {
                mkdir('uploads', 0777, true);
            }

            // ตั้งชื่อไฟล์ใหม่ให้ไม่ซ้ำกัน
            $file_name = 'profile_' . $user_id . '_' . time() . '.' . $image_type;
            $file_path = 'uploads/' . $file_name;
            
            // บันทึกไฟล์ลงเซิร์ฟเวอร์
            file_put_contents($file_path, $image_base64);
            $profile_pic_path = $file_path;
        }

        // สร้างคำสั่ง SQL แบบยืดหยุ่น (อัปเดตเฉพาะสิ่งที่ส่งมา)
        $query = "UPDATE users SET fullname = ?, phone = ?";
        $params = [$fullname, $phone];

        if (!empty($new_password)) {
            $query .= ", password = ?";
            $params[] = password_hash($new_password, PASSWORD_DEFAULT);
        }
        if ($profile_pic_path) {
            $query .= ", profile_pic = ?";
            $params[] = $profile_pic_path;
        }
        $query .= " WHERE id = ?";
        $params[] = $user_id;

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);

        echo json_encode(['success' => true, 'profile_pic' => $profile_pic_path]);
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>