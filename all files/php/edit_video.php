<?php

require_once 'config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['video_id'], $_POST['title'], $_POST['description'], $_POST['subscription_level'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit();
    }

    $video_id = intval($_POST['video_id']);
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $subscription_level = $conn->real_escape_string($_POST['subscription_level']);

    $thumbnail_updated = false;
    $thumbnail_path = '';


    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == UPLOAD_ERR_OK) {

        $target_dir = "../uploads/videos/";


        $original_filename = basename($_FILES["thumbnail"]["name"]);
        $original_filename = preg_replace("/[^A-Za-z0-9_\-\.]/", '_', $original_filename);
        $file_extension = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));


        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($file_extension, $allowed_types)) {
            echo json_encode(['success' => false, 'message' => 'Invalid file type. Allowed types: jpg, jpeg, png, gif']);
            exit();
        }

        $max_file_size = 2 * 1024 * 1024;
        if ($_FILES['thumbnail']['size'] > $max_file_size) {
            echo json_encode(['success' => false, 'message' => 'File size exceeds the 2MB limit']);
            exit();
        }

        if (!is_dir($target_dir)) {
            if (!mkdir($target_dir, 0755, true)) {
                echo json_encode(['success' => false, 'message' => 'Failed to create upload directory']);
                exit();
            }
        }


        $target_file = $target_dir . $original_filename;
        $thumbnail_path = 'uploads/videos/' . $original_filename;

        if (file_exists($target_file)) {
            echo json_encode(['success' => false, 'message' => 'A file with this name already exists. Please rename your file and try again.']);
            exit();
        }

        if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $target_file)) {
            $thumbnail_updated = true;
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to upload new thumbnail']);
            exit();
        }
    }


    if ($thumbnail_updated) {
        $old_thumbnail_sql = "SELECT thumbnail_path FROM videos WHERE id = ?";
        $old_stmt = $conn->prepare($old_thumbnail_sql);
        if ($old_stmt === false) {
            echo json_encode(['success' => false, 'message' => 'SQL prepare error: ' . $conn->error]);
            exit();
        }
        $old_stmt->bind_param("i", $video_id);
        $old_stmt->execute();
        $old_result = $old_stmt->get_result();
        $old_data = $old_result->fetch_assoc();
        $old_stmt->close();

        if ($old_data['thumbnail_path'] && $old_data['thumbnail_path'] !== $thumbnail_path) {
            $old_thumbnail_path = '../' . $old_data['thumbnail_path'];
            if (file_exists($old_thumbnail_path)) {
                unlink($old_thumbnail_path);
            }
        }

        $sql = "UPDATE videos SET title = ?, description = ?, subscription_level = ?, thumbnail_path = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            echo json_encode(['success' => false, 'message' => 'SQL prepare error: ' . $conn->error]);
            exit();
        }
        $stmt->bind_param("ssssi", $title, $description, $subscription_level, $thumbnail_path, $video_id);
    } else {

        $sql = "UPDATE videos SET title = ?, description = ?, subscription_level = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            echo json_encode(['success' => false, 'message' => 'SQL prepare error: ' . $conn->error]);
            exit();
        }
        $stmt->bind_param("sssi", $title, $description, $subscription_level, $video_id);
    }

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Video updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating video: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
