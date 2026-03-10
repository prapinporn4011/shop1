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
                'profilePic' => $userData['profile_pic'] 
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
                    'profilePic' => $userData['profile_pic'] 
                ]]);
                exit;
            }
        }
        echo json_encode(['success' => false]);
    }

    // ---------------- 5. อัปเดตโปรไฟล์ (เซฟรูปลง Database โดยตรง) ----------------
    elseif ($action === 'update_profile') {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'กรุณาเข้าสู่ระบบก่อนทำรายการ']);
            exit;
        }

        $user_id = $_SESSION['user_id'];
        $fullname = trim($data['fullname']);
        $phone = trim($data['phone']);
        $new_password = isset($data['password']) ? trim($data['password']) : '';
        $profile_pic_data = isset($data['profile_pic']) ? $data['profile_pic'] : '';

        if (empty($fullname) || empty($phone)) {
            echo json_encode(['success' => false, 'message' => 'กรุณากรอกข้อมูลให้ครบถ้วน']);
            exit;
        }

        // เตรียมคำสั่ง SQL
        $query = "UPDATE users SET fullname = ?, phone = ?";
        $params = [$fullname, $phone];

        // ถ้ามีการเปลี่ยนรหัสผ่าน
        if (!empty($new_password)) {
            $query .= ", password = ?";
            $params[] = password_hash($new_password, PASSWORD_DEFAULT);
        }
        
        // ถ้ามีการอัปโหลดรูปภาพมาใหม่ (ต้องเป็น base64)
        if (!empty($profile_pic_data) && strpos($profile_pic_data, 'data:image') === 0) {
            $query .= ", profile_pic = ?";
            $params[] = $profile_pic_data;
        }
        
        $query .= " WHERE id = ?";
        $params[] = $user_id;

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);

        // ส่งรูปภาพกลับไปให้หน้าบ้านอัปเดตแบบเรียลไทม์
        echo json_encode(['success' => true, 'profile_pic' => $profile_pic_data]);
    }
// ---------------- 6. ดึงประวัติการสั่งซื้อของฉัน ----------------
    elseif ($action === 'get_my_orders') {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'กรุณาเข้าสู่ระบบ']);
            exit;
        }

        $user_id = $_SESSION['user_id'];

        // ดึงข้อมูลออเดอร์ของ user นี้
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$user_id]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($orders as $o) {
            // ดึงรายการสินค้าในแต่ละออเดอร์
            $stmtItems = $pdo->prepare("
                SELECT oi.*, p.name, p.image 
                FROM order_items oi 
                LEFT JOIN products p ON oi.product_id = p.id 
                WHERE oi.order_id = ?
            ");
            $stmtItems->execute([$o['id']]);
            $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

            // จัดรูปแบบข้อมูลเตรียมส่งกลับไปให้หน้าเว็บ
            $result[] = [
                'orderId' => 'ORD-' . str_pad($o['id'], 5, '0', STR_PAD_LEFT),
                'date' => date('d/m/Y H:i:s', strtotime($o['created_at'])),
                'status' => $o['status'], // ส่งสถานะจริงจาก DB กลับไป
                'total' => (float)$o['total_price'],
                'address' => $o['shipping_address'],
                'items' => array_map(function($i) {
                    $imgUrl = $i['image'] ? $i['image'] : 'default.jpg';
                    if (strpos($imgUrl, 'http') !== 0 && strpos($imgUrl, 'data:') !== 0 && strpos($imgUrl, 'uploads/') !== 0 && $imgUrl !== 'default.jpg') {
                        $imgUrl = 'uploads/' . $imgUrl;
                    }
                    return [
                        'name' => $i['name'] ?: 'สินค้าหมายเลข ' . $i['product_id'],
                        'img' => $imgUrl,
                        'size' => $i['size'],
                        'qty' => (int)$i['qty'],
                        'price' => (float)$i['price']
                    ];
                }, $items)
            ];
        }

        echo json_encode(['success' => true, 'orders' => $result]);
    }
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>