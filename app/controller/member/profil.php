<?php
class Profil extends SENE_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
        $this->load("member_model", "mm");
        $this->setTheme("member");
    }
    function index()
    {
        $data = array();
        $data["sess"] = $this->getKey();
        if (!isset($data["sess"]->member->kode)) {
            redir(base_url("member/login"));
            return;
        }
        $this->setTitle("member Home");
        $this->putThemeContent("profil/home", $data);
        $this->putJsReady("profil/home_bottom", $data);
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
        $kode = $this->input->post("kode");
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
        $this->mm->update($kode, $data);
        $sess = $this->getKey();
        $sess->member->kode = $kode;
        $sess->member->nama = $data["nama"];
        $sess->member->alamat = $data["alamat"];
        $sess->member->foto = $data["foto"];
        $sess->member->email = $data["email"];
        $this->setKey($sess);
    }
    function gantipassword()
    {
        $old = $this->input->post("old");
        $oldpassword = htmlspecialchars($this->input->post("oldpassword"));
        $newpassword = htmlspecialchars($this->input->post("newpassword"));
        $repeatpassword = htmlspecialchars($this->input->post("repeatpassword"));
        $kode = $this->input->post("kode");
        if (password_verify($oldpassword, $old)) {
            if ($newpassword === $repeatpassword) {
                $data = ["password" => password_hash($newpassword, PASSWORD_DEFAULT)];
                $this->mm->update($kode, $data);
                $sess = $this->getKey();
                $sess->member->password = $data["password"];
                $this->setKey($sess);
                echo json_encode(array("message1" => "Sukses Di Ubah"));
                return;
            } else {
                echo json_encode(array("message" => "Password Baru Tidak Sama"));
                return;
            }
        } else {
            echo json_encode(array("message" => "Password Lama Tidak Sama"));
            return;
        }
    }
}
