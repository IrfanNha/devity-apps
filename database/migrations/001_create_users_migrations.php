<?php
include '../../config/Database.php';

class CreateUsersTable
{
  public function up()
  {
    $database = new Database();

    $koneksi = $database->getConnection();

    $sql = "CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            active BOOLEAN DEFAULT 1,
            activation_key VARCHAR(255),
            payment_key VARCHAR(255),
            subs_expiry DATE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

    if ($koneksi->query($sql) === TRUE) {
      echo "Tabel users berhasil dibuat.";
    } else {
      echo "Error: ";
    }
  }

  public function down()
  {
    $koneksi = $this->getDatabaseConnection();

    $sql = "DROP TABLE users";

    if ($koneksi->query($sql) === TRUE) {
      echo "Tabel users berhasil dihapus.";
    } else {
      echo "Error: ";
    }
  }

  private function getDatabaseConnection()
  {
    // Gantilah dengan cara Anda mengambil koneksi dari $databaseConnection
    $databaseConnection = new Database();
    return $databaseConnection->getConnection();
  }
}

// Eksekusi migrasi
$migration = new CreateUsersTable();
$migration->up();
