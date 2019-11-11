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
        return $result->result_array();
    }
    public function CallAPI($a)
    {
        
        $ch = curl_init();
        // set url
        curl_setopt($ch, CURLOPT_URL, $a);
        // $output contains the output json
        $output = curl_exec($ch);
        // close curl resource to free up system resources
        curl_close($ch);
        // {"name":"Baron","gender":"male","probability":0.88,"count":26}

        return $output;
    }
}
