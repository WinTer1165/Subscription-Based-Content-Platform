<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    exit();
}

$user_id = $_SESSION['user_id'];
$user_query = "SELECT subscription_level FROM users WHERE id = ?";
$user_stmt = $conn->prepare($user_query);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user = $user_result->fetch_assoc();
$user_subscription = $user['subscription_level'];

// Fetching videos based on the user's subscription level
if ($user_subscription == 'premium'){
    $sql = "SELECT * FROM videos WHERE 1 ORDER BY created_at DESC";
}
else if ($user_subscription == 'standard'){
    $sql = "SELECT * FROM videos WHERE subscription_level != 'premium' ORDER BY created_at DESC";
}
else{
    $sql = "SELECT * FROM videos WHERE subscription_level <= ? ORDER BY created_at DESC";
}

$stmt = $conn->prepare($sql);
if ($user_subscription != 'premium' && $user_subscription != 'standard') {
    $stmt->bind_param("s", $user_subscription);
}
$stmt->execute();
$result = $stmt->get_result();

$videos = [];
while ($row = $result->fetch_assoc()) {
    $videos[] = $row;
}

echo json_encode(['success' => true, 'videos' => $videos, 'sub' => $user_subscription]);

$stmt->close();
$conn->close();
?>