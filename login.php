<?php
session_start();
include("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Email se user fetch karo
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // 🔐 Password verify
        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];   // 🔥 ADD THIS
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['name'];

            if ($user['role'] == 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: home.php");
            }
            exit();
        } else {
            echo "❌ Wrong Password";
        }
    } else {
        echo "❌ User Not Found";
    }
}
