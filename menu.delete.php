<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}
// Pastikan file Database.php sudah di-include atau di-require di sini.
require 'config/Config.php';
require 'config/Database.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    // Ambil ID menu yang akan dihapus dari parameter GET
    $idMenu = $_GET["id"];

    // Create a new Database instance and establish a connection
    $database = new Database();
    $conn = $database->getConnection();

    // Query SQL untuk menghapus data dari tabel 'menu' berdasarkan ID
    $sql = "DELETE FROM menu WHERE id = :id";
    $stmt = $conn->prepare($sql);

    // Bind parameter ke statement
    $stmt->bindParam(":id", $idMenu);

    // Eksekusi statement
    if ($stmt->execute()) {
        // Redirect ke halaman data menu setelah berhasil menghapus
        header("location: tambah-menu.php?msg=deleted");
    } else {
        // Redirect ke halaman data menu dengan pesan kesalahan jika terjadi masalah
        header("location: tambah-menu.php?msg=undeleted");
    }

    // Close the database connection
    $conn = null;
} else {
    // Jika akses tidak sah, tampilkan pesan kesalahan atau lakukan tindakan sesuai kebutuhan Anda
    echo "Akses tidak sah.";
}
