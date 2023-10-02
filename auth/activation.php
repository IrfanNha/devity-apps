<?php
session_start();
$pages = "activation"; ?>
<?php require '../config/Config.php'; ?>
<?php include '../components/templates/header.php'; ?>


<?php


// Include konfigurasi database
require_once '../config/Database.php';

// Cek jika pengguna sudah login, alihkan ke halaman lain jika iya
if (isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit;
}

// Ambil token dari URL
$token = isset($_GET['token']) ? $_GET['token'] : "";

// Buat instance koneksi database
$database = new Database();
$conn = $database->getConnection();

// Periksa apakah token valid
$query_check_token = "SELECT id FROM users WHERE token = :token AND is_active = 0";
$stmt_check_token = $conn->prepare($query_check_token);
$stmt_check_token->bindParam(':token', $token);
$stmt_check_token->execute();

if ($stmt_check_token->rowCount() > 0) {
  // Update status pengguna menjadi aktif
  $query_activate_user = "UPDATE users SET is_active = 1 WHERE token = :token";
  $stmt_activate_user = $conn->prepare($query_activate_user);
  $stmt_activate_user->bindParam(':token', $token);
  $stmt_activate_user->execute();

  $_SESSION['activate_success'] = "Akun Anda berhasil diaktifkan. Silakan login.";
} else {
  $_SESSION['activate_error'] = "Aktivasi akun gagal. Token tidak valid.";
}


// Tutup koneksi database
$conn = null;
?>

<div class="container" style="height:100vh; display: flex; justify-content: center; align-items: center;">
  <div class="text-center">
    <?php
    if (isset($_SESSION['activate_error'])) { ?>
      <div class='alert alert-danger mt-3 animate__animated animate__bounceIn'>
        <p><?= $_SESSION['activate_error'] ?></p>
        <span>
          <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
          </svg>
        </span>
      </div>
      <p class=" animate__animated animate__bounceIn">Cek kembali token atau ikuti link email</p>
    <?php unset($_SESSION['activate_error']);
    }
    if (isset($_SESSION['activate_success'])) { ?>
      <div class='alert alert-success mt-3 animate__animated animate__bounceIn'>
        <p><?= $_SESSION['activate_success'] ?></p>
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
          <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
          <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z" />
        </svg>
      </div>
      <p class=" animate__animated animate__bounceIn">Akun berhasil diaktivasi Klik disini untuk <a href="login.php" class="text-decoration-none">login</a></p>

    <?php unset($_SESSION['activate_success']);
    } ?>
  </div>
</div>

<?php include '../components/templates/footer.php'; ?>