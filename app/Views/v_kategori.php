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
            <form id="formTambah" action="<?= site_url('Kategori/index') ?>" method="get">
                <!-- Konten Form Tambah -->
                <div class="input-group mb-3">
                    <input type="text" value="<?= isset($keyword) ? $keyword : '' ?>" class="form-control col-sm-4" placeholder="Masukkan keyword pencarian.." name="keyword">
                    <button class="btn btn-outline-primary" type="submit" name="submit">Cari</button>
                    <a href="<?= base_url('Kategori') ?>" type="button" class="btn btn-secondary ml-2">
                        <div class="fas fa-redo"></div>
                    </a>
                </div>
            </form>
            <table class="table table-bordered">
                <thead>
                    <tr class="text-center">
                        <th width="50px">No</th>
                        <th>Id Kategori</th>
                        <th>Kategori</th>

                        <th width="100px">Aksi</th>
                    </tr>
                </thead>
                <?php $no = $offset + 1;
                foreach ($kategori as $key => $value): ?>
                    <tbody>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $value['id_kategori'] ?></td>
                            <td><?= $value['nama_kategori'] ?></td>

                            <td>
                                <button class="btn btn-warning btn-sm btn-flat">
                                    <i class="fas fa-pencil-alt" data-toggle="modal" data-target="#ubah-data<?= $value['id_kategori'] ?>"></i>
                                </button>
                                <button class="btn btn-danger btn-sm btn-flat">
                                    <i class="fas fa-trash" data-toggle="modal" data-target="#hapus-data<?= $value['id_kategori'] ?>"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                <?php endforeach; ?>
            </table>
            <div class="row">
                <div class="col-12 d-flex justify-content-center">
                    <?= $pager->links('kategoris', 'admin_pagination') ?>
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
            <form action="<?= base_url('Kategori/InsertData') ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Kategori</label>
                        <input name="nama_kategori" class="form-control" placeholder="Kategori" required>
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
<?php foreach ($kategori as $key => $value): ?>
    <div class="modal fade" id="ubah-data<?= $value['id_kategori'] ?>">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ubah data <?= $subjudul ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?= site_url('Kategori/UpdateData/' . $value['id_kategori']); ?>" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Kategori</label>
                            <input name="nama_kategori" value="<?= $value['nama_kategori']; ?>" class="form-control" placeholder="Kategori" required>
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
<?php foreach ($kategori as $key => $value): ?>
    <div class="modal fade" id="hapus-data<?= $value['id_kategori'] ?>">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Hapus data <?= $subjudul ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <a>Apakah Anda akan menghapus data <b><?= $value['nama_kategori'] ?></b> ...?</a>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Tutup</button>
                    <a href="<?= base_url('Kategori/HapusData/' . $value['id_kategori']) ?>" class="btn btn-danger btn-flat">Hapus</a>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>