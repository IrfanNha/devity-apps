<?php
include '../../config/Database.php';

class CreateLoginAttemptsTable
{
  public function up()
  {
    $koneksi = $this->getDatabaseConnection();

    $sql = "CREATE TABLE login_attempts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            ip_address VARCHAR(255) NOT NULL,
            success TINYINT(1) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

    if ($koneksi->query($sql) === TRUE) {
      echo "Tabel login_attempts berhasil dibuat.";
    } else {
      echo "Error: " . $koneksi->error;
    }
  }

  public function down()
  {
    $koneksi = $this->getDatabaseConnection();

    $sql = "DROP TABLE login_attempts";

    if ($koneksi->query($sql) === TRUE) {
      echo "Tabel login_attempts berhasil dihapus.";
    } else {
      echo "Error: " . $koneksi->error;
    }
  }

  private function getDatabaseConnection()
  {
    // Gantilah dengan cara Anda mengambil koneksi dari $databaseConnection
    $databaseConnection = new DatabaseConnection();
    return $databaseConnection->getConnection();
  }
}

// Eksekusi migrasi
$migration = new CreateLoginAttemptsTable();

$migration->up();
