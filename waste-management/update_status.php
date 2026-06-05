<?php
session_start();
$conn = new mysqli("localhost", "root", "", "waste_db");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $sql = "UPDATE waste_requests SET status = '$status' WHERE id = $id";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: admin.php?status=updated");
    } else {
        echo "Error: " . $conn->error;
    }
}
$conn->close();
?>