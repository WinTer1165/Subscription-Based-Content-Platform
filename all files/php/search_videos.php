<?php

require_once 'config.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

header('Content-Type: application/json');


$search_query = isset($_GET['q']) ? $_GET['q'] : '';


if ($search_query === '') {
    echo json_encode(['success' => false, 'message' => 'No search query provided']);
    exit();
}

try {
   
    $user_id = $_SESSION['user_id'];

    $user_sql = "SELECT subscription_level FROM users WHERE id = ?";
    $user_stmt = $conn->prepare($user_sql);
    $user_stmt->bind_param("i", $user_id);
    $user_stmt->execute();
    $user_result = $user_stmt->get_result();

    if ($user_result->num_rows == 0) {
        echo json_encode(['success' => false, 'message' => 'User not found']);
        exit();
    }

    $user_data = $user_result->fetch_assoc();
    $user_subscription_level = $user_data['subscription_level'];

   
    $subscription_levels = ['basic', 'standard', 'premium'];

  
    $user_subscription_level = strtolower($user_subscription_level);
    $subscription_levels = array_map('strtolower', $subscription_levels);

   
    $user_level_index = array_search($user_subscription_level, $subscription_levels);

    if ($user_level_index === false) {
        echo json_encode(['success' => false, 'message' => 'Invalid user subscription level']);
        exit();
    }

    
    $allowed_levels = array_slice($subscription_levels, 0, $user_level_index + 1);

  
    $search_query_param = '%' . strtolower($search_query) . '%';

   
    $placeholders = implode(',', array_fill(0, count($allowed_levels), '?'));

    
    $sql = "
        SELECT *
        FROM videos
        WHERE LOWER(title) LIKE ?
          AND LOWER(subscription_level) IN ($placeholders)
        ORDER BY created_at DESC
    ";

   
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        throw new Exception('Error preparing statement: ' . $conn->error);
    }

   
    $params = array_merge([$search_query_param], $allowed_levels);

  
    $types = str_repeat('s', count($params));

  
    $stmt->bind_param($types, ...$params);

   
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result === false) {
        throw new Exception('Error fetching videos: ' . $conn->error);
    }

    $videos = [];
    while ($row = $result->fetch_assoc()) {
        $videos[] = $row;
    }

    echo json_encode(['success' => true, 'videos' => $videos]);

  
    $user_stmt->close();
    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    $conn->close();
}
