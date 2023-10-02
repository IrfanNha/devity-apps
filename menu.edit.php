<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}
// Pastikan file Database.php sudah di-include atau di-require di sini.
require 'config/Config.php';
require 'config/Database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data yang dikirimkan melalui form
    $idMenu = $_POST["id"];
    $namaMakanan = $_POST["nama"];
    $hargaMakanan = $_POST["harga"];

    // Validasi data (Anda dapat menambahkan validasi lain sesuai kebutuhan)
    if (empty($namaMakanan) || empty($hargaMakanan)) {
        echo "Nama makanan dan harga makanan harus diisi.";
    } else {
        // Create a new Database instance and establish a connection
        $database = new Database();
        $conn = $database->getConnection();

        // Query SQL untuk mengupdate data dalam tabel 'menu' berdasarkan ID
        $sql = "UPDATE menu SET nama = :nama, harga = :harga WHERE id = :id";
        $stmt = $conn->prepare($sql);

        // Bind parameter ke statement
        $stmt->bindParam(":id", $idMenu);
        $stmt->bindParam(":nama", $namaMakanan);
        $stmt->bindParam(":harga", $hargaMakanan);

        // Eksekusi statement
        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "error";
        }

        // Close the database connection
        $conn = null;
    }
} else {
    // Jika halaman ini diakses secara langsung tanpa POST request, tampilkan pesan kesalahan.
    echo "Akses tidak sah.";
}
