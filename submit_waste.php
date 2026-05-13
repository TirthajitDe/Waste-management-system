<?php
session_start();
include("connect.php");

// Check login
if(!isset($_SESSION['user_id'])){
    header("Location: auth.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Form data
$waste_type = $_POST['waste_type'];
$weight = $_POST['weight'];
$amount = $_POST['amount'];
$location = isset($_POST['location']) ? $_POST['location'] : "";

// Basic validation
if(empty($waste_type) || empty($weight) || empty($amount)){
    echo "User ID: " . $user_id;
    exit();
}

// Insert query
$sql = "INSERT INTO waste_requests (user_id, waste_type, weight, amount)
        VALUES ('$user_id', '$waste_type', '$weight', '$amount')";

if($conn->query($sql)){
    // Success → back to home
    header("Location: home.php");
    exit();
}else{
    echo "Error: " . $conn->error;
}

?>