<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelAnalisis extends Model
{
    protected $table = 'hasil_analisis_apriori';
    protected $primaryKey = 'id_analisis';
    protected $allowedFields = ['produk_1', 'produk_2', 'support', 'confidence', 'lift', 'bulan', 'tahun'];

    // unuk simpan
    public function simpanHasilAnalisis($data)
    {
        return $this->insertBatch($data);
    }
    // untuk hapus
    public function hapusHasilAnalisis($bulan, $tahun)
    {
        return $this->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->delete();
    }
}
