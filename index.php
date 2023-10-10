<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: auth/login.php");
  exit;
}
$pages = 'Dashboard'
?>
<?php require 'vendor/autoload.php'; ?>
<?php require 'config/Config.php'; ?>
<?php include 'components/templates/header.php' ?>
<?php include 'components/partials/dashboard.header.php' ?>

<?php
require 'config/Database.php';
// Buat koneksi ke database Anda
$database = new Database();
$conn = $database->getConnection();
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users_preferences WHERE user_id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();

if ($stmt->rowCount() > 0) {
  // User has existing preferences, display them for editing
  $preferences = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
  // User doesn't have preferences, display an empty form
  $preferences = [
    'image' => '',
    'store_name' => '',
    'alamat' => '',
    'phone' => ''
  ];
}

// Ambil user_id dari sesi
$user_id = $_SESSION['user_id'];

// Query untuk mengambil data dari tabel-tabel yang berhubungan
$sqlMenu = "SELECT COUNT(*) as totalMenu FROM menu WHERE user_id = :user_id";
$sqlItems = "SELECT COUNT(*) as totalitems FROM items WHERE user_id = :user_id";
$sqlTotalStock = "SELECT SUM(items_qty) as totalStock FROM items_stock WHERE user_id = :user_id";

$stmtMenu = $conn->prepare($sqlMenu);
$stmtItems = $conn->prepare($sqlItems);
$stmtTotalStock = $conn->prepare($sqlTotalStock);

$stmtMenu->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmtItems->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmtTotalStock->bindParam(':user_id', $user_id, PDO::PARAM_INT);

$stmtMenu->execute();
$stmtItems->execute();
$stmtTotalStock->execute();

// Ambil hasil dari query
$resultMenu = $stmtMenu->fetch(PDO::FETCH_ASSOC);
$resultItems = $stmtItems->fetch(PDO::FETCH_ASSOC);
$resultTotalStock = $stmtTotalStock->fetch(PDO::FETCH_ASSOC);

// Close the database connection
$conn = null;

// Menggunakan hasil query untuk menampilkan data di dashboard
$totalMenu = $resultMenu['totalMenu'];
$totalitems = $resultItems['totalitems'];
$totalStock = $resultTotalStock['totalStock'];


?>


