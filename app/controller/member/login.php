<?php
class Login extends SENE_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
        $this->load("member_model", "mm");
        $this->load("history_model", "hm");
        $this->setTheme("member");
    }
    function index()
    {
        $data = array();
        $data["sess"] = $this->getKey();
        if (isset($data["sess"]->member->id)) {
            redir(base_url("member/home"));
            return;
        }
        $this->setTitle("member Login");
        $this->putThemeContent("login/home", $data);
        $this->putJsReady("login/home_bottom", $data);
        $this->loadLayout("col-1-login", $data);
        $this->render();
    }
    function proses()
    {
        $email = htmlspecialchars($this->input->post("email"));
        $password = htmlspecialchars($this->input->post("password"));
        $mm = $this->mm->getByEmail($email);
        if (isset($mm->email)) {
            if (empty($mm->is_active)) {
                echo json_encode(array("message" => "Akun Sudah Non Aktif"));
                return;
            }
            if (password_verify($password, $mm->password)) {
                $sess = $this->getKey();
                if (!is_object($sess)) $sess = new stdClass();
                if (!isset($sess->member)) $sess->member = new stdClass();
                $sess->member = $mm;
                $this->setKey($sess);
                echo json_encode(array("redirect" => base_url("member/home")));
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
