<?php

$host = "localhost";
$user = "root";
$pass = "";
$db   = "shop1";

$conn = new mysqli($host,$user,$pass,$db);

if($conn->connect_error){
    die("เชื่อมต่อฐานข้อมูลไม่ได้ : ".$conn->connect_error);
}

/* dashboard */

$total = $conn->query("SELECT COUNT(*) as total FROM orders")->fetch_assoc()['total'];
$pending = $conn->query("SELECT COUNT(*) as total FROM orders WHERE status='รอชำระเงิน'")->fetch_assoc()['total'];
$shipping = $conn->query("SELECT COUNT(*) as total FROM orders WHERE status='รอจัดส่ง'")->fetch_assoc()['total'];
$success = $conn->query("SELECT COUNT(*) as total FROM orders WHERE status='จัดส่งแล้ว'")->fetch_assoc()['total'];

$orders = $conn->query("SELECT * FROM orders ORDER BY id DESC");

?>

<!DOCTYPE html>
<html lang="th">
<head>

<meta charset="UTF-8">
<title>ระบบหลังบ้านร้านค้า</title>

<link href="https://fonts.googleapis.com/css2?family=Prompt&display=swap" rel="stylesheet">

<style>

body{
font-family:Prompt;
margin:0;
background:#0f172a;
color:white;
}

/* sidebar */

.sidebar{
width:240px;
height:100vh;
background:#020617;
position:fixed;
padding:20px;
}

.sidebar h2{
text-align:center;
margin-bottom:40px;
color:#38bdf8;
}

.sidebar a{
display:block;
padding:12px;
margin:10px 0;
color:white;
text-decoration:none;
border-radius:8px;
transition:0.3s;
}

.sidebar a:hover{
background:#1e293b;
}

/* main */

.main{
margin-left:260px;
padding:30px;
}

/* dashboard */

.dashboard{
display:grid;
grid-template-columns:repeat(4,1fr);
gap:20px;
margin-bottom:40px;
}

.card{
padding:25px;
border-radius:12px;
background:linear-gradient(145deg,#1e293b,#020617);
box-shadow:0 0 10px rgba(0,0,0,0.4);
text-align:center;
}

.card h3{
margin:0;
font-size:18px;
}

.card p{
font-size:28px;
margin-top:10px;
color:#38bdf8;
}

/* table */

table{
width:100%;
border-collapse:collapse;
background:white;
color:black;
border-radius:10px;
overflow:hidden;
}

th{
background:#0f172a;
color:white;
padding:14px;
}

td{
padding:12px;
border-bottom:1px solid #ddd;
}

tr:hover{
background:#f1f5f9;
}

/* buttons */

button{
padding:7px 14px;
border:none;
border-radius:6px;
cursor:pointer;
}

.detail{
background:#0284c7;
color:white;
}

.print{
background:#16a34a;
color:white;
}

.cancel{
background:#dc2626;
color:white;
}

</style>

</head>

<body>

<div class="sidebar">

<h2>⚡ ADMIN PANEL</h2>

<a href="#">📊 แดชบอร์ด</a>
<a href="#">📦 จัดการออเดอร์</a>
<a href="#">🛍 สินค้า</a>
<a href="#">👤 ลูกค้า</a>
<a href="#">💬 แชทลูกค้า</a>
<a href="#">📈 รายงาน</a>
<a href="#">⚙ ตั้งค่า</a>

</div>


<div class="main">

<h1>แดชบอร์ดร้านค้า</h1>

<div class="dashboard">

<div class="card">
<h3>ออเดอร์ทั้งหมด</h3>
<p><?php echo $total ?></p>
</div>

<div class="card">
<h3>รอชำระเงิน</h3>
<p><?php echo $pending ?></p>
</div>

<div class="card">
<h3>รอจัดส่ง</h3>
<p><?php echo $shipping ?></p>
</div>

<div class="card">
<h3>จัดส่งแล้ว</h3>
<p><?php echo $success ?></p>
</div>

</div>


<h2>รายการออเดอร์สินค้า</h2>

<table>

<tr>
<th>เลขออเดอร์</th>
<th>ลูกค้า</th>
<th>สินค้า</th>
<th>ราคา</th>
<th>สถานะ</th>
<th>ที่อยู่</th>
<th>จัดการ</th>
</tr>

<?php while($row = $orders->fetch_assoc()){ ?>

<tr>

<td>#<?php echo $row['id']; ?></td>

<td><?php echo $row['customer_name']; ?></td>

<td><?php echo $row['product_name']; ?></td>

<td><?php echo $row['price']; ?> บาท</td>

<td><?php echo $row['status']; ?></td>

<td><?php echo $row['address']; ?></td>

<td>

<button class="detail">รายละเอียด</button>

<button class="print" onclick="window.print()">ปริ้น</button>

<button class="cancel">ยกเลิก</button>

</td>

</tr>

<?php } ?>

</table>

</div>

</body>
</html>