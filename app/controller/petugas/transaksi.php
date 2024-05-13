<?php

use function PHPSTORM_META\map;

class Transaksi extends SENE_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
        $this->load("member_model", "mm");
        $this->load("transaksi_model", "tm");
        $this->load("produk_model", "pm");
        $this->load("history_model", "hm");
        $this->setTheme("petugas");
    }
    function index()
    {
        $data = array();
        $data["sess"] = $this->getKey();
        if (!isset($data["sess"]->petugas->id)) {
            redir(base_url("petugas/login"));
            return;
        }

        $data["mm"] = $this->mm->diskon();

        $this->setTitle("petugas Home");
        $this->putThemeContent("transaksi/home", $data);
        $this->putJsReady("transaksi/home_bottom", $data);
        $this->loadLayout("col-1-default", $data);
        $this->render();
    }
    function transaksi()
    {
        $data = array();
        $data["sess"] = $this->getKey();
        if (!isset($data["sess"]->petugas->id)) {
            redir(base_url("petugas/login"));
            return;
        }

        $this->setTitle("petugas transaksi");
        $this->putThemeContent("transaksi/transaksi", $data);
        $this->putJsReady("transaksi/transaksi_bottom", $data);
        $this->loadLayout("col-1-default", $data);
        $this->render();
    }
    function print()
    {
        $nofaktur = $this->input->post("nofaktur");
        $data = $this->tm->getPerFaktur($nofaktur);
        if (isset($data->nofaktur)) {
            $struk = "<style>
                    hr {
                        border-top: 3px dotted;
                    }
                </style>
                <div class='container-fluid'>
                    <div class='row'>
                            <div class='col-12 mt-5'>
                                <h2 class='text-center'>" . $data->toko . "</h2>
                            </div>
                            <div class='col-12'>
                                <h2 class='text-center'>" . $data->alamat . "</h2>
                            </div>
                            <div class='col-12'>
                                <hr>
                                <div class='row'>
                                    <div class='col-6'><b>" . $data->tanggaltransaksi . "</b></div>
                                    <div class='col-6'><b>Kasir: " . $data->petugas . "</b></div>
                                </div>
                                <hr>
                            </div>
                            <div class='col-12'>
                                <h6 class='text-center'><b>- Detail Produk -</b></h6>
                                <hr>
                            </div>
                            <div class='col-12'>
                                <div class='row'>
                                    <div class='col-5'><b>Produk</b></div>
                                    <div class='col-2 text-center'><b>QTY</b></div>
                                    <div class='col-5 text-center'><b>HARGA</b></div>";
            $array = json_decode($data->barang);
            foreach ($array as $item) {
                $namaproduk = $item->item;
                $qty = $item->qty;
                $harga = $item->harga;
                $diskon = $item->diskon;
                $struk .= "<div class='col-5'><b>$namaproduk</b></div>
                  <div class='col-2 text-center'><b>$qty</b></div>
                    <div class='col-5 text-center'><b>$harga</b></div>";

                $struk .= "<div class='col-12'><b>Diskon : $diskon<b></div>";
            }
            $struk .= "</div>
                            </div>
                            <div class='col-12'>
                                <hr>
                                <h6 class='text-center'><b>- Detail Bayar -</b></h6>
                                <hr>
                            </div>
                            <div class='col-12'>
                                <div class='row'>
                                    <div class='col-5'>Total Produk</div>
                                    <div class='col-2 text-center'>" . count($array) . "</div>
                                    <div class='col-5 text-center'>Rp." . number_format($data->subtotal, 2, ',', '.') . "</div>
                                </div>
                                <div class='row'>
                                    <div class='col-7'>Diskon</div>
                                    <div class='col-5 text-center'>Rp." . number_format($data->diskon, 2, ',', '.') . "</div>
                                </div>
                                <div class='row'>
                                    <div class='col-7'>Total</div>
                                    <div class='col-5 text-center'>Rp." . number_format($data->total, 2, ',', '.') . "</div>
                                </div>
                                <div class='row'>
                                    <div class='col-7'>Uang Bayar</div>
                                    <div class='col-5 text-center'>Rp." . number_format($data->uang, 2, ',', '.') . "</div>
                                </div>
                                <div class='row'>
                                    <div class='col-7'>Kembalian</div>
                                    <div class='col-5 text-center'>Rp." . number_format($data->kembalian, 2, ',', '.') . "</div>
                                </div>
                            </div>
                            <div class='col-12'>
                                <hr>
                                <h6 class='text-center'><b>Member : " . $data->member . "</b></h6>
                            </div>
                        </div>
                </div>";
            echo $struk;
        }
    }
    function export()
    {
        $awal = $this->input->post("awal");
        $akhir = $this->input->post("akhir");
        $data = $this->tm->export($awal, $akhir);
        $sess = $this->getKey();
        $laporan = [
            "waktu" => date("Y-m-d h:i:s"),
            "pelaku" => "petugas",
            "kejadian" => "petugas " . $sess->petugas->nama . " Telah Mengambil Laporan Transaksi Dari Tanggal " . $awal . " Sampai " . $akhir
        ];
        $this->hm->insert($laporan);
        foreach ($data as $barang) {
            $barangg = json_decode($barang->barang);
            $array = [];
            foreach ($barangg as $item) {
                $baranga = $item->item;
                $qty = $item->qty;

                $array[] = "$baranga Qty: $qty";
            }
            $barang->barang = implode(", ", $array);
            $barang->subtotal = "Rp." . number_format($barang->subtotal, 2,  ",", ".");
            $barang->diskon = "Rp." . number_format($barang->diskon, 2,  ",", ".");
            $barang->total = "Rp." . number_format($barang->total, 2,  ",", ".");
            $barang->uang = "Rp." . number_format($barang->uang, 2,  ",", ".");
            $barang->kembalian = "Rp." . number_format($barang->kembalian, 2,  ",", ".");
        }
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
            0 => "id",
            1 => "nofaktur",
            2 => "tanggaltransaksi",
            3 => "petugas",
            4 => "member",
            5 => "barang",
            6 => "total"
        ];
        $order_by = $order_columns[$order_column];
        $data = $this->tm->getAll($start, $limit, $order_by, $order_dir, $search);
        $recordsTotal = $this->tm->countAll($search);
        $recordsFiltered = $this->tm->countAll();
        $output = [
            "draw" => $this->input->post("draw"),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data
        ];
        echo json_encode($output);
    }
    private function randomstring($length = 6)
    {
        $characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        $randomstring = "";
        for ($i = 0; $i < $length; $i++) {
            $randomstring .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomstring;
    }
    function member()
    {
        $data = $this->mm->get();
        echo json_encode($data);
    }
    function produk()
    {
        $data = $this->pm->get();
        echo json_encode($data);
    }
    function proses()
    {
        $data = [
            "toko" => "YaeStore",
            "alamat" => "Kp.Legok Nangka No.37, Desa Panyirapan, Kec.Soreang, Kab.Bandung, Jawa Barat",
            "nofaktur" => date("Ym") . $this->randomstring(),
            "tanggaltransaksi" => date("Y-m-d h:i:s"),
            "petugas" => $this->input->post("petugas"),
            "member" => $this->input->post("member"),
            "barang" => $this->input->post("barang"),
            "subtotal" => $this->input->post("subtotal"),
            "diskon" => $this->input->post("diskon"),
            "total" => $this->input->post("total"),
            "uang" => $this->input->post("uang"),
            "kembalian" => $this->input->post("kembalian")
        ];

        $struk = "<style>
                    hr {
                        border-top: 3px dotted;
                    }
                </style>
                <div class='container-fluid'>
                    <div class='row'>
                            <div class='col-12 mt-5'>
                                <h2 class='text-center'>{$data['toko']}</h2>
                            </div>
                            <div class='col-12'>
                                <h2 class='text-center'>{$data['alamat']}</h2>
                            </div>
                            <div class='col-12'>
                                <hr>
                                <div class='row'>
                                    <div class='col-6'><b>{$data['tanggaltransaksi']}</b></div>
                                    <div class='col-6'><b>Kasir: {$data['petugas']}</b></div>
                                </div>
                                <hr>
                            </div>
                            <div class='col-12'>
                                <h6 class='text-center'><b>- Detail Produk -</b></h6>
                                <hr>
                            </div>
                            <div class='col-12'>
                                <div class='row'>
                                    <div class='col-5'><b>Produk</b></div>
                                    <div class='col-2 text-center'><b>QTY</b></div>
                                    <div class='col-5 text-center'><b>HARGA</b></div>";
        $array = json_decode($data["barang"]);
        foreach ($array as $item) {
            $namaproduk = $item->item ?? "-";
            $qty = $item->qty ?? "-";
            $harga = $item->harga ?? "-";
            $diskon = $item->diskon ?? "-";
            $struk .= "<div class='col-5'><b>$namaproduk</b></div>
                  <div class='col-2 text-center'><b>$qty</b></div>
                    <div class='col-5 text-center'><b>$harga</b></div>";

            $struk .= "<div class='col-12'><b>Diskon : $diskon<b></div>";
        }
        $struk .= "</div>
                            </div>
                            <div class='col-12'>
                                <hr>
                                <h6 class='text-center'><b>- Detail Bayar -</b></h6>
                                <hr>
                            </div>
                            <div class='col-12'>
                                <div class='row'>
                                    <div class='col-5'>Total Produk</div>
                                    <div class='col-2 text-center'>" . count($array) . "</div>
                                    <div class='col-5 text-center'>Rp." . number_format($data['subtotal'], 2, ',', '.') . "</div>
                                </div>
                                <div class='row'>
                                    <div class='col-7'>Diskon</div>
                                    <div class='col-5 text-center'>Rp." . number_format($data['diskon'], 2, ',', '.') . "</div>
                                </div>
                                <div class='row'>
                                    <div class='col-7'>Total</div>
                                    <div class='col-5 text-center'>Rp." . number_format($data['total'], 2, ',', '.') . "</div>
                                </div>
                                <div class='row'>
                                    <div class='col-7'>Uang Bayar</div>
                                    <div class='col-5 text-center'>Rp." . number_format($data['uang'], 2, ',', '.') . "</div>
                                </div>
                                <div class='row'>
                                    <div class='col-7'>Kembalian</div>
                                    <div class='col-5 text-center'>Rp." . number_format($data['kembalian'], 2, ',', '.') . "</div>
                                </div>
                            </div>
                            <div class='col-12'>
                                <hr>
                                <h6 class='text-center'><b>Member : {$data['member']}</b></h6>
                            </div>
                        </div>
                </div>";

        $this->pm->mengurangistok($array);
        $this->tm->insert($data);
        echo $struk;
    }
}
