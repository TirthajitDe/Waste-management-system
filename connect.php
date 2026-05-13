<?php
$conn = new mysqli("localhost","root","","waste_db");

if($conn->connect_error){
    die("Connection failed");
}
?>