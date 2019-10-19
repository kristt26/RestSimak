<?php

class BeritaAcara_Model extends CI_Model{
    public function Insert($data)
    {
        $result = $this->db->insert("bamengajardosen", $data);
        return $result;
    }
}