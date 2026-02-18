<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelStok extends Model
{

    protected $table = 'stok';
    protected $primaryKey = 'id_stok';
    protected $allowedFields = ['id_produk', 'stok_terkini'];
    protected $useTimestamps = false;
}
