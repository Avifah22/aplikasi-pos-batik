<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelKategori extends Model
{
    protected $table            = 'kategori';
    protected $primaryKey       = 'id_kategori';
    protected $allowedFields    = ['id_kategori', 'nama_kategori'];

    public function getLastKategori()
    {
        return $this->orderBy('id_kategori', 'desc')->first();
    }
}
