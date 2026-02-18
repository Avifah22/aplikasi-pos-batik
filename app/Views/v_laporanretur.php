<div class="col-md-12">
  <div class="card card-primary">
    <div class="card-header">


      <!-- /.card-tools -->
    </div>
    <!-- /.card-header -->

    <div class="card-body">
      <div class="row mb-6">
        <div class="col-sm-4">
          <div class="col-lg-12">
            <!-- filter jenis laporan -->
            <form method="get" action="<?= base_url('Laporan/laporanRetur') ?>">
              <div class="input-group mb-3">
                <select class="form-control" name="jenis_laporan">
                  <option value="">-- Pilih Jenis Laporan --</option>
                  <option value="hari_ini" <?= (isset($_GET['jenis_laporan']) && $_GET['jenis_laporan'] == 'hari_ini') ? 'selected' : '' ?>>Laporan Hari Ini</option>
                  <option value="minggu_ini" <?= (isset($_GET['jenis_laporan']) && $_GET['jenis_laporan'] == 'minggu_ini') ? 'selected' : '' ?>>Laporan Minggu Ini</option>
                  <option value="bulan_ini" <?= (isset($_GET['jenis_laporan']) && $_GET['jenis_laporan'] == 'bulan_ini') ? 'selected' : '' ?>>Laporan Bulan Ini</option>
                </select>
                <button class="btn btn-outline-secondary" type="submit">Cari</button>
                <a href="<?= base_url('Laporan/laporanRetur') ?>" type="button" class="btn btn-secondary ml-2">
                  <div class="fas fa-redo"></div>
                </a>
                <a href="<?= site_url('laporan/exportExcelRetur'); ?>" class="btn btn-success">
                  <i class="fa fa-file-excel-o"></i> Export to Excel
                </a>
              </div>
            </form>
          </div>

        </div>
        <!-- Table Container -->
        <div class="table-responsive">
          <div class="row">
            <div class="col-12">
              <table class="table table-striped">
                <thead>
                  <tr class="text-center">
                    <th>Id Retur</th>
                    <th>No Faktur</th>
                    <th>Id Produk</th>
                    <th>QTY Retur</th>
                    <th>Harga Satuan</th>
                    <th>Total Retur</th>
                    <th>Alasan Retur</th>
                    <th>Foto Produk yang di Retur</th>
                    <th>No Hp Pembeli</th>
                    <th>Kasir</th>
                    <th>Pilihan Retur</th>
                    <th>Tanggal Masuk</th>
                    <th>Tanggal Ubah</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $no = 1 + (5 * ($pager->getCurrentPage('lap_ret') - 1)); ?>
                  <?php foreach ($d_retur as $value): ?>
                    <tr class="text-center">
                      <td><?= $no++ ?></td>
                      <td><?= $value['no_faktur'] ?></td>
                      <td><?= $value['id_produk'] ?></td>
                      <td><?= $value['qty_retur'] ?></td>
                      <td><?= number_format($value['harga_satuan'], 0) ?></td>
                      <td><?= number_format($value['total_retur'], 0) ?></td>
                      <td><?= $value['alasan_retur'] ?></td>
                      <td>
                        <?php if ($value['foto']): ?>
                          <button class="btn btn-info" data-toggle="modal" data-target="#foto-produk-diretur" data-foto="<?= base_url($value['foto']) ?>">
                            Lihat Foto
                          </button>
                        <?php else: ?>
                          No Image
                        <?php endif; ?>
                      </td>
                      <td><?= $value['no_hp'] ?></td>
                      <td><?= $value['nama_user'] ?></td>
                      <td><?= $value['pilihan'] ?></td>
                      <td><?= $value['created_at'] ?></td>
                      <td><?= $value['updated_at'] ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>


              </table>
              <div class="row">
                <div class="col-12 d-flex justify-content-center">
                  <?= $pager->links('lap_ret', 'admin_pagination') ?>
                </div>
              </div>
            </div>
          </div>
          <!-- End of Table Container -->
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
  </div>

  <!-- Modal untuk lihat foto produk -->
  <div class="modal fade" id="foto-produk-diretur" tabindex="-1" role="dialog" aria-labelledby="foto-produk-direturLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="foto-produk-direturLabel">Foto Produk yang Dikirim</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <img id="modal-foto" src="" alt="Foto Produk" class="img-fluid w-100">
        </div>
      </div>
    </div>
  </div>
  <script>
    $('#foto-produk-diretur').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget);
      var fotoUrl = button.data('foto');

      var modal = $(this);
      modal.find('#modal-foto').attr('src', fotoUrl);
    });
  </script>