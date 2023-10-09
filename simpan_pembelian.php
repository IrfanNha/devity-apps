<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // Jika user belum login, mungkin perlu redirect atau memberikan respons khusus
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data order dari request
    $orderData = json_decode($_POST['orderData']);

    // Validasi data order
    if ($orderData === null || !isset($orderData->keranjang) || !isset($orderData->totalHarga)) {
        echo json_encode(["status" => "error", "message" => "Invalid order data"]);
        exit;
    }

    // Mengambil user_id dari sesi
    $userId = $_SESSION["user_id"];

    // Melakukan koneksi ke database
    require 'config/Database.php';
    $database = new Database();
    $conn = $database->getConnection();

    try {
        // Memulai transaksi
        $conn->beginTransaction();

        // Insert data pembelian ke tabel orders
        $insertOrderQuery = "INSERT INTO orders (user_id, total_price) VALUES (:user_id, :total_price)";
        $stmtOrder = $conn->prepare($insertOrderQuery);
        $stmtOrder->bindParam(':user_id', $userId);
        $stmtOrder->bindParam(':total_price', $orderData->totalHarga);
        $stmtOrder->execute();

        // Mengambil ID order yang baru saja dibuat
        $orderId = $conn->lastInsertId();

        // Insert data item pembelian ke tabel order_items
        foreach ($orderData->keranjang as $item) {
            $insertOrderItemQuery = "INSERT INTO order_items (order_id, item_name, item_price, quantity) VALUES (:order_id, :item_name, :item_price, :quantity)";
            $stmtOrderItem = $conn->prepare($insertOrderItemQuery);
            $stmtOrderItem->bindParam(':order_id', $orderId);
            $stmtOrderItem->bindParam(':item_name', $item->nama);
            $stmtOrderItem->bindParam(':item_price', $item->harga);
            $stmtOrderItem->bindParam(':quantity', $item->quantity);
            $stmtOrderItem->execute();
        }

        // Insert data pembelian ke tabel riwayat_pembelian
        foreach ($orderData->keranjang as $item) {
            $insertRiwayatQuery = "INSERT INTO riwayat_pembelian (user_id, item_name, quantity, total_price, order_id) VALUES (:user_id, :item_name, :quantity, :total_price, :order_id)";
            $stmtRiwayat = $conn->prepare($insertRiwayatQuery);
            $stmtRiwayat->bindParam(':user_id', $userId);
            $stmtRiwayat->bindParam(':item_name', $item->nama);
            $stmtRiwayat->bindParam(':quantity', $item->quantity);
            $stmtRiwayat->bindParam(':total_price', $item->subtotal); // Jika Anda memiliki total harga per item, gunakan properti yang sesuai
            $stmtRiwayat->bindParam(':order_id', $orderId);
            $stmtRiwayat->execute();
        }

        // Commit transaksi jika semuanya sukses
        $conn->commit();

        // Return a success response
        $response = ["status" => "success", "message" => "Order saved successfully"];
        echo json_encode($response);

        //         // Add JavaScript code to close the modal and refresh the menu.php page
        //         echo <<<HTML
        // <script>
        //     // Assuming you have a modal with the ID 'mdlProfileUser'. Adjust it based on your actual modal ID.
        //     $('#mdlProfileUser').modal('hide');

        //     // Reload the menu.php page after a short delay (adjust the delay as needed)
        //     setTimeout(function () {
        //         window.location.href = 'menu.php';
        //     }, 1000);
        // </script>
        // HTML;
    } catch (Exception $e) {
        // Rollback transaksi jika terjadi kesalahan
        $conn->rollBack();

        // Return an error response
        echo json_encode(["status" => "error", "message" => "Failed to save order: " . $e->getMessage()]);
    } finally {
        // Menutup koneksi database
        $conn = null;
    }
} else {
    // Return an error response if the request method is not POST
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
