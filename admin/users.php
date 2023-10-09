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

$pages = 'daftar users';
?>

<?php include '../vendor/autoload.php'; ?>
<?php include '../config/Config.php'; ?>
<?php
require_once '../config/Database.php';

$database = new Database();
$conn = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['bayar'])) {
    // Process payment form submission
    $userId = $_POST['user_id'];
    $paymentKey = $_POST['payment_key'];
    $subsExpiry = $_POST['subs_expiry'];

    // Update the user's payment details and set is_paid to 1
    $sql = "UPDATE users SET payment_key = ?, subs_expiry = ?, is_paid = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$paymentKey, $subsExpiry, $userId]);
  } elseif (isset($_POST['batal'])) {
    // Process cancel payment
    $userId = $_POST['user_id'];

    // Update payment_key and subs_expiry to NULL (atau string kosong) for the selected user
    $sql = "UPDATE users SET payment_key = NULL, subs_expiry = NULL, is_paid = 0 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$userId]);
  }
}

// Fetch user data from the database
$sql = "SELECT * FROM users";
$stmt = $conn->prepare($sql);
$stmt->execute();
$userData = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'components/dashboard.header.php'; ?>

<!-- Display user data in a table -->
<div class="container my-5">
  <div class="card">
    <div class="card-header">
      <h3>Status Pemabayaran</h3>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th># </th>
              <th>Username</th>
              <th>Email</th>
              <th>Payment Status</th>
              <th>Masa Aktif</th>
              <th>Payment Key</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $counter = 1; // Inisialisasi variabel counter
            foreach ($userData as $user) : ?>
              <tr>
                <td><?php echo $counter++; ?></td>
                <td><?php echo $user['username']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo ($user['is_paid'] == 1) ? 'Lunas' : 'Belum Lunas'; ?></td>
                <?php
                $now = time(); // Waktu saat ini
                $subsExpiry = strtotime($user['subs_expiry']); // Waktu kadaluarsa langganan

                // Hitung sisa waktu masa aktif
                $interval = date_diff(date_create(), date_create($user['subs_expiry']));
                $masaAktif = $interval->format('%R%ah.  %hj.  %im.'); // Format sisa waktu

                // Tampilkan sisa waktu masa aktif di kolom "Masa Aktif"
                ?>
                <td><?php echo $masaAktif; ?></td>
                <td>
                  <?php echo isset($user['payment_key']) ? $user['payment_key'] : 'NULL'; ?>
                </td>

                <td>
                  <?php if ($user['is_paid'] == 0) : ?>
                    <!-- Button trigger payment modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#paymentModal<?php echo $user['id']; ?>">
                      Bayar
                    </button>
                  <?php else : ?>
                    <div class="d-flex">
                      <!-- Button trigger cancellation modal -->
                      <button type="button" class="btn btn-danger  me-1" data-bs-toggle="modal" data-bs-target="#cancelModal<?php echo $user['id']; ?>">
                        Batal
                      </button>
                      <!-- Link to print page -->
                      <a href="print.php?user_id=<?php echo $user['id']; ?>" class="btn btn-success">
                        <i class="bi bi-printer fw-bold"></i>
                      </a>
                    </div>
                  <?php endif; ?>
                </td>
              </tr>
              <!-- Payment Modal -->
              <div class="modal fade" id="paymentModal<?php echo $user['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <form method="POST" action="users.php">
                      <div class="modal-header">
                        <h5 class="modal-title" id="paymentModalLabel">Bayar</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <div class="mb-3">
                          <label for="payment_key" class="form-label">Payment Key</label>
                          <div class="input-group">
                            <input type="text" class="form-control" id="payment_key" name="payment_key" minlength="12" required>
                            <button class="btn btn-outline-secondary generateRandomKey" type="button">Acak</button>
                          </div>
                        </div>

                        <div class="mb-3">
                          <label for="subs_expiry" class="form-label">Subscription Expiry</label>
                          <input type="date" class="form-control" id="subs_expiry" name="subs_expiry" required>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="bayar">Bayar</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

              <!-- Cancel Payment Modal -->
              <div class="modal fade" id="cancelModal<?php echo $user['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <form method="POST" action="users.php">
                      <div class="modal-header">
                        <h5 class="modal-title" id="cancelModalLabel">Batal Pembayaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <p>Apakah Anda yakin ingin membatalkan pembayaran untuk pengguna ini?</p>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger" name="batal">Batal</button>
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
  <!-- Card Manage Users -->
  <div class="my-5">
    <div class="card">
      <div class="card-header">
        <h3>Manage Users</h3>
        <p class="text-muted d-lg-none d-block"><small>gulir untuk melihat keseluruhan tabel</small></p>
      </div>
      <div class="card-body table-responsive">
        <div class="">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>#</th>
                <th>Username</th>
                <th>Email</th>
                <th>Is Active</th>
                <th>User Rank</th>
                <th>Subscription Expiry</th>
                <th>Is Paid</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $counter2 = 1;
              foreach ($userData as $user) : ?>
                <tr>
                  <td><?php echo $counter2++; ?></td>
                  <td><?php echo $user['username']; ?></td>
                  <td><?php echo $user['email']; ?></td>
                  <td>
                    <input type="checkbox" disabled <?php echo ($user['is_active'] == 1) ? 'checked' : ''; ?>>
                  </td>
                  <td>
                    <select disabled>
                      <option <?php echo ($user['user_rank'] == 1) ? 'selected' : ''; ?> value="1">User</option>
                      <option <?php echo ($user['user_rank'] == 2) ? 'selected' : ''; ?> value="2">Admin</option>
                      <option <?php echo ($user['user_rank'] == 3) ? 'selected' : ''; ?> value="3">Super Admin</option>
                    </select>
                  </td>
                  <td>
                    <input type="text" disabled value="<?php echo $user['subs_expiry']; ?>">
                  </td>
                  <td>
                    <input type="checkbox" disabled <?php echo ($user['is_paid'] == 1) ? 'checked' : ''; ?>>
                  </td>
                  <td>
                    <div class="d-flex">
                      <!-- Button trigger edit user modal -->
                      <button type="button" class="btn btn-primary me-1" data-bs-toggle="modal" data-bs-target="#editUserModal<?php echo $user['id']; ?>">
                        <i class="bi bi-pencil-square fw-bold"></i>
                      </button>
                      <!-- Button trigger delete user modal -->
                      <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal<?php echo $user['id']; ?>">
                        <i class="bi bi-trash3 fw-bold"></i>
                      </button>
                    </div>
                  </td>
                </tr>
                <!-- Edit User Modal -->
                <!-- Edit User Modal -->
                <div class="modal fade" id="editUserModal<?php echo $user['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <form method="POST" action="update-user.php">
                        <div class="modal-header">
                          <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">

                          <!-- Input field for editing username -->
                          <div class="mb-3">
                            <label for="edit_username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="edit_username" name="edit_username" value="<?php echo $user['username']; ?>" required>
                          </div>

                          <!-- Input field for editing email -->
                          <div class="mb-3">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="edit_email" value="<?php echo $user['email']; ?>" required>
                          </div>

                          <!-- Input field for editing user rank -->
                          <div class="mb-3">
                            <label for="edit_user_rank" class="form-label">User Rank</label>
                            <select class="form-control" id="edit_user_rank" name="edit_user_rank">
                              <option value="1" <?php if ($user['user_rank'] == 1) echo 'selected'; ?>>User Biasa</option>
                              <option value="2" <?php if ($user['user_rank'] == 2) echo 'selected'; ?>>Admin</option>
                              <option value="3" <?php if ($user['user_rank'] == 3) echo 'selected'; ?>>Super Admin</option>
                            </select>
                          </div>

                          <!-- Input field for editing subscription expiry -->
                          <div class="mb-3">
                            <label for="edit_subs_expiry" class="form-label">Subscription Expiry</label>
                            <input type="date" class="form-control" id="edit_subs_expiry" name="edit_subs_expiry" value="<?php echo $user['subs_expiry']; ?>">
                          </div>

                          <!-- Input field for editing is active status -->
                          <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="edit_is_active" name="edit_is_active" <?php if ($user['is_active'] == 1) echo 'checked'; ?>>
                            <label class="form-check-label" for="edit_is_active">Aktif</label>
                          </div>

                          <!-- Input field for editing is paid status -->
                          <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="edit_is_paid" name="edit_is_paid" <?php if ($user['is_paid'] == 1) echo 'checked'; ?>>
                            <label class="form-check-label" for="edit_is_paid">Lunas</label>
                          </div>

                          <!-- Add more input fields for editing user data as needed -->
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>


                <!-- Delete User Modal -->
                <div class="modal fade" id="deleteUserModal<?php echo $user['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <form method="POST" action="delete-user.php">
                        <div class="modal-header">
                          <h5 class="modal-title" id="deleteUserModalLabel">Delete User</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                          <p>Apakah anda yakin untuk menghapus user ini?</p>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-danger">Delete</button>
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
  </div>


</div>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var generateRandomKeyButtons = document.querySelectorAll('.generateRandomKey');

    generateRandomKeyButtons.forEach(function(button) {
      button.addEventListener('click', function() {
        var randomKey = generateRandomKey(12); // Panggil fungsi generateRandomKey dengan panjang 10 karakter
        var paymentKeyInput = button.closest('.modal').querySelector('.form-control[name="payment_key"]');
        paymentKeyInput.value = randomKey.toUpperCase(); // Konversi ke huruf besar dan set nilainya pada input payment_key
      });
    });

    // Fungsi untuk menghasilkan teks acak dengan panjang tertentu
    function generateRandomKey(length) {
      var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'; // Karakter yang dapat digunakan
      var randomKey = '';
      for (var i = 0; i < length; i++) {
        var randomIndex = Math.floor(Math.random() * characters.length);
        randomKey += characters.charAt(randomIndex);
      }
      return randomKey;
    }
  });
</script>




<?php include 'components/dashboard.footer.php'; ?>