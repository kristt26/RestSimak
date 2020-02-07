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
                *
              FROM
                `ViewMhsWarning`
                LEFT JOIN `mahasiswa` ON `mahasiswa`.`npm` = `ViewMhsWarning`.`npm`
              WHERE mahasiswa.IdUser = '$data->id'"
            );
            $message = [
                'status' => 'Mahasiswa',
                'data'=> $result->result_object()
            ];
            return $message;
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
            $message = [
                'status' => 'DosenWali',
                'data'=> $result->result_object()
            ];
            return $message;
        }
    }    
}
