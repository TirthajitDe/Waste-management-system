<?php
session_start();
include("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if ((int)$_POST['captcha_register'] !== (int)$_SESSION['cap_reg_ans']) {
        unset($_SESSION['cap_reg_q'], $_SESSION['cap_reg_ans']);
        die("<script>alert('Wrong CAPTCHA! Try again.'); window.history.back();</script>");
    }
    unset($_SESSION['cap_reg_q'], $_SESSION['cap_reg_ans']);

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, email, password, role) 
            VALUES ('$name', '$email', '$password', 'user')";

    if ($conn->query($sql) === TRUE) {
        header("Location: auth.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
