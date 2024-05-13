<?php
class Login extends SENE_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
        $this->load("petugas_model", "am");
        $this->load("history_model", "hm");
        $this->setTheme("petugas");
    }
    function index()
    {
        $data = array();
        $data["sess"] = $this->getKey();
        if (isset($data["sess"]->petugas->id)) {
            redir(base_url("petugas/home"));
            return;
        }
        $this->setTitle("Petugas Login");
        $this->putThemeContent("login/home", $data);
        $this->putJsReady("login/home_bottom", $data);
        $this->loadLayout("col-1-login", $data);
        $this->render();
    }
    function proses()
    {
        $email = htmlspecialchars($this->input->post("email"));
        $password = htmlspecialchars($this->input->post("password"));
        $am = $this->am->getByEmail($email);
        if (isset($am->email)) {
            if (password_verify($password, $am->password)) {
                $sess = $this->getKey();
                if (!is_object($sess)) $sess = new stdClass();
                if (!isset($sess->petugas)) $sess->petugas = new stdClass();
                $sess->petugas = $am;
                $this->setKey($sess);

                $laporan = [
                    "waktu" => date("Y-m-d h:i:s"),
                    "pelaku" => "petugas",
                    "kejadian" => "petugas " . $am->nama . " Telah Melakukan Login"
                ];
                $this->hm->insert($laporan);

                echo json_encode(array("redirect" => base_url("petugas/home")));
                return;
            } else {
                echo json_encode(array("message" => "Email Atau Password Salah"));
                return;
            }
        } else {
            echo json_encode(array("message" => "Tidak Di Temukan Akun Tersebut"));
            return;
        }
    }
}
