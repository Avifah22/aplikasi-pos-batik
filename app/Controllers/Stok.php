<?php

namespace App\Controllers;

use App\Models\ModelProduk;
use App\Models\ModelStok;
use App\Models\ModelStockHistory;
use CodeIgniter\Controller;

class Stok extends Controller
{
    protected $produkModel;
    protected $stokModel;
    protected $stokHistoryModel;

    public function __construct()
    {
        $this->produkModel = new ModelProduk();
        $this->stokModel = new ModelStok();
        $this->stokHistoryModel = new ModelStockHistory();
    }

    public function index()
    {
        $page = $this->request->getVar('page_stocks') ?: 1;
        $perPage = 6;

        $stockData = $this->stokModel
            ->join('produk', 'produk.id_produk = stok.id_produk', 'left')
            ->join('supplier', 'supplier.id_supplier = produk.id_supplier', 'left')
            ->select('stok.*, supplier.nama_supplier, produk.*');

        $keyword = $this->request->getVar('keyword');
        if ($keyword) {
            $stockData = $stockData->like('produk.nama_produk', $keyword);
        }

        $stokPaginated = $stockData->paginate($perPage, 'admin_pagination');
        $offset = ($page - 1) * $perPage;
        $stockData = $stockData->findAll();

        $data = [
            'judul' => 'Stok',
            'stockData' => $stokPaginated,
            'pager' => $this->stokModel->pager,
            'offset' => $offset,
            'keyword' => $keyword,
        ];

        return view('pegawai/v_stok', $data);
    }

    public function view($id_produk)
    {
        $stock = $this->stokModel->where('id_produk', $id_produk)->first();
        $produk = $this->produkModel->find($id_produk);

        $data = [
            'stok' => $stock,
            'produk' => $produk,
        ];

        return view('pegawai/v_stok_detail', $data);
    }

    public function restock($id_produk)
    {
        $produk = $this->produkModel->find($id_produk);
        $stok = $this->stokModel->where('id_produk', $id_produk)->first();

        if (!$produk || !$stok) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Produk atau Stok tidak ditemukan.");
        }

        $supplier = $this->produkModel
            ->join('supplier', 'supplier.id_supplier = produk.id_supplier', 'left')
            ->where('produk.id_produk', $id_produk)
            ->first();
        $nama_supplier = $supplier['id_supplier'];
        $user = $this->stokHistoryModel
            ->join('user', 'user.id_user = stok_history.id_user', 'left')
            ->select('user.*, stok_history.*')
            ->findAll();

        $stock_change = $this->request->getPost('perubahan_stok');
        $user = $this->request->getPost('id_user');
        $transaction_type = 'Restok';

        $new_stock = $stok['stok_terkini'] + $stock_change;
        $this->stokModel->update($stok['id_stok'], ['stok_terkini' => $new_stock]);

        $data = [
            'id_produk' => $id_produk,
            'perubahan_stok' => $stock_change,
            'qty_setelah_ubah' => $new_stock,
            'tanggal_transaksi' => date('Y-m-d H:i:s'),
            'tipe_transaksi' => $transaction_type,
            'id_supplier' => $nama_supplier,
            'id_user' => $user,
        ];

        if (!$this->stokHistoryModel->save($data)) {
            log_message('error', 'Gagal menyimpan riwayat stok: ' . print_r($this->stokHistoryModel->errors(), true));
        }

        session()->setFlashdata('pesan', 'Stok berhasil diperbarui!');
        return redirect()->to(site_url('Stok/view/' . $id_produk));
    }
}
