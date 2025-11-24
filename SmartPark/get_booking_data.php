<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['bookingData'])) {
    echo json_encode(['status' => 'error', 'message' => 'No booking in session']);
    exit;
}

echo json_encode(array_merge(['status' => 'success'], $_SESSION['bookingData']));
?>
