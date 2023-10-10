<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

$pages = 'Riwayat';

// Include necessary files
require 'vendor/autoload.php';
require 'config/Config.php';
require 'config/Database.php';
include 'components/templates/header.php';
include 'components/partials/dashboard.header.php';

// Establish a database connection
$db = new Database();
$conn = $db->getConnection();

// Check if the connection was successful
if (!$conn) {
    die("Database connection failed: " . $db->getError());
}

// Execute SQL query to retrieve data
$query = "SELECT * FROM `riwayat_pembelian`";
$result = $conn->query($query);

?>

<!-- Content -->
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="card mb-2">
                <div class="card-header">
                    <span class="h5 my-2"><i class="fa-solid fa-list"></i> Laporan Keuangan</span>

                    <div class="dropdown" style="margin-top: -30px;">
                        <button class="btn btn-sm btn-primary dropdown-toggle float-end me-1" type="button"
                            data-bs-toggle="dropdown">Cetak</button>
                        <ul class="dropdown-menu">
                            <li><button type="button" onclick="printDoc()" class="dropdown-item"><i
                                        class="fa-solid fa-magnifying-glass"></i> Laporan Keuangan</button></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-hover" id="datatablesSimple">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Item</th>
                                <th scope="col">Total Harga</th>
                                <th scope="col">Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Iterate through the results and display them in the table
                            $count = 1;
                            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>";
                                echo "<th scope='row'>$count</th>";
                                echo "<td>{$row['item_name']}</td>";
                                echo "<td>Rp " . number_format($row['total_price'], 2) . "</td>"; // Format total_price as currency
                                echo "<td>{$row['tgl_pembayaran']}</td>";
                                echo "</tr>";
                                $count++;
                            }
                            ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </main>
</div>

<!-- Content -->
<?php
// No need to close the PDO connection here

include 'components/partials/dashboard.footer.php';
include 'components/templates/footer.php';
?>