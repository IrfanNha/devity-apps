<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}


$pages = 'Tambah Menu';

?>

<?php require 'vendor/autoload.php'; ?>
<?php require 'config/Config.php'; ?>
<?php require 'config/Database.php'; ?>
<?php include 'components/templates/header.php' ?>
<script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
<?php include 'components/partials/dashboard.header.php' ?>
<?php
if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
} else {
    $msg = '';
}

$alert = '';

if ($msg == 'success') {
    $alert = '
    <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
         Anda berhasil menambahkan menu baru...
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}

if ($msg == 'deleted') {
    $alert = '
    <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
         Anda berhasil menghapus menu...
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}

if ($msg == 'edited') {
    $alert = '
    <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
         Anda berhasil mengedit menu...
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}

if ($msg == 'undeleted') {
    $alert = '
    <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
         Anda gagal menghapus menu...
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}

if ($msg == 'unsuccess') {
    $alert = '
    <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
        Anda gagal menambahkan menu baru...
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}
?>
<!-- Content -->
<div class="container my-5">
    <div class="row">
        <!-- Tambah Menu Makanan -->
        <div class="col-md-4 mb-5">
            <div class="card">
                <h5 class="card-header"><i class="fas fa-plus"></i> Tambah Menu Makanan</h5>
                <div class="card-body">
                    <form action="menu.add.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="namaMakanan" class="form-label">Nama Makanan</label>
                            <input type="text" class="form-control" id="namaMakanan" name="namaMakanan" placeholder="masukkan nama makanan" required>
                        </div>
                        <div class="mb-3">
                            <label for="hargaMakanan" class="form-label">Harga</label>
                            <input type="number" class="form-control" id="hargaMakanan" name="hargaMakanan" placeholder="contoh 10000" required>
                        </div>
                        <button type="submit" name="simpan" class="btn btn-dark rounded-5">Tambah</button>
                    </form>
                </div>
            </div>
            <?php
            if ($msg !== '') {
                echo $alert;
            }
            ?>
        </div>

        <!-- Lihat Menu Makanan -->
        <?php include 'menu.component.show.php' ?>
    </div>





    <!-- Content -->
    <?php include 'components/partials/dashboard.footer.php' ?>
    <?php include 'components/templates/footer.php' ?>