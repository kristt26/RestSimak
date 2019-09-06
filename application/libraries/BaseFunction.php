<?php


class BaseFunction
{
    protected $TahunAkademikTable = "tahun_akademik";
    protected $KrsmTable = 'tem_krsm';
    protected $KrsmDetailTabel = 'tem_detail_krsm';
    public function GetTAAktif()
    {
        $this->db->where('status', 'AKTIF');
        $result = $this->db-get($this->TahunAkademikTable);
        return $result->result();
    }

    public function GetTemKrsm($npm)
    {
        
    }
}