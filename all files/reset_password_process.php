<?php
session_start();
include 'php/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_SESSION['code_verified']) && $_SESSION['code_verified'] === true) {
        $id = $_SESSION['user_id'];
        $password = $_POST['password'];
        $password_confirm = $_POST['password_confirm'];

        if ($password !== $password_confirm) {
            $_SESSION['message'] = "Passwords do not match.";
            header("Location: reset_password.php");
            exit();
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashed_password, $id);

        if ($stmt->execute()) {
            $delete_stmt = $conn->prepare("DELETE FROM passwordreset WHERE user_id = ?");
            $delete_stmt->bind_param("i", $id);
            $delete_stmt->execute();

            unset($_SESSION['code_verified']);
            unset($_SESSION['user_id']);

            $_SESSION['message'] = "Your password has been reset successfully. You can now log in.";
            header("Location: login.html");
            exit();
        } else {
            $_SESSION['message'] = "Error updating password. Please try again.";
            header("Location: reset_password.php");
            exit();
        }
    } else {
        header("Location: login.html");
        exit();
    }
} else {
    header("Location: login.html");
    exit();
}
