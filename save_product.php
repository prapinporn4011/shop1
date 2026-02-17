<?php
// 1. ตั้งค่าการเชื่อมต่อฐานข้อมูล
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "my_shop"; // ชื่อฐานข้อมูลตามที่คุณสร้างไว้

$conn = new mysqli($host, $user, $pass, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตั้งค่าภาษาไทย
$conn->set_charset("utf8");

// 2. รับค่าจากฟอร์ม b.php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['product_name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    
    // 3. การจัดการรูปภาพ
    $new_image_name = ""; // ค่าเริ่มต้นกรณีไม่มีรูป
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        
        // แยกนามสกุลไฟล์
        $ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        
        // ตรวจสอบนามสกุลไฟล์ที่อนุญาต
        $allowed = array("jpg", "jpeg", "png", "gif");
        
        if (in_array($ext, $allowed)) {
            // ตั้งชื่อไฟล์ใหม่ด้วยวันที่และเวลา + สุ่มตัวเลข เพื่อป้องกันชื่อซ้ำ
            $new_image_name = date("Ymd_His") . "_" . rand(1000, 9999) . "." . $ext;
            $upload_path = "uploads/" . $new_image_name;
            
            // ย้ายไฟล์ไปยังโฟลเดอร์ uploads
            if (!move_uploaded_file($image_tmp, $upload_path)) {
                echo "<script>alert('เกิดข้อผิดพลาดในการย้ายไฟล์รูปภาพ');</script>";
            }
        } else {
            echo "<script>alert('กรุณาอัปโหลดไฟล์รูปภาพเท่านั้น (jpg, png, gif)');</script>";
        }
    }

    // 4. บันทึกข้อมูลลงฐานข้อมูล
    // ใช้เครื่องหมาย ' ' ครอบตัวแปรที่เป็นสายอักขระ (String)
    $sql = "INSERT INTO products (product_name, image, price, stock) 
            VALUES ('$name', '$new_image_name', '$price', '$stock')";

    if ($conn->query($sql) === TRUE) {
        // เมื่อบันทึกสำเร็จ ให้แสดง Alert และเด้งกลับไปหน้า index.php
        echo "<script>
                alert('บันทึกสินค้าใหม่เรียบร้อยแล้ว');
                window.location.href='index.php';
              </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>