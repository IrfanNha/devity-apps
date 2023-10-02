<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: auth/login.php");
  exit;
}

$pages = 'Item Stock';

require 'vendor/autoload.php';
require 'config/Config.php';
include 'components/templates/header.php';
include 'components/partials/dashboard.header.php';

require 'config/Database.php';
$database = new Database();
$conn = $database->getConnection();

// Fetch existing items from the database
$itemQuery = "SELECT id, item_name FROM items WHERE user_id = :user_id";
$itemStmt = $conn->prepare($itemQuery);
$itemStmt->bindParam(':user_id', $_SESSION['user_id']);
$itemStmt->execute();
$items = $itemStmt->fetchAll(PDO::FETCH_ASSOC);

// Initialize variables to hold selected item and action
$selectedItemId = null;
$action = null;

// Handle form submission for adding and subtracting stock
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $selectedItemId = $_POST['item_id'];
  $items_qty = $_POST['items_qty'];

  if (isset($_POST['add_stock'])) {
    $action = 'add';
  } elseif (isset($_POST['subtract_stock'])) {
    $action = 'subtract';
  }

  // Validate and sanitize user input
  // Perform appropriate validation and sanitization here

  // Check if the action is to subtract stock
  if ($action === 'subtract') {
    // Fetch the current stock quantity
    $currentStockQuery = "SELECT items_qty FROM items_stock WHERE user_id = :user_id AND item_id = :item_id";
    $stockStmt = $conn->prepare($currentStockQuery);
    $stockStmt->bindParam(':user_id', $_SESSION['user_id']);
    $stockStmt->bindParam(':item_id', $selectedItemId);
    $stockStmt->execute();
    $currentStock = $stockStmt->fetch(PDO::FETCH_ASSOC);

    // Check if there's enough stock to subtract
    // Proceed to subtract stock
    $subtractStockQuery = "INSERT INTO items_stock (user_id, item_id, items_qty) 
                             VALUES (:user_id, :item_id, :items_qty * -1)
                             ON DUPLICATE KEY UPDATE items_qty = items_qty - :items_qty";
    $stmt = $conn->prepare($subtractStockQuery);
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->bindParam(':item_id', $selectedItemId);
    $stmt->bindParam(':items_qty', $items_qty);

    if ($stmt->execute()) {
      // Stock subtracted successfully, redirect or show a success message
      echo '<script>window.location.href = "item-stock.php";</script>';
      exit;
    } else {
      // Handle the error (e.g., display an error message)
    }
  } elseif ($action === 'add') {
    // Insert or update items_stock data into the database for adding stock
    $insert_query = "INSERT INTO items_stock (user_id, item_id, items_qty) 
                     VALUES (:user_id, :item_id, :items_qty)
                     ON DUPLICATE KEY UPDATE items_qty = items_qty + :items_qty";
    $stmt = $conn->prepare($insert_query);
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->bindParam(':item_id', $selectedItemId);
    $stmt->bindParam(':items_qty', $items_qty);

    if ($stmt->execute()) {
      // Stock added successfully, redirect or show a success message
      echo '<script>window.location.href = "item-stock.php";</script>';
      exit;
    } else {
      // Handle the error (e.g., display an error message)
    }
  }
}

// Fetch items_stock data and calculate total
$query = "SELECT i.item_name, stk.item_id, SUM(stk.items_qty) AS total_items_qty
          FROM items_stock AS stk
          JOIN items AS i ON stk.item_id = i.id
          WHERE stk.user_id = :user_id
          GROUP BY stk.item_id";

$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$stok_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Display items_stock data and total here -->
<div class="container my-5">

  <!-- Form for adding and subtracting stock -->
  <div class="row mb-0 mb-md-5">
    <div class="col-lg-6">

      <div class="card mb-5 mb-md-0">
        <div class="card-header">
          <h2>Stok Masuk</h2>
        </div>
        <div class="card-body">
          <form method="POST">
            <div class="mb-3">
              <label for="item_id" class="form-label">Select Item</label>
              <select class="form-select" id="item_id" name="item_id" required>
                <option value="">-- Select an Item --</option>
                <?php foreach ($items as $item) : ?>
                  <option value="<?php echo $item['id']; ?>" <?php if ($item['id'] == $selectedItemId) echo 'selected'; ?>>
                    <?php echo $item['item_name']; ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-3">
              <label for="items_qty" class="form-label">Items Quantity</label>
              <input type="number" class="form-control" id="items_qty" name="items_qty" required>
            </div>
            <button type="submit" name="add_stock" class="btn btn-primary  rounded-5">Tambah</button>
          </form>
        </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="card mb-5 mb-md-0"">
        <div class=" card-header">
        <h2>Stok Keluar</h2>
      </div>
      <div class="card-body">
        <form method="POST">
          <div class="mb-3">
            <label for="item_id" class="form-label">Select Item</label>
            <select class="form-select" id="item_id" name="item_id" required>
              <option value="">-- Select an Item --</option>
              <?php foreach ($items as $item) : ?>
                <option value="<?php echo $item['id']; ?>" <?php if ($item['id'] == $selectedItemId) echo 'selected'; ?>>
                  <?php echo $item['item_name']; ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label for="items_qty" class="form-label">Items Quantity</label>
            <input type="number" class="form-control" id="items_qty" name="items_qty" required>
          </div>
          <button type="submit" name="subtract_stock" class="btn btn-danger  rounded-5">Kurang</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php
// Check for stock error and display it
if (isset($_SESSION['stock_error'])) {
  echo '<div class="alert alert-danger" role="alert">' . $_SESSION['stock_error'] . '</div>';
  unset($_SESSION['stock_error']); // Clear the error message
}
?>

<div class="card table-responsive">
  <div class="card-header">
    <h3>Items Stock</h3>
  </div>
  <div class="card-body">
    <table class="table table-hover">
      <thead>
        <tr>
          <th>#</th>
          <th>Item ID</th>
          <th>Item Name</th>
          <th>Items Qty</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $row_count = 0;
        foreach ($stok_items as $stok_item) : ?>
          <tr>
            <td><?php echo ++$row_count ?></td>
            <td><?php echo $stok_item['item_id']; ?></td>
            <td><?php echo $stok_item['item_name']; ?></td>
            <td class="fw-bold"><?php echo $stok_item['total_items_qty']; ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
</div>

<?php
include 'components/partials/dashboard.footer.php';
include 'components/templates/footer.php';
?>