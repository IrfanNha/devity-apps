<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: auth/login.php");
  exit;
}

$pages = 'Preferences';

require 'vendor/autoload.php';
require 'config/Config.php';
include 'components/templates/header.php';
include 'components/partials/dashboard.header.php';

include 'config/Database.php';

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Handle form submission
  $store_name = isset($_POST['store_name']) ? $_POST['store_name'] : '';
  $alamat = isset($_POST['alamat']) ? $_POST['alamat'] : '';
  $phone = isset($_POST['phone']) ? $_POST['phone'] : '';

  // Handle image upload
  if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    // Handle image upload
    $upload_dir = 'images/';
    $file_name = $_FILES['image']['name'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_path = $upload_dir . $file_name;

    if (move_uploaded_file($file_tmp, $file_path)) {
      // Image upload successful, update the image path in preferences
      $preferences['image'] = $file_path;
    } else {
      // Handle the case where the upload failed
      // You can add error handling code here
    }
  }

  // Update or insert preferences into the database
  if ($stmt->rowCount() > 0) {
    // User has existing preferences, update them
    $update_query = "UPDATE users_preferences SET image = :image, store_name = :store_name, alamat = :alamat, phone = :phone WHERE user_id = :user_id";
  } else {
    // User doesn't have preferences, insert new preferences
    $insert_query = "INSERT INTO users_preferences (user_id, image, store_name, alamat, phone) VALUES (:user_id, :image, :store_name, :alamat, :phone)";
  }

  // Handle data deletion if the "Delete All Data" button is pressed
  if (isset($_POST['confirm_delete'])) {
    // Fetch user's hashed password from the database
    $fetch_password_query = "SELECT password FROM users WHERE id = :user_id";
    $stmt = $conn->prepare($fetch_password_query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the entered password matches the hashed password from the database
    $password = $_POST['password'];

    if (password_verify($password, $user['password'])) {
      // Password is correct, proceed with data deletion

      // Delete data in Laporan Keuangan
      $delete_laporan_keuangan_query = "DELETE FROM riwayat_pembelian WHERE user_id = :user_id";
      $stmt = $conn->prepare($delete_laporan_keuangan_query);
      $stmt->bindParam(':user_id', $user_id);
      if ($stmt->execute()) {
        $_SESSION['success_message'] = "Data in Laporan Keuangan berhasil dihapus.";
      } else {
        $_SESSION['error_message'] = "Terjadi kesalahaan ketika menghapus data di Laporan Keuangan.";
      }

      // Delete data in Laporan Menu
      $delete_laporan_menu_query = "DELETE FROM order_items WHERE user_id = :user_id";
      $stmt = $conn->prepare($delete_laporan_menu_query);
      $stmt->bindParam(':user_id', $user_id);
      if ($stmt->execute()) {
        $_SESSION['success_message'] = "Data in Laporan Menu berhasil dihapus.";
      } else {
        $_SESSION['error_message'] = "Terjadi kesalahan ketika menghapus data di Laporan Menu.";
      } 
        
      $delete_menu_query = "DELETE FROM menu WHERE user_id = :user_id";
      $stmt = $conn->prepare($delete_menu_query);
      $stmt->bindParam(':user_id', $user_id);
      if ($stmt->execute()) {
        $_SESSION['success_message'] = "Data Menu berhasil dihapus.";
      } else {
        $_SESSION['error_message'] = "Terjadi kesalahan ketika menghapus data di Menu.";
      } 

      $delete_order_items_query = "DELETE FROM order_items WHERE user_id = :user_id";
      $stmt = $conn->prepare($delete_order_items_query);
      $stmt->bindParam(':user_id', $user_id);
      if ($stmt->execute()) {
        $_SESSION['success_message'] = "Data Menu berhasil dihapus.";
      } else {
        $_SESSION['error_message'] = "Terjadi kesalahan ketika menghapus data di Menu.";
      } 

      // // Delete data in Laporan Modal
      // $delete_laporan_modal_query = "DELETE FROM laporan_modal WHERE user_id = :user_id";
      // $stmt = $conn->prepare($delete_laporan_modal_query);
      // $stmt->bindParam(':user_id', $user_id);
      // if ($stmt->execute()) {
      //   $_SESSION['success_message'] = "Data in Laporan Modal berhasil dihapus.";
      // } else {
      //   $_SESSION['error_message'] = "Terjadi kesalahan ketika menghapus data di Laporan Modal.";
      // }

      // Redirect the user back to the preferences page
      echo '<script>window.location.href = "preferences.php";</script>';
      exit();
    } else {
      // Password is incorrect, show an error message
      $_SESSION['error_message'] = "Password Salah. Coba lagi";
      echo '<script>window.location.href = "preferences.php";</script>';
      exit();
    }
  }



  // Prepare and execute the SQL query
  $stmt = $conn->prepare($update_query ?? $insert_query);
  $stmt->bindParam(':user_id', $user_id);
  $stmt->bindParam(':image', $file_path); // Updated to use the file path
  $stmt->bindParam(':store_name', $store_name);
  $stmt->bindParam(':alamat', $alamat);
  $stmt->bindParam(':phone', $phone);
  $stmt->execute();
}

