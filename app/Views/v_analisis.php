<div class="col-md-12">
  <div class="card card-primary">
    <div class="card-header">
      <h3 class="card-title"><?= $subjudul ?></h3>
    </div>
    <div class="card-body">
      <?php if (session()->getFlashdata('pesan')): ?>
        <script>
          $(function() {
            Swal.fire({
              icon: 'success',
              title: '<?= session()->getFlashdata('pesan') ?>',
              showConfirmButton: false,
              timer: 3000,
              customClass: {
                popup: 'swal-popup',
              },
              willOpen: () => {
                $('.swal-popup').css({
                  'font-size': '20px',
                  'width': '50%',
                  'max-width': '400px',
                  'height': 'auto',
                  'padding': '30px',
                  'border-radius': '10px',
                  'text-align': 'center',
                  'line-height': '1.4',
                  'box-sizing': 'border-box',
                });
              }
            });
          });
        </script>
      <?php endif; ?>
      <!-- Form Proses Analisis -->
      <form action="<?= site_url('Analisis/proses') ?>" method="post">
        <div class="form-group">
          <label for="support">Support Threshold (%):</label>
          <input type="number" id="support" name="support" step="0.01" min="0" max="100" required><br><br>

          <label for="confidence">Confidence Threshold (%):</label>
          <input type="number" id="confidence" name="confidence" step="0.01" min="0" max="100" required><br><br>


          <button type="submit" class="btn btn-primary mb-3">
            <i class="fas fa-cogs"></i> Proses Analisis
          </button>
      </form>

      <h3>Tabel Analisis</h3>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Bulan</th>
            <th>Tahun</th>
            <th>Detail</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $bulanTampil = [];
          foreach ($hasil as $row):
            if (!in_array($row['bulan'], $bulanTampil)):
              $bulanTampil[] = $row['bulan'];
          ?>
              <tr>
                <td><?= $row['bulan'] ?></td>
                <td><?= $row['tahun'] ?></td>
                <td>
                  <a href="<?= site_url('Analisis/detail/' . $row['bulan'] . '/' . $row['tahun']) ?>" class="btn btn-info">Lihat Detail</a>
                </td>
                <td>
                  <button class="btn btn-danger btn-sm btn-flat" data-toggle="modal" data-target="#hapus-data<?= $row['id_analisis'] ?>">
                    <i class="fas fa-trash"></i>
                  </button>
                </td>
              </tr>
            <?php endif; ?>
          <?php endforeach; ?>
        </tbody>
      </table>
      <div class="row">
        <div class="col-12 d-flex justify-content-center">
          <?= $pager->links('analis', 'admin_pagination') ?>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<?php foreach ($hasil as $row): ?>
  <div class="modal fade" id="hapus-data<?= $row['id_analisis'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Hapus</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Apakah Anda yakin ingin menghapus hasil analisis untuk bulan <?= $row['bulan'] ?> pada tahun <?= $row['tahun'] ?>?
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Close</button>

          <!-- Tombol Hapus-->
          <a href="<?= site_url('Analisis/hapusHasilAnalisis/' . $row['bulan'] . '/' . $row['tahun']) ?>" class="btn btn-danger btn-flat">Hapus</a>
        </div>

      </div>
    </div>
  </div>
<?php endforeach; ?>