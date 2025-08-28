<?php
session_start();
include 'php/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $verification_code = trim($_POST['verification_code']);
    $user_id = $_SESSION['user_id'];

    $current_time = time();
    $stmt = $conn->prepare("SELECT code FROM passwordreset WHERE user_id = ? AND expires >= ?");
    $stmt->bind_param("ii", $user_id, $current_time);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($stored_code);
        $stmt->fetch();

        if ($verification_code === $stored_code) {
            // If code is valid
            $_SESSION['code_verified'] = true;
            header("Location: reset_password.php");
            exit();
        } else {
            $_SESSION['message'] = "Invalid verification code.";
            header("Location: verify_code.php");
            exit();
        }
    } else {
        $_SESSION['message'] = "Verification code expired or invalid.";
        header("Location: verify_code.php");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
