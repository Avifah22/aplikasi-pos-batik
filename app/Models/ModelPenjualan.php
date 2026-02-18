<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelPenjualan extends Model
{
  protected $table            = 'penjualan';
  protected $primaryKey       = 'id_penjualan';
  protected $allowedFields    = ['no_faktur', 'total_transaksi', 'uang_yang_diserahkan', 'metode_pembayaran', 'bukti_transfer', 'id_user', 'tanggal'];

  public function NoFaktur()
  {
    $tgl = date('Ymd');
    $query = $this->db->query("SELECT MAX(RIGHT(no_faktur,4))as no_urut from penjualan where Date(tanggal)='$tgl'");
    $hasil = $query->getRowArray();
    $no_urut = intval($hasil['no_urut']);

    if ($no_urut > 0) {
      $tmp = $no_urut + 1;
      $kd = sprintf("%04s", $tmp);
    } else {
      $kd = "0001";
    }
    $no_faktur = $tgl . $kd;
    return $no_faktur;
  }
  public function getPenjualan($no_faktur)
  {
    return $this->where('no_faktur', $no_faktur)->first();
  }
}
