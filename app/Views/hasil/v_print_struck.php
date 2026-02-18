<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        .struk-container {
            width: 280px;
            padding: 10px;
            border: 1px solid #000;
            margin: 0 auto;
            background-color: #fff;
        }

        .logo img {
            width: 120px;
            height: auto;
            margin-bottom: 5px;
        }

        .judul-struk {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .detail-transaksi {
            margin-bottom: 20px;
        }

        .detail-transaksi table {
            width: 100%;
            margin-bottom: 10px;
        }

        .no_faktur {
            font-size: 14px;
            margin-top: 10px;
            text-align: left;
        }

        .detail-transaksi th,
        .detail-transaksi td {
            font-size: 12px;
            padding: 3px;
            text-align: left;
        }

        .harga {
            font-size: 14px;
            font-weight: bold;
            margin-top: 10px;
        }

        .footer-struk {
            margin-top: 20px;
            font-size: 12px;
        }

        .garis {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }

        @media print {
            #backButton {
                display: none;
            }
        }

        @media print {

            #backButton,
            #printButton {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="struk-container">
        <div class="logo">
            <img src="<?= base_url('image/logo.jpg') ?>" alt="Logo Perusahaan">
        </div>

        <div class="judul-struk">
            Toko Batik Joyo Mukti
        </div>

        <div class="no_faktur">
            No Faktur: <?= $penjualan['no_faktur'] ?>
            <br>
            Jam: <?= date("H:i:s") ?>
        </div>

        <div class="garis"></div>
        <?php foreach ($produkDetail as $item): ?>
            <table>
                <thead>
                    <tr>
                        <th colspan="2"><?= $item['nama_produk'] ?> (<?= $item['qty'] ?>)</th>
                        <th><?= number_format($item['harga_satuan'], 0, ',', '.') ?></th>
                        <th></th>
                        <th><?= number_format($item['harga_total'], 0, ',', '.') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="2">Diskon: <?= number_format($item['diskon'] * 100, 2, ',', '.') ?>%</td>
                        <td></td>
                        <td colspan="2" style="text-align:right;">
                            <?= number_format($item['harga_total_setelah_diskon'], 0, ',', '.') ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        <?php endforeach; ?>

        <div class="garis"></div>

        <div class="harga">
            Total: Rp. <?= number_format($penjualan['total_transaksi'], 0, ',', '.') ?>
        </div>


        <div class="harga">
            Kembalian: Rp.
            <?php
            if ($penjualan['metode_pembayaran'] == 'Tunai') {
                $kembalian = $penjualan['uang_yang_diserahkan'] - $penjualan['total_transaksi'];
            } else {
                $kembalian = 0;
            }
            echo number_format($kembalian, 0, ',', '.');
            ?>
        </div>


        <div class="footer-struk">
            <p>Terima kasih atas pembelian Anda!</p>
            <p>Pengembalian produk hanya bisa dilakukan sehari setelah pembelian dengan qty pembelian 2 untuk satu jenis produk</p>
            <button id="backButton" onclick="window.history.back()">Kembali</button>
            <button id="backButton" onclick="window.print()">Cetak Struk</button>

        </div>
    </div>
</body>

</html>