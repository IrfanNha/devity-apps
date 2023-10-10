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

// PHP code to fetch data with the same order_id
if (isset($_GET['order_id'])) {
    $orderID = $_GET['order_id'];
    $query = "SELECT * FROM `riwayat_pembelian` WHERE `order_id` = :order_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':order_id', $orderID, PDO::PARAM_STR);
    $stmt->execute();
    $orderData = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
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
                                <th scope="col">Order ID</th>
                                <th scope="col">Waktu</th>
                                <th scope="col">Operasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Create an associative array to store unique order_id values
                            $uniqueOrderIds = array();

                            // Iterate through the results and display them in the table
                            $count = 1;
                            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                $orderId = $row['order_id'];

                                // Check if this order_id is already displayed
                                if (!isset($uniqueOrderIds[$orderId])) {
                                    echo "<tr>";
                                    echo "<th scope='row'>$count</th>";
                                    echo "<td>{$orderId}</td>";
                                    echo "<td>{$row['tgl_pembayaran']}</td>";
                                    echo '<td><button type="button" class="btn btn-warning btn-sm me-1" data-bs-toggle="modal" data-bs-target="#detailMenuModal" data-order_id="' . $row['order_id'] . '" data-item_name="' . $row['item_name'] . '" data-quantity="' . $row['quantity'] . '">Detail</button></td>';
                                    echo "</tr>";

                                    // Mark this order_id as displayed
                                    $uniqueOrderIds[$orderId] = true;

                                    $count++;
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Modal for displaying transaction details -->
<div class="modal fade" id="detailMenuModal" tabindex="-1" aria-labelledby="detailMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailMenuModalLabel">Transaction Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Content to display transaction details goes here -->
                <p><strong>Order ID:</strong> <span id="modalOrderIDText"></span></p>
                <p><strong>Item Name:</strong> <span id="modalItemName"></span></p>
                <p><strong>Quantity:</strong> <span id="modalQuantity"></span></p>
                <!-- Display related data here -->
                <div id="modalOrderData"></div>
                <!-- Add more fields as needed -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>

<script>
    // JavaScript to populate the modal with data when the button is clicked
    $('#detailMenuModal').on('show.bs.modal', function (event) {
        console.log('Modal is about to show.'); // Debug line
        var button = $(event.relatedTarget); // Button that triggered the modal
        var orderID = button.data('order_id');
        var itemName = button.data('item_name');
        var quantity = button.data('quantity');

        // Update the modal's content with the data
        $('#modalOrderIDText').text(orderID);
        $('#modalItemName').text(itemName);
        $('#modalQuantity').text(quantity);

        // Check if orderData exists
        if (typeof orderData !== 'undefined' && orderData.length > 0) {
            // Clear previous data (if any)
            $('#modalOrderData').empty();

            // Iterate through orderData and display it in the modal
            $.each(orderData, function (index, data) {
                $('#modalOrderData').append('<p><strong>Data ' + (index + 1) + ':</strong> ' + data.item_name + ', ' + data.quantity + '</p>');
            });
        }
        // Add more fields as needed
    });
</script>

<?php
// No need to close the PDO connection here

include 'components/partials/dashboard.footer.php';
include 'components/templates/footer.php';
?>