// ...
?>

<!-- HTML form for preference display and edit -->
<!-- HTML form for preference display and edit -->
<div class="container my-5">
  <div class="card">
    <div class="card-header">
      <h3>Profil toko</h3>
    </div>
    <div class="card-body">
      <form method="POST" enctype="multipart/form-data">
        <!-- File Input for Image -->
        <img class="mb-3" src="<?php echo $preferences['image']; ?>" alt="" height="100vh">
        <div class=" mb-3">
          <label for="image" class="form-label">Upload Image</label>
          <input type="file" class="form-control" id="image" name="image" accept="image/*">
        </div>

        <!-- Input Field for Store Name -->
        <div class="input-group mb-4">
          <div class="form-floating">
            <input type="text" class="form-control" id="store_name" name="store_name" value="<?php echo $preferences['store_name']; ?>">
            <label for="store_name" class="form-label">Store Name</label>
          </div>
        </div>

        <!-- Input Field for Alamat -->
        <div class="input-group mb-4">
          <div class="form-floating">
            <input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo $preferences['alamat']; ?>">
            <label for="alamat" class="form-label">Alamat</label>
          </div>
        </div>

        <!-- Input Field for Phone -->
        <div class="input-group mb-4">
          <div class="form-floating">
            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $preferences['phone']; ?>">
            <label for="phone" class="form-label">Phone</label>
          </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-dark rounded-5">Save</button>
      </form>
    </div>
  </div>
</div>
<div class="container my-5">

  <!-- Add a section for deleting data -->
  <div class="mt-5">
    <h2>Area Sensitive</h2>
    <p>Klik tombol di bawah untuk mereset toko Anda <span class="text-danger">Lakukan dengan hati-hati</span></p>
    <hr>
    <?php
    if (isset($_SESSION['success_message'])) {
      echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
      unset($_SESSION['success_message']); // Clear the session variable
    }

    // Display error message if it exists
    if (isset($_SESSION['error_message'])) {
      echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
      unset($_SESSION['error_message']); // Clear the session variable
    }
    ?>
    <form method="POST" id="deleteData">
      <div class="input-group mb-3">
        <div class="form-floating">
          <input class="form-control border-end-0" id="inputPassword" type="password" placeholder="Password" minlength="4" name="password" required />
          <label for="inputPassword">Konfirmasi Password</label>
        </div>
        <span class="input-group-text bg-transparent border border-start-0">
          <button type="button" id="togglePassword" class="btn btn-link text-decoration-none" onclick="togglePasswordVisibility()">
            <i class="bi bi-eye text-dark" style="font-size: 20px;"></i>
          </button>
        </span>
      </div>
      <button type="submit" name="confirm_delete" class="btn btn-danger rounded-5">Delete All Data</button>
    </form>
  </div>
</div>
<script>
  function togglePasswordVisibility() {
    const passwordInput = document.getElementById('inputPassword');
    const toggleButton = document.getElementById('togglePassword');

    if (passwordInput.type === 'password') {
      passwordInput.type = 'text';
      toggleButton.innerHTML = '<i class="bi bi-eye-slash text-dark" style="font-size: 20px;"></i>'; // Menggunakan tanda kutip ganda di dalam tanda kutip tunggal
    } else {
      passwordInput.type = 'password';
      toggleButton.innerHTML = '<i class="bi bi-eye text-dark" style="font-size: 20px;"></i>'; // Menggunakan tanda kutip ganda di dalam tanda kutip tunggal
    }
  }
</script>
<?php
include 'components/partials/dashboard.footer.php';
include 'components/templates/footer.php';
?>