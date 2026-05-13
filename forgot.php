<?php
include("connect.php");

$step = 1; // default step
$message = "";
$email = "";

// STEP 1: check email
if(isset($_POST['check'])){
    $email = $_POST['email'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if($result->num_rows > 0){
        $step = 2; // go to reset
    } else {
        $message = "❌ Email not found!";
    }
}

// STEP 2: update password
if(isset($_POST['reset'])){
    $email = $_POST['email'];
    $newpass = $_POST['password'];

    $hashed = password_hash($newpass, PASSWORD_DEFAULT);

    $sql = "UPDATE users SET password='$hashed' WHERE email='$email'";

    if($conn->query($sql)){
        $message = "✅ Password Updated Successfully!";
        $step = 1; // back to login step
    } else {
        $message = "❌ Error updating password!";
        $step = 2;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Forgot Password</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body{
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:linear-gradient(135deg,#667eea,#764ba2);
    font-family:Arial;
}

.box{
    background:rgba(255,255,255,0.15);
    backdrop-filter:blur(10px);
    padding:40px;
    width:320px;
    border-radius:12px;
    text-align:center;
    color:white;
}

input{
    width:100%;
    padding:10px;
    margin:10px 0;
    border:none;
    border-radius:6px;
}

button{
    width:100%;
    padding:10px;
    background:#00c6ff;
    border:none;
    color:white;
    border-radius:6px;
}

.message{
    margin-bottom:10px;
}
</style>

</head>

<body>

<div class="box">
<h2>🔐 Forgot Password</h2>

<?php if($message) echo "<p class='message'>$message</p>"; ?>

<?php if($step == 1){ ?>

<!-- STEP 1: EMAIL -->
<form method="POST">
    <input type="email" name="email" placeholder="Enter Email" required>
    <button name="check">Continue</button>
</form>

<?php } else { ?>

<!-- STEP 2: RESET -->
<form method="POST">
    <input type="hidden" name="email" value="<?php echo $email; ?>">
    <input type="password" name="password" placeholder="New Password" required>
    <button name="reset">Update Password</button>
</form>

<?php } ?>

<br>
<a href="auth.php" style="color:white;">⬅ Back to Login</a>

</div>

</body>
</html>