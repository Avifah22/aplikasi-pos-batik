<div class="col-md-12">
  <div class="card card-primary">
    <div class="card-header">
      <h3 class="card-title"><?= $subjudul ?></h3>
      <div class="card-tools">

      </div>
      <!-- /.card-tools -->
    </div>
    <!-- /.card-header -->
    <div class="card-body">
      <div class="table-responsive">
        <div>

        </div>
        <h1>Riwayat Stok untuk Produk: <?= $produk['nama_produk'] ?></h1>

        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Tanggal Transaksi</th>
              <th>Perubahan Stok</th>
              <th>Stok Setelah Perubahan</th>
              <th>Jenis Transaksi</th>
              <th>Nama supplier</th>
              <th>Kasir</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($historyData as $history): ?>
              <tr>
                <td><?= $history['tanggal_transaksi'] ?></td>
                <td><?= $history['perubahan_stok'] ?></td>
                <td><?= $history['qty_setelah_ubah'] ?></td>
                <td><?= $history['tipe_transaksi'] ?></td>
                <td><?= $history['nama_supplier'] ?></td>
                <td><?= $history['nama_user'] ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <div class="row">
          <div class="col-12 d-flex justify-content-center">
            <?= $pager->links('stocks', 'admin_pagination') ?>
          </div>
        </div>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
</div>