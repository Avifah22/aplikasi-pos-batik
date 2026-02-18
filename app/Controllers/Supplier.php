<?php


namespace App\Controllers;

use App\Models\ModelSupplier;
use CodeIgniter\Controller;

class Supplier extends Controller
{
    protected $ModelSupplier;

    public function __construct()
    {
        $this->ModelSupplier = new ModelSupplier();
    }

    public function index()
    {
        $page = $this->request->getVar('page_suppliers') ?: 1;
        $perPage = 6;

        $supplier = $this->ModelSupplier
            ->select('supplier.*, GROUP_CONCAT(produk.id_produk) as produk_names')
            ->join('produk', 'produk.id_supplier = supplier.id_supplier', 'left')
            ->groupBy('supplier.id_supplier');

        $keyword = $this->request->getVar('keyword');
        if ($keyword) {
            $supplier = $supplier->like('supplier.nama_supplier', $keyword);
        }

        // Pagination
        $supplierPaginated = $supplier->paginate($perPage, 'suppliers');
        $offset = ($page - 1) * $perPage;

        $data = [
            'judul' => 'Master Data',
            'subjudul' => 'Supplier',
            'menu' => 'masterdata',
            'submenu' => 'supplier',
            'page' => 'v_supplier',
            'supplier' => $supplierPaginated,
            'pager' => $this->ModelSupplier->pager,
            'offset' => $offset,
            'keyword' => $keyword,
        ];

        return view('v.template/viewtemp', $data);
    }

    public function InsertData()
    {
        $data = [
            'nama_supplier' => $this->request->getPost('nama_supplier'),
            'email' => $this->request->getPost('email'),
            'no_hp' => $this->request->getPost('no_hp'),
            'alamat' => $this->request->getPost('alamat'),
        ];
        if (strlen($data['no_hp']) < 12 || strlen($data['no_hp']) > 13) {
            session()->setFlashdata('error', 'Nomor HP harus memiliki panjang 12 - 13 angka.');
            return redirect()->back()->withInput();
        }

        $this->ModelSupplier->insert($data);
        session()->setFlashdata('pesan', 'Data ditambahkan');
        return redirect()->to('Supplier');
    }

    public function UpdateData($id_supplier)
    {
        $data = [
            'nama_supplier' => $this->request->getPost('nama_supplier'),
            'email' => $this->request->getPost('email'),
            'no_hp' => $this->request->getPost('no_hp'),
            'alamat' => $this->request->getPost('alamat'),
        ];

        $this->ModelSupplier->update($id_supplier, $data);
        session()->setFlashdata('pesan', 'Data diupdate');
        return redirect()->to('Supplier');
    }

    public function HapusData($id_supplier)
    {
        $this->ModelSupplier->delete($id_supplier);
        session()->setFlashdata('pesan', 'Data Berhasil dihapus');
        return redirect()->to('Supplier');
    }
}
