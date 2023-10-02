<?php

class Database
{
  private $host = "localhost"; // Ganti dengan nama host database Anda
  private $db_name = "warungku"; // Ganti dengan nama database Anda
  private $username = "root"; // Ganti dengan nama pengguna database Anda
  private $password = ""; // Ganti dengan kata sandi database Anda
  public $conn;

  public function getConnection()
  {
    $this->conn = null;

    try {
      $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
      $this->conn->exec("set names utf8");
    } catch (PDOException $exception) {
      echo "Koneksi database error: " . $exception->getMessage();
    }

    return $this->conn;
  }

  public function borok()
  {
  }
}
