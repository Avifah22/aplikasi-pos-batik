<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelStockHistory extends Model
{

    protected $table = 'stok_history';
    protected $primaryKey = 'id_stok_history';
    protected $allowedFields = ['id_produk', 'perubahan_stok', 'qty_setelah_ubah', 'tanggal_transaksi', 'tipe_transaksi', 'id_supplier', 'id_user', 'created_at', 'updted_at'];
    protected $useTimestamps = true;
}
