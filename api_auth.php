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
        // กำหนดให้คนที่สมัครใหม่ เป็นแค่ 'user' เสมอ
        $stmt = $pdo->prepare("INSERT INTO users (username, email, phone, password, fullname, role) VALUES (?, ?, ?, ?, ?, 'user')");
        $stmt->execute([$user, $email, $phone, $hashed_password, $user]);

        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['username'] = $user;
        $_SESSION['role'] = 'user';

        echo json_encode(['success' => true, 'user' => [
            'username' => $user, 'name' => $user, 'email' => $email, 'phone' => $phone, 'profilePic' => null, 'role' => 'user'
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
            $_SESSION['role'] = $userData['role']; // เก็บสิทธิ์เข้า Session เซิร์ฟเวอร์

            echo json_encode(['success' => true, 'user' => [
                'username' => $userData['username'],
                'name' => $userData['fullname'],
                'email' => $userData['email'],
                'phone' => $userData['phone'],
                'profilePic' => $userData['profile_pic'],
                'role' => $userData['role'] // ส่งสิทธิ์กลับไปให้หน้าบ้าน
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
            $stmt = $pdo->prepare("SELECT username, fullname, email, phone, profile_pic, role FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($userData) {
                echo json_encode(['success' => true, 'user' => [
                    'username' => $userData['username'],
                    'name' => $userData['fullname'],
                    'email' => $userData['email'],
                    'phone' => $userData['phone'],
                    'profilePic' => $userData['profile_pic'],
                    'role' => $userData['role']
                ]]);
                exit;
            }
        }
        echo json_encode(['success' => false]);
    }

    // ---------------- 5. อัปเดตโปรไฟล์ ----------------
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

        $query = "UPDATE users SET fullname = ?, phone = ?";
        $params = [$fullname, $phone];

        if (!empty($new_password)) {
            $query .= ", password = ?";
            $params[] = password_hash($new_password, PASSWORD_DEFAULT);
        }
        if (!empty($profile_pic_data) && strpos($profile_pic_data, 'data:image') === 0) {
            $query .= ", profile_pic = ?";
            $params[] = $profile_pic_data;
        }
        
        $query .= " WHERE id = ?";
        $params[] = $user_id;

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        echo json_encode(['success' => true, 'profile_pic' => $profile_pic_data]);
    }

    // ---------------- 6. ดึงประวัติการสั่งซื้อ ----------------
    elseif ($action === 'get_my_orders') {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'กรุณาเข้าสู่ระบบ']);
            exit;
        }
        $user_id = $_SESSION['user_id'];
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$user_id]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($orders as $o) {
            $stmtItems = $pdo->prepare("SELECT oi.*, p.name, p.image FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
            $stmtItems->execute([$o['id']]);
            $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

            $result[] = [
                'rawOrderId' => $o['id'],
                'orderId' => 'ORD-' . str_pad($o['id'], 5, '0', STR_PAD_LEFT),
                'date' => date('d/m/Y H:i:s', strtotime($o['created_at'])),
                'status' => $o['status'],
                'total' => (float)$o['total_price'],
                'address' => $o['shipping_address'],
                'items' => array_map(function($i) {
                    $imgUrl = $i['image'] ? $i['image'] : 'default.jpg';
                    if (strpos($imgUrl, 'http') !== 0 && strpos($imgUrl, 'data:') !== 0 && strpos($imgUrl, 'uploads/') !== 0 && $imgUrl !== 'default.jpg') $imgUrl = 'uploads/' . $imgUrl;
                    return ['product_id' => $i['product_id'], 'name' => $i['name'] ?: 'สินค้า', 'img' => $imgUrl, 'size' => $i['size'], 'qty' => (int)$i['qty'], 'price' => (float)$i['price']];
                }, $items)
            ];
        }
        echo json_encode(['success' => true, 'orders' => $result]);
    }
    
    // ---------------- 7. บันทึกรีวิวสินค้า ----------------
    elseif ($action === 'add_review') {
        if (!isset($_SESSION['user_id'])) { echo json_encode(['success' => false, 'message' => 'กรุณาเข้าสู่ระบบ']); exit; }
        
        $stmtCheck = $pdo->prepare("SELECT id FROM reviews WHERE order_id = ? AND product_id = ?");
        $stmtCheck->execute([$data['order_id'], $data['product_id']]);
        if ($stmtCheck->fetch()) { echo json_encode(['success' => false, 'message' => 'คุณได้รีวิวสินค้านี้ในออเดอร์นี้ไปแล้วครับ']); exit; }

        $stmt = $pdo->prepare("INSERT INTO reviews (product_id, user_id, order_id, rating, comment) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$data['product_id'], $_SESSION['user_id'], $data['order_id'], $data['rating'], $data['comment']]);
        echo json_encode(['success' => true]);
    }
    
    // ---------------- 8. ดึงรีวิว ----------------
    elseif ($action === 'get_reviews') {
        $stmt = $pdo->prepare("SELECT r.*, u.fullname, u.username, u.profile_pic FROM reviews r LEFT JOIN users u ON r.user_id = u.id WHERE r.product_id = ? ORDER BY r.created_at DESC");
        $stmt->execute([$data['product_id']]);
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($reviews as $k => $v) { $reviews[$k]['created_at'] = date('d/m/Y H:i', strtotime($v['created_at'])); }
        echo json_encode(['success' => true, 'reviews' => $reviews]);
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>