<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Stok</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="<?= base_url('AdminLTE') ?>/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="<?= base_url('AdminLTE') ?>/dist/css/adminlte.min.css">
</head>

<body class="hold-transition layout-top-nav">
  <div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
      <div class="container">
        <a href="<?= base_url('Penjualan') ?>" class="navbar-brand">
          <span class="brand-text font-weight-light"><i class="fas fa-truck-loading text-success"> </i><b>Restok Barang</b></span>
        </a>
        <!-- Tombol untuk Retur Barang -->
        <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse order-3" id="navbarCollapse">
        </div>

        <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
          <li class="nav-item">
            <a href="<?= base_url('Penjualan') ?>" class="btn btn-info ml-3">
              <i class="fas fa-cart-plus"></i> Transaksi Penjualan
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('Retur') ?>" class="btn btn-info ml-3">
              <i class="fas fa-recycle"></i> Retur Barang
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= base_url('Login/Logout') ?>">
              <i class="fas fa-sign-out-alt"></i>Logout
            </a>
          </li>
        </ul>
      </div>
    </nav>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
      <div class="content">
        <div class="row">
          <!-- Left Section -->
          <div class="col-lg-12">
            <div class="card card-primary card-outline">
              <div class="card-body">
                <div class="row">
                  <div class="col-3">
                    <div class="form-group">
                      <label>Tanggal</label>
                      <label class="form-control form-control-lg"><?= date('d M Y') ?></label>
                    </div>
                  </div>
                  <div class="col-3">
                    <?php
                    date_default_timezone_set('Asia/Jakarta');
                    ?>
                    <div class="form-group">
                      <label>Jam</label>
                      <label class="form-control form-control-lg"><?= date('H:i:s') ?></label>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="form-group">
                      <label>Kasir</label>
                      <label class="form-control form-control-lg"><?= session()->get('nama_user') ?></label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Transaction Section -->
          <div class="col-lg-12">
            <div class="card card-primary card-outline">
              <div class="card-body">
                <div class="row">
                  <div class="col-12">
                    <form id="formTambah" action="<?= site_url('Stok/index') ?>" method="get">
                      <div class="input-group mb-3">
                        <input type="text" value="<?= isset($keyword) ? $keyword : '' ?>" class="form-control col-sm-4" placeholder="Masukkan keyword pencarian.." name="keyword">
                        <button class="btn btn-outline-primary" type="submit" name="submit">Cari</button>
                        <a href="<?= base_url('Stok') ?>" type="button" class="btn btn-warning ml-2">
                          <div class="fas fa-redo"></div>
                        </a>

                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-12">
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
                          <a href="<?= site_url('Stok/view/' . $stock['id_produk']); ?>">Lihat Detail</a>
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
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Footer -->
  <footer class="main-footer">
    <div class="float-right d-none d-sm-inline">
      Anything you want
    </div>
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
  </footer>

  </div>

  <!-- REQUIRED SCRIPTS -->
  <script src="<?= base_url('AdminLTE') ?>/plugins/jquery/jquery.min.js"></script>
  <script src="<?= base_url('AdminLTE') ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?= base_url('AdminLTE') ?>/dist/js/adminlte.min.js"></script>
</body>

</html>