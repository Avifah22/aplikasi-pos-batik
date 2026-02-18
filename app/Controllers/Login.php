<?php

namespace App\Controllers;

use App\Models\ModelUser;

class Login extends BaseController
{
    protected $ModelUser;
    public function __construct()
    {
        $this->ModelUser = new ModelUser();
    }

    public function index()
    {
        // Cek login
        if (session()->get('id_user')) {
            if (session()->get('level') == 1) {
                return redirect()->to('/Admin');
            } else {
                return redirect()->to('/Penjualan');
            }
        }

        return view('v_login');
    }

    public function LoginUser()
    {
        if ($this->validate([
            'username' => [
                'label' => 'Username',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} masih kosong!',
                ]
            ],
            'password' => [
                'label' => 'Password',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} masih kosong!',
                ]
            ],
        ])) {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            $cek_login = $this->ModelUser->CekLogin($username);
            if ($cek_login) {
                if (password_verify($password, $cek_login['password'])) {
                    session()->set([
                        'id_user'   => $cek_login['id_user'],
                        'nama_user' => $cek_login['nama_user'],
                        'level'     => $cek_login['level']
                    ]);

                    if ($cek_login['level'] == 1) {
                        return redirect()->to('/Admin');
                    } else {
                        return redirect()->to('/Penjualan');
                    }
                } else {
                    session()->setFlashdata('gagal', 'Username atau Password Salah!');
                    return redirect()->to('/Login');
                }
            } else {
                session()->setFlashdata('gagal', 'Username atau Password Salah!');
                return redirect()->to('/Login');
            }
        } else {
            session()->setFlashdata('errors', \Config\Services::validation()->getErrors());
            return redirect()->to('/Login')->withInput();
        }
    }

    public function Logout()
    {
        session()->remove(['id_user', 'nama_user', 'level']);
        session()->setFlashdata('pesan', 'Anda sudah Logout');
        return redirect()->to('/Login');
    }
}
