<?php

$host="localhost";
$user="root";
$pass="";
$db="shop";

$conn=mysqli_connect($host,$user,$pass,$db);

?>

<!DOCTYPE html>
<html lang="th">

<head>

<meta charset="UTF-8">
<title>Admin Panel</title>

<link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>

body{
margin:0;
font-family:'Prompt';
background:#f3f4f6;
}

/* sidebar */

.sidebar{

width:250px;
height:100vh;
background:#0f172a;
color:white;
position:fixed;
padding:20px;

}

.sidebar h2{

color:#facc15;

}

.sidebar a{

display:block;
color:white;
text-decoration:none;
padding:12px;
margin-top:10px;
border-radius:6px;

}

.sidebar a:hover{

background:#1e293b;

}

/* content */

.content{

margin-left:270px;
padding:30px;

}

.header{

background:#1e293b;
color:white;
padding:15px;
border-radius:10px;

}

/* cards */

.cards{

display:grid;
grid-template-columns:repeat(4,1fr);
gap:20px;
margin-top:20px;

}

.card{

background:white;
padding:20px;
border-radius:10px;
box-shadow:0 5px 10px rgba(0,0,0,0.1);

}

.card h3{

margin:0;

}

/* table */

table{

width:100%;
border-collapse:collapse;
margin-top:20px;
background:white;

}

table th{

background:#0f172a;
color:white;
padding:12px;

}

table td{

padding:10px;
border-bottom:1px solid #ddd;

}

/* buttons */

.btn{

padding:6px 12px;
border:none;
border-radius:6px;
cursor:pointer;

}

.btn-view{

background:#2563eb;
color:white;

}

.btn-print{

background:#f59e0b;
color:white;

}

</style>

</head>

<body>

<div class="sidebar">

<h2>ThanJai Admin</h2>

<a href="#">Dashboard</a>
<a href="#">จัดการสินค้า</a>
<a href="#">รายการออเดอร์</a>
<a href="#">ลูกค้า</a>
<a href="#">รายงานยอดขาย</a>
<a href="#">ตั้งค่าระบบ</a>

</div>


<div class="content">

<div class="header">

<h2>แดชบอร์ดระบบหลังบ้าน</h2>

</div>


<div class="cards">

<div class="card">

<h3>ออเดอร์ทั้งหมด</h3>

<p>

<?php
$result=mysqli_query($conn,"SELECT * FROM orders");
echo mysqli_num_rows($result);
?>

</p>

</div>


<div class="card">

<h3>รอจัดส่ง</h3>

<p>

<?php
$result=mysqli_query($conn,"SELECT * FROM orders WHERE status='รอจัดส่ง'");
echo mysqli_num_rows($result);
?>

</p>

</div>


<div class="card">

<h3>จัดส่งแล้ว</h3>

<p>

<?php
$result=mysqli_query($conn,"SELECT * FROM orders WHERE status='จัดส่งแล้ว'");
echo mysqli_num_rows($result);
?>

</p>

</div>


<div class="card">

<h3>ยกเลิก</h3>

<p>

<?php
$result=mysqli_query($conn,"SELECT * FROM orders WHERE status='ยกเลิก'");
echo mysqli_num_rows($result);
?>

</p>

</div>

</div>


<h2 style="margin-top:40px;">รายการออเดอร์</h2>


<table>

<tr>

<th>เลขออเดอร์</th>
<th>ชื่อลูกค้า</th>
<th>สินค้า</th>
<th>ราคา</th>
<th>ที่อยู่จัดส่ง</th>
<th>สถานะ</th>
<th>จัดการ</th>

</tr>

<?php

$sql="SELECT * FROM orders";
$result=mysqli_query($conn,$sql);

while($row=mysqli_fetch_assoc($result)){

?>

<tr>

<td>#<?php echo $row['id'] ?></td>

<td><?php echo $row['customer_name'] ?></td>

<td><?php echo $row['product_name'] ?></td>

<td><?php echo $row['price'] ?> บาท</td>

<td><?php echo $row['address'] ?></td>

<td><?php echo $row['status'] ?></td>

<td>

<button class="btn btn-view">ดู</button>

<button class="btn btn-print" onclick="window.print()">ปริ้นใบปะหน้า</button>

</td>

</tr>

<?php } ?>

</table>


</div>


</body>
</html>