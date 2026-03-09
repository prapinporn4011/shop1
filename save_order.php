<?php
session_start();
require_once 'db.php'; // ดึงการเชื่อมต่อ Database

// รับข้อมูล JSON ที่ส่งมาจาก JavaScript (fetch)
$data = json_decode(file_get_contents("php://input"), true);

if ($data) {
    try {
        // เริ่ม Transaction เพื่อความปลอดภัย
        $pdo->beginTransaction();

        // 1. หา user_id จาก username ที่ส่งมา (ถ้าไม่มีให้เป็น NULL)
        $user_id = null;
        if(isset($data['username'])) {
            $stmtUser = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmtUser->execute([$data['username']]);
            $userRow = $stmtUser->fetch();
            if($userRow) $user_id = $userRow['id'];
        }

        // 2. บันทึกลงตาราง orders (อิงตามไฟล์ admin_orders.php ของคุณ)
        $stmtOrder = $pdo->prepare("INSERT INTO orders (user_id, total_price, status, shipping_address, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmtOrder->execute([
            $user_id, 
            $data['total'], 
            $data['status'], // 'pending' หรือ 'paid'
            $data['address']
        ]);
        
        $orderId = $pdo->lastInsertId(); // ดึง ID ล่าสุดที่เพิ่งสร้าง

        // (ถ้าในอนาคตคุณสร้างตาราง order_items ไว้เก็บรายละเอียดสินค้าแต่ละชิ้น ให้ใส่โค้ดตรงนี้)
        /*
        $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, product_id, size, quantity, price) VALUES (?, ?, ?, ?, ?)");
        foreach($data['items'] as $item) {
            $stmtItem->execute([$orderId, $item['id'], $item['size'], $item['qty'], $item['price']]);
        }
        */

        // ยืนยันการบันทึกข้อมูล
        $pdo->commit();

        echo json_encode(['success' => true, 'order_id' => $orderId]);

    } catch (Exception $e) {
        $pdo->rollBack(); // ถ้ายกเลิกให้ถอยกลับ
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No data received']);
}
?>