<?php

namespace App\Controllers;

use App\Models\ModelUser;
use CodeIgniter\Controller;

class User extends Controller
{
    protected $ModelUser;

    public function __construct()
    {
        $this->ModelUser = new ModelUser();
    }

    public function index()
    {
        $page = $this->request->getVar('page_users') ?: 1;
        $perPage = 6;

        $user = $this->ModelUser
            ->select('user.*');

        $keyword = $this->request->getVar('keyword');
        if ($keyword) {
            $user = $user->like('user.username', $keyword);
        }

        $userPaginated = $user->paginate($perPage, 'users');
        $offset = ($page - 1) * $perPage;

        $data = [
            'judul' => 'Master Data',
            'subjudul' => 'User',
            'menu' => 'masterdata',
            'submenu' => 'user',
            'page' => 'v_user',
            'user' => $userPaginated,
            'pager' => $this->ModelUser->pager,
            'offset' => $offset,
            'keyword' => $keyword,
        ];

        return view('v.template/viewtemp', $data);
    }

    public function InsertData()
    {
        $data = [
            'username' => $this->request->getPost('username'),
            'nama_user' => $this->request->getPost('nama_user'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT), // password di-hash
            'level' => $this->request->getPost('level'),
            'tanggal' => date('Y-m-d H:i:s'),
        ];

        $this->ModelUser->insert($data);
        session()->setFlashdata('pesan', 'Data ditambahkan');
        return redirect()->to('User');
    }

    public function UpdateData($id_user)
    {
        $data = [
            'username' => $this->request->getPost('username'),
            'nama_user' => $this->request->getPost('nama_user'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT), // password di-hash
            'level' => $this->request->getPost('level'),
        ];
        $this->ModelUser->update($id_user, $data);
        session()->setFlashdata('pesan', 'Data diupdate');
        return redirect()->to('User');
    }

    public function HapusData($id_user)
    {
        $this->ModelUser->delete($id_user);
        session()->setFlashdata('pesan', 'Data Berhasil dihapus');
        return redirect()->to('User');
    }
}
