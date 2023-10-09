<?php
session_start();

if (isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit;
}

$pages = 'Register' ?>

<?php require '../config/Config.php'; ?>
<?php include '../components/templates/header.php'; ?>

<?php
$subs = isset($_GET['subscriptions']) ? $_GET['subscriptions'] : '';

require_once '../config/Database.php';

require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = htmlspecialchars($_POST["username"]);
  $email = htmlspecialchars($_POST["email"]);
  $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
  $activation_key = htmlspecialchars($_POST["activation_keys"]);

  $database = new Database();
  $conn = $database->getConnection();

  // Check if the email is already registered
  $query_check_email = "SELECT id FROM users WHERE email = :email";
  $stmt_check_email = $conn->prepare($query_check_email);
  $stmt_check_email->bindParam(':email', $email);
  $stmt_check_email->execute();

  if ($stmt_check_email->rowCount() > 0) {
    $_SESSION['register_error'] = "Email sudah terdaftar.";
    echo '<script>window.location.href = "register.php";</script>';
    exit;
  }

  // Check if the activation key exists and is not used
  $query_check_activation_key = "SELECT id FROM activation_keys WHERE activation_key = :activation_key AND is_used = 0";
  $stmt_check_activation_key = $conn->prepare($query_check_activation_key);
  $stmt_check_activation_key->bindParam(':activation_key', $activation_key);
  $stmt_check_activation_key->execute();

  if ($stmt_check_activation_key->rowCount() === 0) {
    $_SESSION['register_error'] = "Activation key tidak valid atau sudah digunakan.";
    echo '<script>window.location.href = "register.php";</script>';
    exit;
  }

  $token = bin2hex(random_bytes(32));
  $query_insert_user = "INSERT INTO users (username, email, password, token) VALUES (:username, :email, :password, :token)";
  $stmt_insert_user = $conn->prepare($query_insert_user);
  $stmt_insert_user->bindParam(':username', $username);
  $stmt_insert_user->bindParam(':email', $email);
  $stmt_insert_user->bindParam(':password', $password);
  $stmt_insert_user->bindParam(':token', $token);
  $stmt_insert_user->execute();

  // Retrieve the user_id of the newly inserted user
  $user_id = $conn->lastInsertId();

  $activation_key_row = $stmt_check_activation_key->fetch(PDO::FETCH_ASSOC);
  $activation_key_id = $activation_key_row['id'];

  // Update the activation key with the user_id and mark it as used
  $query_update_activation_key = "UPDATE activation_keys SET user_id = :user_id, is_used = 1 WHERE id = :activation_key_id";
  $stmt_update_activation_key = $conn->prepare($query_update_activation_key);
  $stmt_update_activation_key->bindParam(':user_id', $user_id);
  $stmt_update_activation_key->bindParam(':activation_key_id', $activation_key_id);
  $stmt_update_activation_key->execute();

  $mail = new PHPMailer();
  $mail->isSMTP();
  $mail->Host = 'smtp.gmail.com';
  $mail->SMTPAuth = true;
  $mail->Username = 'works.davinci.teams@gmail.com';
  $mail->Password = 'bmpbwqhaldplpvnm';
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  $mail->Port = 587;

  $mail->setFrom('works.davinci.teams@gmail.com', 'Davinci Teams');
  $mail->addAddress($email, $username);
  $mail->Subject = 'Aktivasi Akun';
  $mail->Body = "
    <h3>Hi $username,</h3>
    <p>Silakan selesaikan pembuatan akun Anda dengan mengklik tombol di bawah:</p>
    <p><a href='" . BASE_URL . "auth/activation.php?token=$token' style='display: inline-block; padding: 10px 20px; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 5px;'>Aktivasi Akun</a></p>
    <br>
    <br>
    <br>
    <a href='" . BASE_URL . "auth/activation.php?token=$token'>" . BASE_URL . "auth/activation.php?token=$token</a>
  ";
  $mail->AltBody = 'Silakan salin dan tempel tautan ini di browser Anda: ' . BASE_URL . 'auth/activation.php?token=' . $token;

  if ($mail->send()) {
    $_SESSION['register_success'] = "Pendaftaran berhasil. Silakan cek email Anda untuk mengaktifkan akun.";
    echo '<script>window.location.href = "register.php";</script>';
    exit;
  } else {
    $_SESSION['register_error'] = "Terjadi kesalahan saat mendaftar. Silakan coba lagi.";
    echo '<script>window.location.href = "register.php";</script>';
    exit;
  }

  $conn = null;
}
?>




