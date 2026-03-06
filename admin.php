<?php
// DATABASE CONNECTION
$host = "localhost";
$user = "root";
$pass = "";
$db   = "shop_db";

$conn = new mysqli($host,$user,$pass,$db);
if($conn->connect_error){
    die("เชื่อมต่อฐานข้อมูลไม่ได้");
}

$orders = $conn->query("SELECT * FROM orders ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>ระบบหลังบ้าน - จัดการออเดอร์</title>

<style>

body{
font-family: "Prompt",sans-serif;
background:#0d1b2a;
color:white;
margin:0;
}

/* sidebar */

.sidebar{
width:230px;
height:100vh;
background:#1b263b;
position:fixed;
padding:20px;
}

.sidebar h2{
text-align:center;
margin-bottom:40px;
}

.sidebar a{
display:block;
color:white;
text-decoration:none;
padding:12px;
margin:8px 0;
border-radius:8px;
}

.sidebar a:hover{
background:#415a77;
}

/* main */

.main{
margin-left:250px;
padding:30px;
}

/* dashboard */

.dashboard{
display:grid;
grid-template-columns:repeat(4,1fr);
gap:20px;
margin-bottom:30px;
}

.card{
background:#1b263b;
padding:20px;
border-radius:12px;
text-align:center;
}

.card h3{
margin:10px 0;
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
background:#1b263b;
color:white;
padding:12px;
}

td{
padding:12px;
border-bottom:1px solid #ddd;
}

button{
padding:6px 12px;
border:none;
border-radius:6px;
cursor:pointer;
}

.detail{
background:#0077b6;
color:white;
}

.print{
background:#38b000;
color:white;
}

.cancel{
background:#d00000;
color:white;
}

</style>

</head>
<body>

<!-- SIDEBAR -->

<div class="sidebar">

<h2>ADMIN</h2>

<a href="#">📊 แดชบอร์ด</a>
<a href="#">📦 จัดการออเดอร์</a>
<a href="#">🛍 รายการสินค้า</a>
<a href="#">👤 ลูกค้า</a>
<a href="#">💬 ข้อความลูกค้า</a>
<a href="#">📈 รายงานยอดขาย</a>
<a href="#">⚙️ ตั้งค่าระบบ</a>

</div>


<!-- MAIN -->

<div class="main">

<h1>แดชบอร์ดสรุปออเดอร์</h1>

<div class="dashboard">

<div class="card">
<h3>ออเดอร์ทั้งหมด</h3>
<p>120</p>
</div>

<div class="card">
<h3>รอชำระเงิน</h3>
<p>15</p>
</div>

<div class="card">
<h3>รอจัดส่ง</h3>
<p>20</p>
</div>

<div class="card">
<h3>จัดส่งแล้ว</h3>
<p>85</p>
</div>

</div>


<h2>รายการออเดอร์สินค้า</h2>

<table>

<tr>
<th>เลขออเดอร์</th>
<th>ลูกค้า</th>
<th>สินค้า</th>
<th>ยอดเงิน</th>
<th>สถานะ</th>
<th>ที่อยู่จัดส่ง</th>
<th>จัดการ</th>
</tr>

<?php
while($row = $orders->fetch_assoc()){
?>

<tr>

<td>#<?php echo $row['id']; ?></td>

<td><?php echo $row['customer_name']; ?></td>

<td><?php echo $row['product_name']; ?></td>

<td><?php echo $row['price']; ?> บาท</td>

<td><?php echo $row['status']; ?></td>

<td><?php echo $row['address']; ?></td>

<td>

<button class="detail">รายละเอียด</button>

<button class="print" onclick="window.print()">ปริ้นใบปะหน้า</button>

<button class="cancel">ยกเลิก</button>

</td>

</tr>

<?php } ?>

</table>

</div>

</body>
</html>