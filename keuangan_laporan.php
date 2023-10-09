<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

$pages = 'laporan keuangan';
?>

<?php require 'vendor/autoload.php'; ?>
<?php require 'config/Config.php'; ?>
<?php include 'components/templates/header.php'; ?>
<?php include 'components/partials/dashboard.header.php'; ?>

<?php include 'cashier_logic.php'; ?>

<!-- Content -->
<div id="layoutAuthentication">
    <div id="layoutAuthentication_content">
        <main>
            <!-- Div Table -->
            <div class="div-table mt-4">
                <!-- Laporan Kasir -->
                <div class="div-table-row">
                    <div class="div-table-cell">
                        <h3>Laporan Kasir</h3>
                        <?php
                        $cashierData = displayCashierReport('daily'); // Assign the result to $cashierData
                        // Display data in a table format
                        if (!empty($cashierData)) {
                        ?>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Total Sales</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Output your data dynamically -->
                                    <?php foreach ($cashierData as $row) : ?>
                                        <tr>
                                            <td><?php echo $row['transaction_date']; ?></td>
                                            <td><?php echo $row['total_sales']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php
                        } else {
                            echo 'No data available.';
                        }
                        ?>
                    </div>
                </div>


                <!-- Laporan Menu Terjual -->
                <div class="div-table-row">
                    <div class="div-table-cell">
                        <h3>Laporan Menu Terjual</h3>
                        <!-- Include your logic for Menu Terjual here -->
                    </div>
                </div>

                <!-- Laporan Rekap -->
                <div class="div-table-row">
                    <div class="div-table-cell">
                        <h3>Laporan Rekap</h3>
                        <!-- Include your logic for Rekap here -->
                    </div>
                </div>
            </div>

            <!-- Grafik Laporan Keuangan -->
            <div class="chart-section card mt-4">
                <div class="card-header">
                    Grafik Laporan Keuangan
                </div>
                <div class="card-body">
                    <!-- Include your logic to generate and display financial report charts/graphs here -->
                </div>
            </div>
        </main>
    </div>
</div>

<?php include 'components/partials/dashboard.footer.php'; ?>
<?php include 'components/templates/footer.php'; ?>