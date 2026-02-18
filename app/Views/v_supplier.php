<div class="col-md-12">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title"><?= $subjudul ?></h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-toggle="modal" data-target="#tambah-data">
                    <i class="fas fa-plus">Tambah data</i>
                </button>
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <?php if (session()->getFlashdata('pesan')) : ?>
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
            <?php if (session()->getFlashdata('error')) : ?>
                <script>
                    $(function() {
                        Swal.fire({
                            icon: 'info',
                            title: '<?= session()->getFlashdata('error') ?>',
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
                    <form id="formTambah" action="<?= site_url('Supplier/index') ?>" method="get">
                        <div class="input-group mb-3">
                            <input type="text" value="<?= isset($keyword) ? $keyword : '' ?>" class="form-control col-sm-4" placeholder="Masukkan keyword pencarian.." name="keyword">
                            <button class="btn btn-outline-primary" type="submit" name="submit">Cari</button>
                            <a href="<?= base_url('Supplier') ?>" type="button" class="btn btn-secondary ml-2">
                                <div class="fas fa-redo"></div>
                            </a>
                        </div>
                    </form>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr class="text-center">
                            <th width="50px">No</th>
                            <th>Nama Supplier</th>
                            <th>Email</th>
                            <th>no_hp</th>
                            <th>Alamat</th>
                            <th>Produk yang disuplai</th>
                            <th>Tanggal Masuk</th>
                            <th>Tanggal Update</th>
                            <th width="100px">Aksi</th>
                        </tr>
                    </thead>
                    <?php $no = $offset + 1;
                    foreach ($supplier as $key => $value): ?>
                        <tbody>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $value['nama_supplier'] ?></td>
                                <td><?= $value['email'] ?></td>
                                <td><?= $value['no_hp'] ?></td>
                                <td><?= $value['alamat'] ?></td>
                                <td><?= $value['produk_names'] ?></td>
                                <td><?= $value['created_at'] ?></td>
                                <td><?= $value['updated_at'] ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm btn-flat">
                                        <i class="fas fa-pencil-alt" data-toggle="modal" data-target="#ubah-data<?= $value['id_supplier'] ?>"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm btn-flat">
                                        <i class="fas fa-trash" data-toggle="modal" data-target="#hapus-data<?= $value['id_supplier'] ?>"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    <?php endforeach; ?>
                </table>
                <div class="row">
                    <div class="col-12 d-flex justify-content-center">
                        <?= $pager->links('suppliers', 'admin_pagination') ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>

<!-- /.col-md-12 -->

<!-- Modal Tambah Data -->
<div class="modal fade" id="tambah-data">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah data <?= $subjudul ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= site_url('Supplier/InsertData') ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Nama Supplier</label>
                        <input name="nama_supplier" class="form-control" placeholder="Nama Supplier" required>
                    </div>
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="form-group">
                        <label for="">No. Hp</label>
                        <input name="no_hp" class="form-control" placeholder="62****" required>
                    </div>
                    <div class="form-group">
                        <label for="">Alamat</label>
                        <textarea name="alamat" class="form-control" placeholder="Alamat" required></textarea>
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
<?php foreach ($supplier as $key => $value): ?>
    <div class="modal fade" id="ubah-data<?= $value['id_supplier'] ?>">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ubah data <?= $subjudul ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?= site_url('Supplier/UpdateData/' . $value['id_supplier']); ?>" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Nama Supplier</label>
                            <input name="nama_supplier" value="<?= $value['nama_supplier']; ?>" class="form-control" placeholder="Nama Supplier" required>
                        </div>
                        <div class="form-group">
                            <label for="">Email</label>
                            <input type="email" name="email" value="<?= $value['email']; ?>" class="form-control" placeholder="Email" required>
                        </div>
                        <div class="form-group">
                            <label for="">No. Hp</label>
                            <input name="no_hp" value="<?= $value['no_hp']; ?>" class="form-control" placeholder="No. Hp" required>
                        </div>
                        <div class="form-group">
                            <label for="">Alamat</label>
                            <textarea name="alamat" class="form-control" placeholder="Alamat" required><?= $value['alamat']; ?></textarea>
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
<?php foreach ($supplier as $key => $value): ?>
    <div class="modal fade" id="hapus-data<?= $value['id_supplier'] ?>">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Hapus data <?= $subjudul ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <a>Apakah Anda akan menghapus data <b><?= $value['nama_supplier'] ?></b> ...?</a>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Tutup</button>
                    <a href="<?= base_url('Supplier/HapusData/' . $value['id_supplier']) ?>" class="btn btn-danger btn-flat">Hapus</a>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>