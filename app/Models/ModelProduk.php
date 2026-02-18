<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelProduk extends Model
{
    protected $table            = 'produk';
    protected $primaryKey       = 'id_produk';
    protected $allowedFields    = ['id_produk', 'nama_produk', 'harga_satuan', 'warna', 'ukuran', 'id_kategori', 'id_supplier', 'created_at', 'updated_at'];


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
    public function getLastProduk()
    {
        return $this->orderBy('id_produk', 'desc')->first();
    }
    public function getProdukWithKategori()
    {
        return $this->db->table('produk')
            ->select('produk.*, kategori.nama_kategori as kategori')
            ->join('kategori', 'produk.id_kategori = kategori.id_kategori', 'left')
            ->get()->getResultArray();
    }

    public function getProdukWithSupplier()
    {
        return $this->db->table('produk')
            ->select('produk.*, supplier.nama_supplier as supplier')
            ->join('supplier', 'produk.id_supplier = supplier.id_supplier', 'left')
            ->get()->getResultArray();
    }

    public function getStokById($id_produk)
    {
        $builder = $this->db->table('produk');
        $builder->select('produk.*, stok.stok');
        $builder->join('stok', 'produk.id_produk = stok.id_produk');
        $builder->where('produk.id_produk', $id_produk);
        $query = $builder->get();
        return $query->getRow();
    }
}
