<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include the Database class and create a database connection
    require 'config/Database.php';
    $database = new Database();
    $conn = $database->getConnection();

    // Retrieve the user ID from the session
    $user_id = $_SESSION['user_id'];

    // Retrieve the order data from the POST request
    $orderData = json_decode(file_get_contents("php://input"));

    // Insert the order data into the database
    $insertOrderQuery = "INSERT INTO orders (user_id, total_price) VALUES (:user_id, :total_price)";
    $stmt = $conn->prepare($insertOrderQuery);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':total_price', $orderData->totalHarga);

    if ($stmt->execute()) {
        $orderId = $conn->lastInsertId(); // Get the ID of the inserted order

        // Insert individual order items into another table
        foreach ($orderData->keranjang as $item) {
            $insertOrderItemQuery = "INSERT INTO order_items (order_id, item_name, item_price, quantity) VALUES (:order_id, :item_name, :item_price, :quantity)";
            $stmtOrderItem = $conn->prepare($insertOrderItemQuery);
            $stmtOrderItem->bindParam(':order_id', $orderId);
            $stmtOrderItem->bindParam(':item_name', $item->nama);
            $stmtOrderItem->bindParam(':item_price', $item->harga);
            $stmtOrderItem->bindParam(':quantity', $item->quantity);
            $stmtOrderItem->execute();
        }

        // Return a success response
        echo json_encode(["status" => "success", "message" => "Order saved successfully"]);
    } else {
        // Return an error response
        echo json_encode(["status" => "error", "message" => "Failed to save order"]);
    }
} else {
    // Return an error response if the request method is not POST
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
