<?php

class Prodi_Model extends CI_Model
{
    public function select()
    {
        return $this->db->get("program_studi")->result();
    }

}