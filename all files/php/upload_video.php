<?php
error_reporting(0);
ini_set('display_errors', 0);
ob_start();

require_once 'config.php';
session_start();


function json_response($success, $message)
{

    ob_end_clean();
    header('Content-Type: application/json');
    echo json_encode(['success' => $success, 'message' => $message]);
    exit();
}

if (!isset($_SESSION['admin_id'])) {
    json_response(false, 'Unauthorized access');
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    json_response(false, 'Invalid request method');
}

$title = $conn->real_escape_string($_POST['title']);
$description = $conn->real_escape_string($_POST['description']);
$subscription_level = $conn->real_escape_string($_POST['subscription_level']);

$target_dir = "../uploads/videos/";
$video_file = $target_dir . basename($_FILES["video_file"]["name"]);
$thumbnail_file = $target_dir . basename($_FILES["thumbnail"]["name"]);

$uploadOk = 1;
$videoFileType = strtolower(pathinfo($video_file, PATHINFO_EXTENSION));
$imageFileType = strtolower(pathinfo($thumbnail_file, PATHINFO_EXTENSION));

// Check file sizes (approximately 500MB)
if ($_FILES["video_file"]["size"] > 500000000 || $_FILES["thumbnail"]["size"] > 500000000) {
    json_response(false, 'Sorry, your file is too large.');
}

// Allow certain file formats
if (
    $videoFileType != "mp4" && $videoFileType != "avi" && $videoFileType != "mov"
    && $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif"
) {
    json_response(false, 'Sorry, only MP4, AVI, MOV, JPG, JPEG, PNG & GIF files are allowed.');
}

if (
    move_uploaded_file($_FILES["video_file"]["tmp_name"], $video_file) &&
    move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $thumbnail_file)
) {

    $sql = "INSERT INTO videos (title, description, file_path, thumbnail_path, subscription_level) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $title, $description, $video_file, $thumbnail_file, $subscription_level);

    if ($stmt->execute()) {
        json_response(true, 'Video uploaded successfully');
    } else {
        json_response(false, 'Error: ' . $stmt->error);
    }
    $stmt->close();
} else {
    json_response(false, 'Sorry, there was an error uploading your file.');
}

$conn->close();

ob_end_clean();
exit();
