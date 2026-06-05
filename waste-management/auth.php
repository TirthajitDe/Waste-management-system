<?php
session_start();
include("connect.php");



if (!isset($_SESSION['cap_login_q'])) {
    $a = rand(1, 9);
    $b = rand(1, 9);
    $op = rand(0, 1) ? '+' : '-';
    if ($op == '-' && $b > $a) {
        [$a, $b] = [$b, $a];
    }
    $_SESSION['cap_login_ans'] = ($op == '+') ? $a + $b : $a - $b;
    $_SESSION['cap_login_q']  = "$a $op $b";
}

if (!isset($_SESSION['cap_reg_q'])) {
    $c = rand(1, 9);
    $d = rand(1, 9);
    $op2 = rand(0, 1) ? '+' : '-';
    if ($op2 == '-' && $d > $c) {
        [$c, $d] = [$d, $c];
    }
    $_SESSION['cap_reg_ans'] = ($op2 == '+') ? $c + $d : $c - $d;
    $_SESSION['cap_reg_q']  = "$c $op2 $d";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name'];

        if ($user['role'] == 'admin') {
            header("Location: admin.php");
        } else {
            header("Location: home.php");
        }
        exit();
    } else {
        echo "Login Failed";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


</head>

<body>

    <div class="wrapper">

        <!-- REGISTER -->
        <div class="container" id="signup">
            <h1 class="form-title">Create Account</h1>

            <form method="post" action="register.php">
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="name" required>
                    <label>Full Name</label>
                </div>

                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" required>
                    <label>Email</label>
                </div>

                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" required>
                    <label>Password</label>
                </div>
                <div class="captcha-box">
                    <span style="color:#aaa;font-size:13px;">🤖 Verify:</span>
                    <span class="cap-q"><?php echo $_SESSION['cap_reg_q']; ?> = ?</span>
                    <input type="number" name="captcha_register" placeholder="?" required>
                </div>

                <input type="submit" class="btn" value="Sign Up">
            </form>

            <p>Already have account?</p>
            <button id="signInButton">Login</button>
        </div>

        <!-- LOGIN -->
        <div class="container active" id="signIn">
            <h1 class="form-title">Welcome Back</h1>

            <form method="post" action="login.php">
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" required>
                    <label>Email</label>
                </div>

                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" required>
                    <label>Password</label>
                </div>
                <div class="forgot-link">
                    <a href="forgot.php">Forgot Password?</a>
                </div>
                <div class="captcha-box">
                    <span style="color:#aaa;font-size:13px;">🤖 Verify:</span>
                    <span class="cap-q"><?php echo $_SESSION['cap_login_q']; ?> = ?</span>
                    <input type="number" name="captcha_login" placeholder="?" required>
                </div>


                <input type="submit" class="btn" value="Login" name="login">
            </form>

            <p>Don't have account?</p>

            <button id="signUpButton">Register</button>
        </div>

    </div>

    <script src="script.js"></script>
</body>

</html>