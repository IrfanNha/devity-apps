<?php
// Include necessary files
require 'vendor/autoload.php';
require 'config/Config.php';
require 'config/Database.php';

// Check if order_id is provided
if (isset($_GET['order_id'])) {
    // Sanitize the input
    $orderId = intval($_GET['order_id']);

    // Establish a database connection
    $db = new Database();
    $conn = $db->getConnection();

    // Check if the connection was successful
    if (!$conn) {
        die("Database connection failed: " . $db->getError());
    }

    // Execute SQL query to retrieve data based on the order_id
    $query = "SELECT * FROM `riwayat_pembelian` WHERE `order_id` = :order_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch and display the data
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($data) {
        // Output the data in the desired format (e.g., HTML)
        echo "<h4>Order ID: {$data['order_id']}</h4>";
        echo "<p>Item Name: {$data['item_name']}</p>";
        echo "<p>Total Price: {$data['total_price']}</p>";
        echo "<p>Waktu: {$data['tgl_pembayaran']}</p>";
    } else {
        echo "Data not found for order ID: $orderId";
    }

    // Close the PDO connection
    $conn = null;
} else {
    echo "Invalid request";
}
?>
