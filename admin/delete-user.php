<?php
session_start();

// Pastikan pengguna yang mencoba mengakses halaman ini adalah admin atau super admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_rank'] < 3) {
  header("Location: 403.php");
  exit;
}

// Pastikan data yang diperlukan telah dikirimkan melalui POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
  $userId = $_POST['user_id'];

  // Hapus pengguna dari database
  require_once '../config/Database.php';
  $database = new Database();
  $conn = $database->getConnection();

  $sql = "DELETE FROM users WHERE id = ?";
  $stmt = $conn->prepare($sql);
  if ($stmt->execute([$userId])) {
    // Redirect kembali ke halaman daftar pengguna dengan pesan sukses
    header("Location: users.php?delete=success");
    exit;
  } else {
    // Redirect kembali ke halaman daftar pengguna dengan pesan error
    header("Location: users.php?delete=error");
    exit;
  }
} else {
  // Jika data yang diperlukan tidak lengkap, redirect kembali ke halaman daftar pengguna
  header("Location: users.php");
  exit;
}
