<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Retur</title>

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
  <!-- REQUIRED SCRIPTS -->
  <script src="<?= base_url('AdminLTE') ?>/plugins/jquery/jquery.min.js"></script>
  <script src="<?= base_url('AdminLTE') ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
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
        <a href="<?= base_url('Retur') ?>" class="navbar-brand">
          <span class="brand-text font-weight-light"><i class="fas fa-recycle text-warning"></i><b>Retur Produk</b></span>
        </a>

        <!-- Tombol untuk Retur Barang -->
        <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse order-3" id="navbarCollapse"></div>

        <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
          <li class="nav-item">
            <a href="<?= base_url('Penjualan') ?>" class="btn btn-info ml-3">
              <i class="fas fa-cart-plus"></i> Transaksi Penjualan
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('Stok') ?>" class="btn btn-info ml-3">
              <i class="fas fa-truck-loading"></i> Restok Barang
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= base_url('Login/Logout') ?>">
              <i class="fas fa-sign-out-alt"></i> Logout
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
                <div class="row">
                  <div class="col-12">
                    <form id="formTambah" action="<?= site_url('Retur/index') ?>" method="get">
                      <!-- Konten Form Tambah -->
                      <div class="input-group mb-3">
                        <input type="text" class="form-control col-sm-4" placeholder="Masukkan keyword pencarian.." name="keyword">
                        <button class="btn btn-outline-primary" type="submit" name="submit">Cari</button>
                        <button class="btn btn-warning" type="submit" onClick="document.location.reload(true)" style="margin-left: 10px;">
                          <div class="fas fa-redo"></div>
                        </button>
                        <button type="button" id="btnTambah" class="btn btn-primary" data-toggle="modal" data-target="#tambah-data" style="margin-left: 10px;">Tambah</button>
                      </div>
                    </form>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-12">
                    <table class="table table-bordered" id="tabelTransaksi">
                      <thead>
                        <tr class="text-center">
                          <th>Id Retur</th>
                          <th>No Faktur</th>
                          <th>Id Produk</th>
                          <th>QTY Retur</th>
                          <th>Harga Satuan</th>
                          <th>Total Retur</th>
                          <th>Alasan Retur</th>
                          <th>Foto</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <?php $no = 1;
                      foreach ($d_retur as $retur => $value) { ?>
                        <tbody>
                          <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $value['no_faktur'] ?></td>
                            <td><?= $value['id_produk'] ?></td>
                            <td><?= $value['qty_retur'] ?></td>
                            <td><?= number_format($value['harga_satuan'], 0, ',', '.') ?></td>
                            <td><?= number_format($value['total_retur'], 0, ',', '.') ?></td>
                            <td><?= $value['alasan_retur'] ?></td>
                            <td>
                              <?php if ($value['foto']) : ?>
                                <img src="<?= base_url($value['foto']) ?>" alt="Foto Retur" style="width: 100px; height: auto;">
                              <?php else : ?>
                                No Image
                              <?php endif; ?>
                            </td>
                            <td>
                              <form action="<?= base_url('Retur/deleteRetur/' . $value['id_retur']); ?>" method="post" onsubmit="return confirm('Apakah Anda yakin ingin menghapus retur ini?');">
                                <button type="submit" class="btn btn-danger btn-sm btn-fla"><i class="fas fa-trash"></i></button>
                              </form>
                            </td>
                          </tr>
                        </tbody>
                      <?php } ?>
                    </table>
                    <div class="row">
                      <div class="col-12 d-flex justify-content-center">
                        <?= $pager->links('group1', 'admin_pagination') ?>
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

  <!-- Modal untuk input No Faktur -->
  <div class="modal fade" id="tambah-data">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Tambah Data Retur</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="formNoFaktur" method="POST">
          <div class="modal-body">
            <!-- Input No Faktur -->
            <div class="form-group">
              <label for="no_faktur">Pilih Produk</label>
              <select id="no_faktur" name="no_faktur" class="form-control" required>
                <option value="">Pilih Produk</option>
                <?php foreach ($noFaktur as $kat): ?>
                  <option value="<?= $kat['no_faktur'] ?>"><?= $kat['no_faktur'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Close</button>
            <button type="button" id="btnNext" class="btn btn-primary btn-flat">Selanjutnya</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- untuk isi produk setelah No Faktur dimasukkan -->
  <div class="modal fade" id="modalProduk" tabindex="-1" role="dialog" aria-labelledby="modalProdukLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalProdukLabel">Pilih Produk</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="formProduk" action="javascript:void(0);">
          <div class="modal-body">
            <!-- Dropdown untuk memilih produk -->
            <div class="form-group">
              <label for="id_produk">Pilih Produk</label>
              <select id="id_produk" name="id_produk" class="form-control" required>
                <option value="">Pilih Produk</option>
                <!-- Opsi produk dapat dari script -->
              </select>
            </div>
            <div class="form-group">
              <label for="qty_retur">QTY Retur</label>
              <input type="number" id="qty_retur" name="qty_retur" class="form-control" placeholder="QTY Retur" min="1" required>
            </div>
            <div class="form-group">
              <label for="harga_satuan">Harga Satuan</label>
              <input type="text" id="harga_satuan" name="harga_satuan" class="form-control" placeholder="Harga Satuan" readonly required>
            </div>
            <div class="form-group">
              <label for="alasan_retur">Alasan Retur</label>
              <textarea name="alasan_retur" id="alasan_retur" class="form-control" required></textarea>
            </div>
            <div class="form-group">
              <label for="foto">Foto Barang Retur</label>
              <input type="file" id="foto" name="foto" class="form-control" accept="image/*" required>
            </div>
            <div class="form-group">
              <label for="no_hp">Nomer Hp</label>
              <input type="text" id="no_hp" name="no_hp" class="form-control" placeholder="No Hp(opsional)">
            </div>
            <div class="form-group">
              <label for="pilihan">Tipe Retur</label>
              <select id="pilihan" name="pilihan" class="form-control" required>
                <option value="Ganti produk">Ganti Produk Serupa</option>
                <option value="Ganti uang">Ganti uang</option>
              </select>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Tutup</button>
            <button type="button" id="btnSimpan" class="btn btn-primary btn-flat">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>


  <script>
    $(document).ready(function() {
      // Untuk tombol Next
      $('#btnNext').on('click', function() {
        const noFaktur = $('#no_faktur').val();

        // validasi
        if (!noFaktur) {
          alert('Harap masukkan No Faktur terlebih dahulu');
          return;
        }

        // Ambil data produk
        $.ajax({
          type: 'POST',
          url: '<?= base_url('Retur/getProdukByFaktur') ?>',
          data: {
            no_faktur: noFaktur
          },
          success: function(response) {
            console.log('Response dari getProdukByFaktur:', response);
            if (Array.isArray(response) && response.length > 0) {
              let options = '<option value="">Pilih Produk</option>';

              // Untuk isi dropdown produk 
              $.each(response, function(index, product) {
                options += `<option value="${product.id_produk}" data-harga-satuan="${product.harga_satuan}">
                  ${product.nama_produk} (Qty: ${product.total_qty})
                </option>`;
              });


              $('#id_produk').html(options);
              $('#modalProduk').modal('show');
            } else {
              alert('Tidak ada produk ditemukan untuk no faktur ini.');
            }
          },
          error: function(xhr, status, error) {
            console.error('Error saat mengambil data produk:', error);
            alert('Terjadi kesalahan saat mengambil data produk.');
          }
        });
      });

      // Untuk menangani perubahan produk yang dipilih
      $('#id_produk').on('change', function() {
        // Ambil harga satuan dari data-harga-satuan
        var hargaSatuan = $(this).find(':selected').data('harga-satuan');

        $('#harga_satuan').val(hargaSatuan);
      });

      //klik tombol Simpan
      $('#btnSimpan').on('click', function() {
        const qtyRetur = $('#qty_retur').val();
        const hargaSatuan = $('#harga_satuan').val();
        const alasanRetur = $('#alasan_retur').val();
        const noFaktur = $('#no_faktur').val();
        const idProduk = $('#id_produk').val();
        const noHp = $('#no_hp').val();
        const foto = $('#foto')[0].files[0];
        const Pilihan = $('#pilihan').val();

        // Validasi  foto
        if (foto) {
          const allowedTypes = ['image/jpeg', 'image/jpg'];
          const maxSize = 2 * 1024 * 1024; //2 mb

          if (!allowedTypes.includes(foto.type)) {
            alert('Hanya file JPG/JPEG yang diperbolehkan.');
            return;
          }
          if (foto.size > maxSize) {
            alert('Ukuran file tidak boleh lebih dari 2MB.');
            return;
          }
        }

        // debugging
        console.log({
          qtyRetur: qtyRetur,
          hargaSatuan: hargaSatuan,
          alasanRetur: alasanRetur,
          noFaktur: noFaktur,
          idProduk: idProduk,
          foto: foto ? foto.name : 'Tidak ada foto',
          Pilihan: Pilihan,
        });

        if (!qtyRetur || !hargaSatuan || !alasanRetur || !noFaktur || !idProduk || !foto || !Pilihan) {
          alert('Harap lengkapi semua data');
          return;
        }

        const idUser = '<?= session()->get('id_user') ?>';

        const formData = new FormData();
        formData.append('no_faktur', noFaktur);
        formData.append('id_produk', idProduk);
        formData.append('qty_retur', qtyRetur);
        formData.append('harga_satuan', hargaSatuan);
        formData.append('alasan_retur', alasanRetur);
        formData.append('no_hp', noHp);
        formData.append('foto', foto);
        formData.append('pilihan', Pilihan);
        formData.append('id_user', idUser);
        console.log('Pilihan:', Pilihan);

        // untuk tahu qty_retur melebihi qty yang terjual
        $.ajax({
          type: 'POST',
          url: '<?= base_url('Retur/cekQtyRetur') ?>',
          data: {
            no_faktur: noFaktur,
            id_produk: idProduk,
            qty_retur: qtyRetur,
            pilihan: Pilihan
          },
          success: function(response) {
            console.log('Response dari cekQtyRetur:', response);
            if (response.status === 'error') {
              alert(response.message);
              return;
            }

            // Menghitung total harga
            const totalHarga = parseFloat(hargaSatuan) * parseFloat(qtyRetur);

            formData.append('total_harga', totalHarga);

            $.ajax({
              type: 'POST',
              url: '<?= base_url('Retur/InsertData') ?>',
              data: formData,
              processData: false,
              contentType: false,
              success: function(response) {
                console.log('Response dari InsertData:', response);
                if (response.status === 'success') {
                  alert(response.message);
                  $('#modalProduk').modal('hide');
                  location.reload();
                } else {
                  alert('Gagal: ' + response.message);
                }
              },
              error: function(xhr, status, error) {
                alert('Terjadi kesalahan saat menyimpan data.');
              }
            });

          },
          error: function(xhr, status, error) {
            alert('Terjadi kesalahan saat cek jumlah retur.');
          }
        });
      });
    });
  </script>

</body>

</html>