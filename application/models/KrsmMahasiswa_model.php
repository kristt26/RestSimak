<?php

class KrsmMahasiswa_Model extends CI_Model
{
    public function GetOneByTAAktif($npm)
    {
        $this->db->where('status', "AKTIF");
        $queryTA = $this->db->get('tahun_akademik');
        if ($queryTA->num_rows() == 1) {
            $this->db->where('npm', $npm);
            $this->db->where('thakademik', $queryTA->row('thakademik'));
            $this->db->where('gg', $queryTA->row('gg'));
            $resultDetailKrsm = $this->db->get('krsm_detail');
            if($resultDetailKrsm->num_rows()>0){
                return $resultDetailKrsm->result_array();
            }else{
                return 0;
            }
        }

    }
}
