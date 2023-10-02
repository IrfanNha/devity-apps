<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

// if ($_SESSION['user_rank'] !== "3") {
//   header("Location: 403.php");
//   exit;
// }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['user_id'])) {
    // Ambil data dari formulir pengeditan
    $userId = $_POST['user_id'];
    $editedUsername = $_POST['edit_username'];
    $editedEmail = $_POST['edit_email'];
    $editedUserRank = $_POST['edit_user_rank'];
    $editedSubsExpiry = $_POST['edit_subs_expiry'];
    $editedIsActive = isset($_POST['edit_is_active']) ? 1 : 0;
    $editedIsPaid = isset($_POST['edit_is_paid']) ? 1 : 0;

    // Update data pengguna dalam database
    require_once '../config/Database.php';

    $database = new Database();
    $conn = $database->getConnection();

    $sql = "UPDATE users SET username = ?, email = ?, user_rank = ?, subs_expiry = ?, is_active = ?, is_paid = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$editedUsername, $editedEmail, $editedUserRank, $editedSubsExpiry, $editedIsActive, $editedIsPaid, $userId]);

    // Redirect kembali ke halaman pengguna setelah pembaruan
    header("Location: users.php");
    exit;
  }
}
