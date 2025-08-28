<?php
session_start();
include 'php/config.php';
$config2 = require 'config2.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $identifier = trim($_POST['identifier']);

    $identifier = htmlspecialchars($identifier);

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, phone FROM users WHERE username = ?");
    $stmt->bind_param("s", $identifier);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($user_id, $user_phone);
        $stmt->fetch();

        // Generate a secure random verification code which will expire in 10 min
        $verification_code = random_int(100000, 999999);
        $expires = time() + 600;

        // Delete any existing codes for this user
        $delete_stmt = $conn->prepare("DELETE FROM passwordreset WHERE user_id = ?");
        $delete_stmt->bind_param("i", $user_id);
        $delete_stmt->execute();

        // Insert the new code into the database
        $insert_stmt = $conn->prepare("INSERT INTO passwordreset (user_id, code, expires) VALUES (?, ?, ?)");
        $insert_stmt->bind_param("isi", $user_id, $verification_code, $expires);
        $insert_stmt->execute();

        // Sending the verification code via SMS using cURL
        // Twilio credentials
        $account_sid = $config2['twilio']['account_sid'];
        $auth_token = $config2['twilio']['auth_token'];
        $twilio_number = $config2['twilio']['phone_number'];// e.g., '+1234567890'

        $country_code = '+880';
        $to_number = $country_code.$user_phone; // User's phone number in E.164 format

        $message_body = "Your password reset code is: $verification_code";

        // Prepare the data for POST request
        $data = [
            'From' => $twilio_number,
            'To' => $to_number,
            'Body' => $message_body,
        ];

        // Send the POST request with cURL
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.twilio.com/2010-04-01/Accounts/' . $account_sid . '/Messages.json');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $account_sid . ':' . $auth_token);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        $response = curl_exec($ch);

        if ($response === false) {
            $_SESSION['message'] = "Failed to send SMS. Please try again later.";
            header("Location: forgot_password.php");
            exit();
        }

        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($http_code != 201) {
            $error = json_decode($response, true);
            $_SESSION['message'] = "Failed to send SMS: " . ($error['message'] ?? 'Unknown error.');
            $_SESSION['user_id'] = $user_id;
            header("Location: verify_code.php");
            exit();
        }

        curl_close($ch);

        // Proceed to code verification page
        $_SESSION['user_id'] = $user_id;
        header("Location: verify_code.php");
        exit();
    } else {
        // Do not reveal whether the user exists
        $_SESSION['message'] = "If the information provided is correct, a verification code has been sent to your phone.";
        header("Location: forgot_password.php");
        exit();
    }
} else {
    header("Location: forgot_password.php");
    exit();
}
