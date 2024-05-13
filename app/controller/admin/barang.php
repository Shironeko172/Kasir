<?php
class Barang extends SENE_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
        $this->load("barang_model", "bm");
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
        $data["bm"] = $this->bm->kategori();

        $this->setTitle("Barang");
        $this->putThemeContent("barang/home", $data);
        $this->putJsReady("barang/home_bottom", $data);
        $this->loadLayout("col-1-default", $data);
        $this->render();
    }
    function getdata()
    {
        $search = $this->input->post("search")["value"];
        $start = $this->input->post("start");
        $limit = $this->input->post("length");
        $order_column = isset($this->input->post("order")[0]["column"]) ? $this->input->post("order")[0]["column"] : 0;
        $order_dir = isset($this->input->post("order")[0]["dir"]) ? $this->input->post("order")[0]["dir"] : "asc";

        $order_columns = array(
            0 => "kode",
            1 => "tanggalmasuk",
            2 => "namabarang",
            3 => "kategori",
            4 => "stok",
            5 => "harga",
            6 => "diskon"
        );

        $order_by = $order_columns[$order_column];
        $data = $this->bm->getAll($start, $limit, $order_by, $order_dir, $search);
        $total_data = $this->bm->countAll($search);
        $total_filtered = $this->bm->countAll();

        $output = array(
            "draw" => $this->input->post("draw"),
            "recordsTotal" => $total_data,
            "recordsFiltered" => $total_filtered,
            "data" => $data
        );

        echo json_encode($output);
    }
    private function randomstring($length = 4)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomstring = '';
        for ($i = 0; $i < $length; $i++) {
            $randomstring .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomstring;
    }
    function tambah()
    {
        $data = array();
        $data["tanggalmasuk"] = date("Y-m-d h:i:s");
        $data["namabarang"] = htmlspecialchars($this->input->post("namabarang"));
        $data["kategori"] = htmlspecialchars($this->input->post("kategori"));
        $kategoriprefik = array(
            "Scaled Figure" => "SF",
            "Prize Figure" => "PF",
            "Pop Up Parade" => "PP",
            "Model Kit" => "MK",
            "Figma Figure" => "FF",
            "Nendroid" => "NE",
            "Merchandise" => "ME",
            "Plush" => "PL",
            "Light Novel" => "LN",
            "Manga" => "MA"
        );
        if (array_key_exists($data["kategori"], $kategoriprefik)) {
            $prefik = $kategoriprefik[$data["kategori"]];
            $data["kode"] = $prefik . date("Ym") . $this->randomstring();
        } else {
            $data["kode"] = "UI" . date("Ym") . $this->randomstring();
        }
        $data["stok"] = htmlspecialchars($this->input->post("stok"));
        $data["harga"] = htmlspecialchars($this->input->post("harga"));
        $data["diskon"] = 0;
        $data["is_active"] = 1;

        $sess = $this->getKey();
        $laporan = [
            "tanggalwaktu" => date("Y-m-d h:i:s"),
            "kejadian" => "Admin " . $sess->admin->nama . " Menambah Data Produk " . $data["namabarang"]
        ];
        $this->hm->insert($laporan);
        $this->bm->insert($data);
    }
    function edit()
    {
        $data = array();
        $kode = $this->input->post("kode");
        $namabarangril = $this->input->post("namabarangril");
        $data["tanggalmasuk"] = date("Y-m-d h:i:s");
        $data["namabarang"] = htmlspecialchars($this->input->post("namabarang"));
        $data["kategori"] = htmlspecialchars($this->input->post("kategori"));
        $kategoriprefik = array(
            "Scaled Figure" => "SF",
            "Prize Figure" => "PF",
            "Pop Up Parade" => "PP",
            "Model Kit" => "MK",
            "Figma Figure" => "FF",
            "Nendroid" => "NE",
            "Merchandise" => "ME",
            "Plush" => "PL",
            "Light Novel" => "LN",
            "Manga" => "MA"
        );
        if (isset($data["kategori"])) {
            if (array_key_exists($data["kategori"], $kategoriprefik)) {
                $prefik = $kategoriprefik[$data["kategori"]];
                $data["kode"] = $prefik . date("Ym") . $this->randomstring();
            } else {
                $data["kode"] = "UI" . date("Ym") . $this->randomstring();
            }
        } else {
            $data["kode"] = $kode;
        }
        $data["stok"] = htmlspecialchars($this->input->post("stok"));
        $data["harga"] = htmlspecialchars($this->input->post("harga"));

        $sess = $this->getKey();
        $laporan = [
            "tanggalwaktu" => date("Y-m-d h:i:s"),
            "kejadian" => "Admin " . $sess->admin->nama . " Mengubah Data Produk " . $namabarangril . " Menjadi Produk " . $data["namabarang"] . ", Kategori: " . $data["kategori"] . ", Stok: " . $data["stok"] . " Pcs, Harga: Rp." . number_format($data["harga"], 2)
        ];
        $this->hm->insert($laporan);
        $this->bm->update($kode, $data);
    }

    function hapus()
    {
        $kode = $this->input->post("kode");
        $data = [
            "is_active" => 0
        ];
        $sess = $this->getKey();
        $laporan = [
            "tanggalwaktu" => date("Y-m-d h:i:s"),
            "kejadian" => "Admin " . $sess->admin->nama . " Menghapus Data Produk " . $this->input->post("namabarang")
        ];
        $this->hm->insert($laporan);
        $this->bm->update($kode, $data);
    }

    function stok()
    {
        $kode = $this->input->post("kode");
        $sess = $this->getKey();
        $stok = $this->input->post("stok");
        $namabarang = $this->input->post("namabarang");
        $laporan = [
            "tanggalwaktu" => date("Y-m-d h:i:s"),
            "kejadian" => "Admin " . $sess->admin->nama . " Menambah Stok Produk " . $namabarang . " Sebanyak " . $stok
        ];
        $this->hm->insert($laporan);
        $this->bm->stok($kode, $stok);
    }

    function diskon()
    {
        $kode = $this->input->post("kode");
        $sess = $this->getKey();
        $diskon = array();
        $diskon["diskon"] = $this->input->post("diskon");
        $namabarang = $this->input->post("namabarang");
        $laporan = [
            "tanggalwaktu" => date("Y-m-d h:i:s"),
            "kejadian" => "Admin " . $sess->admin->nama . " Menambah diskon Produk " . $namabarang . " Sebanyak " . $diskon["diskon"] . "%"
        ];
        $this->hm->insert($laporan);
        $this->bm->update($kode, $diskon);
    }

    function export()
    {
        $start = $this->input->post("tanggalawal");
        $end = $this->input->post("tanggalakhir");
        $data = $this->bm->export($start, $end);
        echo json_encode($data);
    }
}
