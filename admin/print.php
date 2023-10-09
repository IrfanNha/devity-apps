<?php
session_start();

// Check if the user is not logged in or user_rank is not 3
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

// Check if user_rank is not 3
if ($_SESSION['user_rank'] !== "3") {
  header("Location: 403.php");
  exit;
}

$pages = "print";
// Pastikan Anda telah mengimpor library TCPDF
require_once '../vendor/autoload.php';
require_once '../config/Database.php';

// Ambil ID pengguna dari parameter URL
if (isset($_GET['user_id'])) {
  $userId = $_GET['user_id'];

  try {
    // Gunakan koneksi database yang telah Anda berikan
    $database = new Database();
    $conn = $database->getConnection();

    // Query untuk mengambil data pengguna berdasarkan ID
    $sql = "SELECT username, payment_key, subs_expiry FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
      // Buat objek PDF
      $pdf = new FPDF();
      $pdf->AddPage();
      $pdf->SetFont('Arial', '', 14);

      $pdf->Cell(0, 10, 'Invoice Pembayaran', 0, 1, 'C');
      $pdf->Ln();
      $pdf->SetLineWidth(0.5); // Set lebar garis
      $pdf->Line(10, $pdf->GetY(), $pdf->getPageWidth() - 10, $pdf->GetY()); // Gambar garis horizontal
      $pdf->Ln();

      $pdf->Cell(30, 10, 'Username                :  ' . $user['username'], 0, 1);
      $pdf->Cell(0, 10, 'Payment Key           :  ' . $user['payment_key'], 0, 1);
      $pdf->Cell(0, 10, 'Subscription Expiry  :  ' . $user['subs_expiry'], 0, 1);
      $pdf->Ln();
      $pdf->SetLineWidth(0.5); // Set lebar garis
      $pdf->Line(10, $pdf->GetY(), $pdf->getPageWidth() - 10, $pdf->GetY()); // Gambar garis horizontal
      $pdf->Ln();
      $pdf->Cell(0, 10, 'Pada      : ' . date('l, d F Y'), 0, 1, 'C');

      // Output konten PDF
      header('Content-Type: application/pdf');
      header('Content-Disposition: inline; filename="invoice.pdf"');
      echo $pdf->Output('I');
    } else {
      // Jika pengguna tidak ditemukan
      die("Pengguna tidak ditemukan.");
    }

    // Tutup koneksi
    $database = null;
  } catch (PDOException $e) {
    die("Koneksi ke database gagal: " . $e->getMessage());
  }
} else {
  // Jika parameter "user_id" tidak ada dalam URL
  die("Parameter 'user_id' tidak ditemukan.");
}

?>
<?php require '../config/Config.php'; ?>
<?php include '../components/templates/footer.php'; ?>