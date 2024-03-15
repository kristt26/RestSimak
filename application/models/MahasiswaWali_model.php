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
            END)FROM transkip WHERE transkip.npm=`mahasiswa`.`npm`) AS SKSLulus,
            (SELECT COUNT(daftar_ulang.thakademik) FROM daftar_ulang where daftar_ulang.npm = `mahasiswa`.`npm`) AS Semester
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

		function statusDaftar($uid) {
			$result = $this->db->query("SELECT
				`dosen_wali`.*,
				`mahasiswa`.`nmmhs`,
				`daftar_ulang`.`stdu`,
				`tem_krsm`.`status`
			FROM
				`dosen_wali`
				LEFT JOIN `mahasiswa` ON `dosen_wali`.`npm` = `mahasiswa`.`npm`
				LEFT JOIN `dosen` ON `dosen`.`nidn` = `dosen_wali`.`nidn`
				LEFT JOIN `pegawai` ON `pegawai`.`idpegawai` = `dosen`.`idpegawai`
				LEFT JOIN `daftar_ulang` ON `mahasiswa`.`npm` = `daftar_ulang`.`npm`
				LEFT JOIN `tem_krsm` ON `daftar_ulang`.`npm` = `tem_krsm`.`npm` AND
			`daftar_ulang`.`thakademik` = `tem_krsm`.`thakademik` AND
			`daftar_ulang`.`gg` = `tem_krsm`.`gg`
			LEFT JOIN `tahun_akademik` ON `tahun_akademik`.`thakademik` =
			`daftar_ulang`.`thakademik` AND `tahun_akademik`.`gg` = `daftar_ulang`.`gg`
			WHERE pegawai.IdUser = '$uid' AND tahun_akademik.status = 'AKTIF'");
			return $result->result_object();
		}
}
