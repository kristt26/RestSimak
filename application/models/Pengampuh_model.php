<?php

class Pengampuh_Model extends CI_Model
{
    public function get($Id)
    {
        $id = $Id['idpengampu'];
        $result = $this->db->query("
            SELECT
                `dosen`.*
            FROM
                `dosen_pengampu`
            LEFT JOIN `dosen` ON `dosen`.`nidn` = `dosen_pengampu`.`nidn` 
            WHERE 
                `dosen_pengampu`.idpengampu = '$id'
        ");
        return $result->result_array();
    }
    
}
