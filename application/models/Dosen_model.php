<?php

class Dosen_Model extends CI_Model
{
    public function get()
    {
        $result = $this->db->query("
            SELECT
                *
            FROM
                `dosen`
                RIGHT JOIN `pegawai` ON `dosen`.`idpegawai` = `pegawai`.`idpegawai` 
            WHERE 
                `dosen`.nidn!='null' AND
                `dosen`.scholarId!='null'
        ");
        return $result->result_array();
    }    
}
