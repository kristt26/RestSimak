<?php
class PeriodePenilaian_model extends CI_Model
{
    public function Getperiode()
    {
        $this->db->where("st_period", "Y");
        $result = $this->db->get("periode_ev");
        return $result->result_array();
    }
}
