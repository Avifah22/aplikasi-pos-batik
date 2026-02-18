<?php

namespace App\Controllers;

use App\Models\ModelPenjualan;
use App\Models\ModelPenjualanProduk;
use App\Models\ModelProduk;
use App\Models\ModelRetur;
use CodeIgniter\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Config\Database;

class Laporan extends Controller
{
    protected $penjualanModel;
    protected $penjualanProdukModel;
    protected $produkModel;
    protected $returModel;

    public function __construct()
    {
        $this->penjualanModel = new ModelPenjualan();
        $this->penjualanProdukModel = new ModelPenjualanProduk();
        $this->produkModel = new ModelProduk();
        $this->returModel = new ModelRetur();
    }

    public function index()
    {
        $laporanPenjualan = $this->penjualanModel
            ->join('penjualan_produk', 'penjualan.no_faktur = penjualan_produk.no_faktur')
            ->join('produk', 'penjualan_produk.id_produk = produk.id_produk')
            ->join('user', 'penjualan.id_user = user.id_user')
            ->select('penjualan.no_faktur, produk.nama_produk, penjualan_produk.qty, penjualan_produk.harga_satuan, penjualan_produk.harga_total, penjualan.total_transaksi, penjualan.id_user, penjualan.tanggal as tanggal_masuk, user.nama_user as user')
            ->orderBy('penjualan.no_faktur', 'ASC');

        $keyword = $this->request->getVar('search');
        if ($keyword) {
            $laporanPenjualan = $laporanPenjualan->like('penjualan.tanggal', $keyword); // Filter berdasarkan tanggal
        }

        // jenis laporan
        $laporanType = $this->request->getVar('j_lapPen');
        if ($laporanType == 'laporan bulanan') {
            $laporanPenjualan->where('MONTH(penjualan.tanggal)', date('m'))
                ->where('YEAR(penjualan.tanggal)', date('Y'));
        } elseif ($laporanType == 'laporan hari ini') {
            $laporanPenjualan->where('DATE(penjualan.tanggal)', date('Y-m-d'));
        }

        $perPage = 6;
        $page = $this->request->getVar('page_laporan') ?: 1;

        $laporanPenjualanPaginated = $laporanPenjualan->paginate($perPage, 'laporan');
        $laporanPenjualanResult = $laporanPenjualanPaginated;
        $offset = ($page - 1) * $perPage;

        // Hitung total (qty dan harga_satuan)
        foreach ($laporanPenjualanResult as $key => $data) {
            $laporanPenjualanResult[$key]['total'] = isset($data['qty']) && isset($data['harga_satuan']) ? $data['qty'] * $data['harga_satuan'] : 0;
        }

        $data = [
            'judul'         => 'Laporan',
            'subjudul'      => 'laporan transaksi',
            'menu'          => 'laporan',
            'submenu'       => 'laporan transaksi',
            'page'          => 'v_laporan',
            'laporanData'   => $laporanPenjualanResult,
            'pager'         => $laporanPenjualan->pager,
            'searchKeyword' => $keyword,
            'laporanType'   => $laporanType,
            'offset'         => $offset,
        ];

        return view('v.template/viewtemp', $data);
    }

    public function laporanRetur()
    {
        $d_retur = $this->returModel
            ->join('user', 'retur.id_user = user.id_user')
            ->select('retur.*, user.nama_user');

        $jenis_laporan = $this->request->getVar('jenis_laporan');

        if ($jenis_laporan == 'hari_ini') {
            $start_date = date('Y-m-d 00:00:00');
            $end_date = date('Y-m-d 23:59:59');
            $d_retur = $this->returModel
                ->where('created_at >=', $start_date)
                ->where('created_at <=', $end_date)
                ->paginate(5, 'lap_ret');
        } elseif ($jenis_laporan == 'minggu_ini') {
            $start_date = date('Y-m-d', strtotime('monday this week'));
            $end_date = date('Y-m-d', strtotime('sunday this week'));
            $d_retur = $this->returModel
                ->where('created_at >=', $start_date)
                ->where('created_at <=', $end_date)
                ->paginate(5, 'lap_ret');
        } elseif ($jenis_laporan == 'bulan_ini') {
            $start_date = date('Y-m-01');
            $end_date = date('Y-m-t');
            $d_retur = $this->returModel
                ->where('created_at >=', $start_date)
                ->where('created_at <=', $end_date)
                ->paginate(5, 'lap_ret');
        } else {
            $d_retur = $this->returModel->paginate(5, 'lap_ret'); // Tampilkan semua data
        }

        $data = [
            'judul'    => 'Laporan',
            'subjudul' => 'laporan retur',
            'menu'     => 'laporan',
            'submenu'  => 'laporan retur',
            'page'     => 'v_laporanretur',
            'd_retur'  => $d_retur,
            'pager'    => $this->returModel->pager,
        ];

        return view('v.template/viewtemp', $data);
    }

