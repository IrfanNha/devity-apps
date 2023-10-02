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

// Query database untuk mengambil daftar item
$query = "SELECT id, item_name, price FROM items";
$stmt = $conn->prepare($query);
$stmt->execute();
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Tambahkan formulir untuk transaksi -->
<div class="container">
  <h2>Form Transaksi</h2>
  <form method="POST" action="proses_transaksi.php">
    <div class="form-group">
      <label for="item">Pilih Item:</label>
      <select class="form-control" id="item" name="item[]" multiple>
        <?php foreach ($items as $item) : ?>
          <option value="<?php echo $item['id']; ?>" data-price="<?php echo $item['price']; ?>">
            <?php echo $item['item_name']; ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div id="input-container">
      <!-- Input grup untuk jumlah -->
    </div>
    <div class="form-group">
      <button type="button" class="btn btn-primary" id="add-input">Tambah Item</button>
    </div>
    <div class="form-group">
      <label for="total">Total Pembayaran:</label>
      <input type="text" class="form-control" id="total" name="total" readonly>
    </div>
    <button type="submit" class="btn btn-success">Proses Transaksi</button>
  </form>
</div>

<!-- Tambahkan script JavaScript untuk menangani tampilan input -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Ambil elemen-elemen yang diperlukan
    const itemSelect = document.getElementById('item');
    const inputContainer = document.getElementById('input-container');
    const totalInput = document.getElementById('total');
    const addInputButton = document.getElementById('add-input');

    // Inisialisasi array untuk menyimpan elemen-elemen input yang ditambahkan
    const inputElements = [];

    // Tambahkan event listener untuk tombol "Tambah Item"
    addInputButton.addEventListener('click', function() {
      const selectedItem = itemSelect.options[itemSelect.selectedIndex];
      const itemId = selectedItem.value;
      const itemName = selectedItem.text;
      const itemPrice = selectedItem.getAttribute('data-price');

      // Buat elemen input baru
      const inputGroup = document.createElement('div');
      inputGroup.classList.add('form-group');

      const label = document.createElement('label');
      label.textContent = itemName;

      const input = document.createElement('input');
      input.type = 'number';
      input.classList.add('form-control');
      input.name = `quantity[${itemId}]`;
      input.placeholder = 'Jumlah';

      // Tambahkan elemen input ke dalam container
      inputGroup.appendChild(label);
      inputGroup.appendChild(input);
      inputContainer.appendChild(inputGroup);

      // Simpan elemen input dalam array
      inputElements.push(input);

      // Hitung total pembayaran saat ini
      calculateTotal();
    });

    // Fungsi untuk menghitung total pembayaran
    function calculateTotal() {
      let total = 0;

      for (const input of inputElements) {
        const itemId = input.name.match(/\[(.*?)\]/)[1];
        const quantity = input.value;
        const itemPrice = document.querySelector(`option[value="${itemId}"]`).getAttribute('data-price');
        total += itemPrice * quantity;
      }

      totalInput.value = total;
    }
  });
</script>

<?php
include 'components/partials/dashboard.footer.php';
include 'components/templates/footer.php';
?>