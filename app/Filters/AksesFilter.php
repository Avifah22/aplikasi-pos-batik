<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AksesFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
   public function before(RequestInterface $request, $arguments = null)
{
    // Mendapatkan nama controller yang sedang diakses
    $controller = service('uri')->getSegment(1); // Mendapatkan controller (misalnya "FormRetur")

    // Memeriksa apakah pengguna sudah login, kecuali pada halaman FormRetur
    if (!session()->get('id_user') && $controller !== 'FormRetur') {
        return redirect()->to('/Login');
    }

    // Mendapatkan level pengguna yang sedang login
    $level = session()->get('level');
    $uri = service('uri')->getSegment(1); // Mendapatkan segmen pertama dari URI untuk mengecek akses halaman

    // Jika level pegawai, hanya boleh mengakses "penjualan", "retur", dan "stok"
    if ($level == 2) { // Misal, level 2 untuk pegawai
        if ($uri != 'Penjualan' && $uri != 'Retur' && $uri != 'Stok' && $controller !== 'FormRetur') {
            return redirect()->to('/Penjualan');  // Redirect jika mencoba mengakses halaman lain selain Penjualan, Retur, dan FormRetur
        }
    }

    // Jika level pemilik (misal level 1), jangan boleh mengakses "Penjualan", "Retur", dan "Stok"
    if ($level == 1) { // Misal, level 1 untuk pemilik
        if ($uri == 'Penjualan' || $uri == 'Retur' || $uri == 'Stok') {
            return redirect()->to('/Admin');  // Redirect jika mencoba mengakses Penjualan, Retur, atau Stok
        }
    }

    return null; // Jika tidak ada masalah, lanjutkan permintaan
}


    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
