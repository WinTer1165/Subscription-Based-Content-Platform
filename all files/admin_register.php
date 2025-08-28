<?php

require_once 'php/config.php';

// Checking if admin exists
$sql = "SELECT COUNT(*) AS total FROM admins";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if ($row['total'] > 0) {
    // If admin exist show this message and redirect to admin login page using js
    echo "An admin account already exists. Registration is closed.";
    echo "<script>setTimeout(function() {window.location.href = '../admin/login.html';}, 5000);</script>";
    exit;
}

$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validating username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Validating password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validating confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if ($password != $confirm_password) {
            $confirm_password_err = "Passwords do not match.";
        }
    }

    // Checking for input errors
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
        
        $sql = "INSERT INTO admins (username, password) VALUES (?, ?)";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

           
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); 

            if (mysqli_stmt_execute($stmt)) {
                // For successful registration
                echo "Admin account created successfully.";
                echo "<script>setTimeout(function() {window.location.href = '../admin/login.html';}, 2000);</script>";
                exit;
            } else {
                echo "Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Registration</title>
    <link rel="stylesheet" href="/admin-css/admin-setup.css">
</head>

<body>
    <h2>Register Admin Account</h2>
    <form action="admin_register.php" method="post">
        <div>
            <label>Username:</label><br>
            <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>">
            <span style="color:red;"><?php echo $username_err; ?></span>
        </div>
        <br>
        <div>
            <label>Password:</label><br>
            <input type="password" name="password">
            <span style="color:red;"><?php echo $password_err; ?></span>
        </div>
        <br>
        <div>
            <label>Confirm Password:</label><br>
            <input type="password" name="confirm_password">
            <span style="color:red;"><?php echo $confirm_password_err; ?></span>
        </div>
        <br>
        <div>
            <input type="submit" value="Register">
        </div>
    </form>
</body>

</html>