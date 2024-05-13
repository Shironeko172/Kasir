<?php
class member_model extends SENE_Model
{
    public $tbl = "member";
    public $tbl_as = "mm";
    public $tbl1 = "diskon";
    public $tbl1_as = "ds";

    function __construct()
    {
        parent::__construct();
        $this->db->from($this->tbl);
    }
    function update($kode, $data)
    {
        $this->db->where("kode", $kode);
        return $this->db->update($this->tbl, $data);
    }
    function updateByEmail($email, $data)
    {
        $this->db->where("email", $email);
        return $this->db->update($this->tbl, $data);
    }
    function insert($data)
    {
        $this->db->insert($this->tbl, $data);
    }
    function getByEmail($email)
    {
        $this->db->where("email", $email);
        return $this->db->get_first();
    }
    function getByEmail2($email)
    {
        $this->db->where("is_active", 1);
        $this->db->where("email", $email);
        return $this->db->get_first();
    }
    function gettoken($token)
    {
        $this->db->where("token", $token);
        return $this->db->get();
    }
    function insertdiskon($data)
    {
        $this->db->insert($this->tbl1, $data);
    }
    function diskon()
    {
        $this->db->where("is_active", 1);
        $this->db->from($this->tbl1);
        return $this->db->get();
    }
    function updatediskon($id, $data)
    {
        $this->db->where("id", $id);
        return $this->db->update($this->tbl1, $data);
    }
    function get()
    {
        $this->db->where("is_active", 1);
        return $this->db->get();
    }

    function found()
    {
        $this->db->select_as("COUNT(*)", "jumlah");
        $this->db->where("is_active", 1);
        return $this->db->get_first();
    }
    private function FilterKeyword1($keyword)
    {
        if (strlen($keyword) > 0) {
            $this->db->where("id", $keyword, "OR", "%Like%", 1, 0);
            $this->db->where("diskon", $keyword, "OR", "%Like%", 0, 0);
            $this->db->where("barangmin", $keyword, "OR", "%Like%", 0, 0);
            $this->db->where("barangmax", $keyword, "OR", "%Like%", 0, 0);
            $this->db->where("hargamin", $keyword, "OR", "%Like%", 0, 0);
            $this->db->where("hargamax", $keyword, "OR", "%Like%", 0, 1);
        }
        return $this;
    }
    function getAll1($page = 0, $pagesize = 10, $sortCol = "id", $sortDir = "desc", $keyword = "")
    {
        $this->db->select("id");
        $this->db->select("diskon");
        $this->db->select("barangmin");
        $this->db->select("barangmax");
        $this->db->select("hargamin");
        $this->db->select("hargamax");
        $this->db->where("is_active", 1);

        $this->db->from($this->tbl1);
        $this->FilterKeyword1($keyword);
        $this->db->order_by($sortCol, $sortDir)->limit($pagesize, $page);
        return $this->db->get();
    }
    function countAll1($keyword = "")
    {
        $this->db->select_as("COUNT(*)", "jumlah", 0);
        $this->db->where("is_active", 1);
        $this->db->from($this->tbl1);
        $this->FilterKeyword1($keyword);
        $d = $this->db->get_first();
        if (isset($d->jumlah)) {
            return $d->jumlah;
        }
        return 0;
    }
    private function FilterKeyword($keyword)
    {
        if (strlen($keyword) > 0) {
            $this->db->where("kode", $keyword, "OR", "%Like%", 1, 0);
            $this->db->where("nama", $keyword, "OR", "%Like%", 0, 0);
            $this->db->where("alamat", $keyword, "OR", "%Like%", 0, 0);
            $this->db->where("email", $keyword, "OR", "%Like%", 0, 1);
        }
        return $this;
    }
    function getAll($page = 0, $pagesize = 10, $sortCol = "kode", $sortDir = "desc", $keyword = "")
    {
        $this->db->select("kode");
        $this->db->select("foto");
        $this->db->select("nama");
        $this->db->select("alamat");
        $this->db->select("email");
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
