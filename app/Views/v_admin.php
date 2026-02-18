<div class="col-lg-3 col-6">
  <!-- small box -->
  <div class="small-box bg-info">
    <div class="inner">
      <h3><?= $penjualanMingguan ?></h3>
      <p>Penjualan Mingguan</p>
    </div>
    <div class="icon">
      <i class="fas fa-chart-bar"></i>
    </div>
    <a href="<?= base_url('Laporan') ?>" class="small-box-footer">
      More info <i class="fas fa-arrow-circle-right"></i>
    </a>
  </div>
</div>
<!-- ./col -->
<div class="col-lg-3 col-6">
  <!-- small box -->
  <div class="small-box bg-success">
    <div class="inner">
      <h3><?= $produk ?></h3>
      <p>Produk</p>
    </div>
    <div class="icon">
      <i class="fas fa-tshirt"></i>
    </div>
    <a href="<?= base_url('Produk') ?>" class="small-box-footer">
      More info <i class="fas fa-arrow-circle-right"></i>
    </a>
  </div>
</div>
<!-- ./col -->

<!-- ./col -->
<div class="col-lg-3 col-6">
  <!-- small box -->
  <div class="small-box bg-warning">
    <div class="inner">
      <h3><?= $kategori ?></h3>
      <p>Kategori</p>
    </div>
    <div class="icon">
      <i class="fas fa-box"></i>
    </div>
    <a href="<?= base_url('Kategori') ?>" class="small-box-footer">
      More info <i class="fas fa-arrow-circle-right"></i>
    </a>
  </div>
</div>
<!-- ./col -->

<div class="col-lg-3 col-6">
  <!-- small box -->
  <div class="small-box bg-danger">
    <div class="inner">
      <h3><?= $barangRetur ?></h3>
      <p>Barang di Retur</p>
    </div>
    <div class="icon">
      <i class="fas fa-times-circle"></i>
    </div>
    <a href="<?= base_url('Laporan/LaporanRetur') ?>" class="small-box-footer">
      More info <i class="fas fa-arrow-circle-right"></i>
    </a>
  </div>
</div>
<!-- ./col -->


<!-- Grafik Penjualan dan Retur -->
<div class="col-lg-12 col-12">
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Grafik Penjualan dan Retur Bulanan</h3>
    </div>
    <div class="card-body">
      <canvas id="penjualanReturChart" style="width:50%; height:100px;"></canvas>
    </div>
  </div>
</div>

<script>
  // Ambil data penjualan dan retur
  const penjualanData = <?= json_encode($penjualanData) ?>;
  const returData = <?= json_encode($returData) ?>;

  // Data untuk grafik
  const labels = penjualanData.map(item => item.bulan + '-' + item.tahun);
  const penjualanValues = penjualanData.map(item => item.total_penjualan);
  const returValues = returData.map(item => item.total_retur);

  // buat grafik
  const ctx = document.getElementById('penjualanReturChart').getContext('2d');
  const penjualanReturChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
          label: 'Penjualan',
          data: penjualanValues,
          backgroundColor: 'rgba(54, 162, 235, 0.5)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1
        },
        {
          label: 'Retur',
          data: returValues,
          backgroundColor: 'rgba(255, 99, 132, 0.5)',
          borderColor: 'rgba(255, 99, 132, 1)',
          borderWidth: 1
        }
      ]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>