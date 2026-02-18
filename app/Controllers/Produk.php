<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ModelProduk;
use App\Models\ModelKategori;
use App\Models\ModelSupplier;

class Produk extends Controller
{
    protected $ModelProduk;
    protected $ModelKategori;
    protected $ModelSupplier;

    public function __construct()
    {
        $this->ModelProduk = new ModelProduk();
        $this->ModelKategori = new ModelKategori();
        $this->ModelSupplier = new ModelSupplier();
    }

    public function index()
    {
        $page = $this->request->getVar('page_produks') ?: 1;
        $perPage = 6;

        $produk = $this->ModelProduk
            ->select('produk.*, kategori.nama_kategori, supplier.nama_supplier')
            ->join('kategori', 'kategori.id_kategori = produk.id_kategori', 'left')
            ->join('supplier', 'produk.id_supplier = supplier.id_supplier', 'left');

        $keyword = $this->request->getVar('keyword');
        if ($keyword) {
            $produk =  $produk->like('produk.nama_produk', $keyword);
        }

        $produkPaginated = $produk->paginate($perPage, 'produks');
        $offset = ($page - 1) * $perPage;

        $data = [
            'judul' => 'Master Data',
            'subjudul' => 'Produk',
            'menu' => 'masterdata',
            'submenu' => 'produk',
            'page' => 'v_produk',
            'produk' => $produkPaginated,
            'supplier' => $this->ModelSupplier->findAll(),
            'kategori' => $this->ModelKategori->findAll(),
            'pager' => $this->ModelProduk->pager,
            'offset' => $offset,
            'keyword' => $keyword,
        ];

        return view('v.template/viewtemp', $data);
    }

    public function InsertData()
    {
        $last_produk = $this->ModelProduk->getLastProduk();

        if ($last_produk) {
            $last_id = $last_produk['id_produk'];
            $number = (int) substr($last_id, 2);
            $new_number = str_pad($number + 1, 3, "0", STR_PAD_LEFT);
            $new_id = "P-" . $new_number;
        } else {
            $new_id = "P-001";
        }

        while ($this->ModelProduk->find($new_id)) {
            $number = (int) substr($new_id, 2);
            $new_number = str_pad($number + 1, 3, "0", STR_PAD_LEFT);
            $new_id = "P-" . $new_number;
        }

        $data = [
            'id_produk' => $new_id,
            'nama_produk' => $this->request->getPost('nama_produk'),
            'harga_satuan' => $this->request->getPost('harga_satuan'),
            'warna' => $this->request->getPost('warna'),
            'ukuran' => $this->request->getPost('ukuran'),
            'id_kategori' => $this->request->getPost('id_kategori'),
            'id_supplier' => $this->request->getPost('id_supplier'),
        ];

        $this->ModelProduk->insert($data);
        session()->setFlashdata('pesan', 'Data ditambahkan');
        return redirect()->to('Produk');
    }

    public function UpdateData($id_produk)
    {
        $data = [
            'id_produk' => $this->request->getPost('id_produk'),
            'nama_produk' => $this->request->getPost('nama_produk'),
            'harga_satuan' => $this->request->getPost('harga_satuan'),
            'warna' => $this->request->getPost('warna'),
            'ukuran' => $this->request->getPost('ukuran'),
            'id_kategori' => $this->request->getPost('id_kategori'),
            'id_supplier' => $this->request->getPost('id_supplier'),
        ];

        $this->ModelProduk->update($id_produk, $data);
        session()->setFlashdata('pesan', 'Data diupdate');
        return redirect()->to('Produk');
    }

    public function HapusData($id_produk)
    {
        $this->ModelProduk->delete($id_produk);
        session()->setFlashdata('pesan', 'Data Berhasil dihapus');
        return redirect()->to('Produk');
    }
}
