<?php

class TahunAkademik_Model extends CI_Model
{
    public function getTAAktif()
    {
        $result = $this->db->query("SELECT * FROM tahun_akademik WHERE status='AKTIF'");
        if ($result->num_rows()) {
            return $result->result_array();
        } else {
            return 0;
        }
    }
    public function TAAktif()
    {
        $result = $this->db->query("SELECT * FROM tahun_akademik WHERE status='AKTIF'");
        if ($result->num_rows()) {
            return $result->row_array();
        } else {
            return 0;
        }
    }

    public function select()
    {
        $result = $this->db->query("SELECT * FROM tahun_akademik")->result();
        return $result;
    }
}