<section id="bglogin" class="bg-dark">

</section>
<div id="layoutAuthentication">
  <div id="layoutAuthentication_content">
    <div class="container mt-5">
      <div class="row justify">
        <div class="col-lg-5">
          <div class="container">
            <div class="card border-0 rounded-lg mt-lg-3 mt-md-3">

              <!-- tabel form -->
              <div class="card-body">
                <h2 class="text-md-start text-center  fw-bold mt-4">Warung<span class="text-yellow">Ku</span></h2>
                <p class="text-muted text-md-start text-center ">Kemudahan dalam bertransaksi.</p>
                <form method="POST" class="mt-5">
                  <div class="form-floating mb-3">
                    <input class=" form-control rounded-3" id="username" name="username" type="text" placeholder="username" autocomplete="off" required />
                    <label for="username">Username</label>
                  </div>
                  <div class="form-floating mb-3">
                    <input class="form-control  rounded-3" id="inputEmail" type="email" placeholder="email" minlength="4" name="email" required />
                    <label for="inputEmail">Email</label>
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

                  <div class="form-floating mb-3">
                    <input class=" form-control rounded-3" id="activation_keys" name="activation_keys" type="text" placeholder="activation keys" autocomplete="off" required />
                    <label for="activation_keys">Activation keys</label>
                  </div>
                  <button type="submit" name="login" class="btn btn-dark col-12 rounded-3 mt-4">Register</button>
                </form>
                <?php
                if (isset($_SESSION['register_error'])) {
                  echo "<div class='alert alert-danger mt-3 animate__animated animate__bounceIn'><p>" . $_SESSION['register_error'] . "</p></div>";
                  unset($_SESSION['register_error']);
                }
                if (isset($_SESSION['register_success'])) {
                  echo "<div class='alert alert-success mt-3 animate__animated animate__bounceIn'><p>" . $_SESSION['register_success'] . "</p></div>";
                  unset($_SESSION['register_success']);
                }

                ?>
                <div class="small text-center pt-5">
                  <p class="text-muted">Sudah Mempunyai Akun ? <a href="login.php" class="text-decoration-none text-yellow fw-bold">Login</a></p>
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
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Berlangganan Sekarang juga</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="">
          <img src="../assets/img/poster.png" alt="" width="100%">
        </div>
      </div>
      <div class="modal-footer">
        <a href="register.php?subscriptions=yes" type="button" class="btn btn-dark rounded-5">Berlangganan</a>
      </div>
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JavaScript -->

<script>
  // Fungsi untuk menampilkan modal saat halaman dimuat
  $(document).ready(function() {
    const modalShownKey = 'modalShown';
    const modalShownTimestampKey = 'modalShownTimestamp';

    // Ambil timestamp terakhir kali modal ditampilkan
    const lastShownTimestamp = localStorage.getItem(modalShownTimestampKey);

    // Jika tidak ada timestamp atau timestamp lebih dari 5 menit yang lalu, tampilkan modal
    if (!lastShownTimestamp || (Date.now() - lastShownTimestamp > 5 * 60 * 1000)) {
      $('#exampleModal').modal('show');

      // Setelah menampilkan modal, simpan status dan timestamp di localStorage
      localStorage.setItem(modalShownKey, 'true');
      localStorage.setItem(modalShownTimestampKey, Date.now());
    }
  });
</script>
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