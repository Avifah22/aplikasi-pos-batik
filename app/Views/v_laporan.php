<div class="col-md-12">
    <div class="card card-primary">
        <div class="card-header ">

            <div class="col-4">
                <form method="get" action="<?= base_url('laporan') ?>" class="d-flex align-items-center">
                    <div class="input-group mb-3">
                        <select id="j_lapPen" name="j_lapPen" class="form-control" style="color: black; width:3px" required>
                            <option value="">--Pilih Jenis Laporan--</option>
                            <option value="laporan hari ini" <?= $laporanType == 'laporan hari ini' ? 'selected' : '' ?>>Laporan Hari Ini</option>
                            <option value="laporan bulanan" <?= $laporanType == 'laporan bulanan' ? 'selected' : '' ?>>Laporan Bulanan</option>
                        </select>
                        <button type="submit" class="btn btn-success ml-2">Tampilkan</button>
                        <a href="<?= base_url('laporan') ?>" type="button" class="btn btn-secondary ml-2">
                            <div class="fas fa-redo"></div>
                        </a>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.card-header -->

        <div class="card-body">


            <!-- Pencarian -->
            <div class="row mb-6">
                <div class="col-sm-6">
                    <form method="get" action="<?= base_url('laporan') ?>">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="search" value="<?= esc($searchKeyword) ?>" placeholder="YY-MM-DD">
                            <button class="btn btn-outline-secondary" type="submit">Cari</button>
                            <a href="<?= base_url('laporan') ?>" type="button" class="btn btn-primary mr-2">
                                <div class="fas fa-redo"></div>
                            </a>
                            <a href="<?= site_url('laporan/exportExcel'); ?>" class="btn btn-success">
                                <i class="fa fa-file-excel-o"></i> Export to Excel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table Container -->
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No Faktur</th>
                                    <th>Nama Produk</th>
                                    <th>QTY</th>
                                    <th>Harga Satuan</th>
                                    <th>Total</th>
                                    <th>Total Transaksi</th>
                                    <th>Kasir</th>
                                    <th>Tanggal Masuk</th>
                                </tr>
                            </thead>
                            <?php
                            $currentNoFaktur = '';
                            $totalPendapatan = 0;
                            $rowspan = 1;
                            $noFakturCount = [];

                            foreach ($laporanData as $data) {
                                if (!isset($noFakturCount[$data['no_faktur']])) {
                                    $noFakturCount[$data['no_faktur']] = 0;
                                }
                                $noFakturCount[$data['no_faktur']]++;
                            }

                            foreach ($laporanData as $index => $data):
                                // Jika no_faktur berubah, tampilkan header fktur baru
                                if ($data['no_faktur'] != $currentNoFaktur):
                                    $currentNoFaktur = $data['no_faktur'];
                                    $rowspan = $noFakturCount[$currentNoFaktur];
                            ?>
                                    <tr>
                                        <td rowspan="<?= $rowspan ?>" class="text-center"><?= $data['no_faktur'] ?></td>
                                        <td><?= $data['nama_produk'] ?></td>
                                        <td><?= $data['qty'] ?></td>
                                        <td><?= number_format($data['harga_satuan'], 0) ?></td>
                                        <td><?= number_format($data['total'], 0) ?></td>
                                        <td><?= number_format($data['total_transaksi'], 0) ?></td>
                                        <td><?= $data['user'] ?></td>
                                        <td><?= date('d-m-Y', strtotime($data['tanggal_masuk'])); ?></td>
                                    </tr>
                                <?php
                                else:
                                ?>
                                    <tr>
                                        <td><?= $data['nama_produk'] ?></td>
                                        <td><?= $data['qty'] ?></td>
                                        <td><?= number_format($data['harga_satuan'], 2) ?></td>
                                        <td><?= number_format($data['total'], 2) ?></td>
                                        <td></td>
                                        <td><?= $data['user'] ?></td>
                                        <td><?= date('d-m-Y', strtotime($data['tanggal_masuk'])); ?></td>
                                    </tr>
                            <?php
                                endif;
                            endforeach;
                            ?>
                        </table>

                        <div class="row">
                            <div class="col-12 d-flex justify-content-center">
                                <?= $pager->links('laporan', 'admin_pagination') ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>