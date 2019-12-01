<?php

class Matakuliah_Model extends CI_Model
{
    public function AmbilMatakuliah()
    {
        $result = $this->db->query("
            SELECT
                `program_studi`.`kdps`,
                `program_studi`.`nmps`,
                `program_studi`.`jenjang`,
                `matakuliah`.*
            FROM
                `program_studi`
                LEFT JOIN `matakuliah` ON `matakuliah`.`kdps` = `program_studi`.`kdps`
            WHERE 
                program_studi.status = 'true' and
                matakuliah.kurikulum in(2011,2018,2019)
        ");
        return $result->result_array();
    }

    public function ambilkrsm($IdUser)
    {
        $result = $this->db->query("
            SELECT
                `krsm_detail`.*,
                `dosen_pengampu`.`kdps`
            FROM
                `krsm_detail`
                LEFT JOIN `tahun_akademik` ON `tahun_akademik`.`thakademik` =
                `krsm_detail`.`thakademik` AND `tahun_akademik`.`gg` = `krsm_detail`.`gg`
                LEFT JOIN `dosen_pengampu` ON `dosen_pengampu`.`kmk` = `krsm_detail`.`kmk`
                LEFT JOIN `jadwal_kuliah` ON `jadwal_kuliah`.`idpengampu` =
                `dosen_pengampu`.`idpengampu` AND `jadwal_kuliah`.`kelas` =
                `krsm_detail`.`kelas`
                LEFT JOIN `mahasiswa` ON `krsm_detail`.`npm` = `mahasiswa`.`npm`
            WHERE
                `mahasiswa`.`IdUser` = '$IdUser' AND
                `tahun_akademik`.`status` = 'AKTIF' AND
                `dosen_pengampu`.`mengajar` = 'Y'
        ");
        return $result->result_array();
    }
}