    public function exportExcel()
    {
        $db = Database::connect();

        $builder = $db->table('penjualan')
            ->select('penjualan.no_faktur, produk.nama_produk, penjualan_produk.qty, penjualan_produk.harga_satuan, penjualan_produk.harga_total, penjualan.total_transaksi, penjualan.id_user, penjualan.tanggal as tanggal_masuk, user.nama_user as user')
            ->join('penjualan_produk', 'penjualan.no_faktur = penjualan_produk.no_faktur')
            ->join('produk', 'penjualan_produk.id_produk = produk.id_produk')
            ->join('user', 'penjualan.id_user = user.id_user')
            ->orderBy('penjualan.no_faktur');

        $laporanData = $builder->get()->getResultArray();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No Faktur');
        $sheet->setCellValue('B1', 'Nama Produk');
        $sheet->setCellValue('C1', 'QTY');
        $sheet->setCellValue('D1', 'Harga Satuan');
        $sheet->setCellValue('E1', 'Total');
        $sheet->setCellValue('F1', 'Pendapatan');
        $sheet->setCellValue('G1', 'User');
        $sheet->setCellValue('H1', 'Tanggal Masuk');

        $currentNoFaktur = '';
        $totalPendapatan = 0;
        $row = 2;

        foreach ($laporanData as $data) {
            if ($data['no_faktur'] != $currentNoFaktur) {
                $currentNoFaktur = $data['no_faktur'];
                $sheet->setCellValue('A' . $row, $data['no_faktur']);
                $sheet->setCellValue('B' . $row, $data['nama_produk']);
                $sheet->setCellValue('C' . $row, $data['qty']);
                $sheet->setCellValue('D' . $row, number_format($data['harga_satuan'], 2));
                $sheet->setCellValue('E' . $row, number_format($data['harga_total'], 2));
                $sheet->setCellValue('F' . $row, number_format($data['total_transaksi'], 2));
                $sheet->setCellValue('G' . $row, $data['user']);
                $sheet->setCellValue('H' . $row, date('d-m-Y', strtotime($data['tanggal_masuk'])));
                $totalPendapatan += $data['total_transaksi'];
                $row++;
            } else {
                $sheet->setCellValue('B' . $row, $data['nama_produk']);
                $sheet->setCellValue('C' . $row, $data['qty']);
                $sheet->setCellValue('D' . $row, number_format($data['harga_satuan'], 2));
                $sheet->setCellValue('E' . $row, number_format($data['harga_total'], 2));
                $sheet->setCellValue('G' . $row, $data['user']);
                $sheet->setCellValue('H' . $row, date('d-m-Y', strtotime($data['tanggal_masuk'])));
                $row++;
            }
        }

        $sheet->setCellValue('F' . $row, 'Total Pendapatan');
        $sheet->setCellValue('G' . $row, number_format($totalPendapatan, 2));

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'laporan_penjualan.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function exportExcelRetur()
    {
        $db = Database::connect();

        $builder = $db->table('retur')
            ->select('retur.id_produk, produk.nama_produk, retur.qty_retur, retur.alasan_retur, supplier.nama_supplier, supplier.no_hp, supplier.alamat')
            ->join('produk', 'produk.id_produk = retur.id_produk')
            ->join('supplier', 'supplier.id_supplier = produk.id_supplier')
            ->orderBy('supplier.nama_supplier, produk.nama_produk');

        $laporanData = $builder->get()->getResultArray();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Nama Supplier');
        $sheet->setCellValue('B1', 'No HP Supplier');
        $sheet->setCellValue('C1', 'Alamat');
        $sheet->setCellValue('D1', 'Nama Produk');
        $sheet->setCellValue('E1', 'Alasan Retur');
        $sheet->setCellValue('F1', 'Qty Retur');

        $row = 2;
        $currentSupplier = '';

        foreach ($laporanData as $data) {
            // Jeda untuk nama supplier selanjutnya dengan baris kosong
            if ($data['nama_supplier'] != $currentSupplier) {
                if ($currentSupplier != '') {
                    $row++;
                }

                $sheet->setCellValue('A' . $row, $data['nama_supplier']);
                $sheet->setCellValue('B' . $row, $data['no_hp']);
                $sheet->setCellValue('C' . $row, $data['alamat']);
                $row++;
            }

            $sheet->setCellValue('D' . $row, $data['nama_produk']);
            $sheet->setCellValue('E' . $row, $data['alasan_retur']);
            $sheet->setCellValue('F' . $row, $data['qty_retur']);
            $row++;

            $currentSupplier = $data['nama_supplier'];
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'laporanReturSupplier.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }
}
