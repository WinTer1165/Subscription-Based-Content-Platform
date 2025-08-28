<?php
session_start();
header('Content-Type: application/json');
require_once 'config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized access.',
        'redirect' => true
    ]);
    exit;
}

// Validate the user ID
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid user ID.']);
    exit;
}

$userId = intval($_POST['id']);

// Delete user from database using prepared statement
$sql = "DELETE FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $userId);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete user.']);
}

$stmt->close();
$conn->close();
