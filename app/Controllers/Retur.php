<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ModelRetur;
use App\Models\ModelPenjualanProduk;
use App\Models\ModelPenjualan;

class Retur extends Controller
{

    protected $ModelRetur;
    protected $penjualanProdukModel;
    protected $penjualanModel;

    public function __construct()
    {
        $this->ModelRetur = new ModelRetur();
        $this->penjualanProdukModel = new ModelPenjualanProduk();
        $this->penjualanModel = new ModelPenjualan();

        if (!session()->get('id_user')) {
            return redirect()->to('/Login');
        }

        if (session()->get('level') == 1) {
            return redirect()->to('/Admin');
        }
    }

    public function index()
    {
        $keyword = $this->request->getVar('keyword');
        $dataretur = $keyword ? $this->ModelRetur->cari($keyword) : $this->ModelRetur->orderBy('id_retur', 'DESC')->findAll();

        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));

        $n_faktur = $this->penjualanModel
            ->where("DATE(tanggal) =", $today)
            ->orWhere("DATE(tanggal) =", $yesterday)
            ->findAll();

        $d_retur = $this->ModelRetur
            ->where("DATE(created_at) =", $today)
            ->orWhere("DATE(created_at) =", $yesterday)
            ->paginate(5, 'group1');

        $data = [
            'judul' => 'Retur',
            'd_retur' => $d_retur,
            'pager' => $this->ModelRetur->pager,
            'noFaktur' => $n_faktur
        ];

        return view('pegawai/v_retur', $data);
    }

    public function getProdukByFaktur()
    {
        $noFaktur = $this->request->getPost('no_faktur');
        $produkData = $this->ModelRetur->getProdukByFaktur($noFaktur);
        log_message('debug', 'No Faktur: ' . $noFaktur);
        log_message('debug', 'Produk Data: ' . print_r($produkData, true));

        if (empty($produkData)) {
            return $this->response->setJSON(['error' => 'Produk tidak ditemukan']);
        }

        return $this->response->setJSON($produkData);
    }

    public function InsertData()
    {
        $data = $this->request->getPost();
        $produkData = $this->penjualanProdukModel->getProdukByFakturAndId($data['no_faktur'], $data['id_produk']);

        if (empty($produkData)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Produk tidak ditemukan atau data tidak lengkap']);
        }

        if ($data['qty_retur'] > $produkData['qty']) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Jumlah retur tidak boleh lebih besar dari jumlah yang terjual']);
        }

        if ($data['pilihan'] == 'Ganti uang') {
            // Jika qty yang tersisa setelah retur kurang dari 2, hanya bisa pilih "Ganti produk"
            $new_qty = $produkData['qty'] - $data['qty_retur'];
            if ($new_qty < 2) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Jumlah produk setelah retur kurang dari 2. Pilihan hanya dapat "Ganti produk"'
                ]);
            }
        }

        // Menghitung dan update data baru produk
        $new_qty = $produkData['qty'] - $data['qty_retur'];
        $new_total_harga = $new_qty * $data['harga_satuan'];

        // Jika pilihan "Ganti uang"=> update data produk pd penjualan_produk
        if ($data['pilihan'] == 'Ganti uang') {
            $this->penjualanProdukModel->update($produkData['id_penjualan_produk'], [
                'qty' => $new_qty,
                'harga_total' => $new_total_harga
            ]);
            $this->updateTotalTransaksi($data['no_faktur']);
        }

        $foto = $this->request->getFile('foto');
        $fotoPath = null;

        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            // Validasi tipe & ukuran file
            $allowedTypes = ['image/jpg', 'image/jpeg'];
            $maxSize = 2 * 1024 * 1024;

            if (!in_array($foto->getMimeType(), $allowedTypes)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Hanya file dengan format JPG/JPEG yang diperbolehkan']);
            }

            if ($foto->getSize() > $maxSize) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Ukuran file tidak boleh lebih dari 2MB']);
            }
            $newName = $foto->getRandomName();
            $uploadPath = ROOTPATH . 'public/uploads/';

            if ($foto->move($uploadPath, $newName)) {
                $fotoPath = 'uploads/' . $newName;
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'File upload gagal']);
            }
        }

        $this->ModelRetur->insert([
            'no_faktur' => $data['no_faktur'],
            'id_produk' => $data['id_produk'],
            'qty_retur' => $data['qty_retur'],
            'harga_satuan' => $data['harga_satuan'],
            'total_retur' => $data['total_harga'],
            'alasan_retur' => $data['alasan_retur'],
            'no_hp' => $data['no_hp'],
            'id_user' => $data['id_user'],
            'pilihan' => $data['pilihan'],
            'foto' => $fotoPath
        ]);

        //Jika pilihan "Ganti uang"=> Update data penjualan_produk dan total transaksi 
        if ($data['pilihan'] == 'Ganti uang') {
            $this->penjualanProdukModel->update($produkData['id_penjualan_produk'], [
                'qty' => $new_qty,
                'harga_total' => $new_total_harga
            ]);

            $this->updateTotalTransaksi($data['no_faktur']);
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Data berhasil disimpan']);
    }

    protected function updateTotalTransaksi($noFaktur)
    {
        $produkData = $this->penjualanProdukModel->where('no_faktur', $noFaktur)->findAll();

        $totalTransaksi = 0;
        foreach ($produkData as $produk) {
            $totalTransaksi += $produk['harga_total'];
        }

        $updateStatus = $this->penjualanModel->update(
            ['no_faktur' => $noFaktur],
            ['total_transaksi' => $totalTransaksi],
        );

        $penjualanData = $this->penjualanModel->where('no_faktur', $noFaktur)->first();

        if ($penjualanData) {
            $updateStatus = $this->penjualanModel->update($penjualanData['id_penjualan'], [
                'total_transaksi' => $totalTransaksi
            ]);
            if ('total_transaksi' == 0.00) {
                $this->ModelRetur->delete('no_faktur');
            }

            if ($updateStatus) {
                log_message('debug', 'Total transaksi untuk no_faktur ' . $noFaktur . ' berhasil diupdate menjadi: ' . $totalTransaksi);
            } else {
                log_message('error', 'Gagal mengupdate total transaksi untuk no_faktur ' . $noFaktur);
            }
        }

        if ($updateStatus) {
            log_message('debug', 'Total transaksi untuk no_faktur ' . $noFaktur . ' berhasil diupdate');
        } else {
            log_message('error', 'Gagal mengupdate total transaksi untuk no_faktur ' . $noFaktur);
        }
    }

    public function returProduk($id_retur)
    {
        $retur = $this->ModelRetur->find($id_retur);

        if ($retur) {
            $produk = $this->penjualanProdukModel->where('no_faktur', $retur['no_faktur'])
                ->where('id_produk', $retur['id_produk'])
                ->first();

            if ($produk) {
                $hargaTotalSebelumRetur = $produk['harga_total'];
                $qtySebelumRetur = $produk['qty'];

                $newQty = $produk['qty'] - $retur['qty_retur'];
                $newTotalHarga = $newQty * $produk['harga_satuan'];

                $this->penjualanProdukModel->update($produk['id_penjualan_produk'], [
                    'qty' => $newQty,
                    'harga_total' => $newTotalHarga
                ]);

                $this->updateTotalTransaksi($retur['no_faktur']);
            }
        }
    }

    public function getHargaSatuan()
    {
        $idProduk = $this->request->getPost('id_produk');

        if (!$idProduk) {
            return $this->response->setJSON(['error' => 'ID produk tidak valid']);
        }

        $hargaSatuan = $this->penjualanProdukModel->getHargaSatuanById($idProduk);

        if ($hargaSatuan) {
            return $this->response->setJSON(['harga_satuan' => $hargaSatuan]);
        } else {
            return $this->response->setJSON(['error' => 'Harga satuan tidak ditemukan']);
        }
    }

    public function cekQtyRetur()
    {
        $data = $this->request->getPost();
        $pilihan = $this->request->getPost('pilihan');

        if (empty($data['no_faktur']) || empty($data['id_produk']) || empty($data['qty_retur'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak lengkap']);
        }

        $produkData = $this->penjualanProdukModel->getProdukByFakturAndId($data['no_faktur'], $data['id_produk']);
        if ($produkData) {
            $maxQtyRetur = $produkData['qty'];
            if ($data['qty_retur'] > $maxQtyRetur) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Jumlah retur tidak boleh lebih besar dari jumlah yang terjual']);
            }
            return $this->response->setJSON(['status' => 'success']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Produk tidak ditemukan']);
    }

    public function deleteRetur($id_retur)
    {
        $retur = $this->ModelRetur->find($id_retur);

        if (!$retur) {
            return redirect()->back()->with('error', 'Data retur tidak ditemukan');
        }

        $this->ModelRetur->delete($id_retur);

        session()->setFlashdata('pesan', 'Data Berhasil dihapus');
        return redirect()->to('Retur');
    }
}
