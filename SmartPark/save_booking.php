<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "parking_system";

// Create server connection (without DB first)
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure database exists
if (!$conn->query("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")) {
    die("Failed to create database: " . $conn->error);
}

// Select database
if (!$conn->select_db($dbname)) {
    $fallbackDb = "parking_app";
    if (!$conn->query("CREATE DATABASE IF NOT EXISTS `$fallbackDb` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")) {
        die("Failed to create fallback database: " . $conn->error);
    }
    if (!$conn->select_db($fallbackDb)) {
        die("Failed to select fallback database: " . $conn->error);
    }
    $dbname = $fallbackDb;
}

// Ensure bookings table exists
$createTableSql = "CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id VARCHAR(32) NOT NULL UNIQUE,
    slot_number VARCHAR(20) NOT NULL,
    booking_date DATE NOT NULL,
    check_in_time TIME NOT NULL,
    check_out_time TIME NOT NULL,
    user_name VARCHAR(150) NOT NULL,
    user_email VARCHAR(255) NOT NULL,
    user_phone VARCHAR(30) NOT NULL,
    amount DECIMAL(10,2) DEFAULT NULL,
    status VARCHAR(20) DEFAULT 'confirmed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if (!$conn->query($createTableSql)) {
    die("Failed to create bookings table: " . $conn->error);
}

// Get form data
$slotNumber = $_POST['slotNumber'];
$userName = $_POST['userName'];
$userEmail = $_POST['userEmail'];
$userPhone = $_POST['userPhone'];
$bookingDate = $_POST['bookingDate'];
$checkInTime = $_POST['checkInTime'];
$checkOutTime = $_POST['checkOutTime'];

// Generate booking ID
$bookingId = 'BK' . strtoupper(uniqid());

// Insert into database
$sql = "INSERT INTO bookings (
    booking_id, 
    slot_number, 
    booking_date, 
    check_in_time, 
    check_out_time, 
    user_name, 
    user_email, 
    user_phone
) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param(
    "ssssssss", 
    $bookingId,
    $slotNumber,
    $bookingDate,
    $checkInTime,
    $checkOutTime,
    $userName,
    $userEmail,
    $userPhone
);

function calculateParkingFee($checkIn, $checkOut) {
    // Simple calculation - replace with your actual pricing logic
    $start = new DateTime($checkIn);
    $end = new DateTime($checkOut);
    $diff = $start->diff($end);
    $hours = $diff->h + ($diff->days * 24);
    return max(1, $hours) * 40; // ₹40 per hour with 1 hour minimum
}

// After successful booking insertion
if ($stmt->execute()) {
    session_start();
    $_SESSION['bookingData'] = [
        'bookingId' => $bookingId,
        'slotNumber' => $slotNumber,
        'bookingDate' => $bookingDate,
        'checkInTime' => $checkInTime,
        'checkOutTime' => $checkOutTime,
        'userName' => $userName,
        'userEmail' => $userEmail,
        'userPhone' => $userPhone,
        'amount' => calculateParkingFee($checkInTime, $checkOutTime) // Add your pricing logic
    ];
    
    // Redirect to payment page
    header("Location: payment/pay.html");
    exit();
}

$stmt->close();
$conn->close();
?>
