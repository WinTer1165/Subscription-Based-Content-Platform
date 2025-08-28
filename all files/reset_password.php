<?php
session_start();
if (!isset($_SESSION['code_verified']) || $_SESSION['code_verified'] !== true) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reset Password | Premium Content Streaming Platform</title>
    <link rel="stylesheet" href="../user-css/user-reset.css" />
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
    <div id="reset-password">
        <div class="container">
            <h1>Reset Your Password</h1>
            <?php if (isset($_SESSION['message'])) {
                echo "<p class='form-message form-error'>{$_SESSION['message']}</p>";
                unset($_SESSION['message']);
            } ?>
            <form action="reset_password_process.php" method="POST">
                <label for="password">New Password:</label>
                <input type="password" id="password" name="password" required>

                <label for="password_confirm">Confirm New Password:</label>
                <input type="password" id="password_confirm" name="password_confirm" required>

                <button type="submit"><i class="fas fa-redo"></i> Reset Password</button>
            </form>
        </div>
    </div>


</body>

</html>