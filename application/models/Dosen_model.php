<?php

class Dosen_Model extends CI_Model
{
    public function get()
    {
        $result = $this->db->get("dosen");
        return $result->result_array();
    }    
}
