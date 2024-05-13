<?php
class history_model extends SENE_Model
{
    public $tbl = "history";
    public $tbl_as = "hm";

    function __construct()
    {
        parent::__construct();
        $this->db->from($this->tbl);
    }

    function insert($data)
    {
        $this->db->insert($this->tbl, $data);
    }

    private function FilterKeyword($keyword)
    {
        if (strlen($keyword) > 0) {
            $this->db->where("id", $keyword, "OR", "%Like%", 1, 0);
            $this->db->where("waktu", $keyword, "OR", "%Like%", 0, 0);
            $this->db->where("pelaku", $keyword, "OR", "%Like%", 0, 0);
            $this->db->where("kejadian", $keyword, "OR", "%Like%", 0, 1);
        }
        return $this;
    }
    function getAll($page = 0, $pagesize = 10, $sortCol = "id", $sortDir = "desc", $keyword = "")
    {
        $this->db->select("id");
        $this->db->select("waktu");
        $this->db->select("pelaku");
        $this->db->select("kejadian");

        $this->db->from($this->tbl);
        $this->FilterKeyword($keyword);
        $this->db->order_by($sortCol, $sortDir)->limit($pagesize, $page);
        return $this->db->get();
    }
    function countAll($keyword = "")
    {
        $this->db->select_as("COUNT(*)", "jumlah", 0);
        $this->db->from($this->tbl);
        $this->FilterKeyword($keyword);
        $d = $this->db->get_first();
        if (isset($d->jumlah)) {
            return $d->jumlah;
        }
        return 0;
    }
}
