<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Transaksi Penjualan</title>

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
          <span class="brand-text font-weight-light"><i class="fas fa-shopping-cart text-primary"> </i><b>Transaksi Penjualan<b></span>
        </a>
        <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse order-3" id="navbarCollapse">
        </div>
        <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
          <li class="nav-item">
            <a href="<?= base_url('Stok') ?>" class="btn btn-info ml-3">
              <i class="fas fa-truck-loading"></i> Restok Barang
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
          <div class="col-lg-7">
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
                  <div class="col-4">
                    <div class="form-group">
                      <label>No faktur</label>
                      <label class="form-control form-control-lg"><?= $no_faktur ?></label>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label>Tanggal</label>
                      <label class="form-control form-control-lg"><?= date('d M Y') ?></label>
                    </div>
                  </div>
                  <div class="col-2">
                    <?php
                    date_default_timezone_set('Asia/Jakarta');
                    ?>
                    <div class="form-group">
                      <label>Jam</label>
                      <label class="form-control form-control-lg"><?= date('H:i') ?></label>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="form-group">
                      <label>Kasir</label>
                      <label class="form-control form-control-lg"><?= session()->get('nama_user') ?></label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Right Section -->
          <div class="col-lg-5">
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h5 class="card-title m-0"></h5>
              </div>
              <div class="card-body bg-black color-pallete text-right">
                <label id="totalTransaksi" class="display-4 text-green">Rp.0</label>
              </div>
            </div>
          </div>

          <!-- Transaction Section -->
          <div class="col-lg-12">
            <div class="card card-primary card-outline">
              <div class="card-body">
                <div class="row">
                  <div class="col-12">
                    <form id="formProduk" action="javascript:void(0);">
                      <div class="row">
                        <div class="col-2">
                          <select id="id_produk" name="id_produk" class="form-control" style="color: black;" required>
                            <option value="">Pilih Produk</option>
                            <?php foreach ($produk as $prod): ?>
                              <option value="<?= $prod['id_produk'] ?>"
                                data-nama="<?= $prod['nama_produk'] ?>"
                                data-harga="<?= $prod['harga_satuan'] ?>"
                                data-kategori="<?= $prod['kategori'] ?>">
                                <?= $prod['id_produk'] ?> - <?= $prod['nama_produk'] ?> (<?= $prod['kategori'] ?>)
                              </option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                        <div class="col-2">
                          <input id="nama_produk" name="nama_produk" class="form-control" placeholder="Nama Produk" readonly>
                        </div>
                        <div class="col-1">
                          <input id="nama_kategori" name="nama_kategori" class="form-control" placeholder="Kategori" readonly>
                        </div>
                        <div class="col-1">
                          <input id="harga_satuan" name="harga_satuan" class="form-control" placeholder="Harga Satuan" readonly>
                        </div>
                        <div class="col-1">
                          <input type="number" id="qty" name="qty" class="form-control" placeholder="QTY" min="1">
                        </div>
                        <!-- input tersembunyi-->
                        <input type="hidden" name="no_faktur" id="noFakturInput" value="<?= $no_faktur ?>">
                        <input type="hidden" name="total_harga" id="totalTransaksiInput" value="0">

                        <div class="col-5">
                          <button type="button" id="btnTambah" class="btn btn-primary"><i class="fas fa-cart-plus"></i> Tambah</button>
                          <button type="button" class="btn btn-warning" id="btnHapusForm"><i class="fas fa-sync"></i> Hapus</button>
                          <button type="button" class="btn btn-success" id="btnBayar"><i class="fas fa-cash-register"></i> Bayar</button>
                        </div>
                      </div>
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
                        <th>Id</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga Satuan</th>
                        <th>QTY</th>
                        <th>Harga Total</th>
                        <th>Diskon</th>
                        <th>Harga Total Setelah Diskon</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
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

  <!--form Pilihan Pembayaran -->
  <div class="container" style="display: grid; place-items: center;">
    <div id="pembayaranModal" class="modal" style="display:none;max-width: 50%; margin: 0 auto;">
      <div class="modal-content">
        <h4>Pilih Metode Pembayaran</h4>
        <div>
          <input type="radio" id="metodeTunai" name="metode_pembayaran" value="Tunai" checked>
          <label for="metodeTunai">Tunai</label>
        </div>
        <div>
          <input type="radio" id="metodeTransfer" name="metode_pembayaran" value="Transfer">
          <label for="metodeTransfer">Transfer</label>
        </div>
        <!-- Form Pembayaran Tunai -->
        <div id="formTunai" style="display:block;">
          <div class="form-group">
            <label for="uang_yang_diserahkan">Jumlah Uang Tunai</label>
            <input type="number" id="uang_yang_diserahkan" name="uang_yang_diserahkan" class="form-control" placeholder="Jumlah Uang Tunai" oninput="hitungKembalian()" min="0">
          </div>
          <div class="form-group">
            <label for="kembalian">Kembalian</label>
            <input type="text" id="kembalian" name="kembalian" class="form-control" placeholder="Kembalian" readonly>
          </div>
        </div>
        <!-- Form Transfer -->
        <div id="formTransfer" style="display:none;">
          <div class="form-group">
            <label for="nama_bank">Bukti Transfer</label>
            <input type="file" id="foto" name="foto" class="form-control" accept="image/*" required>
          </div>
        </div>
        <!-- Tombol Kirim -->
        <button type="button" class="btn btn-primary" id="btnSubmitPembayaran">Konfirmasi Pembayaran</button>
        <button type="button" class="btn btn-secondary" id="btnBatal">Batal</button>
      </div>
    </div>
  </div>

  <!-- REQUIRED SCRIPTS -->
  <script src="<?= base_url('AdminLTE') ?>/plugins/jquery/jquery.min.js"></script>
  <script src="<?= base_url('AdminLTE') ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?= base_url('AdminLTE') ?>/dist/js/adminlte.min.js"></script>
  <script>
    let totalTransaksi = 0;

    document.addEventListener('DOMContentLoaded', function() {
      const idProdukEl = document.getElementById('id_produk');
      const namaProdukEl = document.getElementById('nama_produk');
      const hargaSatuanEl = document.getElementById('harga_satuan');
      const kategoriEl = document.getElementById('nama_kategori');
      const qtyEl = document.getElementById('qty');

      // Saat produk dipilih
      idProdukEl.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        namaProdukEl.value = selected.getAttribute('data-nama');
        hargaSatuanEl.value = selected.getAttribute('data-harga');
        kategoriEl.value = selected.getAttribute('data-kategori');
      });

      // Tambah produk ke tabel
      document.getElementById('btnTambah').addEventListener('click', function() {
        const idProduk = idProdukEl.value;
        const namaProduk = namaProdukEl.value;
        const kategori = kategoriEl.value;
        const hargaSatuan = parseFloat(hargaSatuanEl.value);
        const qty = parseInt(qtyEl.value);
        const hargaTotal = hargaSatuan * qty;

        if (!idProduk || !namaProduk || isNaN(hargaSatuan) || isNaN(qty) || qty <= 0) {
          alert('Data produk tidak lengkap atau salah.');
          return;
        }

        // Cek stok ke server
        $.post('<?= base_url('Penjualan/cek_stok') ?>', {
          id_produk: idProduk,
          qty
        }, function(response) {
          if (response.status === 'error') {
            alert('Stok tidak cukup! Tersisa: ' + response.sisa_stok);
            return;
          }

          // Hitung diskon
          let diskon = 0;
          if (qty >= 15) diskon = 0.14;
          else if (qty >= 10) diskon = 0.09;
          else if (qty >= 5) diskon = 0.04;

          const hargaSetelahDiskon = hargaTotal - (hargaTotal * diskon);

          const tbody = document.querySelector('#tabelTransaksi tbody');
          const row = tbody.insertRow();
          row.innerHTML = `
        <td>${idProduk}</td>
        <td>${namaProduk}</td>
        <td>${kategori}</td>
        <td class="text-right">${hargaSatuan.toFixed(2)}</td>
        <td class="text-center">${qty}</td>
        <td class="text-center">${hargaTotal.toFixed(2)}</td>
        <td class="text-right">${diskon}</td>
        <td class="text-right">${hargaSetelahDiskon.toFixed(2)}</td>
        <td><a class="btn btn-flat btn-danger btn-sm btnHapusRow"><i class="fa fa-times"></i></a></td>
      `;

          // Update total transaksi
          totalTransaksi += hargaSetelahDiskon;
          document.getElementById('totalTransaksi').textContent = `Rp.${totalTransaksi.toFixed(2)}`;
          document.getElementById('totalTransaksiInput').value = totalTransaksi.toFixed(2);
        });
      });

      // Hapus form input produk
      document.getElementById('btnHapusForm').addEventListener('click', function() {
        idProdukEl.value = '';
        namaProdukEl.value = '';
        kategoriEl.value = '';
        hargaSatuanEl.value = '';
        qtyEl.value = '';
      });

      // Buka modal pembayaran
      document.getElementById('btnBayar').addEventListener('click', function() {
        document.getElementById('pembayaranModal').style.display = 'block';
      });

      // Tutup modal bila klik di luar
      document.querySelector('.modal').addEventListener('click', function(e) {
        if (e.target === this) {
          this.style.display = 'none';
        }
      });

      // Toggle metode pembayaran
      document.getElementById('metodeTunai').addEventListener('change', function() {
        document.getElementById('formTunai').style.display = 'block';
        document.getElementById('formTransfer').style.display = 'none';
      });

      document.getElementById('metodeTransfer').addEventListener('change', function() {
        document.getElementById('formTunai').style.display = 'none';
        document.getElementById('formTransfer').style.display = 'block';
      });

      // Hitung kembalian
      document.getElementById('uang_yang_diserahkan').addEventListener('input', function() {
        const uang = parseFloat(this.value) || 0;
        const total = parseFloat(document.getElementById('totalTransaksiInput').value);
        const kembalian = uang - total;

        document.getElementById('kembalian').value = kembalian >= 0 ? kembalian.toFixed(2) : 'Uang tidak cukup!';
      });

      // Submit pembayaran
      document.getElementById('btnSubmitPembayaran').addEventListener('click', function(e) {
        e.preventDefault();

        const noFaktur = document.getElementById('noFakturInput').value;
        const total = parseFloat(document.getElementById('totalTransaksiInput').value);
        const metode = document.querySelector('input[name="metode_pembayaran"]:checked').value;
        const uang = parseFloat(document.getElementById('uang_yang_diserahkan').value) || 0;
        let kembalian = 0;

        if (metode === 'Tunai' && uang < total) {
          alert('Uang tidak cukup!');
          return;
        }

        if (metode === 'Transfer' && document.getElementById('foto').files.length === 0) {
          alert('Upload bukti transfer!');
          return;
        }

        kembalian = uang - total;

        // Ambil data produk dari tabel
        const produk = [];
        document.querySelectorAll('#tabelTransaksi tbody tr').forEach(row => {
          produk.push({
            id_produk: row.cells[0].textContent.trim(),
            nama_produk: row.cells[1].textContent.trim(),
            qty: parseInt(row.cells[4].textContent.trim()),
            harga_satuan: parseFloat(row.cells[3].textContent.trim()),
            harga_total: parseFloat(row.cells[5].textContent.trim()),
            diskon: parseFloat(row.cells[6].textContent.trim()),
            harga_total_setelah_diskon: parseFloat(row.cells[7].textContent.trim()),
            created_at: new Date().toISOString().slice(0, 19).replace('T', ' ')
          });
        });

        const formData = new FormData();
        const fileInput = document.getElementById('foto');
        if (fileInput.files.length > 0) {
          formData.append('bukti_transfer', fileInput.files[0]);
        }

        formData.append('no_faktur', noFaktur);
        formData.append('total_transaksi', total);
        formData.append('metode_pembayaran', metode);
        formData.append('uang_yang_diserahkan', uang);
        formData.append('kembalian', kembalian);
        formData.append('id_user', '<?= session()->get('id_user') ?>');
        formData.append('tanggal', new Date().toISOString().slice(0, 19).replace('T', ' '));
        formData.append('produk', JSON.stringify(produk));

        $.ajax({
          url: '<?= base_url('Penjualan/save') ?>',
          method: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(response) {
            if (response.status === 'success') {
              window.location.href = "<?= base_url('Penjualan/tampilkan_struk') ?>/" + response.no_faktur;
            } else {
              alert(response.message);
            }
          },
          error: function(error) {
            alert('Terjadi kesalahan saat menyimpan data.');
          }
        });
      });

      // Tombol Batal
      document.getElementById('btnBatal').addEventListener('click', function() {
        document.getElementById('pembayaranModal').style.display = 'none';
      });

      // Hapus baris dari tabel (delegasi)
      document.querySelector('#tabelTransaksi tbody').addEventListener('click', function(e) {
        if (e.target.closest('.btnHapusRow')) {
          const row = e.target.closest('tr');
          const subtotal = parseFloat(row.cells[7].textContent.trim());
          totalTransaksi -= subtotal;
          document.getElementById('totalTransaksi').textContent = `Rp.${totalTransaksi.toFixed(2)}`;
          document.getElementById('totalTransaksiInput').value = totalTransaksi.toFixed(2);
          row.remove();
        }
      });
    });
  </script>


</body>

</html>