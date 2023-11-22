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
$user_id = $_SESSION['user_id'];
// // Check if the connection was successful
// if (!$conn) {
//     die("Database connection failed: " . $db->getError());
// }

// Execute SQL query to retrieve data
$query = "SELECT * FROM `riwayat_pembelian` WHERE user_id = $user_id";
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
                    <div class="dropdown">
                        <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="laporanDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="background-color: transparent; border: none; color: black;">
                            <span class="h5 my-2"><i class="fa-solid fa-list"></i> <span id="laporanDropdownText">Laporan Keuangan</span></span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="laporanDropdown">
                            <li><a class="dropdown-item" href="#" id="laporanKeuangan">Laporan Keuangan</a></li>
                            <li><a class="dropdown-item" href="#" id="laporanMenu">Laporan Menu</a></li>
                            <li><a class="dropdown-item" href="#" id="laporanModal">Laporan Modal</a></li>
                        </ul>
                    </div>
                    <div class="dropdown" style="margin-top: -30px;">
                        <button class="btn btn-sm btn-primary dropdown-toggle float-end me-1" type="button" data-bs-toggle="dropdown">Cetak</button>
                        <ul class="dropdown-menu">
                            <li><button type="button" onclick="printDoc('hari')" class="dropdown-item"><i class="fa-solid fa-magnifying-glass"></i> Per Hari</button></li>
                            <li><button type="button" onclick="printDoc('minggu')" class="dropdown-item"><i class="fa-solid fa-magnifying-glass"></i> Per Minggu</button></li>
                            <li><button type="button" onclick="printDoc('bulan')" class="dropdown-item"><i class="fa-solid fa-magnifying-glass"></i> Per Bulan</button></li>
                            <li><button type="button" onclick="printDoc('tahun')" class="dropdown-item"><i class="fa-solid fa-magnifying-glass"></i> Per Tahun</button></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body" id="laporanKeuanganSection">
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
                <!-- Additional section for Laporan Menu -->
                <div class="card-body" id="laporanMenuSection" style="display: none;">
                    <table class="table table-hover" id="datatablesSimple">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Order ID</th>
                                <th scope="col">Menu</th>
                                <th scope="col">Jumlah</th>
                                <th scope="col">Waktu</th>
                                <th scope="col">Operasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Query to fetch data from order_items table
                            $queryMenu = "SELECT * FROM `order_items` WHERE user_id = $user_id";
                            $resultMenu = $conn->query($queryMenu);

                            // Counter for numbering rows
                            $countMenu = 1;

                            // Iterate through the results and display them in the table
                            while ($rowMenu = $resultMenu->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>";
                                echo "<th scope='row'>$countMenu</th>";
                                echo "<td>{$rowMenu['order_id']}</td>";
                                echo "<td>{$rowMenu['item_name']}</td>";
                                echo "<td>{$rowMenu['quantity']}</td>";
                                echo "<td>{$rowMenu['created_at']}</td>";
                                echo '<td><button type="button" class="btn btn-warning btn-sm me-1" data-bs-toggle="modal" data-bs-target="#detailMenuModal" data-order_id="' . $rowMenu['order_id'] . '" data-item_name="' . $rowMenu['item_name'] . '" data-quantity="' . $rowMenu['quantity'] . '">Detail</button></td>';
                                echo "</tr>";

                                // Increment the counter
                                $countMenu++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <!-- Additional section for Laporan Modal -->
                <div class="card-body" id="laporanModalSection" style="display: none;">
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
<!-- Ensure jQuery is loaded before the script -->
<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>

<script>
    function updateDropdownText(text) {
        // Set the text of the dropdown button
        $("#laporanDropdownText").text(text);
    }

    $(document).ready(function() {
        $("#laporanKeuangan").click(function() {
            // Update button text and perform other actions
            updateDropdownText('Laporan Keuangan');
            // Hide other sections and show Laporan Menu section
            $("#laporanKeuanganSection").show();
            $("#laporanMenuSection").hide();
            $("#laporanModalSection").hide();
        });

        $("#laporanMenu").click(function() {
            // Update button text and perform other actions
            updateDropdownText('Laporan Menu');
            // Hide other sections and show Laporan Menu section
            $("#laporanKeuanganSection").hide();
            $("#laporanMenuSection").show();
            $("#laporanModalSection").hide();
        });

        $("#laporanModal").click(function() {
            // Update button text and perform other actions
            updateDropdownText('Laporan Modal');
            // Hide other sections and show Laporan Modal section
            $("#laporanKeuanganSection").hide();
            $("#laporanMenuSection").hide();
            $("#laporanModalSection").show();
        });
    });

    // JavaScript to populate the modal with data when the button is clicked
    $('#detailMenuModal').on('show.bs.modal', function(event) {
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
            $.each(orderData, function(index, data) {
                $('#modalOrderData').append('<p><strong>Data ' + (index + 1) + ':</strong> ' + data.item_name + ', ' + data.quantity + '</p>');
            });
        }
        // Add more fields as needed
    });

    function printDoc(option) {
        console.log('Printing option selected:', option);

        // Get the content you want to print
        var contentToPrint = document.getElementById('layoutSidenav_content');

        // Create a new window for printing
        var printWindow = window.open('', '_blank');

        // Write the content into the new window
        printWindow.document.write('<html><head><title>Print</title></head><body>');
        printWindow.document.write('<h1>Printing Option: ' + option + '</h1>');
        printWindow.document.write(contentToPrint.innerHTML);
        printWindow.document.write('</body></html>');

        // Close the document
        printWindow.document.close();

        // Wait for the content to be loaded before printing
        printWindow.onload = function() {
            printWindow.print();
            printWindow.onafterprint = function() {
                printWindow.close();
            };
        };
    }
</script>


<?php
// No need to close the PDO connection here

include 'components/partials/dashboard.footer.php';
include 'components/templates/footer.php';
?>