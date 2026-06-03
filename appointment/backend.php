<?php
// Allow CORS & JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Database connection
$host = "localhost";
$user = "root";   // default XAMPP user
$pass = "";       // default XAMPP password (empty)
$db   = "appointment_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}

// Get form values
$name  = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$date  = $_POST['appointment_date'] ?? '';
$time  = $_POST['appointment_time'] ?? '';
$msg   = $_POST['message'] ?? '';

if ($name && $email && $date && $time) {
    $stmt = $conn->prepare("INSERT INTO appointments (name, email, phone, appointment_date, appointment_time, message) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $email, $phone, $date, $time, $msg);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Appointment booked successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to book appointment"]);
    }
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Please fill all required fields"]);
}

$conn->close();
?>
