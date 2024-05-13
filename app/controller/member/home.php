<?php
class Home extends SENE_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
        $this->load("produk_model", "pm");
        $this->load("history_model", "hm");
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
        $data["pm"] = $this->pm->getdiskon();
        $this->setTitle("member Home");
        $this->putThemeContent("home/home", $data);
        $this->putJsReady("home/home_bottom", $data);
        $this->loadLayout("col-1-default", $data);
        $this->render();
    }
    function logout()
    {
        $sess = $this->getKey();
        $sess->member = new stdClass();
        $this->setKey($sess);
        redir(base_url("member/login"));
        return;
    }
}
