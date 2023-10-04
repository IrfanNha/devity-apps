<?php
// Include your database configuration file
require_once 'config/Database.php';

// Create a new Database instance and establish a connection
$database = new Database();
$conn = $database->getConnection();

// Assuming you have a session or some way to identify the user (adjust as needed)
$user_id = $_SESSION['user_id'];

// Query to get store details for the logged-in user
$sql = "SELECT * FROM users_preferences WHERE user_id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
$stmt->execute();

// Fetch store details as an associative array
$storeDetails = $stmt->fetch(PDO::FETCH_ASSOC);

// Close the database connection
$conn = null;

// Return the store details as JSON
header('Content-Type: application/json');
echo json_encode($storeDetails);
?>
