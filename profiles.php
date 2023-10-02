<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: auth/login.php");
  exit;
}
$pages = 'User Profile';
?>

<?php require 'vendor/autoload.php'; ?>
<?php require 'config/Config.php'; ?>
<?php include 'components/templates/header.php'; ?>
<?php include 'components/partials/dashboard.header.php'; ?>

<!-- content -->

<?php
require 'config/Database.php';
// Include your Database class and establish a database connection

$user_id = $_SESSION['user_id'];

// Fetch user information from the database
$database = new Database();
$conn = $database->getConnection();

$query = "SELECT username, email, created_at FROM users WHERE id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();

if ($stmt->rowCount() > 0) {
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
}

// Handle form submissions for username and password updates
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Check which fields are being updated
  if (isset($_POST['update_username'])) {
    // Handle username update
    $new_username = $_POST['new_username'];

    // Perform validation on the new username (e.g., check for uniqueness)
    // Add validation and error handling here

    // Update the username in the database
    $update_query = "UPDATE users SET username = :new_username WHERE id = :user_id";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bindParam(':new_username', $new_username);
    $update_stmt->bindParam(':user_id', $user_id);
    $update_stmt->execute();

    // Optionally, update the $user variable with the new username
    $user['username'] = $new_username;
  }

  if (isset($_POST['update_password'])) {
    // Handle password update
    $new_password = $_POST['new_password'];

    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update the hashed password in the database
    $update_query = "UPDATE users SET password = :hashed_password WHERE id = :user_id";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bindParam(':hashed_password', $hashed_password);
    $update_stmt->bindParam(':user_id', $user_id);
    $update_stmt->execute();
  }
}
?>

<div class="container my-5">
  <div class="row mb-3">
    <div class="col-md-4 mb-4 mb-md-0">
      <div class="card">
        <div class="card-header">

          <h5>Username</h5>

        </div>
        <div class="card-body">
          <p class="form-control-plaintext" id="username"><?php echo $user['username']; ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-4 mb-4 mb-md-0">
      <div class="card">
        <div class="card-header">
          <h5>Email</h5>
        </div>
        <div class="card-body">
          <p class="form-control-plaintext" id="email"><?php echo $user['email']; ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-4 mb-4 mb-md-0">
      <div class="card">
        <div class="card-header">
          <h5>Account Created On</h5>
        </div>
        <div class="card-body">
          <p class="form-control-plaintext" id="created_at"><?php echo $user['created_at']; ?></p>
        </div>
      </div>
    </div>
  </div>


  <div class="card">
    <div class="card-header">
      <h4>Update</h4>
    </div>
    <div class="card-body">
      <div class="row justify mt-3">
        <div class="col-md-6 mb-4 mb-md-0">
          <form method="POST">
            <div class="form-floating mb-3">
              <input class=" form-control rounded-3" id="new_username" name="new_username" type="text" placeholder="username baru" autocomplete="off" required />
              <label for="username">Username</label>
            </div>
            <button type="submit" name="update_username" class="btn btn-dark rounded-5">Save</button>
          </form>
        </div>

        <div class="col-md-6">
          <form method="POST">

            <div class="input-group mb-3">
              <div class="form-floating">
                <input class="form-control border-end-0" id="inputPassword" type="password" placeholder="Password" minlength="4" name="new_password" required />
                <label for="inputPassword">Password</label>
              </div>
              <span class="input-group-text bg-transparent border border-start-0">
                <button type="button" id="togglePassword" class="btn btn-link text-decoration-none" onclick="togglePasswordVisibility()">
                  <i class="bi bi-eye text-dark" style="font-size: 20px;"></i>
                </button>
              </span>
            </div>

            <button type="submit" name="update_password" class="btn btn-dark rounded-5">Save</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Form for updating username -->

  <!-- Form for updating password -->
</div>
</div>

<!-- end content -->
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
<?php include 'components/partials/dashboard.footer.php'; ?>
<?php include 'components/templates/footer.php'; ?>