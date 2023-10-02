<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}
$pages = 'Menu';
?>

<?php require 'vendor/autoload.php'; ?>
<?php require 'config/Config.php'; ?>
<?php require 'config/Database.php'; ?>

<?php include 'components/templates/header.php' ?>
<script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
<style>
    .notification-badge {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 40px;
        height: 40px;
        background-color: black;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 18px;
        cursor: pointer;
    }
</style>
<?php include 'components/partials/dashboard.header.php' ?>
<!-- Content -->

<div id="layoutAuthentication">
    <div id="layoutAuthentication_content">
        <main>
            <div class="container mt-5">
                <div class="row">
                    <?php
                    // Create a new Database instance and establish a connection
                    $database = new Database();
                    $conn = $database->getConnection();

                    // Mengambil user_id dari sesi
                    $userId = $_SESSION["user_id"];

                    // Modifikasi kueri SQL untuk mengambil data menu sesuai dengan user_id
                    $sql = "SELECT * FROM menu WHERE user_id = :user_id";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(":user_id", $userId, PDO::PARAM_INT);
                    $stmt->execute();

                    // Assign the result of the executed statement to $result
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    // Loop through the menu items and display them
                    if (count($result) > 0) {
                        foreach ($result as $row) {
                            echo '<div class="col-md-3 mt-2">';
                            echo '<div class="card">';
                            echo '<div class="card-body">';
                            echo '<h5 class="card-title">' . $row['nama'] . '</h5>';
                            echo '<p class="card-text">Harga: Rp. ' . $row['harga'] . '</p>';
                            echo '<button class="btn btn-primary" onclick="tambahItem(\'' . $row['nama'] . '\', ' . $row['harga'] . ')">Tambah ke Keranjang</button>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo "No menu items available.";
                    }

                    // Close the database connection
                    $conn = null;
                    ?>
                </div>
                <div class="notification-badge">
                    <i class="fa-solid fa-cart-shopping" data-bs-toggle="modal" href="#mdlProfileUser"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cartItemCount">
                        0
                        <span class="visually-hidden">unread messages</span>
                    </span>
                </div>
            </div>
        </main>
    </div>
    <div class="modal" tabindex="-1" id="mdlProfileUser">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                                <th>Operasi</th>
                            </tr>
                        </thead>
                        <tbody id="keranjang">
                            <!-- Item yang ditambahkan ke sini -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3"><strong>Total:</strong></td>
                                <td><strong id="totalHarga">Rp. 0</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="resetKeranjang">Reset</button>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalKonfirmasiPesanan">Konfirmasi</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Add this code within your HTML file -->
    <div class="modal" tabindex="-1" id="modalKonfirmasiPesanan">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Modal body content goes here -->
                    <!-- You can add any additional information you want to display here -->
                </div>
                <div class="modal-footer">
                    <!-- Button to print the receipt -->
                    <!-- Button to print the receipt -->
                    <button type="button" class="btn btn-success" onclick="cetakStruk()">Cetak Struk</button>
                    <!-- Button to save the order (customize as needed) -->
                    <button type="button" class="btn btn-primary" id="simpanBtn">Simpan</button>
                </div>
            </div>
        </div>
    </div>

</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<script>
    let keranjang = [];
    let totalHarga = 0;
    let cartItemCount = 0; // Initialize cart item count

    function tambahItem(nama, harga) {
        // Check if the item is already in the cart
        const existingItem = keranjang.find(item => item.nama === nama);

        if (existingItem) {
            // If the item is in the cart, increment its quantity and subtotal
            existingItem.quantity++;
            existingItem.subtotal = existingItem.quantity * harga;
        } else {
            // If the item is not in the cart, add it with quantity 1
            keranjang.push({
                nama,
                harga,
                quantity: 1,
                subtotal: harga
            });
        }

        // Update the total price
        totalHarga += harga;

        // Increment the cart item count
        cartItemCount++;
        updateCartBadge();

        updateKeranjang();

        // Kirim Data Ke server database
        $.ajax({
            type: "POST",
            url: "insert_order.php", // Insert Data
            data: {
                nama: nama,
                harga: harga
            },
            success: function(response) {
                // Handle the server's response here if needed
                console.log("Data inserted successfully.");
            },
            error: function(xhr, status, error) {
                // Handle errors here if needed
                console.error("Error inserting data: " + error);
            }
        });
    }

    function updateCartBadge() {
        // Update the cart item count in the badge
        $('#cartItemCount').text(cartItemCount);
    }

    function updateKeranjang() {
        let keranjangHTML = '';
        for (const item of keranjang) {
            keranjangHTML += `
            <tr>
                <td>${item.nama}</td>
                <td>Rp. ${item.harga}</td>
                <td>${item.quantity}</td>
                <td>Rp. ${item.subtotal}</td>
                <td>
                    <button class="btn btn-danger btn-sm d-flex justify-content-center align-items-center" onclick="kurangiItem('${item.nama}', ${item.harga})"><i class="fas fa-minus-circle"></i></button>
                </td>
            </tr>
        `;
        }
        $('#keranjang').html(keranjangHTML);
        $('#totalHarga').text('Rp. ' + totalHarga);
    }


    // Fungsi untuk mengurangi item
    function kurangiItem(nama, harga) {
        // Find the item in the cart
        const existingItem = keranjang.find(item => item.nama === nama);

        if (existingItem) {
            // Decrease the quantity by 1
            existingItem.quantity--;

            // Update the subtotal for the item
            existingItem.subtotal = existingItem.quantity * harga;

            // Update the total price
            totalHarga -= harga;

            // Update the cart item count
            cartItemCount--;

            // Remove the item from the cart if the quantity becomes zero
            if (existingItem.quantity === 0) {
                keranjang = keranjang.filter(item => item.nama !== nama);
            }

            // Update the cart badge and cart display
            updateCartBadge();
            updateKeranjang();
        }
    }


    // Fungsi untuk mereset keranjang
    function resetKeranjang() {
        // Empty the cart array and reset total price
        keranjang = [];
        totalHarga = 0;

        // Update the cart item count to 0
        cartItemCount = 0;
        updateCartBadge();

        // Clear the cart table
        $('#keranjang').html('');
        $('#totalHarga').text('Rp. 0');
    }

    // Event listener untuk tombol "Reset"
    document.getElementById("resetKeranjang").addEventListener("click", function() {
        resetKeranjang(); // Memanggil fungsi resetKeranjang saat tombol "Reset" ditekan
    });

    // Fungsi untuk mencetak struk
    function cetakStruk() {
        // Buka jendela cetak
        var printWindow = window.open('', '', 'width=600,height=400');

        // Isi struk yang akan dicetak
        var strukHTML = `
    <html>
    <head>
        <title>Struk Pesanan</title>
        <!-- Include Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-..."
            crossorigin="anonymous">
    </head>
    <body>
        <div class="container mt-3">
            <h2 class="mb-4">Struk Pesanan</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    ${keranjang.map(item => `
                        <tr>
                            <td>${item.nama}</td>
                            <td>Rp. ${item.harga}</td>
                            <td>${item.quantity}</td>
                            <td>Rp. ${item.subtotal}</td>
                        </tr>
                    `).join('')}
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"><strong>Total:</strong></td>
                        <td><strong>Rp. ${totalHarga}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </body>
    </html>
    `;

        // Menuliskan struk ke jendela cetak
        printWindow.document.open();
        printWindow.document.write(strukHTML);
        printWindow.document.close();

        // Cetak struk
        printWindow.print();
        printWindow.close();
    }

    // Event listener untuk tombol "Cetak Struk"
    document.getElementById("cetakStruk").addEventListener("click", function() {
        cetakStruk(); // Memanggil fungsi cetakStruk saat tombol "Cetak Struk" ditekan
    });

    // Event listener for the "Konfirmasi" button
    document.getElementById("konfirmasiButton").addEventListener("click", function() {
        $('#konfirmasiModal').modal('hide'); // Close the current modal
        $('#modalKonfirmasiPesanan').modal('show'); // Show the new modal
    });

    // Event listener for the "Cetak Struk" button inside the modal
    document.getElementById("cetakStrukBtn").addEventListener("click", function() {
        cetakStruk(); // Call the cetakStruk function when the "Cetak Struk" button is clicked
        $('#konfirmasiModal').modal('hide'); // Close the modal
    });

    
    document.getElementById("simpanBtn").addEventListener("click", function() {
    // Create an object to store the order data
    const orderData = {
        keranjang,
        totalHarga
    };

    // Make an AJAX request to save the order
    $.ajax({
        type: "POST",
        url: "save_order.php", // Ensure the correct path
        contentType: "application/json",
        data: JSON.stringify(orderData),
        success: function(response) {
            // Handle the server's response here
            console.log(response);

            // Check if the response is successful before closing the modal
            if (response.status === "success") {
                // Close the modal
                $('#modalKonfirmasiPesanan').modal('hide');

                // Reset the cart after saving
                resetKeranjang();

                // Optionally, perform any other actions after successful save
            } else {
                // Handle the case where the save was not successful
                console.error("Error saving order: " + response.message);
                // Optionally, show an error message to the user
            }
        },
        error: function(xhr, status, error) {
            // Handle errors here
            console.error("Error saving order: " + error);
            // Optionally, show an error message to the user
        }
    });
});
</script>

<!-- Content -->
<?php include 'components/partials/dashboard.footer.php' ?>
<?php include 'components/templates/footer.php' ?>