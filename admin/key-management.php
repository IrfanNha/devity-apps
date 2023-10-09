<?php
session_start();

// Check if the user is not logged in or user_rank is not 3
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

// Check if user_rank is not 3
if ($_SESSION['user_rank'] !== "3") {
  header("Location: 403.php");
  exit;
}

$pages = 'Manage Key';

// Include your database connection code here (require_once '../config/Database.php')
require_once '../config/Database.php';

// Banyaknya data per halaman
$records_per_page = 10;

// Tentukan halaman saat ini
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
  $current_page = $_GET['page'];
} else {
  $current_page = 1;
}

// Hitung offset (mulai dari record ke berapa)
$offset = ($current_page - 1) * $records_per_page;

// Inisialisasi koneksi database
$database = new Database();
$conn = $database->getConnection();

// Handle Generate Keys Action
if (isset($_POST['generate_keys'])) {
  // Generate and insert new activation keys into the database
  $num_keys = 10; // Number of keys to generate
  for ($i = 0; $i < $num_keys; $i++) {
    $activation_key = generateActivationKey(25);
    $query = "INSERT INTO activation_keys (activation_key, is_used) VALUES (:activation_key, 0)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':activation_key', $activation_key);
    $stmt->execute();
  }
}

// Handle Give Users Activation Keys Action
if (isset($_POST['give_activation_key'])) {
  $username = $_POST['username'];
  $activation_key = $_POST['activation_key'];

  // Find user by username and retrieve user_id
  $query = "SELECT id FROM users WHERE username = :username";
  $stmt = $conn->prepare($query);
  $stmt->bindParam(':username', $username);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($row) {
    $user_id = $row['id'];

    // Check if the activation key is not used
    $query = "SELECT id FROM activation_keys WHERE activation_key = :activation_key AND is_used = 0";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':activation_key', $activation_key);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
      $activation_key_id = $row['id'];

      // Update the activation key with the user_id and mark it as used
      $query = "UPDATE activation_keys SET user_id = :user_id, is_used = 1 WHERE id = :activation_key_id";
      $stmt = $conn->prepare($query);
      $stmt->bindParam(':user_id', $user_id);
      $stmt->bindParam(':activation_key_id', $activation_key_id);
      $stmt->execute();
    } else {
      // Activation key is already used or does not exist
      // Handle the error as needed
    }
  } else {
    // User does not exist
    // Handle the error as needed
  }
}

// Function to generate random activation key
function generateActivationKey($length)
{
  $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $activation_key = '';
  for ($i = 0; $i < $length; $i++) {
    $activation_key .= $characters[rand(0, strlen($characters) - 1)];
  }
  return $activation_key;
}

// Query untuk mengambil data dengan paginasi
$query = "SELECT * FROM activation_keys LIMIT :offset, :records_per_page";
$stmt = $conn->prepare($query);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':records_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();

// Menghitung jumlah total data (untuk paginasi)
$total_records_query = "SELECT COUNT(*) as total FROM activation_keys";
$total_records_stmt = $conn->prepare($total_records_query);
$total_records_stmt->execute();
$total_records = $total_records_stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Menghitung jumlah halaman
$total_pages = ceil($total_records / $records_per_page);
?>

<?php include '../vendor/autoload.php'; ?>
<?php include '../config/Config.php'; ?>
<?php include 'components/dashboard.header.php'; ?>

<!-- Display Activation Keys Table -->
<!-- ... Your previous code ... -->

<!-- Display Activation Keys Table -->
<div class="container my-5">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h3 class="m-0">Activation keys <span><i class="bi bi-person-vcard"></i></span></h3>
      <form method="POST">
        <button type="submit" class="btn btn-success" name="generate_keys">Generate Keys</button>
      </form>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Activation Key</th>
              <th>User ID</th>
              <th>Username</th> <!-- Add this line -->
              <th>Is Used</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Modify your SQL query to join activation_keys and users tables
            $query = "SELECT ak.id, ak.activation_key, ak.user_id, ak.is_used, u.username 
                      FROM activation_keys ak 
                      LEFT JOIN users u ON ak.user_id = u.id 
                      LIMIT :offset, :records_per_page";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindParam(':records_per_page', $records_per_page, PDO::PARAM_INT);
            $stmt->execute();
            $rowCount = 0;

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
              echo "<tr>";
              echo "<td>" . ++$rowCount . "</td>";
              echo "<td>{$row['activation_key']}</td>";
              echo "<td>{$row['user_id']}</td>";
              echo "<td>{$row['username']}</td>"; // Display username
              echo "<td>";
              if ($row['is_used'] == 0) {
                echo "<input type='checkbox' disabled> Unused";
              } else {
                echo "<input type='checkbox' disabled checked> Used";
              }
              echo "</td>";
              echo "<td>";

              echo "<a href='delete-activation-keys.php?id={$row['id']}' class='btn btn-danger'><i class='bi bi-trash3 fw-bold'></i></a>";
              echo " ";
              echo "<button class='btn btn-primary copy-key' data-key='{$row['activation_key']}'><i class='bi bi-clipboard fw-bold'></i></button>";
              echo "</td>";
              echo "</tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
    <div class="card-footer">
      <ul class="pagination mb-0 pb-0">
        <?php
        for ($page = 1; $page <= $total_pages; $page++) {
          echo "<li class='page-item " . ($current_page == $page ? 'active' : '') . "'>";
          echo "<a class='page-link' href='?page={$page}'>{$page}</a>";
          echo "</li>";
        }
        ?>
      </ul>
    </div>
  </div>
</div>

<!-- ... Your previous code ... -->



<!-- Give Users Activation Keys Form -->
<div class="container mb-5">
  <div class="card">
    <div class="card-header">
      <h3>Beri Activation Keys </h3>
    </div>
    <div class="card-body">
      <form method="POST">
        <div class="form-group mb-3">
          <label for="username">Search Username:</label>
          <input type="text" class="form-control" id="username" name="username" list="usernames" autocomplete="off">
          <!-- Buat datalist dengan id "usernames" -->
          <datalist id="usernames">
            <?php
            // Query untuk mengambil semua nama pengguna dari tabel "users"
            $queryUsernames = "SELECT username FROM users";
            $stmtUsernames = $conn->prepare($queryUsernames);
            $stmtUsernames->execute();

            // Tampilkan hasil query sebagai pilihan dalam datalist
            while ($rowUsername = $stmtUsernames->fetch(PDO::FETCH_ASSOC)) {
              echo "<option value='{$rowUsername['username']}'>";
            }
            ?>
          </datalist>
        </div>
        <div class="form-group mb-3">
          <label for="activation_key">Activation Key:</label>
          <input type="text" class="form-control" id="activation_key" name="activation_key">
        </div>
        <button type="submit" class="btn btn-primary" name="give_activation_key">Beri Activation Key</button>
      </form>
    </div>
  </div>
</div>


<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Temukan semua tombol "Salin ke Papan Klip"
    var copyButtons = document.querySelectorAll('.copy-key');

    // Tambahkan event listener untuk setiap tombol
    copyButtons.forEach(function(button) {
      button.addEventListener('click', function() {
        // Dapatkan activation key dari atribut data
        var activationKey = this.getAttribute('data-key');

        // Salin activation key ke clipboard
        var textArea = document.createElement('textarea');
        textArea.value = activationKey;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);

        // Beri tahu pengguna bahwa key telah disalin
        alert('Activation Key telah disalin ke clipboard: ' + activationKey);
      });
    });
  });
</script>

<?php include 'components/dashboard.footer.php'; ?>