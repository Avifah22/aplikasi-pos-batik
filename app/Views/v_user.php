<div class="col-md-12">
  <div class="card card-primary">
    <div class="card-header">
      <h3 class="card-title"><?= $subjudul ?></h3>
      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-toggle="modal" data-target="#tambah-data">
          <i class="fas fa-plus">Tambah data</i>
        </button>
      </div>
      <!-- /.card-tools -->
    </div>
    <!-- /.card-header -->
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

      <div class="table-responsive">
        <div>
          <form id="formTambah" action="<?= site_url('User/index') ?>" method="get">
            <!-- Konten Form Tambah -->
            <div class="input-group mb-3">
              <input type="text" value="<?= isset($keyword) ? $keyword : '' ?>" class="form-control col-sm-4" placeholder="Pencarian berdasarkan Username.." name="keyword">
              <button class="btn btn-outline-primary" type="submit" name="submit">Cari </button>
              <a href="<?= base_url('User') ?>" type="button" class="btn btn-secondary ml-2">
                <div class="fas fa-redo"></div>
              </a>
            </div>
          </form>
        </div>
        <table class="table table-bordered">
          <thead>
            <tr class="text-center">
              <th width="50px">no</th>
              <th>Username</th>
              <th>Nama User</th>
              <th>Password</th>
              <th>Level</th>
              <th>Tanggal masuk</th>
              <th width="100px">aksi</th>
            </tr>
          </thead>
          <?php $no = 1;
          foreach ($user as $us => $value) { ?>
            <tbody>
              <td><?= $no++ ?></td>
              <td><?= $value['username'] ?></td>
              <td><?= $value['nama_user'] ?></td>
              <td><?= $value['password'] ?></td>
              <td class="text-center"><?php
                                      if ($value['level'] == '1') { ?>
                  <span class="badge bg-success">Admin</span>
                <?php } else { ?>
                  <span class="badge bg-primary">Kasir</span>
                <?php } ?>
              </td>
              <td><?= $value['tanggal'] ?></td>
              <td>
                <button class="btn btn-warning btn-sm btn-flat"><i class="fas fa-pencil-alt" data-toggle="modal" data-target="#ubah-data<?= $value['id_user'] ?>"></i></button>
                <button class="btn btn-danger btn-sm btn-flat"><i class="fas fa-trash" data-toggle="modal" data-target="#hapus-data<?= $value['id_user'] ?>"></i></button>
              </td>
            </tbody>
          <?php } ?>
        </table>
        <div class="row">
          <div class="col-12 d-flex justify-content-center">
            <?= $pager->links('users', 'admin_pagination') ?>
          </div>
        </div>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
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
      <form action="<?= site_url('User/InsertData') ?>" method="post">

        <div class="modal-body">
          <div class="form-group">
            <label for="">Username</label>
            <input name="username" class="form-control" placeholder="username" required>
          </div>
          <div class="form-group">
            <label for="">Nama User</label>
            <input name="nama_user" class="form-control" placeholder="Nama User" required>
          </div>
          <div class="form-group">
            <label for="">Password</label>
            <input name="password" class="form-control" placeholder="password" required>
          </div>
          <div class="form-group">
            <label for="">Level</label>
            <select name="level" class="form-control">
              <option value="1">Admin</option>
              <option value="2" selected>Kasir</option>
            </select>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary btn-flat">Simpan</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- Modal Ubah Data -->
<?php foreach ($user as $us => $value): ?>
  <div class="modal fade" id="ubah-data<?= $value['id_user'] ?>">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Ubah data <?= $subjudul ?></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="<?= site_url('User/UpdateData/' . $value['id_user']); ?>" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label for="">Username</label>
              <input name="username" value="<?= $value['username'] ?>" class="form-control" placeholder="Username" required>
            </div>
            <div class="form-group">
              <label for="">Nama User</label>
              <input name="nama_user" value="<?= $value['nama_user'] ?>" class="form-control" placeholder="Nama User" required>
            </div>
            <div class="form-group">
              <label for="">Password</label>
              <input name="password" value="<?= $value['password'] ?>" class="form-control" placeholder="Password" required>
            </div>
            <div class="form-group">
              <label for="" value="<?= $value['level'] ?>"></label>
              <select name="level" class="form-control">
                <option value="1">Admin</option>
                <option value="2">Kasir</option>
              </select>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-warning btn-flat">Ubah</button>
          </div>
        </form>
      </div>
    </div>
  </div>
<?php endforeach; ?>

<!-- Modal Hapus Data -->
<?php foreach ($user as $us => $value): ?>
  <div class="modal fade" id="hapus-data<?= $value['id_user'] ?>">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Hapus data <?= $subjudul ?></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <a>Apakah Anda akan menghapus data <b><?= $value['username'] ?></b> ...?</a>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Tutup</button>
          <a href="<?= base_url('User/HapusData/' . $value['id_user']) ?>" class="btn btn-danger btn-flat">Hapus</a>
        </div>
      </div>
    </div>
  </div>
<?php endforeach; ?>