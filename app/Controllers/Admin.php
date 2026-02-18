<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ModelAdmin;

class Admin extends Controller
{
    protected $modelAdmin;
    protected $session;

    public function __construct()
    {
        // Inisialisasi session dan model
        $this->session = \Config\Services::session();
        $this->modelAdmin = new ModelAdmin();
    }

    public function index(): string
    {
        // Ambil data dari model
        $penjualanMingguan = $this->modelAdmin->getJumlahTransaksiMingguan();
        $produk = $this->modelAdmin->getProdukCount();
        $kategori = $this->modelAdmin->getKategoriCount();
        $barangRetur = $this->modelAdmin->getBarangReturCount();
        $penjualanData = $this->modelAdmin->getPenjualanBulanan();
        $returData = $this->modelAdmin->getReturBulanan();

        // Kirim data ke view
        $data = [
            'judul' => 'Dashboard',
            'subjudul' => '',
            'menu' => 'dashboard',
            'submenu' => '',
            'page' => 'v_admin',
            'penjualanMingguan' => $penjualanMingguan,
            'produk' => $produk,
            'kategori' => $kategori,
            'barangRetur' => $barangRetur,
            'penjualanData' => $penjualanData,
            'returData' => $returData,
        ];

        return view('v.template/viewtemp', $data);
    }
}
