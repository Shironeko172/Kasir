<?php
class Home extends SENE_Controller
{
    function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
        $this->load("member_model", "mm");
        $this->load("petugas_model", "pm");
        $this->load("produk_model", "opm");
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

        $data["opm"] = $this->opm->found();
        $data["pm"] = $this->pm->found();
        $data["mm"] = $this->mm->found();

        $this->setTitle("Admin Home");
        $this->putThemeContent("home/home", $data);
        $this->putJsReady("home/home_bottom", $data);
        $this->loadLayout("col-1-default", $data);
        $this->render();
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
            1 => "waktu",
            2 => "pelaku",
            3 => "kejadian"
        ];
        $order_by = $order_columns[$order_column];
        $data = $this->hm->getAll($start, $limit, $order_by, $order_dir, $search);
        $recordsTotal = $this->hm->countAll($search);
        $recordsFiltered = $this->hm->countAll();
        $output = [
            "draw" => $this->input->post("draw"),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data
        ];
        echo json_encode($output);
    }
    function logout()
    {
        $sess = $this->getKey();
        $laporan = [
            "waktu" => date("Y-m-d h:i:s"),
            "pelaku" => "admin",
            "kejadian" => "Admin " . $sess->admin->nama . " Telah Melakukan Logout"
        ];
        $this->hm->insert($laporan);
        $sess->admin = new stdClass();
        $this->setKey($sess);
        redir(base_url("admin/login"));
        return;
    }
}
