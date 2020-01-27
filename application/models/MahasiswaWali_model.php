<?php

class MahasiswaWali_model extends CI_Model
{
    public function Select($iddosen)
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
                `program_studi`.`jenjang`
            FROM
                `dosen_wali`
                LEFT JOIN `mahasiswa` ON `dosen_wali`.`npm` = `mahasiswa`.`npm`
                RIGHT JOIN `program_studi` ON `mahasiswa`.`kdps` = `program_studi`.`kdps`
            WHERE iddosen = '$iddosen' AND mahasiswa.statuskul IN('AKTIF','TIDAK AKTIF','CUTI')");
        $data = $result->result_object();
        foreach ($data as $key => $value) {
            $query = $this->db->query("CALL GetIPK('$value->npm')");
            $res = $query->result();
            //add this two line
            $query->next_result();
            $query->free_result();
            //end of new code
            $value->IPK = $res[0]->IPK;
            $value->SKSLulus = $res[0]->SKSLulus;

        }
        return $data;
    }
}
