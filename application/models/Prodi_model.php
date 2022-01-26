<?php

class Prodi_Model extends CI_Model
{
    public function select()
    {
        $data = $this->db->query("SELECT * FROM program_studi")->result_object();
        foreach ($data as $key => $value) {
            $value->kurikulum = $this->db->query("SELECT DISTINCt kurikulum FROM matakuliah WHERE kdps = '$value->kdps' AND kurikulum IS NOT NULL");
        }
    }

}