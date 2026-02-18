<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelPenjualanProduk extends Model
{
    protected $table            = 'penjualan_produk';
    protected $primaryKey       = 'id_penjualan_produk';
    protected $allowedFields    = ['id_penjualan_produk', 'no_faktur', 'id_produk', 'qty', 'harga_satuan', 'harga_total', 'diskon', 'harga_total_setelah_diskon', 'created_at'];

    public function getProdukByFaktur($no_faktur)
    {

        $produkData = $this->db->table('penjualan_produk')
            ->join('produk', 'penjualan_produk.id_produk = produk.id_produk')
            ->select('produk.id_produk, produk.nama_produk, penjualan_produk.qty, penjualan_produk.harga_satuan,penjualan_produk.harga_total')
            ->where('penjualan_produk.no_faktur', $no_faktur)
            ->get()
            ->getResultArray();

        return $produkData;
    }
    public function getHargaSatuanById($idProduk)
    {
        // ambil harga satuan dari produk berdasarkan id produk
        $result = $this->db->table('penjualan_produk')
            ->select('harga_satuan')
            ->where('id_produk', $idProduk)
            ->limit(1)
            ->get()
            ->getRowArray();

        return $result ? $result['harga_satuan'] : null;
    }
    public function getProdukByFakturAndId($noFaktur, $idProduk)
    {
        return $this->db->table($this->table)
            ->select('id_penjualan_produk, id_produk, no_faktur, qty, harga_satuan')
            ->where('no_faktur', $noFaktur)
            ->where('id_produk', $idProduk)
            ->get()
            ->getRowArray();
    }
    public function getDataTransaksi()
    {
        // ambil data transaksi, hanya id produk dari setiap transaksi
        $builder = $this->db->table('penjualan_produk');
        $builder->select('no_faktur, GROUP_CONCAT(id_produk) as id_produk');
        $builder->groupBy('no_faktur');
        return $builder->get()->getResult();
    }
}
