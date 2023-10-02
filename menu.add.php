<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}
// Pastikan file Database.php sudah di-include atau di-require di sini.
require 'config/Config.php';
require 'config/Database.php';

// Inisialisasi variabel notifikasi
$notification = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data yang dikirimkan melalui form
    $namaMakanan = $_POST["namaMakanan"];
    $hargaMakanan = $_POST["hargaMakanan"];

    // Validasi data (Anda dapat menambahkan validasi lain sesuai kebutuhan)
    if (empty($namaMakanan) || empty($hargaMakanan)) {
        $notification = "Nama makanan dan harga makanan harus diisi.";
    } else {
        // Create a new Database instance and establish a connection
        $database = new Database();
        $conn = $database->getConnection();

        // Ambil user_id dari session
        $user_id = $_SESSION['user_id'];

        // Query SQL untuk menyisipkan data ke dalam tabel 'menu' dengan menyertakan user_id
        $sql = "INSERT INTO menu (nama, harga, user_id) VALUES (:nama, :harga, :user_id)";
        $stmt = $conn->prepare($sql);

        // Bind parameter ke statement
        $stmt->bindParam(":nama", $namaMakanan);
        $stmt->bindParam(":harga", $hargaMakanan);
        $stmt->bindParam(":user_id", $user_id);

        // Eksekusi statement
        if ($stmt->execute()) {
            header("location: tambah-menu.php?msg=success");
        } else {
            header("location: tambah-menu.php?msg=unsuccess");
        }

        // Close the database connection
        $conn = null;
    }
} else {
    // Jika halaman ini diakses secara langsung tanpa POST request, tampilkan pesan kesalahan.
    $notification = "Akses tidak sah.";
}
