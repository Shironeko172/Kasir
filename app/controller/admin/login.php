<?php
class Login extends SENE_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
        $this->load("admin_model", "am");
        $this->load("history_model", "hm");
        $this->setTheme("admin");
    }
    function index()
    {
        $data = array();
        $data["sess"] = $this->getKey();
        if (isset($data["sess"]->admin->id)) {
            redir(base_url("admin/home"));
            return;
        }
        $this->setTitle("Admin Login");
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
                if (!isset($sess->admin)) $sess->admin = new stdClass();
                $sess->admin = $am;
                $this->setKey($sess);

                $laporan = [
                    "waktu" => date("Y-m-d h:i:s"),
                    "pelaku" => "admin",
                    "kejadian" => "Admin " . $am->nama . " Telah Melakukan Login"
                ];
                $this->hm->insert($laporan);

                echo json_encode(array("redirect" => base_url("admin/home")));
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
