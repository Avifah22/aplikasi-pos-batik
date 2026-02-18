<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelAdmin extends Model
{
    public function getJumlahTransaksiMingguan()
    {
        return $this->db->table('penjualan')
            ->where('WEEK(tanggal)', 'WEEK(CURDATE())', false)
            ->countAllResults();
    }

    public function getProdukCount()
    {
        return $this->db->table('produk')
            ->countAllResults();
    }

    public function getKategoriCount()
    {
        return $this->db->table('kategori')
            ->countAllResults();
    }

    public function getBarangReturCount()
    {
        return $this->db->table('retur')
            ->countAllResults();
    }

    public function getPenjualanBulanan()
    {
        return $this->db->table('penjualan_produk')
            ->select('MONTH(created_at) AS bulan, YEAR(created_at) AS tahun, SUM(qty * harga_satuan) AS total_penjualan')
            ->groupBy('YEAR(created_at), MONTH(created_at)')
            ->orderBy('tahun ASC, bulan ASC')
            ->get()
            ->getResultArray();
    }

    public function getReturBulanan()
    {
        return $this->db->table('retur')
            ->select('MONTH(created_at) AS bulan, YEAR(created_at) AS tahun, SUM(qty_retur * harga_satuan) AS total_retur')
            ->groupBy('YEAR(created_at), MONTH(created_at)')
            ->orderBy('tahun ASC, bulan ASC')
            ->get()
            ->getResultArray();
    }
}
