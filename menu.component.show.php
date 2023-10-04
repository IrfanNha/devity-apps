<!-- Lihat Menu Makanan -->
<div class="col-md-8">
    <div class="card">
        <div class="card-header">
            <span class="h5 my-2"><i class="fas fa-list"></i> Data Menu Makanan</span>
        </div>
        <div class="card-body">
            <table class="table table-hover" id="datatablesSimple">
                <thead>
                    <tr>
                        <th scope="col">Nama</th>
                        <th scope="col">Harga</th>
                        <th scope="col">Operasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $database = new Database();
                    $conn = $database->getConnection();

                    $user_id = $_SESSION['user_id']; // Ambil user_id dari session

                    $sql = "SELECT * FROM menu WHERE user_id = :user_id";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                    $stmt->execute();

                    // Selanjutnya, Anda dapat mengambil data dari hasil query
                    if ($stmt->rowCount() > 0) {
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            // Tampilkan data sesuai kebutuhan, misalnya:
                            echo '<tr>';
                            echo '<td>' . $row['nama'] . '</td>';
                            echo '<td>Rp. ' . number_format($row['harga'], 0, ',', '.') . ',00</td>';
                            echo '<td>';
                            echo '<div class="d-flex">';
                            echo '<button type="button" class="btn btn-warning btn-sm me-1" data-bs-toggle="modal" data-bs-target="#editMenuModal" data-id="' . $row['id'] . '" data-nama="' . $row['nama'] . '" data-harga="' . $row['harga'] . '"><i class="fas fa-edit"></i></button>';
                            echo '<button type="button" class="btn btn-danger btn-sm me-3" title="Hapus" id="btnHapus" data-id="' . $row['id'] . '" data-nama="' . $row['nama'] . '" data-harga="' . $row['harga'] . '"><i class="fas fa-trash"></i></button>';
                            echo '</div>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="3" class="text-muted">Tidak ada menu yang tersedia.</td></tr>';
                    }

                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL HAPUS DATA -->
<div class="modal" id="mdlHapus" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah anda yakin akan menghapus data ini ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a href="" id="btnMdlHapus" class="btn btn-primary">Iya</a>
            </div>
        </div>
    </div>
</div>
<!-- MODAL EDIT DATA -->
<div class="modal" id="mdlHapus" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah anda yakin akan menghapus data ini ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a href="" id="btnMdlHapus" class="btn btn-primary">Iya</a>
            </div>
        </div>
    </div>
</div>


<!-- Modal Edit Menu -->
<div class="modal fade" id="editMenuModal" tabindex="-1" aria-labelledby="editMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editMenuModalLabel">Edit Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editMenuForm">
                    <div class="mb-3">
                        <label for="editNamaMakanan" class="form-label">Nama Makanan</label>
                        <input type="text" class="form-control" id="editNamaMakanan" name="editNamaMakanan" required>
                    </div>
                    <div class="mb-3">
                        <label for="editHargaMakanan" class="form-label">Harga</label>
                        <input type="number" class="form-control" id="editHargaMakanan" name="editHargaMakanan" required>
                    </div>
                    <input type="hidden" id="editMenuId" name="editMenuId">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="simpanEditMenu">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</div>




<!-- JQUERY -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $(document).on('click', "#btnHapus", function() {
            $('#mdlHapus').modal('show');
            let id = $(this).data('id');
            let nama = $(this).data('nama');
            let harga = $(this).data('harga');
            $('#btnMdlHapus').attr("href", "menu.delete.php?id=" + id + "&nama=" + nama + "&harga=" + harga);
        })
    })
</script>
<script>
    // Fungsi untuk mengisi data menu ke dalam modal
    function isiDataMenu(id, nama, harga) {
        document.getElementById("editMenuId").value = id;
        document.getElementById("editNamaMakanan").value = nama;
        document.getElementById("editHargaMakanan").value = harga;
    }

    // Event handler untuk mengisi data menu saat modal ditampilkan
    $('#editMenuModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var nama = button.data('nama');
        var harga = button.data('harga');
        isiDataMenu(id, nama, harga);
    });

    // Event handler untuk tombol "Simpan Perubahan"
    $('#simpanEditMenu').on('click', function() {
        // Ambil data dari form
        var id = document.getElementById("editMenuId").value;
        var nama = document.getElementById("editNamaMakanan").value;
        var harga = document.getElementById("editHargaMakanan").value;

        // Lakukan pengiriman data melalui AJAX
        $.ajax({
            url: "menu.edit.php", // Ganti sesuai dengan alamat file proses-edit-menu.php
            type: "POST",
            data: {
                id: id,
                nama: nama,
                harga: harga
            },
            success: function(response) {
                // Tampilkan pesan sukses atau pesan error sesuai respons dari proses-edit-menu.php
                if (response === "success") {
                    $('#editMenuModal').modal('hide'); // Tutup modal setelah berhasil
                    // Jika Anda ingin melakukan pengecekan sukses lainnya, Anda bisa menambahkan kode di sini

                    // Me-refresh halaman
                    location.reload();
                    // Arahkan pengguna ke halaman tambah-menu.php dengan pesan sukses
                    window.location.href = "tambah-menu.php?msg=edited";
                } else {
                    alert("Terjadi kesalahan saat mengupdate data."); // Ubah pesan ini sesuai kebutuhan
                    // Jika Anda ingin menangani error lainnya, Anda bisa menambahkan kode di sini
                }
            },
            error: function(xhr, status, error) {
                alert("Terjadi kesalahan saat mengirim data: " + error); // Tampilkan pesan error jika terjadi kesalahan
            }
        });
    });
</script>