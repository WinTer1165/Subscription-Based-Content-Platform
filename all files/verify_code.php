<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Verify Code | Premium Content Streaming Platform</title>
    <link rel="stylesheet" href="../user-css/user-verify.css" />
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
    
    <div id="verify-code">
        <div class="container">
            <h1>Enter Verification Code</h1>
            <?php if (isset($_SESSION['message'])) {
                echo "<p class='form-message form-error'>{$_SESSION['message']}</p>";
                unset($_SESSION['message']);
            } ?>
            <form action="verify_code_process.php" method="POST">
                <label for="verification_code">Verification Code:</label>
                <input type="text" id="verification_code" name="verification_code" required>
                <button type="submit"><i class="fas fa-check"></i> Verify Code</button>
            </form>
        </div>
    </div>

</body>

</html>