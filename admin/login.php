<?php
session_start();

// Check if the user is not logged in or user_rank is not 3
if (isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit;
}



$pages = 'Login';

require '../config/Config.php';
include '../components/templates/header.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = htmlspecialchars($_POST["username"]);
  $password = $_POST["password"];

  // You should validate and sanitize user input here.

  // Create a database connection
  require_once '../config/Database.php';
  $database = new Database();
  $conn = $database->getConnection();
  $ipinfo_url = "https://ipinfo.io";
  $ch = curl_init($ipinfo_url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $ipinfo_data = curl_exec($ch);
  curl_close($ch);

  // Menguraikan data JSON untuk mendapatkan alamat IP
  $ipinfo_json = json_decode($ipinfo_data, true);
  $public_ip = isset($ipinfo_json['ip']) ? $ipinfo_json['ip'] : $_SERVER['REMOTE_ADDR'];

  // Query to check if the user exists
  $query_check_user = "SELECT id, username, password, user_rank, subs_expiry FROM users WHERE username = :username";
  $stmt_check_user = $conn->prepare($query_check_user);
  $stmt_check_user->bindParam(':username', $username);
  $stmt_check_user->execute();

  // Check if the user exists and verify the password
  if ($stmt_check_user->rowCount() > 0) {
    $user = $stmt_check_user->fetch(PDO::FETCH_ASSOC);
    if (password_verify($password, $user['password'])) {
      // Password is correct, set user session
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['username'] = $user['username'];
      $_SESSION['user_rank'] = $user['user_rank'];
      $_SESSION['subs_expiry'] = $user['subs_expiry'];

      // Insert a login attempt into the database

      $ip_address = $public_ip;
      $success = true; // Login was successful
      $query_insert_attempt = "INSERT INTO login_attempts (user_id, ip_address, success) VALUES (:user_id, :ip_address, :success)";
      $stmt_insert_attempt = $conn->prepare($query_insert_attempt);
      $stmt_insert_attempt->bindParam(':user_id', $user['id']);
      $stmt_insert_attempt->bindParam(':ip_address', $ip_address);
      $stmt_insert_attempt->bindParam(':success', $success);
      $stmt_insert_attempt->execute();

      // Redirect to the user's dashboard or any other page
      echo '<script>window.location.href = "index.php";</script>';
      exit;
    } else {
      // Password is incorrect
      $_SESSION['login_error'] = "Password Salah.";
    }
  } else {
    // User does not exist
    $_SESSION['login_error'] = "User Tidak Ditemukan";
  }

  // Insert a failed login attempt into the database
  $ip_address = $public_ip;
  $success = 0; // Login was unsuccessful
  $query_insert_attempt = "INSERT INTO login_attempts (ip_address, success) VALUES (:ip_address, :success)";
  $stmt_insert_attempt = $conn->prepare($query_insert_attempt);
  $stmt_insert_attempt->bindParam(':ip_address', $ip_address);
  $stmt_insert_attempt->bindParam(':success', $success);
  $stmt_insert_attempt->execute();

  // Close the database connection
  $conn = null;
}
?>




<div id="layoutAuthentication">
  <div id="layoutAuthentication_content">
    <main>
      <div class="container mt-5">
        <div class="row justify">
          <div class="col-lg-5">
            <div class="container">
              <div class="card border-0 rounded-lg mt-lg-3 mt-md-3">

                <!-- tabel form -->
                <div class="card-body">
                  <h2 class="text-md-start text-center  fw-bold mt-4">Warung<span class="text-yellow">Ku</span></h2>
                  <p class="text-muted text-md-start text-center ">Selamat datang kembali Admin.</p>
                  <form method="POST" class="mt-5">
                    <div class="form-floating mb-3">
                      <input class="form-control rounded-3" id="username" name="username" type="text" placeholder="Username" autocomplete="off" required />
                      <label for="username">Username</label>
                    </div>
                    <div class="form-floating mb-3">
                      <label for="inputPassword">Password</label>
                      <!-- Add a button to toggle password visibility -->

                    </div>
                    <div class="input-group mb-3">
                      <div class="form-floating">
                        <input class="form-control border-end-0" id="inputPassword" type="password" placeholder="Password" minlength="4" name="password" required />
                        <label for="inputPassword">Password</label>
                      </div>
                      <span class="input-group-text bg-transparent border border-start-0">
                        <button type="button" id="togglePassword" class="btn btn-link text-decoration-none" onclick="togglePasswordVisibility()">
                          <i class="bi bi-eye text-dark" style="font-size: 20px;"></i>
                        </button>
                      </span>
                    </div>
                    <button type="submit" name="login" class="btn btn-dark col-12 rounded-3 mt-4">Login</button>
                  </form>

                  <?php
                  if (isset($_SESSION['login_error'])) {
                    echo "<div class='alert alert-danger mt-3 animate__animated animate__bounceIn'><p>" . $_SESSION['login_error'] . "</p></div>";
                    unset($_SESSION['login_error']);
                  }
                  ?>

                  <div class="small text-center pt-5">
                    <p class="text-muted">Belum Mempunyai Akun ? <a href="../join-subscriptions.php" class="text-decoration-none text-yellow fw-bold">Register</a></p>
                  </div>
                </div>
                <!-- akhir table form -->
                <!-- logo -->
                <!-- <div class="card-footer text-center py-3 bg-transparent">
                  <div class="text-muted small">Copyright &copy; Warungku
                    <?= date("Y") ?>
                  </div>
                </div> -->
                <!-- akhir logo -->
              </div>
            </div>
          </div>
          <div class="col-lg-7 d-flex my-5 my-md-0">
            <div class="ps-0 ps-md-5 mx-auto my-auto">
              <img src="../assets/img/Warungku.png" alt="logo.png" width="300" class="img-fluid">
            </div>
          </div>
        </div>
      </div>

    </main>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>




<?php include '../components/templates/footer.php'; ?>