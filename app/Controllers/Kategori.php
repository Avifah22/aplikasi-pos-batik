<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ModelKategori;

class Kategori extends Controller
{
    protected $Modelkategori;

    public function __construct()
    {
        $this->Modelkategori = new ModelKategori();
    }

    public function index()
    {
        $page = $this->request->getVar('page_kategoris') ?: 1;
        $perPage = 6;

        $kategori = $this->Modelkategori->select('kategori.*');

        $keyword = $this->request->getVar('keyword');
        if ($keyword) {
            $kategori = $kategori->like('kategori.nama_kategori', $keyword);
        }

        $kategoriPaginated = $kategori->paginate($perPage, 'kategoris');

        $offset = ($page - 1) * $perPage;
        $data = [
            'judul' => 'Master Data',
            'subjudul' => 'Kategori',
            'menu' => 'masterdata',
            'submenu' => 'kategori',
            'page' => 'v_kategori',
            'kategori' => $kategoriPaginated,
            'pager' => $this->Modelkategori->pager,
            'offset' => $offset,
            'keyword' => $keyword,
        ];

        return view('v.template/viewtemp', $data);
    }

    public function InsertData()
    {
        $last_kategori = $this->Modelkategori->getLastKategori();

        if ($last_kategori) {
            $last_id = $last_kategori['id_kategori'];
            $number = (int) substr($last_id, 2);
            $new_number = str_pad($number + 1, 2, "0", STR_PAD_LEFT);
            $new_id = "K-" . $new_number;
        } else {
            $new_id = "K-01";
        }

        while ($this->Modelkategori->find($new_id)) {
            $number = (int) substr($new_id, 2);
            $new_number = str_pad($number + 1, 2, "0", STR_PAD_LEFT);
            $new_id = "K-" . $new_number;
        }

        $data = [
            'id_kategori' => $new_id,
            'nama_kategori' => $this->request->getPost('nama_kategori'),
        ];

        $this->Modelkategori->insert($data);
        session()->setFlashdata('pesan', 'Data ditambahkan');
        return redirect()->to('Kategori');
    }

    public function UpdateData($id_kategori)
    {
        $data = [
            'nama_kategori' => $this->request->getPost('nama_kategori'),
        ];

        $this->Modelkategori->update($id_kategori, $data);
        session()->setFlashdata('pesan', 'Data diupdate');
        return redirect()->to('Kategori');
    }

    public function HapusData($id_kategori)
    {
        $this->Modelkategori->delete($id_kategori);
        session()->setFlashdata('pesan', 'Data berhasil dihapus');
        return redirect()->to('Kategori');
    }
}
