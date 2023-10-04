<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}
$pages = 'Menu';
?>

<?php require 'vendor/autoload.php'; ?>
<?php require 'config/Config.php'; ?>
<?php require 'config/Database.php'; ?>

<?php include 'components/templates/header.php' ?>
<script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
<style>
    .menu-container {
        max-width: 800px;
        /* Adjust the width as needed */
    }

    .cashier-container {
        flex: 1;
        padding: 20px;
    }

    .notification-badge {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 40px;
        height: 40px;
        background-color: black;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 18px;
        cursor: pointer;
    }
</style>
<?php include 'components/partials/dashboard.header.php' ?>

<!-- Content -->
<div id="layoutAuthentication">
    <div id="layoutAuthentication_content">
        <main>
            <div class="container mt-5">
                <div class="row">
                    <!-- Menu Section -->
                    <div class="col-md-6 menu-container">
                        <?php
                        // Create a new Database instance and establish a connection
                        $database = new Database();
                        $conn = $database->getConnection();

                        // Mengambil user_id dari sesi
                        $userId = $_SESSION["user_id"];

                        // Modifikasi kueri SQL untuk mengambil data menu sesuai dengan user_id
                        $sql = "SELECT * FROM menu WHERE user_id = :user_id";
                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(":user_id", $userId, PDO::PARAM_INT);
                        $stmt->execute();

                        // Assign the result of the executed statement to $result
                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        // Loop through the menu items and display them
                        if (count($result) > 0) {
                            foreach ($result as $row) {
                                echo '<div class="col-md-3 mt-2">';
                                echo '<div class="card">';
                                echo '<div class="card-body">';
                                echo '<h5 class="card-title">' . $row['nama'] . '</h5>';
                                echo '<p class="card-text">Harga: Rp. ' . $row['harga'] . '</p>';
                                echo '<button class="btn btn-primary" onclick="tambahItem(\'' . $row['nama'] . '\', ' . $row['harga'] . ')">Tambah ke Keranjang</button>';
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                            }
                        } else {
                            echo "No menu items available.";
                        }

                        // Close the database connection
                        $conn = null;
                        ?>
                    </div>

                    <!-- Cashier Section -->
                    <div class="col-md-6 cashier-container">
                        <!-- Move your cashier content here -->
                        <!-- You can customize the layout as needed -->
                        <div class="notification-badge">
                            <i class="fa-solid fa-cart-shopping" data-bs-toggle="modal" href="#mdlProfileUser"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cartItemCount">
                                0
                                <span class="visually-hidden">unread messages</span>
                            </span>
                        </div>
                        <!-- ... (previous modal for cashier) -->
                    </div>
                </div>
            </div>
        </main>
    </div>
    <!-- ... (previous modals and scripts) -->
    <div class="modal" tabindex="-1" id="mdlProfileUser">
        <!-- ... (previous modal content) -->
    </div>
    <!-- Add this code within your HTML file -->
    <div class="modal" tabindex="-1" id="modalKonfirmasiPesanan">
        <!-- ... (previous modal content) -->
    </div>
</div>
</div>

<!-- Additional JavaScript Code -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<script>
    // ... (previous JavaScript code)
</script>

<!-- Your Appended Code -->
<?php include 'components/partials/dashboard.footer.php' ?>
<?php include 'components/templates/footer.php' ?>
<?php
// ... (rest of your appended code)
?>