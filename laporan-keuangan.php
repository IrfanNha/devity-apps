<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}


$pages = 'Riwayat';

?>

<?php require 'vendor/autoload.php'; ?>
<?php require 'config/Config.php'; ?>
<?php require 'config/Database.php'; ?>
<?php include 'components/templates/header.php' ?>
<script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
<?php include 'components/partials/dashboard.header.php' ?>

<!-- Content -->
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="card mb-2">
                <div class="card-header">
                    <span class="h5 my-2"><i class="fa-solid fa-list"></i> Laporan Keuangan</span>

                    <div class="dropdown" style="margin-top: -30px;">
                        <button class="btn btn-sm btn-primary dropdown-toggle float-end me-1" type="button"
                            data-bs-toggle="dropdown">Cetak</button>
                        <ul class="dropdown-menu">
                            <li><button type="button" onclick="printDoc()" class="dropdown-item"><i
                                        class="fa-solid fa-magnifying-glass"></i> Laporan Keuangan</button></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-hover" id="datatablesSimple">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">
                                    <center>Item</center>
                                </th>
                                <th scope="col">
                                    <center>Total Harga</center>
                                </th>
                                <th scope="col">
                                    <center>Waktu</center>
                                </th>
                                <th scope="col">
                                    <center>Operasi</center>
                                </th>

                            </tr>
                        </thead>
                        <tbody>




                            <tr>

                            </tr>


                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </main>

    
</div>





<!-- Content -->
<?php include 'components/partials/dashboard.footer.php' ?>
<?php include 'components/templates/footer.php' ?>