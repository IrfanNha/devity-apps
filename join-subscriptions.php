<?php $pages = 'Berlangganan' ?>
<?php require 'config/Config.php'; ?>

<?php include 'components/templates/header.php'; ?>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <a href="auth/register.php?subscriptions=yes" class="text-decoration-none">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title text-dark fs-5" id="exampleModalLabel">Berlangganan Sekarang juga </h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="">
            <img src="assets/img/poster.png" alt="" width="100%">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-dark rounded-5">Berlangganan</button>
        </div>
      </div>
  </div>
  </a>
</div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JavaScript -->


<script>
  // Fungsi untuk menampilkan modal saat halaman dimuat
  $(document).ready(function() {
    $('#exampleModal').modal('show');
  });
</script>

</body>

</html>




<?php include 'components/templates/footer.php'; ?>