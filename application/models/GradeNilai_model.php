<?php

class GradeNilai_Model extends CI_Model
{
    public function get()
    {
        $result = $this->db->get("default_nilai");
        return $result->result_array();
    }
}
