<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelSupplier extends Model
{
    protected $table            = 'supplier';
    protected $primaryKey       = 'id_supplier';
    protected $allowedFields    = ['id_supplier', 'nama_supplier', 'email', 'no_hp', 'alamat', 'created_at', 'updated_at'];
}
