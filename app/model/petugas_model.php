<?php
class petugas_model extends SENE_Model
{
    public $tbl = "petugas";
    public $tbl_as = "pm";

    function __construct()
    {
        parent::__construct();
        $this->db->from($this->tbl);
    }
    function update($id, $data)
    {
        $this->db->where("id", $id);
        return $this->db->update($this->tbl, $data);
    }
    function insert($data)
    {
        $this->db->insert($this->tbl, $data);
    }
    function getByEmail($email)
    {
        $this->db->where("is_active", 1);
        $this->db->where("email", $email);
        return $this->db->get_first();
    }

    function found()
    {
        $this->db->select_as("COUNT(*)", "jumlah");
        $this->db->where("is_active", 1);
        return $this->db->get_first();
    }
    private function FilterKeyword($keyword)
    {
        if (strlen($keyword) > 0) {
            $this->db->where("id", $keyword, "OR", "%Like%", 1, 0);
            $this->db->where("nama", $keyword, "OR", "%Like%", 0, 0);
            $this->db->where("alamat", $keyword, "OR", "%Like%", 0, 0);
            $this->db->where("email", $keyword, "OR", "%Like%", 0, 0);
            $this->db->where("tempatlahir", $keyword, "OR", "%Like%", 0, 0);
            $this->db->where("tanggallahir", $keyword, "OR", "%Like%", 0, 1);
        }
        return $this;
    }
    function getAll($page = 0, $pagesize = 10, $sortCol = "id", $sortDir = "desc", $keyword = "")
    {
        $this->db->select("id");
        $this->db->select("foto");
        $this->db->select("nama");
        $this->db->select("alamat");
        $this->db->select("email");
        $this->db->select("tanggallahir");
        $this->db->select("tempatlahir");
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
