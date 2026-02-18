<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ModelPenjualanProduk;
use App\Models\ModelPenjualan;
use App\Models\ModelProduk;
use App\Models\ModelAnalisis;

class Analisis extends Controller
{
    public function index()
    {
        $page = $this->request->getVar('page_analis') ?: 1;
        $perPage = 6;

        $analisis = new ModelAnalisis();
        $analisisQuery = $analisis->select('hasil_analisis_apriori.*');

        $analisPaginated = $analisisQuery->paginate($perPage, 'analis');
        $offset = ($page - 1) * $perPage;

        $data = [
            'judul' => 'Analisis',
            'subjudul' => '',
            'menu' => 'analisis',
            'submenu' => '',
            'page' => 'v_analisis',
            'hasil' => $analisPaginated,
            'pager' => $analisis->pager,
            'offset' => $offset,
        ];

        return view('v.template/viewtemp', $data);
    }

    public function proses()
    {
        $minSupport = $this->request->getPost('support');
        $minConfidence = $this->request->getPost('confidence');

        if ($minSupport < 0 || $minSupport > 100 || $minConfidence < 0 || $minConfidence > 100) {
            session()->setFlashdata('pesan', 'Support dan Confidence harus antara 0 dan 100.');
            return redirect()->back();
        }

        // Ambil data penjualan
        $bulan = date('m');
        $tahun = date('Y');
        $penjualanProdukModel = new ModelPenjualanProduk();
        $transaksi = $this->ambilDataTransaksi($bulan, $tahun, $penjualanProdukModel);

        // Cek data transaksi ada
        if (empty($transaksi) || !is_array($transaksi)) {
            session()->setFlashdata('pesan', 'Tidak ada data transaksi untuk dianalisis.');
            return redirect()->back();
        }

        // untuk 100 data acak
        $transaksi = $this->ambilDataTransaksiAcak($transaksi, 100);

        $daftarTransaksi = $this->siapkanDataTransaksi($transaksi);

        // Cek apakah data ada
        if (empty($daftarTransaksi)) {
            session()->setFlashdata('pesan', 'Data transaksi tidak valid.');
            return redirect()->back();
        }

        // Jalankan analisis Apriori 
        $rules = $this->jalankanApriori($daftarTransaksi, $minSupport, $minConfidence);

        // Cek aturan
        if (empty($rules)) {
            session()->setFlashdata('pesan', 'Tidak ada aturan asosiasi yang dihasilkan.');
            return redirect()->back();
        }
        $this->simpanHasilAnalisis($rules, $bulan, $tahun);

        session()->setFlashdata('pesan', 'Analisis Apriori selesai.');
        return redirect()->to('Analisis');
    }


    private function ambilDataTransaksi($bulan, $tahun, $penjualanProdukModel)
    {
        $query = $penjualanProdukModel->builder()
            ->select('ps.no_faktur, pr.nama_produk')
            ->join('penjualan ps', 'penjualan_produk.no_faktur = ps.no_faktur')
            ->join('produk pr', 'penjualan_produk.id_produk = pr.id_produk')
            ->where('YEAR(ps.tanggal)', $tahun)
            ->where('MONTH(ps.tanggal)', $bulan)
            ->get();

        return $query->getResultArray();
    }

    private function ambilDataTransaksiAcak($transaksi, $jumlah = 100)
    {
        // Ambil data acak
        if (count($transaksi) < $jumlah) {
            return $transaksi;
        }

        $keys = array_rand($transaksi, $jumlah);
        $sampleTransaksi = [];

        if (!is_array($keys)) {
            $sampleTransaksi[] = $transaksi[$keys];
        } else {
            foreach ($keys as $key) {
                $sampleTransaksi[] = $transaksi[$key];
            }
        }

        return $sampleTransaksi;
    }

    private function siapkanDataTransaksi($transaksi)
    {
        $daftarTransaksi = [];
        foreach ($transaksi as $item) {
            $noFaktur = $item['no_faktur'];
            $produk = $item['nama_produk'];

            if (!isset($daftarTransaksi[$noFaktur])) {
                $daftarTransaksi[$noFaktur] = [];
            }

            $daftarTransaksi[$noFaktur][] = $produk;
        }

        return $daftarTransaksi;
    }


