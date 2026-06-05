<?php
session_start();
include("connect.php");

$step = 1;
$message = "";
$email = "";

if (isset($_POST['check'])) {
    $email = $_POST['email'];
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // YAHAN RANDOM CODE GENERATE KARO
        $_SESSION['temp_otp'] = rand(100000, 999999);
        $step = 3;
    } else {
        $message = "❌ Email not found!";
    }
}

// STEP 3: Verification Code check karke Password reset par bhejna
if (isset($_POST['verify'])) {
    $email = $_POST['email'];
    $code = $_POST['v_code'];

    // Yahan abhi hum dummy check kar rahe hain (e.g. 123456)
    // Aap baad mein real OTP logic daal sakte ho
    if ($code == $_SESSION['temp_otp']) {
        $step = 2;
    } else {
        $message = "❌ Invalid verification code!";
        $step = 3;
    }
}

// STEP 2: Password Update karna
if (isset($_POST['reset'])) {
    $email = $_POST['email'];
    $newpass = $_POST['password'];
    $hashed = password_hash($newpass, PASSWORD_DEFAULT);
    $sql = "UPDATE users SET password='$hashed' WHERE email='$email'";
    if ($conn->query($sql)) {
        $message = "✅ Password Updated Successfully!";
        $step = 1;
    } else {
        $message = "❌ Error updating password!";
        $step = 2;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | EcoCollect Style</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            border: 1px solid #e0e0e0;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 400px;
        }

        .form-title {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #111;
        }

        .sub-text {
            color: #666;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .input-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            margin-bottom: 20px;
            font-size: 15px;
        }

        .btn {
            width: 100%;
            background: #d85d31;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            font-size: 16px;
        }

        .btn:hover {
            background: #bf4f28;
        }

        .error-box {
            background: #fff5f5;
            color: #c53030;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #feb2b2;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1 class="form-title">Reset password</h1>

        <?php if ($message != ""): ?>
            <div class="error-box"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if ($step == 1): ?>
            <p class="sub-text">Enter your email and we'll help you get back into your account.</p>
            <form method="POST">
                <label class="input-label">Email</label>
                <input type="email" name="email" placeholder="e.g. name@company.com" required>
                <button type="submit" name="check" class="btn">Send reset link</button>
            </form>

        <?php elseif ($step == 3): ?>
            <p class="sub-text">We'll email you a 6-digit code to verify it's you.</p>

            <form method="POST">
                <input type="hidden" name="email" value="<?php echo $email; ?>">

                <label class="input-label">6-digit code</label>
                <input type="text" name="v_code" placeholder="______" maxlength="6" required
                    style="letter-spacing: 10px; text-align: center; font-size: 20px;">

                <p style="font-size: 13px; color: #666; background: #fdf2f2; padding: 10px; border-radius: 8px; border: 1px dashed #feb2b2; margin-top: -10px; margin-bottom: 20px; text-align: left;">
                    <strong>Demo mode.</strong> Your code: <span style="color: #d85d31; font-weight: bold;"><?php echo $_SESSION['temp_otp']; ?></span>
                </p>
                <button type="submit" name="verify" class="btn">Verify</button>
            </form>

        <?php elseif ($step == 2): ?>
            <p class="sub-text">Enter a new secure password for your account.</p>
            <form method="POST">
                <input type="hidden" name="email" value="<?php echo $email; ?>">
                <label class="input-label">New Password</label>
                <input type="password" name="password" placeholder="••••••••" required>
                <button type="submit" name="reset" class="btn">Update Password</button>
            </form>
        <?php endif; ?>

        <a href="auth.php" class="back-link">Back to sign in</a>
    </div>

</body>

</html>