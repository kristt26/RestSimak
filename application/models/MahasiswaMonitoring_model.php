<?php

class MahasiswaMonitoring_model extends CI_Model
{
    public function __construct() {
        parent::__construct();
        $this->load->library('Authorization_Token');
    }
    public function get($data)
    {
        if(!empty($data) && $this->authorization_token->userInRole("Mahasiswa")){
            $result = $this->db->query(
                "SELECT
                    `List_Draft_DO`.*
                FROM
                    `List_Draft_DO`
                    LEFT JOIN `mahasiswa` ON `mahasiswa`.`npm` = `List_Draft_DO`.`npm`
                WHERE IdUser='$data->id'"
            );
            return $result->result_object();
        }else
        {
            $result = $this->db->query(
                "SELECT
                    `ViewMonitorMhs`.*
                FROM
                    `ViewMonitorMhs`
                    LEFT JOIN `dosen_wali` ON `dosen_wali`.`npm` = `ViewMonitorMhs`.`npm`
                    LEFT JOIN `dosen` ON `dosen`.`iddosen` = `dosen_wali`.`iddosen`
                    LEFT JOIN `pegawai` ON `pegawai`.`idpegawai` = `dosen`.`idpegawai`
                WHERE IdUser = '$data->id'"
            );
            return $result->result_object();
        }
    }    
}
