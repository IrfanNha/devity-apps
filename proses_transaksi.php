<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: auth/login.php");
  exit;
}

require 'config/Database.php';
$database = new Database();
$conn = $database->getConnection();

// Ambil data transaksi dari formulir
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Data yang diambil dari formulir
  $itemQuantities = $_POST['quantity'];
  $total = $_POST['total'];

  // User ID dari sesi
  $userId = $_SESSION['user_id'];

  // Mulai transaksi
  try {
    $conn->beginTransaction();

    $itemDetails = array(); // Untuk menyimpan rincian item dalam bentuk array

    // Loop melalui itemQuantities untuk memproses setiap item yang dibeli
    foreach ($itemQuantities as $itemId => $quantity) {
      if ($quantity > 0) {
        // Ambil harga item dari database
        $query = "SELECT price FROM items WHERE id = :itemId";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
          $price = $row['price'];

          // Simpan rincian item dalam bentuk array
          $itemDetails[] = array(
            'item_id' => $itemId,
            'quantity' => $quantity,
            'price' => $price
          );

          // Simpan transaksi ke tabel laporan_kasir
          $query = "INSERT INTO laporan_kasir (laporan_keuangan_id, item_details, harga) VALUES (:laporan_keuangan_id, :item_details, :harga)";
          $stmt = $conn->prepare($query);
          $stmt->bindParam(':laporan_keuangan_id', $laporanKeuanganId, PDO::PARAM_INT);
          $stmt->bindParam(':item_details', json_encode($itemDetails), PDO::PARAM_STR);
          $stmt->bindParam(':harga', $price, PDO::PARAM_INT);
          $stmt->execute();

          // Update stok item jika perlu
          // Misalnya, Anda memiliki tabel items_stock yang mencatat stok tiap item
          // Anda dapat mengurangi stok berdasarkan quantity yang dibeli
          // Contoh: UPDATE items_stock SET stock = stock - :quantity WHERE item_id = :itemId

          // Lakukan operasi lain yang diperlukan untuk transaksi
        }
      }
    }

    // Simpan transaksi ke tabel laporan_keuangan
    $query = "INSERT INTO laporan_keuangan (user_id, total) VALUES (:userId, :total)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':total', $total, PDO::PARAM_INT);
    $stmt->execute();

    // Selesaikan transaksi
    $conn->commit();

    // Redirect ke halaman sukses atau tampilkan pesan sukses
    echo '<script>alert("Transaksi berhasil!"); window.location.href = "success.php";</script>';
    exit;
  } catch (PDOException $e) {
    // Rollback transaksi jika terjadi kesalahan
    $conn->rollBack();

    // Handle kesalahan (misalnya, tampilkan pesan kesalahan)
    echo "Terjadi kesalahan: " . $e->getMessage();
  }
}
