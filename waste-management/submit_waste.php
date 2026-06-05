<?php
session_start();
// Connection fix: waste_management -> waste_db
$conn = new mysqli("localhost", "root", "", "waste_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Session check taaki crash na ho
    if(!isset($_SESSION['user_id'])) {
        die("Session expired. Please login again.");
    }

    $user_id = $_SESSION['user_id']; 
    $waste_type = $_POST['waste_type'];
    $quantity   = $_POST['quantity'];
    $location   = $_POST['location'];
    $pickup_date = $_POST['pickup_date'];
    $notes      = $_POST['notes'];
    $coordinates = $_POST['coordinates'];

    // --- STEP: VALIDATION FOR PAY METHOD ---
    // Agar pay_method nahi mila toh error nahi aayegi, default 'Cash' ya khali chala jayega
    $pay_method = isset($_POST['pay_method']) ? $_POST['pay_method'] : 'Not Selected';

    // Hum amount ko waste_type ke hisab se set kar rahe hain
    $pricing = ['Household' => 199, 'Recyclable' => 299, 'E-Waste' => 499, 'Garden' => 150];
    $amount = $pricing[$waste_type];

    // Status column tumhare DB mein pehle se hai, isliye query safe hai
    $sql = "INSERT INTO waste_requests (user_id, waste_type, quantity, amount, location, pickup_date, notes, coordinates, pay_method, status) 
            VALUES ('$user_id', '$waste_type', '$quantity', '$amount', '$location', '$pickup_date', '$notes', '$coordinates', '$pay_method', 'Pending')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Pickup Scheduled Successfully!'); window.location.href='home.php';</script>";
    } else {
        // Agar abhi bhi error aaye toh ye line batayegi ki kaunsa column miss ho raha hai
        echo "Database Error: " . $conn->error;
    }
}
$conn->close();
?>