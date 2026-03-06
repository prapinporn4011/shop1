<?php

$conn = new mysqli("localhost","root","","shop_system");

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>ระบบหลังบ้านร้านค้า</title>

<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300;400;600&display=swap" rel="stylesheet">

<style>

*{
box-sizing:border-box;
font-family:'Noto Sans Thai',sans-serif;
}

body{
margin:0;
background:#0f1b2e;
color:white;
}

/* SIDEBAR */

.sidebar{
width:240px;
height:100vh;
background:#081225;
position:fixed;
}

.logo{
padding:20px;
text-align:center;
font-size:22px;
font-weight:600;
color:#4fd1c5;
border-bottom:1px solid #1e2b45;
}

.menu a{
display:block;
padding:15px 20px;
color:#cbd5e1;
text-decoration:none;
}

.menu a:hover{
background:#1e2b45;
color:white;
}

/* MAIN */

.main{
margin-left:240px;
padding:30px;
}

/* DASHBOARD CARD */

.cards{
display:grid;
grid-template-columns:repeat(4,1fr);
gap:20px;
margin-bottom:30px;
}

.card{
background:#16263f;
padding:20px;
border-radius:12px;
}

.card h3{
margin:0;
font-size:16px;
color:#94a3b8;
}

.card h2{
margin-top:10px;
font-size:28px;
color:#4fd1c5;
}

/* TABLE */

table{
width:100%;
border-collapse:collapse;
background:#16263f;
border-radius:10px;
overflow:hidden;
}

th{
background:#0b1628;
text-align:left;
padding:12px;
}

td{
padding:12px;
border-bottom:1px solid #233556;
}

tr:hover{
background:#1e2b45;
}

/* STATUS */

.status{
padding:5px 10px;
border-radius:6px;
font-size:12px;
}

.wait{
background:#facc15;
color:black;
}

.ship{
background:#22c55e;
}

.cancel{
background:#ef4444;
}

/* BUTTON */

button{
padding:6px 12px;
border:none;
border-radius:6px;
cursor:pointer;
}

.btn{
background:#4fd1c5;
}

.print{
background:#38bdf8;
color:white;
}

h1{
margin-bottom:20px;
}

</style>
</head>

<body>

<!-- SIDEBAR -->

<div class="sidebar">

<div class="logo">
ระบบหลังบ้าน
</div>

<div class="menu">

<a href="admin.php">📊 แดชบอร์ด</a>
<a href="admin.php?page=orders">📦 จัดการออเดอร์</a>
<a href="admin.php?page=products">🛍 จัดการสินค้า</a>
<a href="admin.php?page=customers">👥 ลูกค้า</a>
<a href="admin.php?page=report">📈 รายงานยอดขาย</a>

</div>

</div>

<!-- MAIN -->

<div class="main">

<?php

/* DASHBOARD */

if($page=="dashboard"){

$total=$conn->query("SELECT COUNT(*) as c FROM orders")->fetch_assoc();
$pending=$conn->query("SELECT COUNT(*) as c FROM orders WHERE status='wait'")->fetch_assoc();
$ship=$conn->query("SELECT COUNT(*) as c FROM orders WHERE status='ship'")->fetch_assoc();
$cancel=$conn->query("SELECT COUNT(*) as c FROM orders WHERE status='cancel'")->fetch_assoc();

?>

<h1>📊 แดชบอร์ด</h1>

<div class="cards">

<div class="card">
<h3>ออเดอร์ทั้งหมด</h3>
<h2><?php echo $total['c']; ?></h2>
</div>

<div class="card">
<h3>รอชำระเงิน</h3>
<h2><?php echo $pending['c']; ?></h2>
</div>

<div class="card">
<h3>รอจัดส่ง</h3>
<h2><?php echo $ship['c']; ?></h2>
</div>

<div class="card">
<h3>ออเดอร์ยกเลิก</h3>
<h2><?php echo $cancel['c']; ?></h2>
</div>

</div>

<?php
}

/* ORDERS */

if($page=="orders"){

$q=$conn->query("SELECT * FROM orders");

?>

<h1>📦 รายการออเดอร์</h1>

<table>

<tr>
<th>เลขออเดอร์</th>
<th>ลูกค้า</th>
<th>ยอดรวม</th>
<th>สถานะ</th>
<th>จัดการ</th>
</tr>

<?php while($row=$q->fetch_assoc()){

$c=$conn->query("SELECT * FROM customers WHERE id=".$row['customer_id'])->fetch_assoc();

?>

<tr>

<td>#<?php echo $row['id']; ?></td>

<td><?php echo $c['name']; ?></td>

<td><?php echo $row['total']; ?> บาท</td>

<td>
<span class="status <?php echo $row['status']; ?>">
<?php echo $row['status']; ?>
</span>
</td>

<td>

<a href="admin.php?page=detail&id=<?php echo $row['id']; ?>">
<button class="btn">ดูรายละเอียด</button>
</a>

<button class="print" onclick="window.print()">ปริ้น</button>

</td>

</tr>

<?php } ?>

</table>

<?php
}

/* ORDER DETAIL */

if($page=="detail"){

$id=$_GET['id'];

$order=$conn->query("SELECT * FROM orders WHERE id=$id")->fetch_assoc();
$c=$conn->query("SELECT * FROM customers WHERE id=".$order['customer_id'])->fetch_assoc();

?>

<h1>📄 รายละเอียดออเดอร์</h1>

<p><b>เลขออเดอร์ :</b> <?php echo $order['id']; ?></p>

<h3>ข้อมูลลูกค้า</h3>

<p>
ชื่อ : <?php echo $c['name']; ?><br>
เบอร์ : <?php echo $c['phone']; ?><br>
ที่อยู่ : <?php echo $c['address']; ?>
</p>

<p><b>ยอดรวม :</b> <?php echo $order['total']; ?> บาท</p>

<button class="print" onclick="window.print()">🖨 ปริ้นใบปะหน้า</button>

<?php
}

/* PRODUCTS */

if($page=="products"){

$q=$conn->query("SELECT * FROM products");

?>

<h1>🛍 รายการสินค้า</h1>

<table>

<tr>
<th>ID</th>
<th>ชื่อสินค้า</th>
<th>ราคา</th>
<th>สต๊อก</th>
</tr>

<?php while($p=$q->fetch_assoc()){ ?>

<tr>

<td><?php echo $p['id']; ?></td>
<td><?php echo $p['name']; ?></td>
<td><?php echo $p['price']; ?> บาท</td>
<td><?php echo $p['stock']; ?></td>

</tr>

<?php } ?>

</table>

<?php
}

/* CUSTOMERS */

if($page=="customers"){

$q=$conn->query("SELECT * FROM customers");

?>

<h1>👥 ลูกค้า</h1>

<table>

<tr>
<th>ชื่อลูกค้า</th>
<th>เบอร์โทร</th>
<th>ที่อยู่</th>
</tr>

<?php while($c=$q->fetch_assoc()){ ?>

<tr>

<td><?php echo $c['name']; ?></td>
<td><?php echo $c['phone']; ?></td>
<td><?php echo $c['address']; ?></td>

</tr>

<?php } ?>

</table>

<?php
}

/* REPORT */

if($page=="report"){

$r=$conn->query("SELECT SUM(total) as revenue FROM orders")->fetch_assoc();

?>

<h1>📈 รายงานยอดขาย</h1>

<h2>ยอดขายรวม : <?php echo $r['revenue']; ?> บาท</h2>

<?php
}

?>

</div>

</body>
</html>