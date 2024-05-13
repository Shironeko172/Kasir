<?php
class transaksi_model extends SENE_Model
{
    public $tbl = "transaksi";
    public $tbl_as = "tr";

    function __construct()
    {
        parent::__construct();
        $this->db->from($this->tbl);
    }

    function insert($data)
    {
        $this->db->insert($this->tbl, $data);
    }
    function getPerFaktur($nofaktur)
    {
        $this->db->where("nofaktur", $nofaktur);
        return $this->db->get_first();
    }
    function found()
    {
        $this->db->select_as("COUNT(*)", "jumlah");
        $this->db->where("is_active", 1);
        return $this->db->get_first();
    }
    function export($awal, $akhir)
    {
        $sql = "SELECT nofaktur, tanggaltransaksi, petugas, member, barang, subtotal, diskon, total, uang, kembalian FROM `$this->tbl` WHERE (tanggaltransaksi BETWEEN '$awal' AND '$akhir') AND (is_active = 1)";
        return $this->db->query($sql);
    }
    private function FilterKeyword($keyword)
    {
        if (strlen($keyword) > 0) {
            $this->db->where("nofaktur", $keyword, "OR", "%Like%", 1, 0);
            $this->db->where("tanggaltransaksi", $keyword, "OR", "%Like%", 0, 0);
            $this->db->where("petugas", $keyword, "OR", "%Like%", 0, 0);
            $this->db->where("member", $keyword, "OR", "%Like%", 0, 0);
            $this->db->where("barang", $keyword, "OR", "%Like%", 0, 0);
            $this->db->where("total", $keyword, "OR", "%Like%", 0, 1);
        }
        return $this;
    }
    function getAll($page = 0, $pagesize = 10, $sortCol = "nofaktur", $sortDir = "desc", $keyword = "")
    {
        $this->db->select("id");
        $this->db->select("nofaktur");
        $this->db->select("tanggaltransaksi");
        $this->db->select("petugas");
        $this->db->select("member");
        $this->db->select("barang");
        $this->db->select("total");
        $this->db->select("diskon");
        $this->db->select("uang");
        $this->db->select("kembalian");
        $this->db->where("is_active", 1);

        $this->db->from($this->tbl);
        $this->FilterKeyword($keyword);
        $this->db->order_by($sortCol, $sortDir)->limit($pagesize, $page);
        return $this->db->get();
    }
    function countAll($keyword = "")
    {
        $this->db->select_as("COUNT(*)", "jumlah", 0);
        $this->db->where("is_active", 1);
        $this->db->from($this->tbl);
        $this->FilterKeyword($keyword);
        $d = $this->db->get_first();
        if (isset($d->jumlah)) {
            return $d->jumlah;
        }
        return 0;
    }
}
