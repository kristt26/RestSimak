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
     
    public function prodi($data)
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
                    LEFT JOIN `program_studi` ON `ViewMonitorMhs`.`kdps` = `program_studi`.`kdps`
                    LEFT JOIN `kaprodi` ON `program_studi`.`idprodi` = `kaprodi`.`idprodi`
                    LEFT JOIN `pegawai` ON `kaprodi`.`idpegawai` = `pegawai`.`idpegawai`
                WHERE IdUser = '$data->id'"
            );
            $message = [
                'status' => 'DosenWali',
                'data'=> $result->result_object()
            ];
            return $message;
        }
    }    

    public function mahasiswa_prodi($data)
    {
        $result = $this->db->query(
            "SELECT
            `mahasiswa`.`npm`,
            `mahasiswa`.`kelas`,
            `mahasiswa`.`nmmhs`,
            `mahasiswa`.`almt`,
            `mahasiswa`.`notlp`,
            `mahasiswa`.`statuskul`,
            `mahasiswa`.`jk`,
            `program_studi`.`kdps`,
            `program_studi`.`nmps`,
            `program_studi`.`jenjang`,
            (SELECT
            SUM(CASE
            WHEN transkip.ket= 'L' THEN 1*transkip.nxsks
            ELSE
            0*transkip.nxsks
            END)/SUM(CASE
            WHEN transkip.ket = 'L' THEN 1*transkip.sks
            ELSE
            0*transkip.sks
            END) FROM transkip WHERE transkip.npm=`mahasiswa`.`npm`)
            AS IPK ,

            (SELECT
            SUM(CASE
            WHEN transkip.ket = 'L' THEN 1*transkip.sks
            ELSE
            0*transkip.sks
            END)FROM transkip WHERE transkip.npm=`mahasiswa`.`npm`) AS SKSLulus,
            (SELECT COUNT(daftar_ulang.thakademik) FROM daftar_ulang where daftar_ulang.npm = `mahasiswa`.`npm`) AS Semester
          FROM
            `mahasiswa`
            LEFT JOIN `program_studi` ON `mahasiswa`.`kdps` = `program_studi`.`kdps`
            LEFT JOIN `kaprodi` ON `program_studi`.`idprodi` = `kaprodi`.`idprodi`
            LEFT JOIN `pegawai` ON `kaprodi`.`idpegawai` = `pegawai`.`idpegawai`
          WHERE mahasiswa.statuskul IN('AKTIF','TIDAK AKTIF','CUTI') AND pegawai.IdUser='$data->id'"
        );
        return $result->result_object();
    }
}