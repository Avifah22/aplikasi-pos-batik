<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Restok</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="<?= base_url('AdminLTE') ?>/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="<?= base_url('AdminLTE') ?>/dist/css/adminlte.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="<?= base_url('AdminLTE') ?>/plugins/toastr/toastr.min.css" />
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url('AdminLTE') ?>/dist/css/adminlte.min.css">
  <link
    rel="stylesheet"
    href="<?= base_url('AdminLTE') ?>/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css" />
  <!-- jQuery -->
  <script src="<?= base_url('AdminLTE') ?>/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="<?= base_url('AdminLTE') ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="<?= base_url('AdminLTE') ?>/dist/js/adminlte.min.js"></script>
  <!-- SweetAlert2 -->
  <script src="<?= base_url('AdminLTE') ?>/plugins/sweetalert2/sweetalert2.min.js"></script>
  <!-- Toastr -->
  <script src="<?= base_url('AdminLTE') ?>/plugins/toastr/toastr.min.js"></script>
</head>

<body class="hold-transition layout-top-nav">
  <div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
      <div class="container">
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
          <div class="col-md-12">
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
              <div class="card card-primary">
                <div class="card-header">
                  <h1>Detail Stok untuk <?= $produk['nama_produk'] ?></h1>
                  <h2>Stok Terkini: <?= $stok['stok_terkini'] ?></h2>
                </div>


                <h2>Restok Produk</h2>
                <form action="<?= site_url('Stok/restock/' . $produk['id_produk']); ?>" method="post">
                  <label for="perubahan_stok">Jumlah Stok yang Ditambahkan:</label>
                  <input type="number" name="perubahan_stok" min="1" value="1" required>
                  <input type="hidden" name="transaction_type" value="Restok">
                  <input type="hidden" name="id_user" value="<?= session()->get('id_user') ?>">

                  <button type="submit">Update Stok</button>
                </form>
                <a href="<?= base_url('Stok') ?>" class="btn btn-secondary btn-sm m" style="width:70px">
                  Kembali
                </a>

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

</body>

</html>