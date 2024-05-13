<?php
class produk_model extends SENE_Model
{
    public $tbl = "produk";
    public $tbl_as = "hm";
    public $tbl1 = "kategori";
    public $tbl1_as = "kt";

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
    function getByNamaProduk($namaproduk)
    {
        $this->db->where("is_active", 1);
        $this->db->where("namaproduk", $namaproduk);
        return $this->db->get_first();
    }
    function get()
    {
        $this->db->where("is_active", 1);
        return $this->db->get();
    }
    function mengurangistok($du)
    {
        $sql = "UPDATE `$this->tbl` SET ";
        foreach ($du as $item) {
            $kode = $item->kode;
            $qty = $item->qty;

            $stok = " stok - $qty";
            $stokkeluar = " stokkeluar + $qty ";

            $sql .= "stok = CASE WHEN kode = '$kode' THEN $stok ELSE stok END, ";
            $sql .= "stokkeluar = CASE WHEN kode = '$kode' THEN $stokkeluar ELSE stokkeluar END, ";
        }
        $sql = rtrim($sql, ", ");

        $sql .= " WHERE kode IN (";
        $sql .= "'" . implode("','", array_column($du, 'kode')) . "')";

        return $this->db->exec($sql);
    }
    function kategori()
    {
        $this->db->from($this->tbl1);
        return $this->db->get();
    }
    function tambahstok($kode, $data)
    {
        $sql = "UPDATE `$this->tbl` SET stokmasuk = stokmasuk + $data, stok = stok + $data WHERE kode = '$kode'";
        return $this->db->exec($sql);
    }
    function kurangistok($kode, $data)
    {
        $sql = "UPDATE `$this->tbl` SET stokkeluar = stokkeluar + $data, stok = stok - $data WHERE kode = '$kode'";
        return $this->db->exec($sql);
    }
    function export($awal, $akhir)
    {
        $sql = "SELECT kode, tanggalmasuk, namaproduk, kategori, stok, stokmasuk, stokkeluar, harga FROM `$this->tbl` WHERE (tanggalmasuk BETWEEN '$awal' AND '$akhir') AND (is_active = 1)";
        return $this->db->query($sql);
    }
    function tambahkt($data)
    {
        $this->db->insert($this->tbl1, $data);
    }
    function insert($data)
    {
        $this->db->insert($this->tbl, $data);
    }
    function getdiskon()
    {
        $sql = "SELECT * FROM `$this->tbl` WHERE (diskon > 0) AND (is_active = 1)";
        return $this->db->query($sql);
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
            $this->db->where("kode", $keyword, "OR", "%Like%", 1, 0);
            $this->db->where("tanggalmasuk", $keyword, "OR", "%Like%", 0, 0);
            $this->db->where("namaproduk", $keyword, "OR", "%Like%", 0, 0);
            $this->db->where("kategori", $keyword, "OR", "%Like%", 0, 0);
            $this->db->where("stok", $keyword, "OR", "%Like%", 0, 0);
            $this->db->where("harga", $keyword, "OR", "%Like%", 0, 0);
            $this->db->where("diskon", $keyword, "OR", "%Like%", 0, 1);
        }
        return $this;
    }
    function getAll($page = 0, $pagesize = 10, $sortCol = "kode", $sortDir = "desc", $keyword = "")
    {
        $this->db->select("kode");
        $this->db->select("tanggalmasuk");
        $this->db->select("namaproduk");
        $this->db->select("kategori");
        $this->db->select("stok");
        $this->db->select("harga");
        $this->db->select("diskon");
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
