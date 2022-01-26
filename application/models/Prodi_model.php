<?php

class Prodi_Model extends CI_Model
{
    public function select()
    {
        return $this->db->query("SELECT * FROM program_studi")->result_object();
    }

}