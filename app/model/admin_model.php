<?php
class admin_model extends SENE_Model
{
    public $tbl = "admin";
    public $tbl_as = "am";

    function __construct()
    {
        parent::__construct();
        $this->db->from($this->tbl);
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
}
