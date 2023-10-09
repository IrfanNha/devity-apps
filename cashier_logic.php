<?php
require_once 'config/Database.php';

if (!function_exists('displayCashierReport')) {
    function displayCashierReport($reportPeriod)
    {
        // Create a new Database instance and establish a connection
        $database = new Database();
        $conn = $database->getConnection();
    
        // Customize the query based on the selected report period
        $sql = '';
        switch ($reportPeriod) {
            case 'daily':
                $sql = "SELECT * FROM laporan_kasir WHERE DATE(transaction_date) = CURDATE()";
                break;
            case 'monthly':
                $sql = "SELECT * FROM laporan_kasir WHERE MONTH(transaction_date) = MONTH(CURDATE())";
                break;
            case 'yearly':
                $sql = "SELECT * FROM laporan_kasir WHERE YEAR(transaction_date) = YEAR(CURDATE())";
                break;
            default:
                $sql = "SELECT * FROM laporan_kasir";
                break;
        }
    
        // Prepare and execute the SQL statement
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    
        // Fetch all rows as an associative array
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Close the database connection
        $conn = null;
    
        return $result; // Return the result data
    }
    
}
?>
