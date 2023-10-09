<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

require_once '../config/Database.php';

// Retrieve the activation key's ID from the URL parameter
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
  $activation_key_id = $_GET['id'];

  $database = new Database();
  $conn = $database->getConnection();

  // Delete the activation key
  $query = "DELETE FROM activation_keys WHERE id = :activation_key_id";
  $stmt = $conn->prepare($query);
  $stmt->bindParam(':activation_key_id', $activation_key_id);
  $stmt->execute();

  // Redirect back to the activation keys page or any other appropriate page
  header("Location: key-management.php");
  exit;
} else {
  // Handle invalid or missing activation key ID
  // Redirect to an error page or display an error message
  header("Location: error.php");
  exit;
}
