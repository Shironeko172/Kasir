<?php
class member extends SENE_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
        $this->load("member_model", "mm");
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

        $this->setTitle("Admin member");
        $this->putThemeContent("member/home", $data);
        $this->putJsReady("member/home_bottom", $data);
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
    private function randomkode($length = 15)
    {
        $characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $randomstring = "";
        for ($i = 0; $i < $length; $i++) {
            $randomstring .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomstring;
    }
    function edit()
    {
        $data = array();
        $kode = $this->input->post("kode");
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
        $data["email"] = htmlspecialchars($this->input->post("email"));
        $sess = $this->getKey();
        $laporan = [
            "waktu" => date("Y-m-d h:i:s"),
            "pelaku" => "admin",
            "kejadian" => "Admin " . $sess->admin->nama . " Telah Mengubah Data Petugas " . $namaril
        ];
        $this->hm->insert($laporan);
        $this->mm->update($kode, $data);
    }
    function hapus()
    {
        $data = array("is_active" => 0);
        $kode = $this->input->post("kode");
        $nama = $this->input->post("nama");
        $sess = $this->getKey();
        $laporan = [
            "waktu" => date("Y-m-d h:i:s"),
            "pelaku" => "admin",
            "kejadian" => "Admin " . $sess->admin->nama . " Telah Menghapus Produk " . $nama
        ];
        $this->hm->insert($laporan);
        $this->mm->update($kode, $data);
    }
    function tambah()
    {
        $data = array();
        $lokasifile = "skin/media/";
        $namafile = $this->randomstring() . "_" . basename($_FILES["foto"]["name"]);
        $targetfile = $lokasifile . $namafile;
        $data["kode"] = $this->randomkode();
        $data["foto"] = $targetfile;
        move_uploaded_file($_FILES["foto"]["tmp_name"], $targetfile);
        $data["nama"] = htmlspecialchars($this->input->post("nama"));
        $data["alamat"] = htmlspecialchars($this->input->post("alamat"));
        $data["email"] = htmlspecialchars($this->input->post("email"));
        $data["password"] = password_hash(htmlspecialchars($this->input->post("password")), PASSWORD_DEFAULT);
        $data["is_active"] = 1;
        $sess = $this->getKey();
        $laporan = [
            "waktu" => date("Y-m-d h:i:s"),
            "pelaku" => "admin",
            "kejadian" => "Admin " . $sess->admin->nama . " Telah Menambah Member Baru Dengan Nama " . $data["nama"]
        ];
        $this->hm->insert($laporan);
        $this->mm->insert($data);
    }
    function tambahdiskon()
    {
        $data = array();
        $data["diskon"] = htmlspecialchars($this->input->post("diskon"));
        $data["barangmin"] = htmlspecialchars($this->input->post("barangmin"));
        $data["barangmax"] = htmlspecialchars($this->input->post("barangmax"));
        $data["hargamin"] = htmlspecialchars($this->input->post("hargamin"));
        $data["hargamax"] = htmlspecialchars($this->input->post("hargamax"));
        $data["is_active"] = 1;
        $sess = $this->getKey();
        $laporan = [
            "waktu" => date("Y-m-d h:i:s"),
            "pelaku" => "admin",
            "kejadian" => "Admin " . $sess->admin->nama . " Telah Menambah Diskon Khusus Member Dengan Total Diskon " . $data["diskon"]
        ];
        $this->hm->insert($laporan);
        $this->mm->insertdiskon($data);
    }
    function editdiskon()
    {
        $data = array();
        $id = $this->input->post("id");
        $diskon = $this->input->post("rildiskon");
        $data["diskon"] = htmlspecialchars($this->input->post("diskon"));
        $data["barangmin"] = htmlspecialchars($this->input->post("barangmin"));
        $data["barangmax"] = htmlspecialchars($this->input->post("barangmax"));
        $data["hargamin"] = htmlspecialchars($this->input->post("hargamin"));
        $data["hargamax"] = htmlspecialchars($this->input->post("hargamax"));

        $sess = $this->getKey();
        $laporan = [
            "waktu" => date("Y-m-d h:i:s"),
            "pelaku" => "admin",
            "kejadian" => "Admin " . $sess->admin->nama . " Telah Mengubah Data Petugas " . $diskon
        ];
        $this->hm->insert($laporan);
        $this->mm->updatediskon($id, $data);
    }
    function hapusdiskon()
    {
        $data = array("is_active" => 0);
        $id = $this->input->post("id");
        $diskon = $this->input->post("diskon");
        $sess = $this->getKey();
        $laporan = [
            "waktu" => date("Y-m-d h:i:s"),
            "pelaku" => "admin",
            "kejadian" => "Admin " . $sess->admin->nama . " Telah Menghapus Diskon " . $diskon . "%"
        ];
        $this->hm->insert($laporan);
        $this->mm->updatediskon($id, $data);
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
            1 => "foto",
            2 => "nama",
            3 => "alamat",
            4 => "tanggallahir",
            5 => "email"
        ];
        $order_by = $order_columns[$order_column];
        $data = $this->mm->getAll($start, $limit, $order_by, $order_dir, $search);
        $recordsTotal = $this->mm->countAll($search);
        $recordsFiltered = $this->mm->countAll();
        $output = [
            "draw" => $this->input->post("draw"),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data
        ];
        echo json_encode($output);
    }
    function getdatadiskon()
    {
        $search = isset($this->input->post("search")["value"]) ? $this->input->post("search")["value"] : "";
        $start = $this->input->post("start");
        $limit = $this->input->post("length");
        $order_column = isset($this->input->post("order")[0]["column"]) ? $this->input->post("order")[0]["column"] : 0;
        $order_dir = isset($this->input->post("order")[0]["dir"]) ? $this->input->post("order")[0]["dir"] : "asc";

        $order_columns = [
            0 => "id",
            1 => "diskon",
            2 => "barangmin",
            3 => "barangmax",
            4 => "hargamin",
            5 => "hargamax"
        ];
        $order_by = $order_columns[$order_column];
        $data = $this->mm->getAll1($start, $limit, $order_by, $order_dir, $search);
        $recordsTotal = $this->mm->countAll1($search);
        $recordsFiltered = $this->mm->countAll1();
        $output = [
            "draw" => $this->input->post("draw"),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data
        ];
        echo json_encode($output);
    }
}
