<?php
class petugas extends SENE_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
        $this->load("petugas_model", "pm");
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

        $this->setTitle("Kelola Produk");
        $this->putThemeContent("petugas/home", $data);
        $this->putJsReady("petugas/home_bottom", $data);
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
    function edit()
    {
        $data = array();
        $id = $this->input->post("id");
        $namaril = $this->input->post("namaril");
        if (isset($_FILES["foto"]) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $lokasifile = "skin/media/";
            $namafile = $this->randomstring() . "_" . basename($_FILES["foto"]["name"]);
            $targetfile = $lokasifile . $namafile;
            $data["foto"] = $targetfile;
            move_uploaded_file($_FILES["foto"]["tmp_name"], $targetfile);
        } else {
            $data["foto"] = $this->input->post("foto");
        }
        $data["nama"] = htmlspecialchars($this->input->post("nama"));
        $data["alamat"] = htmlspecialchars($this->input->post("alamat"));
        $data["tempatlahir"] = htmlspecialchars($this->input->post("tempatlahir"));
        $data["tanggallahir"] = htmlspecialchars($this->input->post("tanggallahir"));
        $data["email"] = htmlspecialchars($this->input->post("email"));
        $sess = $this->getKey();
        $laporan = [
            "waktu" => date("Y-m-d h:i:s"),
            "pelaku" => "petugas",
            "kejadian" => "Petugas " . $sess->petugas->nama . " Telah Mengubah Data Petugas " . $namaril
        ];
        $this->hm->insert($laporan);
        $this->pm->update($id, $data);
    }
    function hapus()
    {
        $data = array("is_active" => 0);
        $id = $this->input->post("id");
        $nama = $this->input->post("nama");
        $sess = $this->getKey();
        $laporan = [
            "waktu" => date("Y-m-d h:i:s"),
            "pelaku" => "petugas",
            "kejadian" => "petugas " . $sess->petugas->nama . " Telah Menghapus Produk " . $nama
        ];
        $this->hm->insert($laporan);
        $this->pm->update($id, $data);
    }
    function tambah()
    {
        $data = array();
        $lokasifile = "skin/media/";
        $namafile = $this->randomstring() . "_" . basename($_FILES["foto"]["name"]);
        $targetfile = $lokasifile . $namafile;
        $data["foto"] = $targetfile;
        move_uploaded_file($_FILES["foto"]["tmp_name"], $targetfile);
        $data["nama"] = htmlspecialchars($this->input->post("nama"));
        $data["alamat"] = htmlspecialchars($this->input->post("alamat"));
        $data["tempatlahir"] = htmlspecialchars($this->input->post("tempatlahir"));
        $data["tanggallahir"] = htmlspecialchars($this->input->post("tanggallahir"));
        $data["email"] = htmlspecialchars($this->input->post("email"));
        $data["password"] = password_hash(htmlspecialchars($this->input->post("password")), PASSWORD_DEFAULT);
        $sess = $this->getKey();
        $laporan = [
            "waktu" => date("Y-m-d h:i:s"),
            "pelaku" => "petugas",
            "kejadian" => "petugas " . $sess->petugas->nama . " Telah Menambah Petugas Baru Dengan Nama " . $data["nama"]
        ];
        $this->hm->insert($laporan);
        $this->pm->insert($data);
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
            1 => "foto",
            2 => "nama",
            3 => "alamat",
            4 => "tanggallahir",
            5 => "email"
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
