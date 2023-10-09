<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: auth/login.php");
  exit;
}

$pages = 'Kasir';

require 'vendor/autoload.php';
require 'config/Config.php';
require 'config/Database.php'; // Menggunakan koneksi database dari Database.php
$database = new Database();
$conn = $database->getConnection();

include 'components/templates/header.php';
include 'components/partials/dashboard.header.php';

// Inisialisasi variabel
$item_id = "";
$quantity = "";
$total = "";

// Menangani penjualan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $item_id = $_POST["item_id"];
  $quantity = $_POST["quantity"];

  // Validasi item dan jumlah
  if (!empty($item_id) && is_numeric($quantity) && $quantity > 0) {
    // Query untuk mendapatkan informasi item
    $query = "SELECT item_name, price FROM items WHERE id = :item_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':item_id', $item_id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
      $item_name = $row['item_name'];
      $price = $row['price'];
      $total = $price * $quantity;

      // Simpan riwayat penjualan ke database
      $query = "INSERT INTO riwayat_penjualan (user_id, item_name, quantity, total) VALUES (:user_id, :item_name, :quantity, :total)";
      $stmt = $conn->prepare($query);
      $stmt->bindParam(':user_id', $_SESSION['user_id']);
      $stmt->bindParam(':item_name', $item_name);
      $stmt->bindParam(':quantity', $quantity);
      $stmt->bindParam(':total', $total);
      $stmt->execute();

      // Reset variabel
      $item_id = "";
      $quantity = "";
      $total = "";
    } else {
      echo "Item tidak ditemukan.";
    }
  } else {
    echo "Isian tidak valid.";
  }
}
?>

<!-- Tambahkan konten HTML untuk halaman kasir di sini -->
<div class="container my-5">
  <div class="mb-5">
    <h3>Selamat datang di halaman kasir</h3>
    <form method="post" action="">
      <div class="form-group mb-3">
        <label for="item_id">Pilih Item:</label>
        <select class="form-control" name="item_id" id="item_id">
          <!-- Ambil daftar item dari database dan tampilkan dalam pilihan -->
          <?php
          $query = "SELECT id, item_name, price FROM items";
          $stmt = $conn->query($query);
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value='" . $row['id'] . "'>" . $row['item_name'] . " - Rp " . number_format($row['price'], 2) . "</option>";
          }
          ?>
        </select>
      </div>
      <div class="form-group mb-3">
        <label for="quantity">Jumlah:</label>
        <input type="number" class="form-control" name="quantity" id="quantity" value="<?php echo $quantity; ?>">
      </div>
      <button type="submit" class="btn btn-primary">Tambahkan ke Keranjang</button>
    </form>
  </div>

  <!-- Menampilkan riwayat penjualan -->
  <h3>Riwayat Penjualan:</h3>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Item</th>
        <th>Jumlah</th>
        <th>Total</th>
        <th>Tanggal</th>
      </tr>
    </thead>
    <tbody>
      <!-- Ambil data riwayat penjualan dari database dan tampilkan dalam tabel -->
      <?php
      $query = "SELECT item_name, quantity, total, created_at FROM riwayat_penjualan WHERE user_id = :user_id";
      $stmt = $conn->prepare($query);
      $stmt->bindParam(':user_id', $_SESSION['user_id']);
      $stmt->execute();
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $row['item_name'] . "</td>";
        echo "<td>" . $row['quantity'] . "</td>";
        echo "<td>Rp " . number_format($row['total'], 2) . "</td>";
        echo "<td>" . $row['created_at'] . "</td>";
        echo "</tr>";
      }
      ?>
    </tbody>
  </table>
</div>

<?php
include 'components/partials/dashboard.footer.php';
include 'components/templates/footer.php';
?>