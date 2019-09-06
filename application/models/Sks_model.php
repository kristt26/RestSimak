<?php
class Sks_Model extends CI_Model
{
    public function SksMahasiswa($npm)
    {
        $ResultTahun = $this->db->query("SELECT * FROM tahun_akademik WHERE status ='AKTIF'");
        $TahunAkademik = $ResultTahun->row('thakademik');
        $Semester = $ResultTahun->row('gg');
        $resultkhs = $this->db->query("SELECT * FROM krsm_detail WHERE npm ='$npm' AND thakademik = '$TahunAkademik' AND gg ='$Semester'");
        $SKS = 0;
        $Praktikum = 0;
        if($resultkhs->num_rows()){
            foreach ($resultkhs->result() as $key => $value) {
                $SKS+=$value->sks;
                $matakuliah = $value->nmmk;
                $Cari = strpos($matakuliah, 'PRAKT.');
                if($Cari !== false){
                    $Praktikum+=1;
                }else{
                    $Cari1 = strpos($matakuliah, '[+]');
                    if($Cari1 !== false){
                        $Praktikum+=1;
                    }
                }
            }
        }
        $Data = [
            'SKS'=>$SKS,
            'Praktikum'=>$Praktikum
        ];
        return $Data;
    }
}
