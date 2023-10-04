<?php
// Get the total and cash amount from the request
$totalHarga = $_POST['totalHarga'];
$cashAmount = $_POST['cashAmount'];

// Calculate the change
$kembalian = $cashAmount - $totalHarga;

// Return the change to JavaScript
echo json_encode(['kembalian' => $kembalian]);
exit;
?>
