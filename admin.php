<?php
session_start();
include("connect.php");

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: auth.php");
    exit();
}

// Fetch data from DB
$sql = "SELECT waste_requests.*, users.name 
        FROM waste_requests 
        JOIN users ON waste_requests.user_id = users.id 
        ORDER BY waste_requests.created_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Panel</title>
<link rel="stylesheet" href="admin.css">
</head>

<body>

<header>
<h1>Admin Dashboard</h1>
<a href="logout.php" class="logout">Logout</a>
</header>

<div class="container">

<h2>User Requests</h2>

<table>
<tr>
<th>Name</th>
<th>Type</th>
<th>Weight</th>
<th>Amount</th>
<th>Status</th>
<th>Action</th>
<th>Date</th>
</tr>

<?php
if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
?>
<tr>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['waste_type']; ?></td>
<td><?php echo $row['weight']; ?> kg</td>
<td>₹<?php echo $row['amount']; ?></td>
<td><?php echo $row['status']; ?></td>

<td>
<form action="update_status.php" method="POST">
<input type="hidden" name="id" value="<?php echo $row['id']; ?>">

<select name="status">
<option value="Pending">Pending</option>
<option value="Approved">Approved</option>
<option value="Collected">Collected</option>
</select>

<button type="submit">Update</button>
</form>
</td>

<td><?php echo $row['created_at']; ?></td>

</tr>

<?php
    }
}else{
    echo "<tr><td colspan='7'>No Requests Found</td></tr>";
}
?>

</table>

</div>

</body>
</html>