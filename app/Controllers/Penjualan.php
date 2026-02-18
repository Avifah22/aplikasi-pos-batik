<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ModelPenjualan;
use App\Models\ModelPenjualanProduk;
use App\Models\ModelProduk;
use App\Models\ModelStok;
use App\Models\ModelStockHistory;

class Penjualan extends Controller
{
    protected $ModelPenjualan;
    protected $ModelPenjualanProduk;
    protected $ModelProduk;
    protected $ModelStock;
    protected $ModelStockHistory;

    public function __construct()
    {
        $this->ModelPenjualan = new ModelPenjualan();
        $this->ModelPenjualanProduk = new ModelPenjualanProduk();
        $this->ModelProduk = new ModelProduk();
        $this->ModelStock = new ModelStok();
        $this->ModelStockHistory = new ModelStockHistory();
    }

    public function index()
    {
        $data = [
            'judul' => 'Penjualan',
            'no_faktur' => $this->ModelPenjualan->NoFaktur(),
            'produk' => $this->ModelProduk->getProdukWithKategori(),
        ];

        return view('pegawai/v_penjualan', $data);
    }

    public function save()
    {
        $request = \Config\Services::request();
        $file = $this->request->getFile('bukti_transfer');

        $buktiTransferPath = null;
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // kasih nama file
            $randomFileName = bin2hex(random_bytes(16)) . '.' . $file->getExtension();

            //Path file yang diupload
            $buktiTransferPath = 'uploads/transfer_bukti/' . $randomFileName;
            $file->move(ROOTPATH . 'public/uploads/transfer_bukti', $file->getName());
        }

        $dataPenjualan = [
            'no_faktur' => $request->getPost('no_faktur'),
            'total_transaksi' => $request->getPost('total_transaksi'),
            'uang_yang_diserahkan' => $request->getPost('uang_yang_diserahkan'),
            'metode_pembayaran' => $request->getPost('metode_pembayaran'),
            'id_user' => $request->getPost('id_user'),
            'tanggal' => $request->getPost('tanggal'),
            'bukti_transfer' => $buktiTransferPath,
        ];

        try {
            $this->ModelPenjualan->insert($dataPenjualan);

            $id_Penjualan = $this->ModelPenjualan->getInsertID();

            $produk = $request->getPost('produk');
            if (is_string($produk)) {
                $produk = json_decode($produk, true);
            }
            foreach ($produk as $prod) {
                $dataPenjualanProduk = [
                    'no_faktur' => $request->getPost('no_faktur'),
                    'id_produk' => $prod['id_produk'],
                    'qty' => $prod['qty'],
                    'harga_satuan' => $prod['harga_satuan'],
                    'harga_total' => $prod['harga_total'],
                    'diskon' => $prod['diskon'],
                    'harga_total_setelah_diskon' => $prod['harga_total_setelah_diskon'],
                    'created_at' => date('Y-m-d H:i:s')
                ];

                $this->ModelPenjualanProduk->insert($dataPenjualanProduk);

                $produkData = $this->ModelProduk->find($prod['id_produk']);
                $stokData = $this->ModelStock->where('id_produk', $prod['id_produk'])->first();

                if ($produkData && $stokData) {
                    $newStock = $stokData['stok_terkini'] - $prod['qty'];

                    if ($newStock >= 0) {
                        $this->ModelStock->update($stokData['id_stok'], ['stok_terkini' => $newStock]);

                        $stokHistoryData = [
                            'id_produk' => $prod['id_produk'],
                            'tipe_transaksi' => 'Penjualan',
                            'qty' => $prod['qty'],
                            'stok_terkini' => $newStock,
                            'perubahan_stok' => -$prod['qty'],
                            'qty_setelah_ubah' => $newStock,
                            'id_supplier' => $produkData['id_supplier'],
                            'id_user' => $request->getPost('id_user'),
                            'tanggal_transaksi' => date('Y-m-d H:i:s'),
                        ];
                        $this->ModelStockHistory->insert($stokHistoryData);
                    } else {
                        throw new \Exception('Stok tidak mencukupi');
                    }
                } else {
                    throw new \Exception('Produk atau stok tidak ditemukan');
                }
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Transaksi berhasil',
                'no_faktur' => $request->getPost('no_faktur')
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function cek_stok()
    {
        $id_produk = $this->request->getPost('id_produk');
        $qty = $this->request->getPost('qty');
        $stokData = $this->ModelStock->where('id_produk', $id_produk)->first();

        if ($stokData) {
            //stok ditemukan, apakah stok mencukupi
            if ($stokData['stok_terkini'] >= $qty) {
                return $this->response->setJSON(['status' => 'success', 'stok' => $stokData['stok_terkini']]);
            } else {
                //stok tidak mencukupi
                return $this->response->setJSON(['status' => 'error', 'sisa_stok' => $stokData['stok_terkini']]);
            }
        } else {
            //stok tidak ditemukan
            return $this->response->setJSON(['status' => 'error', 'message' => 'Produk tidak ditemukan']);
        }
    }

    public function tampilkan_struk($no_faktur)
    {
        $query = $this->ModelPenjualanProduk
            ->select('penjualan_produk.id_produk, penjualan_produk.qty, penjualan_produk.harga_satuan, penjualan_produk.harga_total, penjualan_produk.diskon, penjualan_produk.harga_total_setelah_diskon, produk.nama_produk')
            ->join('produk', 'penjualan_produk.id_produk = produk.id_produk', 'inner')
            ->where('penjualan_produk.no_faktur', $no_faktur)
            ->get();

        $detailProduk = $query->getResultArray();
        $penjualan = $this->ModelPenjualan->getPenjualan($no_faktur);

        $data = [
            'produkDetail' => $detailProduk,
            'penjualan' => $penjualan
        ];

        return view('hasil/v_print_struck', $data);
    }
}
