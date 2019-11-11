<?php

class Dosen_Model extends CI_Model
{
    public function get()
    {

        $result = $this->db->query("
            SELECT
                *
            FROM
                `dosen`
                RIGHT JOIN `pegawai` ON `dosen`.`idpegawai` = `pegawai`.`idpegawai` 
            WHERE 
                `dosen`.nidn!='null' AND
                `dosen`.scholarId!='null'
        ");
        $Data = $result->result_array();
        $b=array(
            "Data"=>array()
        );
        foreach ($Data as $ke => $value) {
            $a = "http://cse.bth.se/~fer/googlescholar-api/googlescholar.php?user=".$value['scholarId'];
            $response = file_get_contents($a);
            $data = array(
                "nidn"=>$value["nidn"],
                "nmsdn"=>$value["nmdsn"],
                "pendidikan"=>$value['pendakhir'],
                "Publikasi"=> $response
            );
            array_push($b["Data"], $data);
        }
        return $b["Data"];
    }    
}
