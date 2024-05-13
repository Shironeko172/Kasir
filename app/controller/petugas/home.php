<?php
class Home extends SENE_Controller
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

        $this->setTitle("petugas Home");
        $this->putThemeContent("home/home", $data);
        $this->putJsReady("home/home_bottom", $data);
        $this->loadLayout("col-1-default", $data);
        $this->render();
    }
    private function randomstring($length = 32)
    {
        $characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
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
    function tambah()
    {
        $data = [
            "kode" => $this->randomkode(),
            "foto" => "skin/media/Icon.png",
            "nama" => htmlspecialchars($this->input->post("nama")),
            "email" => htmlspecialchars($this->input->post("email")),
        ];
        $sess = $this->getKey();
        $laporan = [
            "waktu" => date("Y-m-d h:i:s"),
            "pelaku" => "petugas",
            "kejadian" => "Petugas " . $sess->petugas->nama . " Telah Mendaftarkan Member" . $data["nama"]
        ];
        $this->hm->insert($laporan);
        $this->mm->insert($data);
        $tdata = [
            "token" => $this->randomstring(),
            "expire_token" => date("Y-m-d h:i:s", strtotime("+1 hour"))
        ];
        $mm = $this->mm->getByEmail($data["email"]);
        if (isset($mm->email)) {
            $this->mm->update($data["kode"], $tdata);
            $token = $tdata["token"];
            $to = $data["email"];
            $subject = "yaestore@gmail.com";
            $text = "Silahkan Aktifkan Akun Di Link : " . base_url("petugas/home/aktifkan/") . $token;
            $header = "From: YaeStore";
            mail($to, $subject, $text, $header);
            return;
        }
    }
    function aktifkan($token)
    {
        $data = array();
        $this->setTitle("petugas Home");
        $data["mm"] = $this->mm->gettoken($token);
        $this->putThemeContent("home/aktif", $data);
        $this->putJsReady("home/aktif_bottom", $data);
        $this->loadLayout("col-1-login", $data);
        $this->render();
    }
    function prosesaktif()
    {
        $kode = $this->input->post("kode");
        $data = [
            "password" => password_hash(htmlspecialchars($this->input->post("password")), PASSWORD_DEFAULT),
            "token" => "null",
            "expire_token" => "null",
            "is_active" => 1
        ];
        $this->mm->update($kode, $data);
    }
    function logout()
    {
        $sess = $this->getKey();
        $laporan = [
            "waktu" => date("Y-m-d h:i:s"),
            "pelaku" => "petugas",
            "kejadian" => "Petugas " . $sess->petugas->nama . " Telah Melakukan Logout"
        ];
        $this->hm->insert($laporan);
        $sess->petugas = new stdClass();
        $this->setKey($sess);
        redir(base_url("petugas/login"));
        return;
    }
}
