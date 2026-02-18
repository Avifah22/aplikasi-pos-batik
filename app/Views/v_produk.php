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
                <form id="formTambah" action="<?= site_url('Produk/index') ?>" method="get">
                    <div class="input-group mb-3">
                        <input type="text" value="<?= isset($keyword) ? $keyword : '' ?>" class="form-control col-sm-4" placeholder="Masukkan keyword pencarian.." name="keyword">
                        <button class="btn btn-outline-primary" type="submit" name="submit">Cari</button>
                        <a href="<?= base_url('Produk') ?>" type="button" class="btn btn-secondary ml-2">
                            <div class="fas fa-redo"></div>
                        </a>
                    </div>
                </form>

                <table class="table table-bordered">
                    <thead>
                        <tr class="text-center">
                            <th width="50px">No</th>
                            <th>Id Produk</th>
                            <th>Produk</th>
                            <th>Harga Satuan</th>
                            <th>Warna</th>
                            <th>Ukuran</th>
                            <th>Kategori</th>
                            <th>Supplier</th>
                            <th>Tanggal Masuk</th>
                            <th>Tanggal Update</th>
                            <th width="100px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = $offset + 1;
                        foreach ($produk as $key => $value): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $value['id_produk'] ?></td>
                                <td><?= $value['nama_produk'] ?></td>
                                <td><?= number_format($value['harga_satuan'], 0) ?></td>
                                <td><?= $value['warna'] ?></td>
                                <td><?= $value['ukuran'] ?></td>
                                <td><?= $value['nama_kategori'] ?></td>
                                <td><?= $value['nama_supplier'] ?></td>
                                <td><?= $value['created_at'] ?></td>
                                <td><?= $value['updated_at'] ?></td>
                                <td>
                                    <div class="d-flex">
                                        <button class="btn btn-warning btn-sm btn-flat" data-toggle="modal" data-target="#ubah-data<?= $value['id_produk'] ?>">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm btn-flat" data-toggle="modal" data-target="#hapus-data<?= $value['id_produk'] ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="row">
                    <div class="col-12 d-flex justify-content-center">
                        <?= $pager->links('produks', 'admin_pagination') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
            <form action="<?= site_url('Produk/InsertData') ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Nama Produk</label>
                        <input name="nama_produk" class="form-control" placeholder="Nama Produk" required>
                    </div>
                    <div class="form-group">
                        <label for="">Harga Satuan</label>
                        <input name="harga_satuan" class="form-control" placeholder="Harga Satuan" required>
                    </div>
                    <div class="form-group">
                        <label for="">Warna</label>
                        <input name="warna" class="form-control" placeholder="Warna" required>
                    </div>
                    <div class="form-group">
                        <label for="">Ukuran</label>
                        <input name="ukuran" class="form-control" placeholder="Ukuran" required>
                    </div>
                    <div class="row mb-3">
                        <label for="id_kategori" class="col-sm-2 col-form-label">Pilih Kategori</label>
                        <div class="col-sm-10">
                            <select id="id_kategori" name="id_kategori" class="form-control" style="color: black;" required>
                                <option value="">Pilih Kategori</option>
                                <?php foreach ($kategori as $kat): ?>
                                    <option value="<?= $kat['id_kategori'] ?>"><?= $kat['nama_kategori'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="id_supplier" class="col-sm-2 col-form-label">Pilih Supplier</label>
                        <div class="col-sm-10">
                            <select id="id_supplier" name="id_supplier" class="form-control" style="color: black;" required>
                                <option value="">Pilih Supplier</option>
                                <?php foreach ($supplier as $supp): ?>
                                    <option value="<?= $supp['id_supplier'] ?>"><?= $supp['nama_supplier'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary btn-flat">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Ubah Data -->
<?php foreach ($produk as $key => $value): ?>
    <div class="modal fade" id="ubah-data<?= $value['id_produk'] ?>">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ubah data <?= $subjudul ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?= site_url('produk/UpdateData/' . $value['id_produk']); ?>" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Id Produk</label>
                            <input name="id_produk" value="<?= $value['id_produk'] ?>" class="form-control" placeholder="Nama Produk" required readonly>
                        </div>
                        <div class="form-group">
                            <label for="">Nama Produk</label>
                            <input name="nama_produk" value="<?= $value['nama_produk'] ?>" class="form-control" placeholder="Nama Produk" required>
                        </div>
                        <div class="form-group">
                            <label for="">Harga Satuan</label>
                            <input name="harga_satuan" value="<?= $value['harga_satuan'] ?>" class="form-control" placeholder="Harga Satuan" required>
                        </div>
                        <div class="form-group">
                            <label for="">Warna</label>
                            <input name="warna" value="<?= $value['warna'] ?>" class="form-control" placeholder="Warna" required>
                        </div>
                        <div class="form-group">
                            <label for="">Ukuran</label>
                            <input name="ukuran" value="<?= $value['ukuran'] ?>" class="form-control" placeholder="Ukuran" required>
                        </div>
                        <div class="row mb-3">
                            <label for="id_kategori" class="col-sm-2 col-form-label">Pilih Kategori</label>
                            <div class="col-sm-10">
                                <select id="id_kategori" name="id_kategori" class="form-control" style="color: black;" required>
                                    <?php foreach ($kategori as $kat): ?>
                                        <option value="<?= $kat['id_kategori'] ?>"><?= $kat['nama_kategori'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="id_supplier" class="col-sm-2 col-form-label">Pilih Supplier</label>
                            <div class="col-sm-10">
                                <select id="id_supplier" name="id_supplier" class="form-control" style="color: black;" required>
                                    <?php foreach ($supplier as $supp): ?>
                                        <option value="<?= $supp['id_supplier'] ?>"><?= $supp['nama_supplier'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
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
<?php foreach ($produk as $key => $value): ?>
    <div class="modal fade" id="hapus-data<?= $value['id_produk'] ?>">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Hapus data <?= $subjudul ?></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <a>Apakah Anda akan menghapus data <b><?= $value['nama_produk'] ?></b> ...?</a>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Tutup</button>
                    <a href="<?= base_url('Produk/HapusData/' . $value['id_produk']) ?>" class="btn btn-danger btn-flat">Hapus</a>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>