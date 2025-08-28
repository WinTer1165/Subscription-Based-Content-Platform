<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $video_id = $conn->real_escape_string($_POST['id']);

    $sql = "SELECT file_path, thumbnail_path FROM videos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $video_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $video = $result->fetch_assoc();

    // Delete the video from db
    $sql = "DELETE FROM videos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $video_id);

    if ($stmt->execute()) {
        // Delete the videos and image from folder
        if (file_exists($video['file_path'])) {
            unlink($video['file_path']);
        }
        if (file_exists($video['thumbnail_path'])) {
            unlink($video['thumbnail_path']);
        }
        echo json_encode(['success' => true, 'message' => 'Video deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting video: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}

$conn->close();
?>