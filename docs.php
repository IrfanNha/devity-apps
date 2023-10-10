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

<!-- Custom CSS for images -->
<style>
  .max-height-300vh {
    max-height: 300vh;
  }
</style>

<!-- Content -->
<div id="layoutAuthentication">
  <div id="layoutAuthentication_content">
    <main>
      <div class="container mt-5">
        <h1>Dokumentasi</h1>

        <!-- Image 1 -->
        <div class="mb-4">
          <h3>DASHBOARD</h3>
          <img src="assets/dokumentasi/doc-index.png" alt="Image 1" class="img-fluid max-height-300vh">
          <p>Description for Image 1.</p>
        </div>

        <!-- Image 2 -->
        <div class="mb-4">
          <h3>DASHBOARD</h3>
          <img src="assets/dokumentasi/doc-index (1).jpg" alt="Image 2" class="img-fluid max-height-300vh">
          <p>Description for Image 2.</p>
        </div>

        <!-- Image 3 -->
        <div class="mb-4">
          <h3>PROFIL TOKO</h3>
          <img src="assets/dokumentasi/doc-profiletoko.png" alt="Image 3" class="img-fluid max-height-300vh">
          <p>Description for Image 3.</p>
        </div>

        <!-- Image 4 -->
        <div class="mb-4">
          <h3>PROFIL TOKO</h3>
          <img src="assets/dokumentasi/doc-profiletoko (2).png" alt="Image 4" class="img-fluid max-height-300vh">
          <p>Description for Image 2.</p>
        </div>

        <!-- Image 2 -->
        <div class="mb-4">
          <h3>DASHBOARD</h3>
          <img src="assets/dokumentasi/doc-index (1).jpg" alt="Image 2" class="img-fluid max-height-300vh">
          <p>Description for Image 2.</p>
        </div>

        <!-- Image 2 -->
        <div class="mb-4">
          <h3>DASHBOARD</h3>
          <img src="assets/dokumentasi/doc-index (1).jpg" alt="Image 2" class="img-fluid max-height-300vh">
          <p>Description for Image 2.</p>
        </div>

        <!-- Image 2 -->
        <div class="mb-4">
          <h3>DASHBOARD</h3>
          <img src="assets/dokumentasi/doc-index (1).jpg" alt="Image 2" class="img-fluid max-height-300vh">
          <p>Description for Image 2.</p>
        </div>

        <!-- Image 2 -->
        <div class="mb-4">
          <h3>DASHBOARD</h3>
          <img src="assets/dokumentasi/doc-index (1).jpg" alt="Image 2" class="img-fluid max-height-300vh">
          <p>Description for Image 2.</p>
        </div>

        <!-- Image 2 -->
        <div class="mb-4">
          <h3>DASHBOARD</h3>
          <img src="assets/dokumentasi/doc-index (1).jpg" alt="Image 2" class="img-fluid max-height-300vh">
          <p>Description for Image 2.</p>
        </div>

        <!-- Image 2 -->
        <div class="mb-4">
          <h3>DASHBOARD</h3>
          <img src="assets/dokumentasi/doc-index (1).jpg" alt="Image 2" class="img-fluid max-height-300vh">
          <p>Description for Image 2.</p>
        </div>

        <!-- Image 2 -->
        <div class="mb-4">
          <h3>DASHBOARD</h3>
          <img src="assets/dokumentasi/doc-index (1).jpg" alt="Image 2" class="img-fluid max-height-300vh">
          <p>Description for Image 2.</p>
        </div>

        <!-- Image 2 -->
        <div class="mb-4">
          <h3>DASHBOARD</h3>
          <img src="assets/dokumentasi/doc-index (1).jpg" alt="Image 2" class="img-fluid max-height-300vh">
          <p>Description for Image 2.</p>
        </div>

        <!-- Image 2 -->
        <div class="mb-4">
          <h3>DASHBOARD</h3>
          <img src="assets/dokumentasi/doc-index (1).jpg" alt="Image 2" class="img-fluid max-height-300vh">
          <p>Description for Image 2.</p>
        </div>

        <!-- Add more images and descriptions as needed -->

      </div>
    </main>
  </div>
</div>

<?php include 'components/partials/dashboard.footer.php'; ?>
<?php include 'components/templates/footer.php'; ?>