<!-- Main content -->
<section class="content my-5">
  <div class="container">

    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      <h4 class="alert-heading">Selamat Datang <?= $_SESSION['username']; ?></h4>
      <p>Selesaikan setup toko anda, dengan konfigurasi toko di halaman <strong><a href="preferences.php" class="text-decoration-none">profil toko</a></strong> lalu tambahkan items anda di <strong><a href="items.php" class="text-decoration-none">items</a></strong></p>
      <hr>
      <p class="mb-0">Untuk selengkapnya dapat anda baca di <strong><a href="#" class="text-decoration-none">Dokumentasi</a></strong>. selamat menggunakan aplikasi kami</p>
    </div>
    <?php
    if (!isset($preferences['store_name'])) { ?>
      <div class="alert alert-warning alert-dismissible fade <?= !isset($preferences['store_name']) ? 'show' : '' ?>" role="alert">
        <strong>Halo <?= $_SESSION['username']; ?></strong> sepertinya toko anda belum memiliki nama <a href="preferences.php" class="text-decoration-none">klik disini</a> untuk mengubah nama
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php } ?>

    <div class="d-flex justify-content-between align-items-center">
      <h2><?= isset($preferences['store_name']) ? $preferences['store_name'] : 'belum diberi nama' ?></h2>
      <a href="preferences.php" class="btn btn-dark rounded-pill px-4">Edit</a>
    </div>
    <hr>

    <div class="row row-cols-1 row-cols-md-4 g-4">
      <div class="col">
        <div class="card  mb-lg-0 mb-3">
          <div class="card-body ">
            <span><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-card-checklist" viewBox="0 0 16 16">
                <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z" />
                <path d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0zM7 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0z" />
              </svg></span>
            <span>Menu </span>
            <span class="card-text"><?php echo $totalMenu; ?></span>
          </div>
        </div>
        <!-- /.info-box -->
      </div>
      <!-- /.col -->
      <div class="col">
        <div class="card  mb-lg-0 mb-3">
          <div class="card-body ">
            <span><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-calendar3-event" viewBox="0 0 16 16">
                <path d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857V3.857z" />
                <path d="M12 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2z" />
              </svg></span>
            <span>Total items</span>
            <span class="card-text"><?php echo $totalitems; ?></span>
          </div>
        </div>
        <!-- /.info-box -->
      </div>
      <div class="col">
        <div class="card  mb-lg-0 mb-3">
          <div class="card-body ">
            <span><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-database" viewBox="0 0 16 16">
                <path d="M4.318 2.687C5.234 2.271 6.536 2 8 2s2.766.27 3.682.687C12.644 3.125 13 3.627 13 4c0 .374-.356.875-1.318 1.313C10.766 5.729 9.464 6 8 6s-2.766-.27-3.682-.687C3.356 4.875 3 4.373 3 4c0-.374.356-.875 1.318-1.313ZM13 5.698V7c0 .374-.356.875-1.318 1.313C10.766 8.729 9.464 9 8 9s-2.766-.27-3.682-.687C3.356 7.875 3 7.373 3 7V5.698c.271.202.58.378.904.525C4.978 6.711 6.427 7 8 7s3.022-.289 4.096-.777A4.92 4.92 0 0 0 13 5.698ZM14 4c0-1.007-.875-1.755-1.904-2.223C11.022 1.289 9.573 1 8 1s-3.022.289-4.096.777C2.875 2.245 2 2.993 2 4v9c0 1.007.875 1.755 1.904 2.223C4.978 15.71 6.427 16 8 16s3.022-.289 4.096-.777C13.125 14.755 14 14.007 14 13V4Zm-1 4.698V10c0 .374-.356.875-1.318 1.313C10.766 11.729 9.464 12 8 12s-2.766-.27-3.682-.687C3.356 10.875 3 10.373 3 10V8.698c.271.202.58.378.904.525C4.978 9.71 6.427 10 8 10s3.022-.289 4.096-.777A4.92 4.92 0 0 0 13 8.698Zm0 3V13c0 .374-.356.875-1.318 1.313C10.766 14.729 9.464 15 8 15s-2.766-.27-3.682-.687C3.356 13.875 3 13.373 3 13v-1.302c.271.202.58.378.904.525C4.978 12.71 6.427 13 8 13s3.022-.289 4.096-.777c.324-.147.633-.323.904-.525Z" />
              </svg></span>
            <span>Total Data Entries</span>
            <span class="card-text"><?php echo isset($totalStock) ? $totalStock : 0 ?></span>
          </div>
        </div>
        <!-- /.info-box -->
      </div>
      <div class="col mb-lg-0 mb-3">
        <div class="card ">
          <div class="card-body ">
            <span><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-cup-straw" viewBox="0 0 16 16">
                <path d="M13.902.334a.5.5 0 0 1-.28.65l-2.254.902-.4 1.927c.376.095.715.215.972.367.228.135.56.396.56.82 0 .046-.004.09-.011.132l-.962 9.068a1.28 1.28 0 0 1-.524.93c-.488.34-1.494.87-3.01.87-1.516 0-2.522-.53-3.01-.87a1.28 1.28 0 0 1-.524-.93L3.51 5.132A.78.78 0 0 1 3.5 5c0-.424.332-.685.56-.82.262-.154.607-.276.99-.372C5.824 3.614 6.867 3.5 8 3.5c.712 0 1.389+.045 1.985.127l.464-2.215a.5.5 0 0 1 .303-.356l2.5-1a.5.5 0 0 1 .65.278zM9.768 4.607A13.991 13.991 0 0 0 8 4.5c-1.076 0-2.033.11-2.707.278A3.284 3.284 0 0 0 4.645 5c.146.073.362.15.648.222C5.967 5.39 6.924 5.5 8 5.5c.571 0 1.109-.03 1.588-.085l.18-.808zm.292 1.756C9.445 6.45 8.742 6.5 8 6.5c-1.133 0-2.176-.114-2.95-.308a5.514 5.514 0 0 1-.435-.127l.838 8.03c.013.121.06.186.102.215.357.249 1.168.69 2.438.69 1.27 0 2.081-.441 2.438-.69.042-.029.09-.094.102-.215l.852-8.03a5.517 5.517 0 0 1-.435.127 8.88 8.88 0 0 1-.89.17zM4.467 4.884s.003.002.005.006l-.005-.006zm7.066 0-.005.006c.002-.004.005-.006.005-.006zM11.354 5a3.174 3.174 0 0 0-.604-.21l-.099.445.055-.013c.286-.072.502-.149.648-.222z" />
              </svg></span>
            <span>Total Data Entries</span>
            <span class="card-text"><?php echo isset($totalStock) ? $totalStock : 0 ?></span>
          </div>
          <div class="progress-bar" role="progressbar" aria-label="Basic example" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <!-- /.info-box -->
      </div>
      <div class="col">
        <div class="card ">
          <div class="card-header">
            <p>Stock Masuk :</p>
          </div>
          <div class="card-body ">
            <div>
              <?php
              $limitStock = 1000;
              $stockPercent = ($totalStock / $limitStock) * 100
              ?>
              <div class="progress">
                <div class="progress-bar bg-yellow" role="progressbar" aria-label="Success example" style="width: <?= intval($stockPercent) ?>%" aria-valuenow="<?= intval($stockPercent) ?>" aria-valuemin="0" aria-valuemax="100"><span class=" fw-bold"><?= intval($stockPercent) ?>%</span></div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.info-box -->
      </div>


      <!-- ... -->
    </div>
    <!-- /.row -->
  </div>
</section>


<?php
include 'components/partials/dashboard.footer.php';
include 'components/templates/footer.php';
?>