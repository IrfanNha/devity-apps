<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: auth/login.php");
  exit;
}

$pages = 'Items';

require 'vendor/autoload.php';
require 'config/Config.php';
include 'components/templates/header.php'; ?>
<style>
  /* Mengubah semua input dengan id "item_code" menjadi huruf besar (uppercase) */
  #item_code {
    text-transform: uppercase;
  }
</style>
<?php
include 'components/partials/dashboard.header.php';

require 'config/Database.php';
$database = new Database();
$conn = $database->getConnection();

// Handle Create (Add) operation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create'])) {
  $item_name = $_POST['item_name'];
  $item_code = strtoupper($_POST['item_code']);
  $price = $_POST['price']; // Ambil harga dari input form
  $user_id = $_SESSION['user_id'];

  // Validate and sanitize user input
  // Perform appropriate validation and sanitization here

  // Check if the item_code already exists in the database
  $check_query = "SELECT id FROM items WHERE item_code = :item_code AND user_id = :user_id";
  $stmt_check = $conn->prepare($check_query);
  $stmt_check->bindParam(':item_code', $item_code);
  $stmt_check->bindParam(':user_id', $user_id);
  $stmt_check->execute();

  if ($stmt_check->rowCount() > 0) {
    // Item with the same item_code already exists, handle the error
    echo "Item with the same item_code already exists.";
  } else {
    // Insert item into the database
    $insert_query = "INSERT INTO items (item_name, item_code, user_id, price) VALUES (:item_name, :item_code, :user_id, :price)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bindParam(':item_name', $item_name);
    $stmt->bindParam(':item_code', $item_code);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':price', $price); // Bind parameter harga

    if ($stmt->execute()) {
      // Item added successfully, redirect or show a success message
      echo '<script>window.location.href = "items.php";</script>';
      exit;
    } else {
      // Handle the error (e.g., display an error message)
    }
  }
}

// Handle Edit operation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit'])) {
  $edit_id = $_POST['edit_id'];
  $new_item_name = $_POST['new_item_name'];
  // Perform validation and update the item in the database
  $update_query = "UPDATE items SET item_name = :item_name WHERE id = :edit_id";
  $stmt_update = $conn->prepare($update_query);
  $stmt_update->bindParam(':item_name', $new_item_name);
  $stmt_update->bindParam(':edit_id', $edit_id);
  if ($stmt_update->execute()) {
    // Item updated successfully, redirect or show a success message
    echo '<script>window.location.href = "items.php";</script>';
    exit;
  } else {
    // Handle the error (e.g., display an error message)
  }
}

// Handle Delete operation
if (isset($_GET['delete'])) {
  $delete_id = $_GET['delete'];

  // Hapus data stok terkait dari tabel items_stock
  $delete_stock_query = "DELETE FROM items_stock WHERE item_id = :delete_id";
  $stmt_delete_stock = $conn->prepare($delete_stock_query);
  $stmt_delete_stock->bindParam(':delete_id', $delete_id);

  if ($stmt_delete_stock->execute()) {
    // Setelah menghapus data stok terkait, Anda bisa menghapus item dari tabel items
    $delete_query = "DELETE FROM items WHERE id = :delete_id";
    $stmt_delete = $conn->prepare($delete_query);
    $stmt_delete->bindParam(':delete_id', $delete_id);

    if ($stmt_delete->execute()) {
      // Item berhasil dihapus, redirect atau tampilkan pesan sukses
      echo '<script>window.location.href = "items.php";</script>';
      exit;
    } else {
      // Handle kesalahan (contoh: tampilkan pesan kesalahan)
    }
  } else {
    // Handle kesalahan (contoh: tampilkan pesan kesalahan)
  }
}

// Handle Read (List) operation
$query = "SELECT id, item_name, item_code, price, created_at FROM items WHERE user_id = :user_id";
$stmt = $conn->prepare($query);
$user_id = $_SESSION['user_id'];
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
$row_count = 0;
?>

<!-- Display the list of items here -->
<div class="container">
  <button type="button" class="btn btn-dark rounded-5 mt-3" data-bs-toggle="modal" data-bs-target="#addItemModal">
    Add Item
  </button>
  <!-- Modal for adding a new item -->
  <!-- Modal for adding a new item -->
  <div class="modal fade" id="addItemModal" tabindex="-1" role="dialog" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addItemModalLabel">Add Item</h5>
          <button type="button" class="close btn" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="POST">
            <div class="mb-3">
              <label for="item_name" class="form-label">Item Name</label>
              <input type="text" class="form-control" id="item_name" name="item_name" required>
            </div>
            <div class="mb-3">
              <label for="item_code" class="form-label">Item CODE</label>
              <input type="text" class="form-control mb-3" id="item_code" name="item_code" minlength="10" maxlength="10" required>
              <button type="button" class="btn btn-secondary" id="generateItemCode">Generate Item Code</button>
            </div>
            <!-- Button to generate item code -->
            <div class="mb-3">
              <label for="price" class="form-label">Price</label>
              <input type="text" class="form-control" id="price" name="price" required>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary rounded-5" data-bs-dismiss="modal">Close</button>
              <button type="submit" name="create" class="btn btn-primary rounded-5">Add Item</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Function to generate item code based on item name
    document.getElementById('generateItemCode').addEventListener('click', function() {
      const itemName = document.getElementById('item_name').value.trim();
      if (itemName.length >= 3) {
        const generatedCode = itemName.substring(0, 3).toUpperCase() + Math.floor(1000000 + Math.random() * 9000000);
        document.getElementById('item_code').value = generatedCode;
      } else {
        alert('Item name minimal 3 karakter');
      }
    });
  </script>



  <div class="card my-5">
    <div class="card-header">
      <h3>Daftar Items</h3>
    </div>
    <div class="card-body table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>No. </th>
            <th>Item</th>
            <th>Item Code</th>
            <th>Price</th> <!-- Tambahkan kolom Harga -->
            <th>
              <div class="d-md-block d-none">Created At</div>
            </th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($items as $item) : ?>
            <tr>
              <td><?php echo ++$row_count ?></td>
              <td><?php echo $item['item_name']; ?></td>
              <td><?php echo $item['item_code']; ?></td>
              <td>Rp. <?php echo number_format($item['price'], 0, ',', '.'); ?></td>
              <td>
                <div class="d-md-block d-none"><?php echo $item['created_at']; ?> </div>
              </td>
              <td>
                <div class="d-flex">
                  <a href="#" class="btn btn-warning me-1" data-bs-toggle="modal" data-bs-target="#editItemModal<?php echo $item['id']; ?>">
                    <i class="bi bi-pencil-square fw-bold"></i>
                  </a>
                  <a href="items.php?delete=<?php echo $item['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item?')">
                    <i class="bi bi-trash3"></i>
                  </a>
                </div>
              </td>
            </tr>

            <!-- Edit Item Modal -->
            <div class="modal fade" id="editItemModal<?php echo $item['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editItemModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="editItemModalLabel">Edit Item</h5>
                    <button type="button" class="close btn" data-bs-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <form method="POST">
                    <div class="modal-body">
                      <input type="hidden" name="edit_id" value="<?php echo $item['id']; ?>">
                      <div class="mb-3">
                        <label for="new_item_name" class="form-label">New Item Name</label>
                        <input type="text" class="form-control" id="new_item_name" name="new_item_name" value="<?php echo $item['item_name']; ?>" required>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="submit" name="edit" class="btn btn-primary">Save Changes</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Form for adding a new item -->


<?php
include 'components/partials/dashboard.footer.php';
include 'components/templates/footer.php';
?>