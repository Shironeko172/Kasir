<?php
class Produk extends SENE_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
        $this->load("produk_model", "pm");
        $this->load("history_model", "hm");
        $this->setTheme("admin");
    }
    function index()
    {
        $data = array();
        $data["sess"] = $this->getKey();
        if (!isset($data["sess"]->admin->id)) {
            redir(base_url("admin/login"));
            return;
        }

        $this->setTitle("Admin produk");
        $this->putThemeContent("produk/home", $data);
        $this->putJsReady("produk/home_bottom", $data);
        $this->loadLayout("col-1-default", $data);
        $this->render();
    }
    private function randomstring($length = 4)
    {
        $characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        $randomstring = "";
        for ($i = 0; $i < $length; $i++) {
            $randomstring .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomstring;
    }
    function diskon()
    {
        $data = array("diskon" => $this->input->post("diskon"));
        $namaproduk = $this->input->post("namaprodukril");
        $kode = $this->input->post("kode");
        $sess = $this->getKey();
        $laporan = [
            "waktu" => date("Y-m-d h:i:s"),
            "pelaku" => "admin",
            "kejadian" => "Admin " . $sess->admin->nama . " Telah Menambah Diskon Produk " . $namaproduk
        ];
        $this->hm->insert($laporan);
        $this->pm->update($kode, $data);
    }
    function edit()
    {
        $data = array();
        $namaproduk = $this->input->post("namaprodukril");
        $kode = $this->input->post("koderil");
        $kategori = $this->input->post("kategoriril");
        $data["namaproduk"] = htmlspecialchars($this->input->post("namaproduk"));
        $data["harga"] = htmlspecialchars($this->input->post("harga"));
        $data["kategori"] = htmlspecialchars($this->input->post("kategori"));
        if ($kategori === $data["kategori"]) {
            $data["kode"] = $kode;
        } else {
            $data["kode"] = $this->input->post("kode") . date("Ym") . $this->randomstring();
        }
        $sess = $this->getKey();
        $laporan = [
            "waktu" => date("Y-m-d h:i:s"),
            "pelaku" => "admin",
            "kejadian" => "Admin " . $sess->admin->nama . " Telah Mengubah Data Produk " . $namaproduk . " Menjadi " . $data["namaproduk"] . " , " . $data["kategori"] . " , " . number_format($data["harga"], 2, ",", ".")
        ];
        $this->hm->insert($laporan);
        $this->pm->update($kode, $data);
    }
    function export()
    {
        $awal = $this->input->post("awal");
        $akhir = $this->input->post("akhir");
        $data = $this->pm->export($awal, $akhir);
        foreach ($data as $barang) {
            $barang->harga = "Rp." . number_format($barang->harga, 2,  ",", ".");
            $barang->{'Stok Saat Ini'} = $barang->stok . "Pcs";
            unset($barang->stok);
            $barang->{'Stok Masuk'} = $barang->stokmasuk . "Pcs";
            unset($barang->stokmasuk);
            $barang->{'Stok Keluar'} = $barang->stokkeluar . "Pcs";
            unset($barang->stokkeluar);
        }
        $sess = $this->getKey();
        $laporan = [
            "waktu" => date("Y-m-d h:i:s"),
            "pelaku" => "admin",
            "kejadian" => "Admin " . $sess->admin->nama . " Telah Mengambil Laporan Stok Dari Tanggal " . $awal . " Sampai " . $akhir
        ];
        $this->hm->insert($laporan);
        echo json_encode($data);
    }
    function hapus()
    {
        $data = array("is_active" => 0);
        $kode = $this->input->post("kode");
        $namaproduk = $this->input->post("namaproduk");
        $sess = $this->getKey();
        $laporan = [
            "waktu" => date("Y-m-d h:i:s"),
            "pelaku" => "admin",
            "kejadian" => "Admin " . $sess->admin->nama . " Telah Menghapus Produk " . $namaproduk
        ];
        $this->hm->insert($laporan);
        $this->pm->update($kode, $data);
    }
    function tambahstok()
    {
        $kode = $this->input->post("kode");
        $stok = $this->input->post("stok");
        $sess = $this->getKey();
        $laporan = [
            "waktu" => date("Y-m-d h:i:s"),
            "pelaku" => "admin",
            "kejadian" => "Admin " . $sess->admin->nama . " Telah Menambah Stok Sebanyak " . $stok
        ];
        $this->pm->tambahstok($kode, $stok);
        $this->hm->insert($laporan);
    }
    function kurangistok()
    {
        $kode = $this->input->post("kode");
        $stok = $this->input->post("stok");
        $sess = $this->getKey();
        $laporan = [
            "waktu" => date("Y-m-d h:i:s"),
            "pelaku" => "admin",
            "kejadian" => "Admin " . $sess->admin->nama . " Telah Mengurangi Stok Sebanyak " . $stok
        ];
        $this->pm->kurangistok($kode, $stok);
        $this->hm->insert($laporan);
    }
    function tambah()
    {
        $data = [
            "kode" => $this->input->post("kode") . date("Ym") . $this->randomstring(),
            "tanggalmasuk" => date("Y-m-d h:i:s"),
            "namaproduk" => htmlspecialchars($this->input->post("namaproduk")),
            "kategori" => htmlspecialchars($this->input->post("kategori")),
            "stok" => htmlspecialchars($this->input->post("stok")),
            "stokmasuk" => htmlspecialchars($this->input->post("stok")),
            "harga" => htmlspecialchars($this->input->post("harga")),
            "stokkeluar" => 0,
            "diskon" => 0,
            "is_active" => 1
        ];
        $namaprodu = $data["namaproduk"];
        $pm = $this->pm->getByNamaProduk($namaprodu);
        if (!empty($pm->namaproduk)) {
            echo json_encode(array("message1" => "Data Sudah Tersedia"));
            return;
        } else {
            $sess = $this->getKey();
            $laporan = [
                "waktu" => date("Y-m-d h:i:s"),
                "pelaku" => "admin",
                "kejadian" => "Admin " . $sess->admin->nama . " Telah Menambah Produk Baru Dengan Nama Produk " . $data["namaproduk"]
            ];
            $this->hm->insert($laporan);
            $this->pm->insert($data);
            echo json_encode(array("message" => "Sukses Di Tambahkan"));
            return;
        }
    }
    function tambahkt()
    {
        $data = [
            "kategori" => $this->input->post("kategori"),
            "kode" => $this->input->post("kode")
        ];
        $sess = $this->getKey();
        $laporan = [
            "waktu" => date("Y-m-d h:i:s"),
            "pelaku" => "admin",
            "kejadian" => "Admin " . $sess->admin->nama . " Telah Menambah Kategori " . $data["kategori"]
        ];
        $this->hm->insert($laporan);
        $this->pm->tambahkt($data);
    }
    function kategori()
    {
        $data = $this->pm->kategori();
        echo json_encode($data);
    }
    function getdata()
    {
        $search = isset($this->input->post("search")["value"]) ? $this->input->post("search")["value"] : "";
        $start = $this->input->post("start");
        $limit = $this->input->post("length");
        $order_column = isset($this->input->post("order")[0]["column"]) ? $this->input->post("order")[0]["column"] : 0;
        $order_dir = isset($this->input->post("order")[0]["dir"]) ? $this->input->post("order")[0]["dir"] : "asc";

        $order_columns = [
            0 => "kode",
            1 => "tanggalmasuk",
            2 => "namaproduk",
            3 => "kategori",
            4 => "stok",
            5 => "harga",
            6 => "diskon",
        ];
        $order_by = $order_columns[$order_column];
        $data = $this->pm->getAll($start, $limit, $order_by, $order_dir, $search);
        $recordsTotal = $this->pm->countAll($search);
        $recordsFiltered = $this->pm->countAll();
        $output = [
            "draw" => $this->input->post("draw"),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data
        ];
        echo json_encode($output);
    }
}
