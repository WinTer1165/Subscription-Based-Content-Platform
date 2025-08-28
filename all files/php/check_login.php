<?php
session_start(); 
//checking if user is logged in
if (isset($_SESSION['user_id'])) {
    $isLoggedIn = true;
} else {
    $isLoggedIn = false;
}
header('Content-Type: application/json');
echo json_encode(['isLoggedIn' => $isLoggedIn]);
