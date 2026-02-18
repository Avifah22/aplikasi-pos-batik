<?php

namespace App\Controllers;

use App\Models\ModelStockHistory;
use App\Models\ModelStok;
use App\Models\ModelProduk;
use CodeIgniter\Controller;

class StokHistory extends Controller
{
    protected $stockHistoryModel;
    protected $produkModel;
    protected $stokModel;

    public function __construct()
    {
        $this->stockHistoryModel = new ModelStockHistory();
        $this->produkModel = new ModelProduk();
        $this->stokModel = new ModelStok();
    }

    public function index()
    {
        $page = $this->request->getVar('page_stockhist') ?: 1;
        $perPage = 6;

        $stockData = $this->stokModel
            ->join('produk', 'produk.id_produk = stok.id_produk', 'left')
            ->join('supplier', 'supplier.id_supplier = produk.id_supplier', 'left')
            ->select('stok.*, supplier.nama_supplier, produk.*');

        $keyword = $this->request->getVar('keyword');
        if ($keyword) {
            $stockData = $stockData->like('produk.nama_produk', $keyword);
        }

        $stokPaginated = $stockData->paginate($perPage, 'stocks');
        $offset = ($page - 1) * $perPage;
        $produks = $this->produkModel->findAll();

        $data = [
            'judul'    => 'Laporan',
            'subjudul' => 'laporan stok',
            'menu'     => 'laporan',
            'submenu'  => 'laporan stok',
            'page'     => 'stok/v_stok',
            'stockData' => $stokPaginated,
            'produks' => $produks,
            'pager' => $this->stokModel->pager,
            'offset' => $offset,
            'keyword' => $keyword,
        ];

        return view('v.template/viewtemp', $data);
    }

    public function detail($productId)
    {
        $page = $this->request->getVar('page_stockhist') ?: 1;
        $perPage = 6;

        $stokhistQuery = $this->stockHistoryModel
            ->where('stok_history.id_produk', $productId)
            ->join('supplier', 'stok_history.id_supplier = supplier.id_supplier', 'left')
            ->join('user', 'stok_history.id_user = user.id_user', 'left')
            ->orderBy('tanggal_transaksi', 'DESC')
            ->select('stok_history.*, supplier.nama_supplier, user.nama_user');

        $stokhistPaginated = $stokhistQuery->paginate($perPage, 'stocks');
        $offset = ($page - 1) * $perPage;
        $produk = $this->produkModel->find($productId);

        $data = [
            'judul'        => 'Laporan',
            'subjudul'     => 'laporan stok',
            'menu'         => 'laporan',
            'submenu'      => 'laporan stok',
            'page'         => 'v_stokHist',
            'historyData'  => $stokhistPaginated,
            'produk'       => $produk,
            'pager'        => $this->stockHistoryModel->pager,
            'offset'       => $offset,
        ];

        return view('v.template/viewtemp', $data);
    }

    public function InsertData()
    {
        $data = [
            'id_produk' => $this->request->getPost('id_produk'),
            'stok_terkini' => $this->request->getPost('stok_terkini'),
        ];

        $this->stokModel->insert($data);
        session()->setFlashdata('pesan', 'Data ditambahkan');
        return redirect()->to('StokHistory');
    }
}
