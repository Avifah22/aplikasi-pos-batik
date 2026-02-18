<div class="col-md-12">
  <div class="card card-primary">
    <div class="card-header">
      <h3 class="card-title"><?= $subjudul ?></h3>
      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-toggle="modal" data-target="#tambah-data"><i class="fas fa-plus">Tambah data</i>
        </button>
      </div>
      <!-- /.card-tools -->
    </div>
    <!-- /.card-header -->
    <div class="card-body">
      <div class="table-responsive">
        <div>
          <form id="formTambah" action="<?= site_url('StokHistory/index') ?>" method="get">
            <div class="input-group mb-3">
              <input type="text" value="<?= isset($keyword) ? $keyword : '' ?>" class="form-control col-sm-4" placeholder="Masukkan nama produk.." name="keyword">
              <button class="btn btn-outline-primary" type="submit" name="submit">Cari</button>
              <a href="<?= base_url('StokHistory') ?>" type="button" class="btn btn-primary ml-2">
                <div class="fas fa-redo"></div>
              </a>
          </form>
        </div>
        <table class="table table-bordered">
          <thead>
            <tr class="text-center">
              <th>Nama Produk</th>
              <th>Stok Terkini</th>
              <th>Nama Supplier</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <?php foreach ($stockData as $stock): ?>
            <tr>
              <td><?= $stock['nama_produk'] ?></td>
              <td><?= $stock['stok_terkini'] ?></td>
              <td><?= $stock['nama_supplier'] ?></td>
              <td>
                <a href="<?= site_url('StokHistory/detail/' . $stock['id_produk']); ?>">Lihat Detail</a>
              </td>
            </tr>
          <?php endforeach; ?>
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
</div>
</div>
<!-- modal tambah data -->
<div class="modal fade" id="tambah-data">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Tambah data <?= $subjudul ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?= site_url('StokHistory/InsertData') ?>" method="post">
        <div class="modal-body">
          <div class="form-group">
            <label for="id_produk" class="col-sm-3 col-form-label">Pilih Produk</label>
            <div class="col-sm-10">
              <select id="id_produk" name="id_produk" class="form-control" style="color: black;" required>
                <option value="">Pilih Produk</option>
                <?php foreach ($produks as $prod): ?>
                  <option value="<?= $prod['id_produk'] ?>"><?= $prod['nama_produk'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="">Stok</label>
            <input name="stok_terkini" class="form-control" placeholder="Stok" required>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary btn-flat">Simpan</button>
          </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->