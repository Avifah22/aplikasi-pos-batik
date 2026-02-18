<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelUser extends Model
{
  protected $table            = 'user';
  protected $primaryKey       = 'id_user';
  protected $allowedFields    = ['id_user', 'username', 'nama_user', 'password', 'level', 'tanggal'];

  public function CekLogin($username)
  {
    return $this->db->table('user')
      ->where([
        'username' => $username,
      ])->get()->getRowArray();
  }
  public function Cari($keyword)
  {
    return $this->table('user')->like('nama_user', $keyword);
  }
}
