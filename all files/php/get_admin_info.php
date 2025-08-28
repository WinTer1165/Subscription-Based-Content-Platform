<?php
// Checking if admin is logged in
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    $response = [
        'logged_in' => true,
        'admin_username' => $_SESSION['admin_username']
    ];
} else {
    $response = [
        'logged_in' => false
    ];
}

echo json_encode($response);
