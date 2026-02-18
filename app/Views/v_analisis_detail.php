<div class="col-md-12">
  <div class="card card-primary">
    <div class="card-header">
      <h3 class="card-title">Detail Analisis - Bulan <?= $bulan ?> Tahun <?= $tahun ?></h3>
    </div>
    <div class="card-body">
      <!-- Tabel Detail Hasil Analisis -->
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Produk 1</th>
            <th>Produk 2</th>
            <th>Frekuensi barang dijual bersamaan (%)</th>
            <th>Peluang Produk Terjual Bersama (%)</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($hasilAnalisis as $row): ?>
            <tr>
              <td><?= $row['produk_1'] ?></td>
              <td><?= $row['produk_2'] ?></td>
              <td><?= $row['support'] ?>%</td>
              <td><?= $row['confidence'] ?>%</td>
            </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <!-- Tampilkan produk yang supportnya tinggi -->
          <?php if (count($hasilAnalisis) > 0): ?>
            <h3>Dari hasil analisis diatas diketahui bahwa:</h3>
            <p>
              Produk dengan support tertinggi adalah:
              <strong><?= $hasilAnalisis[0]['produk_1'] ?> dan <?= $hasilAnalisis[0]['produk_2'] ?></strong>
              dengan frekuensi barang dijual bersamaan sebesar **<?= $hasilAnalisis[0]['support'] ?>%.
              Disimpulkan kedua produk itu berpeluang untuk dijual secara bersamaan
            </p>
          <?php endif; ?>
        </tfoot>
      </table>
      <a href="<?= site_url('Analisis') ?>" class="btn btn-secondary">Kembali</a>
    </div>
  </div>
</div>