<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

// Include necessary files and initialize the database connection
require 'vendor/autoload.php';
require 'config/Config.php';
require 'config/Database.php';

// Get user ID from session
$userId = $_SESSION["user_id"];

// Get the order data from the POST request
$orderData = json_decode(file_get_contents("php://input"), true);

// Initialize the database connection
$database = new Database();
$conn = $database->getConnection();

// Prepare the SQL statement to insert the order into the riwayat_order table
$sql = "INSERT INTO riwayat_order (data, user_id) VALUES (:data, :user_id)";
$stmt = $conn->prepare($sql);
$stmt->bindParam(":data", json_encode($orderData), PDO::PARAM_STR);
$stmt->bindParam(":user_id", $userId, PDO::PARAM_INT);

// Execute the statement
if ($stmt->execute()) {
    // Return a success response if the insertion is successful
    echo json_encode(["status" => "success", "message" => "Order saved successfully."]);
} else {
    // Return an error response if there's an issue with the insertion
    echo json_encode(["status" => "error", "message" => "Error saving order."]);
}

// Close the database connection
$conn = null;
?>
