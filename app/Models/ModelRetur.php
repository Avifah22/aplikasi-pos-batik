<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelRetur extends Model
{
    protected $table            = 'retur';
    protected $primaryKey       = 'id_retur';
    protected $allowedFields    = ['id_retur', 'no_faktur', 'id_produk', 'qty_retur', 'harga_satuan', 'total_retur', 'alasan_retur', 'foto', 'no_hp', 'id_user', 'pilihan', 'created_at', 'updated_at'];


    protected $beforeInsert = ['setCreatedAt'];
    protected $beforeUpdate = ['setUpdatedAt'];

    protected function setCreatedAt(array $data)
    {
        if (!isset($data['data']['created_at'])) {
            $data['data']['created_at'] = date('Y-m-d H:i:s');
        }

        if (!isset($data['data']['updated_at'])) {
            $data['data']['updated_at'] = date('Y-m-d H:i:s');
        }

        return $data;
    }

    protected function setUpdatedAt(array $data)
    {
        $data['data']['updated_at'] = date('Y-m-d H:i:s');
        return $data;
    }

    public function getProdukByFaktur($noFaktur)
    {
        return $this->db->table('penjualan_produk')
            ->select('penjualan_produk.id_produk, produk.nama_produk, penjualan_produk.harga_satuan, SUM(penjualan_produk.qty) as total_qty')
            ->join('produk', 'produk.id_produk = penjualan_produk.id_produk')
            ->where('penjualan_produk.no_faktur', $noFaktur)
            ->groupBy('penjualan_produk.id_produk, produk.nama_produk, penjualan_produk.harga_satuan')
            ->get()
            ->getResultArray();
    }

    public function cekQtyRetur()
    {
        $noFaktur = $this->request->getPost('no_faktur');
        $idProduk = $this->request->getPost('id_produk');
        $qtyRetur = $this->request->getPost('qty_retur');

        $penjualanProdukModel = new ModelPenjualanProduk();
        $produkData = $penjualanProdukModel->getProdukByFakturAndId($noFaktur, $idProduk);

        if (empty($produkData)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Produk tidak ditemukan']);
        }
        $qtyTerjual = $produkData['qty_terjual'];

        // untuk mengetahui qty_retur melebihi qty_terjual
        if ($qtyRetur > $qtyTerjual) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Jumlah retur tidak boleh lebih besar dari jumlah yang terjual']);
        }

        return $this->response->setJSON(['status' => 'success']);
    }

    public function cari($keyword)
    {
        return $this->table('retur')->like('no_faktur', $keyword);
    }

    public function CariLaporan($keyword)
    {
        return $this->table('retur')->like('created_at', $keyword);
    }

    public function findTodayRetur()
    {
        return $this->where('DATE(created_at)', date('Y-m-d'))
            ->findAll();
    }

    public function findThisMonthRetur()
    {
        return $this->where('MONTH(created_at)', date('m'))
            ->where('YEAR(created_at)', date('Y'))
            ->findAll();
    }
}
