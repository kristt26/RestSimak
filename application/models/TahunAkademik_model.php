<?php

class TahunAkademik_Model extends CI_Model
{
    public function getTAAktif()
    {
        $result = $this->db->query("SELECT * FROM tahun_akademik WHERE status='AKTIF'");
        if($result->num_rows()){
            return $result->result_array();
        }else
        {
            return 0;
        }
    }
}