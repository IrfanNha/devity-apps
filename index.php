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
  <div class="container-fluid">
    <div class="row row-cols-1 row-cols-md-4 g-4">
      <div class="col">
        <div class="card  mb-lg-0 mb-3">
          <div class="card-body ">
            <span><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-basket3" viewBox="0 0 16 16">
                <path d="M5.757 1.071a.5.5 0 0 1 .172.686L3.383 6h9.234L10.07 1.757a.5.5 0 1 1 .858-.514L13.783 6H15.5a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H.5a.5.5 0 0 1-.5-.5v-1A.5.5 0 0 1 .5 6h1.717L5.07 1.243a.5.5 0 0 1 .686-.172zM3.394 15l-1.48-6h-.97l1.525 6.426a.75.75 0 0 0 .729.574h9.606a.75.75 0 0 0 .73-.574L15.056 9h-.972l-1.479 6h-9.21z" />
              </svg></span>
            <span>Menu Makanan</span>
            <span class="card-text"><?php echo $totalMenu; ?></span>
          </div>
        </div>
        <!-- /.info-box -->
      </div>
      <!-- /.col -->
      <div class="col">
        <div class="card  mb-lg-0 mb-3">
          <div class="card-body ">
            <span><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-cup-straw" viewBox="0 0 16 16">
                <path d="M13.902.334a.5.5 0 0 1-.28.65l-2.254.902-.4 1.927c.376.095.715.215.972.367.228.135.56.396.56.82 0 .046-.004.09-.011.132l-.962 9.068a1.28 1.28 0 0 1-.524.93c-.488.34-1.494.87-3.01.87-1.516 0-2.522-.53-3.01-.87a1.28 1.28 0 0 1-.524-.93L3.51 5.132A.78.78 0 0 1 3.5 5c0-.424.332-.685.56-.82.262-.154.607-.276.99-.372C5.824 3.614 6.867 3.5 8 3.5c.712 0 1.389+.045 1.985.127l.464-2.215a.5.5 0 0 1 .303-.356l2.5-1a.5.5 0 0 1 .65.278zM9.768 4.607A13.991 13.991 0 0 0 8 4.5c-1.076 0-2.033.11-2.707.278A3.284 3.284 0 0 0 4.645 5c.146.073.362.15.648.222C5.967 5.39 6.924 5.5 8 5.5c.571 0 1.109-.03 1.588-.085l.18-.808zm.292 1.756C9.445 6.45 8.742 6.5 8 6.5c-1.133 0-2.176-.114-2.95-.308a5.514 5.514 0 0 1-.435-.127l.838 8.03c.013.121.06.186.102.215.357.249 1.168.69 2.438.69 1.27 0 2.081-.441 2.438-.69.042-.029.09-.094.102-.215l.852-8.03a5.517 5.517 0 0 1-.435.127 8.88 8.88 0 0 1-.89.17zM4.467 4.884s.003.002.005.006l-.005-.006zm7.066 0-.005.006c.002-.004.005-.006.005-.006zM11.354 5a3.174 3.174 0 0 0-.604-.21l-.099.445.055-.013c.286-.072.502-.149.648-.222z" />
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
            <span><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-cup-straw" viewBox="0 0 16 16">
                <path d="M13.902.334a.5.5 0 0 1-.28.65l-2.254.902-.4 1.927c.376.095.715.215.972.367.228.135.56.396.56.82 0 .046-.004.09-.011.132l-.962 9.068a1.28 1.28 0 0 1-.524.93c-.488.34-1.494.87-3.01.87-1.516 0-2.522-.53-3.01-.87a1.28 1.28 0 0 1-.524-.93L3.51 5.132A.78.78 0 0 1 3.5 5c0-.424.332-.685.56-.82.262-.154.607-.276.99-.372C5.824 3.614 6.867 3.5 8 3.5c.712 0 1.389+.045 1.985.127l.464-2.215a.5.5 0 0 1 .303-.356l2.5-1a.5.5 0 0 1 .65.278zM9.768 4.607A13.991 13.991 0 0 0 8 4.5c-1.076 0-2.033.11-2.707.278A3.284 3.284 0 0 0 4.645 5c.146.073.362.15.648.222C5.967 5.39 6.924 5.5 8 5.5c.571 0 1.109-.03 1.588-.085l.18-.808zm.292 1.756C9.445 6.45 8.742 6.5 8 6.5c-1.133 0-2.176-.114-2.95-.308a5.514 5.514 0 0 1-.435-.127l.838 8.03c.013.121.06.186.102.215.357.249 1.168.69 2.438.69 1.27 0 2.081-.441 2.438-.69.042-.029.09-.094.102-.215l.852-8.03a5.517 5.517 0 0 1-.435.127 8.88 8.88 0 0 1-.89.17zM4.467 4.884s.003.002.005.006l-.005-.006zm7.066 0-.005.006c.002-.004.005-.006.005-.006zM11.354 5a3.174 3.174 0 0 0-.604-.21l-.099.445.055-.013c.286-.072.502-.149.648-.222z" />
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
            <p>Stock masuk :</p>
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