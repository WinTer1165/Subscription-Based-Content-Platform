<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    exit();
}

$sql = "SELECT * FROM videos where 1 ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result === FALSE) {
    exit();
}

$videos = [];
while ($row = $result->fetch_assoc()) {
    $videos[] = $row;
}

echo json_encode(['success' => true, 'videos' => $videos]);

$conn->close();
