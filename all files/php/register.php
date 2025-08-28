<?php
header('Content-Type: application/json');
require_once 'config.php';

function sendResponse($success, $message)
{
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Invalid request method');
}

$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    sendResponse(false, 'Invalid JSON data');
}

$username = isset($data['username']) ? trim($data['username']) : '';
$email = isset($data['email']) ? trim($data['email']) : '';
$password = isset($data['password']) ? $data['password'] : '';
$full_name = isset($data['fullName']) ? trim($data['fullName']) : '';
$phone = isset($data['phone']) ? trim($data['phone']) : '';
$subscription = isset($data['subscription']) ? trim($data['subscription']) : '';
$payment_method = isset($data['paymentMethod']) ? trim($data['paymentMethod']) : '';
$payment_details = isset($data['paymentDetails']) ? json_encode($data['paymentDetails']) : null;

//server side validation
if (empty($username) || empty($email) || empty($password) || empty($full_name) || empty($phone) || empty($subscription) || empty($payment_method)) {
    sendResponse(false, 'Please fill in all required fields.');
}

// Check if username or email already exists
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
$stmt->bind_param("ss", $username, $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->close();
    sendResponse(false, 'Username or email already exists.');
}
$stmt->close();

// Hash the password securely
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Prepare the INSERT statement
$stmt = $conn->prepare("INSERT INTO users (username, email, password, full_name, phone, subscription_level, payment_method, payment_details) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

if (!$stmt) {
    sendResponse(false, 'Database error: ' . $conn->error);
}

$stmt->bind_param("ssssssss", $username, $email, $hashedPassword, $full_name, $phone, $subscription, $payment_method, $payment_details);

if ($stmt->execute()) {
    $stmt->close();
    sendResponse(true, 'Registration successful!');
} else {
    $stmt->close();
    sendResponse(false, 'Error: ' . $conn->error);
}

$conn->close();
