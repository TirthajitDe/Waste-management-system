<?php
session_start();
include("connect.php");

// Admin check
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: auth.php");
    exit();
}

// Get data from form
$id = $_POST['id'];
$status = $_POST['status'];

// Validation
if(empty($id) || empty($status)){
    echo "Invalid request!";
    exit();
}

// Update query
$sql = "UPDATE waste_requests SET status='$status' WHERE id='$id'";

if($conn->query($sql)){
    header("Location: admin.php"); // back to admin
    exit();
}else{
    echo "Error: " . $conn->error;
}
?>