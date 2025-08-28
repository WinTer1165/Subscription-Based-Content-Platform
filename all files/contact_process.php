<?php
session_start();
include 'php/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));

    if ($email === false) {
        $_SESSION['form_error'] = "Invalid email address.";
        header("Location: contact.php");
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO contactmessages (name, email, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $subject, $message);

    if ($stmt->execute()) {
        $_SESSION['form_success'] = "Thank you for contacting us. We'll respond to your inquiry shortly.";
    } else {
        $_SESSION['form_error'] = "Sorry, there was an error submitting your message. Please try again later.";
    }

    $stmt->close();
    $conn->close();

    header("Location: contact.php");
    exit;
} else {
    $_SESSION['form_error'] = "Invalid request method.";
    header("Location: contact.php");
    exit;
}
?>