    private function jalankanApriori($daftarTransaksi, $minSupport, $minConfidence)
    {
        log_message('debug', 'Menjalankan algoritma Apriori untuk data transaksi.');
        $frequentItemsets = [];
        $totalTransactions = count($daftarTransaksi);

        $itemCount = [];
        foreach ($daftarTransaksi as $transaksi) {
            foreach ($transaksi as $item) {
                if (!isset($itemCount[$item])) {
                    $itemCount[$item] = 0;
                }
                $itemCount[$item]++;
            }
        }

        foreach ($itemCount as $item => $count) {
            $support = $count / $totalTransactions * 100;
            if ($support >= $minSupport) {
                $frequentItemsets[] = [
                    'itemset' => [$item],
                    'support' => $support
                ];
            }
        }

        $pairCount = [];
        foreach ($daftarTransaksi as $transaksi) {
            $transaksi = array_values(array_unique($transaksi));
            $transaksiCount = count($transaksi);

            if ($transaksiCount > 1) {
                for ($i = 0; $i < $transaksiCount; $i++) {
                    for ($j = $i + 1; $j < $transaksiCount; $j++) {
                        $pair = [min($transaksi[$i], $transaksi[$j]), max($transaksi[$i], $transaksi[$j])];
                        $pairKey = implode(',', $pair);
                        if (!isset($pairCount[$pairKey])) {
                            $pairCount[$pairKey] = 0;
                        }
                        $pairCount[$pairKey]++;
                    }
                }
            }
        }
        foreach ($pairCount as $pairKey => $count) {
            $support = $count / $totalTransactions * 100;
            if ($support >= $minSupport) {
                $frequentItemsets[] = [
                    'itemset' => explode(',', $pairKey),
                    'support' => $support
                ];
            }
        }
        $rules = [];
        foreach ($frequentItemsets as $itemset) {
            if (count($itemset['itemset']) == 2) {
                $item1 = $itemset['itemset'][0];
                $item2 = $itemset['itemset'][1];

                $supportAB = $itemset['support'];
                $supportA = $itemCount[$item1] / $totalTransactions;
                $supportB = $itemCount[$item2] / $totalTransactions;

                if ($supportA > 0) {
                    $confidenceAB = $supportAB / $supportA;
                    $liftAB = ($confidenceAB / $supportB) / 100;

                    if ($confidenceAB >= $minConfidence) {
                        $rules[] = [
                            'rule' => "$item1 → $item2",
                            'support' => $supportAB,
                            'confidence' => $confidenceAB,
                            'lift' => $liftAB
                        ];
                    }
                }
            }
        }

        return $rules;
    }

    public function simpanHasilAnalisis($rules, $bulan, $tahun)
    {
        $model = new ModelAnalisis();
        $hasilAnalisis = [];

        foreach ($rules as $rule) {
            $produk1 = explode(' → ', $rule['rule'])[0];
            $produk2 = explode(' → ', $rule['rule'])[1];

            $data = [
                'produk_1' => $produk1,
                'produk_2' => $produk2,
                'support' => $rule['support'],
                'confidence' => $rule['confidence'],
                'bulan' => $bulan,
                'tahun' => $tahun
            ];

            $hasilAnalisis[] = $data;
        }

        if (!empty($hasilAnalisis)) {
            usort($hasilAnalisis, function ($a, $b) {
                return $b['support'] <=> $a['support'];
            });
            // Ambil 5 teratas
            $top5 = array_slice($hasilAnalisis, 0, 5);

            $model->insertBatch($top5);
        }
    }

    public function detail($bulan, $tahun)
    {
        $hasilAnalisis = (new ModelAnalisis())->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->orderBy('support', 'DESC')
            ->findAll();

        $data = [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'hasilAnalisis' => $hasilAnalisis,
            'judul' => 'Analisis',
            'subjudul' => 'detail',
            'menu' => 'analisis',
            'submenu' => 'detail',
            'page' => 'v_analisis_detail',
        ];

        return view('v.template/viewtemp', $data);
    }

    public function hapusHasilAnalisis($bulan, $tahun)
    {
        (new ModelAnalisis())->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->delete();

        session()->setFlashdata('pesan', "Hasil analisis bulan $bulan tahun $tahun dihapus");
        return redirect()->to('Analisis');
    }
}
