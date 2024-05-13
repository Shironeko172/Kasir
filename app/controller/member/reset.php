<?php
class Reset extends SENE_Controller
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
        if (isset($data["sess"]->member->kode)) {
            redir(base_url("member/login"));
            return;
        }
        $this->setTitle("member Home");
        $this->putThemeContent("reset/home", $data);
        $this->putJsReady("reset/home_bottom", $data);
        $this->loadLayout("col-1-login", $data);
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
    function reset()
    {
        $email  = htmlspecialchars($this->input->post("email"));
        $mm = $this->mm->getByEmail($email);
        $tdata = [
            "token" => $this->randomstring(),
            "expire_token" => date("Y-m-d h:i:s", strtotime("+1 hour"))
        ];
        if (isset($mm->email)) {
            $this->mm->updateByEmail($email, $tdata);
            $token = $tdata["token"];
            $to = $email;
            $subject = "yaestore@gmail.com";
            $text = "Link Untuk Reset Password : " . base_url("member/reset/reste/") . $token;
            $header = "From: YaeStore";
            mail($to, $subject, $text, $header);
            return;
        }
    }
    function reste($token)
    {
        $data = array();
        $this->setTitle("petugas Home");
        $data["mm"] = $this->mm->gettoken($token);
        $this->putThemeContent("reset/reset", $data);
        $this->putJsReady("reset/reset_bottom", $data);
        $this->loadLayout("col-1-login", $data);
        $this->render();
    }
    function resetpassword()
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
}
