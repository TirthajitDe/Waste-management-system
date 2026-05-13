<?php
include("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

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
?>