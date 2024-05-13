<?php
class Barang_Model extends SENE_Model
{
    public $tbl = "barang";
    public $tbl_as = "br";
    public $tbl1 = "kategori";
    public $tbl1_as = "kt";

    function __construct()
    {
        parent::__construct();
        $this->db->from($this->tbl);
    }

    function getdata()
    {
        $this->db->where("is_active", "1");
        return $this->db->get();
    }

    function export($start, $end)
    {
        $sql = "SELECT kode, tanggalmasuk, namabarang, kategori, stok, harga, diskon FROM `$this->tbl` WHERE (tanggalmasuk BETWEEN '$start' AND '$end') AND (is_active = '1')";
        return $this->db->query($sql);
    }

    function stok($kode, $data)
    {
        $sql = "UPDATE `$this->tbl` set stok = stok + $data where kode = '$kode'";
        return $this->db->exec($sql);
    }

    function update($kode, $data)
    {   
        $this->db->where("kode", $kode);
        return $this->db->update($this->tbl, $data);
    }

    function kategori()
    {
        $this->db->select("kategori");
        $this->db->from($this->tbl1);
        return $this->db->get();
    }

    function insert($data)
    {
        return $this->db->insert($this->tbl,$data);
    }

    function found()
    {
        $this->db->select_as("COUNT(*)", "jumlah");
        $this->db->where("is_active", 1);
        $this->db->from($this->tbl);
        return $this->db->get_first();
    }
    private function filter_keyword($keyword)
    {
        if (strlen($keyword > 0)) {
            $this->db->where("kode", $keyword, "OR", "%Like%", 1, 0);
            $this->db->where("tanggalmasuk", $keyword, "OR", "%Like%", 0, 0);
            $this->db->where("namabarang", $keyword, "OR", "%Like%", 0, 0);
            $this->db->where("kategori", $keyword, "OR", "%Like%", 0, 0);
            $this->db->where("stok", $keyword, "OR", "%Like%", 0, 0);
            $this->db->where("harga", $keyword, "OR", "%Like%", 0, 0);
            $this->db->where("diskon", $keyword, "OR", "%Like%", 0, 1);
        }
        return $this;
    }
    function getAll($page = 0, $pageSize = 10, $sortCol = "tanggalmasuk", $sortDir = "desc", $keyword = "")
    {
        $this->db->select("kode");
        $this->db->select("tanggalmasuk");
        $this->db->select("namabarang");
        $this->db->select("kategori");
        $this->db->select("stok");
        $this->db->select("harga");
        $this->db->select("diskon");
        $this->db->where("is_active", 1);

        $this->db->from($this->tbl);
        $this->filter_keyword($keyword);
        $this->db->order_by($sortCol, $sortDir)->limit($pageSize, $page);
        return $this->db->get();
    }
    function countAll($keyword = "")
    {
        $this->db->select_as("COUNT(*)", "jumlah", 0);
        $this->db->where("is_active", 1);
        $this->db->from($this->tbl);
        $this->filter_keyword($keyword);
        $d = $this->db->get_first();
        if (isset($d->jumlah)) {
            return $d->jumlah;
        }
        return 0;
    }
    function pengurangan($du)
    {
        $sql = "UPDATE barang SET stok = CASE kode ";
        foreach($du as $item) {
            $kode_item = $item['kode'];
            $qty = $item['qty'];

            $sql .= "WHEN '$kode_item' THEN stok - $qty ";
        }
        $sql .= "END WHERE kode IN(";
        $sql .= "'" . implode("','", array_column($du, 'kode')) . "')";

        return $this->db->exec($sql);
    }
}
