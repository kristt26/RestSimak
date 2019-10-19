<?php

class BeritaAcara_Model extends CI_Model{
    public function Insert($data)
    {
        $result = $this->db->insert("bamengajardosen", $data);
        return $result;
    }

    public function get($idjadwal, $dosenid)
    {
        $this->db->where("idjadwal", $idjadwal);
        $this->db->where("dosenid", $dosenid);
        $result = $this->db->get("bamengajardosen");
        return $result->result_array();
    }
}