<?php
session_start();
require_once 'db.php'; 

$data = json_decode(file_get_contents("php://input"), true);

if ($data) {
    try {
        $pdo->beginTransaction();

        $user_id = null;
        if(isset($data['username'])) {
            $stmtUser = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmtUser->execute([$data['username']]);
            $userRow = $stmtUser->fetch();
            if($userRow) $user_id = $userRow['id'];
        }

        $stmtOrder = $pdo->prepare("INSERT INTO orders (user_id, total_price, status, shipping_address, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmtOrder->execute([
            $user_id, 
            $data['total'], 
            $data['status'], 
            $data['address']
        ]);
        
        $orderId = $pdo->lastInsertId(); 

        // เอาคอมเมนต์ออก เพื่อให้บันทึกสินค้าลง Database
            $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, product_id, size, qty, price) VALUES (?, ?, ?, ?, ?)");           foreach($data['items'] as $item) {
            $stmtItem->execute([$orderId, $item['id'], $item['size'], $item['qty'], $item['price']]);
        }

        $pdo->commit();
        echo json_encode(['success' => true, 'order_id' => $orderId]);

    } catch (Exception $e) {
        $pdo->rollBack(); 
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No data received']);
}
?>