<?php

$conn = new mysqli("localhost","root","","shop_system");

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Sport Admin</title>

<style>

body{
margin:0;
font-family:Arial;
background:#0a192f;
color:white;
}

.sidebar{
width:230px;
height:100vh;
background:#020c1b;
position:fixed;
}

.logo{
text-align:center;
padding:20px;
font-size:22px;
font-weight:bold;
color:#64ffda;
}

.menu a{
display:block;
padding:15px 20px;
color:white;
text-decoration:none;
}

.menu a:hover{
background:#112240;
}

.main{
margin-left:230px;
padding:30px;
}

.cards{
display:grid;
grid-template-columns:repeat(4,1fr);
gap:20px;
margin-bottom:30px;
}

.card{
background:#112240;
padding:20px;
border-radius:10px;
}

.card h2{
margin:0;
color:#64ffda;
}

table{
width:100%;
border-collapse:collapse;
background:#112240;
border-radius:10px;
}

th,td{
padding:12px;
}

th{
background:#020c1b;
}

.status{
padding:4px 8px;
border-radius:5px;
font-size:12px;
}

.wait{background:orange;}
.ship{background:green;}
.cancel{background:red;}

button{
padding:6px 10px;
border:none;
background:#64ffda;
border-radius:5px;
cursor:pointer;
}

</style>

</head>

<body>

<div class="sidebar">

<div class="logo">SPORT ADMIN</div>

<div class="menu">

<a href="admin.php">Dashboard</a>
<a href="admin.php?page=orders">Orders</a>
<a href="admin.php?page=products">Products</a>
<a href="admin.php?page=customers">Customers</a>
<a href="admin.php?page=report">Report</a>

</div>

</div>

<div class="main">

<?php

if($page == "dashboard"){

$order = $conn->query("SELECT COUNT(*) as total FROM orders")->fetch_assoc();
?>

<h1>Dashboard</h1>

<div class="cards">

<div class="card">
<p>Total Orders</p>
<h2><?php echo $order['total']; ?></h2>
</div>

<div class="card">
<p>Pending</p>
<h2>
<?php
$r=$conn->query("SELECT COUNT(*) as c FROM orders WHERE status='waiting'")->fetch_assoc();
echo $r['c'];
?>
</h2>
</div>

<div class="card">
<p>Shipping</p>
<h2>
<?php
$r=$conn->query("SELECT COUNT(*) as c FROM orders WHERE status='shipping'")->fetch_assoc();
echo $r['c'];
?>
</h2>
</div>

<div class="card">
<p>Cancel</p>
<h2>
<?php
$r=$conn->query("SELECT COUNT(*) as c FROM orders WHERE status='cancel'")->fetch_assoc();
echo $r['c'];
?>
</h2>
</div>

</div>

<?php
}

if($page == "orders"){
?>

<h1>Orders</h1>

<table>

<tr>
<th>ID</th>
<th>Customer</th>
<th>Total</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php

$q = $conn->query("SELECT * FROM orders");

while($row=$q->fetch_assoc()){

$cust = $conn->query("SELECT * FROM customers WHERE id=".$row['customer_id'])->fetch_assoc();

?>

<tr>

<td>#<?php echo $row['id']; ?></td>

<td><?php echo $cust['name']; ?></td>

<td><?php echo $row['total']; ?></td>

<td>
<span class="status <?php echo $row['status']; ?>">
<?php echo $row['status']; ?>
</span>
</td>

<td>

<a href="admin.php?page=detail&id=<?php echo $row['id']; ?>">
<button>Detail</button>
</a>

<button onclick="window.print()">Print</button>

</td>

</tr>

<?php } ?>

</table>

<?php
}

if($page=="detail"){

$id=$_GET['id'];

$order=$conn->query("SELECT * FROM orders WHERE id=$id")->fetch_assoc();

$cust=$conn->query("SELECT * FROM customers WHERE id=".$order['customer_id'])->fetch_assoc();

?>

<h1>Order Detail</h1>

<p>Order ID : <?php echo $order['id']; ?></p>

<h3>Customer</h3>

<p>
Name : <?php echo $cust['name']; ?><br>
Phone : <?php echo $cust['phone']; ?><br>
Address : <?php echo $cust['address']; ?>
</p>

<p>Total : <?php echo $order['total']; ?></p>

<button onclick="window.print()">Print Label</button>

<?php
}

if($page=="products"){

$q=$conn->query("SELECT * FROM products");

?>

<h1>Products</h1>

<table>

<tr>
<th>ID</th>
<th>Name</th>
<th>Price</th>
<th>Stock</th>
</tr>

<?php while($p=$q->fetch_assoc()){ ?>

<tr>

<td><?php echo $p['id']; ?></td>
<td><?php echo $p['name']; ?></td>
<td><?php echo $p['price']; ?></td>
<td><?php echo $p['stock']; ?></td>

</tr>

<?php } ?>

</table>

<?php
}

if($page=="customers"){

$q=$conn->query("SELECT * FROM customers");

?>

<h1>Customers</h1>

<table>

<tr>
<th>Name</th>
<th>Phone</th>
<th>Address</th>
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

if($page=="report"){

$r=$conn->query("SELECT SUM(total) as revenue FROM orders")->fetch_assoc();

?>

<h1>Report</h1>

<p>Total Revenue : <?php echo $r['revenue']; ?> บาท</p>

<?php } ?>

</div>

</body>
</html>