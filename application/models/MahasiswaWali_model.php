<?php

class MahasiswaWali_model extends CI_Model
{
    public function Select($IdUser)
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
            END)FROM transkip WHERE transkip.npm=`mahasiswa`.`npm`) AS SKSLulus
          FROM
            `dosen_wali`
            LEFT JOIN `mahasiswa` ON `dosen_wali`.`npm` = `mahasiswa`.`npm`
            RIGHT JOIN `program_studi` ON `mahasiswa`.`kdps` = `program_studi`.`kdps`
            RIGHT JOIN `dosen` ON `dosen`.`iddosen` = `dosen_wali`.`iddosen`
            RIGHT JOIN `pegawai` ON `pegawai`.`idpegawai` = `dosen`.`idpegawai`
          WHERE mahasiswa.statuskul IN('AKTIF','TIDAK AKTIF','CUTI') AND pegawai.IdUser='$IdUser'"
        );
        return $result->result_object();
    }
}
