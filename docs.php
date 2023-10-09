<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: auth/login.php");
  exit;
}
$pages = 'Dokumentasi';
?>

<?php require 'vendor/autoload.php'; ?>
<?php require 'config/Config.php'; ?>
<?php include 'components/templates/header.php'; ?>
<?php include 'components/partials/dashboard.header.php'; ?>





<?php include 'components/partials/dashboard.footer.php'; ?>
<?php include 'components/templates/footer.php'; ?>