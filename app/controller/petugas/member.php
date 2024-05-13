<?php
class Member extends SENE_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
        $this->load("member_model", "mm");
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

        $this->setTitle("Member");
        $this->putThemeContent("member/home", $data);
        $this->putJsReady("member/home_bottom", $data);
        $this->loadLayout("col-1-default", $data);
        $this->render();
    }
    function tambah()
    {
        $data = array();
        $data["foto"] = "skin/media/icon.png";
        $data["nama"] = htmlspecialchars($this->input->post("nama"));
        $data["email"] = htmlspecialchars($this->input->post("email"));
        $data["password"] = password_hash($this->input->post("password"), PASSWORD_DEFAULT);
        $data["is_active"] = 1;

        $sess = $this->getKey();
        $laporan = [
            "tanggalwaktu" => date("Y-m-d h:i:s"),
            "kejadian" => "Petugas " . $sess->petugas->nama . " Telah Mendaftarkan Member Dengan Nama " . $data["nama"]
        ];
        $this->hm->insert($laporan);
        $this->mm->insert($data);
    }
}
