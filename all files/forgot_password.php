<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Forgot Password | Premium Content Streaming Platform</title>
    <link rel="stylesheet" href="../user-css/user-forgot.css" />
    <link rel="icon" type="image/x-icon" href="../images/live-streaming.png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>

<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="login.html">Login</a></li>
                <li><a href="register.html">Register</a></li>
            </ul>
        </nav>
    </header>
    <div id="contact">
        <div class="container">
            <h1>Reset Your Password</h1>
            <?php if (isset($_SESSION['message'])) {
                echo "<p class='form-message form-error'>{$_SESSION['message']}</p>";
                unset($_SESSION['message']);
            } ?>
            <form action="forgot_password_process.php" method="POST">
                <label for="identifier">Enter your username:</label>
                <input type="text" id="identifier" name="identifier" required>
                <button type="submit"><i class="fas fa-paper-plane"></i> Send Verification Code</button>
            </form>
        </div>
    </div>
</body>

</html>