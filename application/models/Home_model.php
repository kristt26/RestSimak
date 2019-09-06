<?php
class Home_Model extends CI_Model
{
    public function GetBiodata($data, $role)
    {
        if ($role == "Mahasiswa") {
            $resultMahasiswa = $this->db->query("
            SELECT
            `mahasiswa`.*,
            `program_studi`.`nmps`,
            `program_studi`.`jenjang` AS `jenjang1`,
            `pegawai`.`Nama`,
            `dosen`.`nmdsn`,
            `dosen`.`nidn`
          FROM
            `mahasiswa`
            LEFT JOIN `program_studi` ON `program_studi`.`kdps` = `mahasiswa`.`kdps`
            LEFT JOIN `kaprodi` ON `kaprodi`.`idprodi` = `program_studi`.`idprodi`
            RIGHT JOIN `pegawai` ON `pegawai`.`idpegawai` = `kaprodi`.`idpegawai`
            LEFT JOIN `dosen_wali` ON `dosen_wali`.`npm` = `mahasiswa`.`npm`
            RIGHT JOIN `dosen` ON `dosen`.`nidn` = `dosen_wali`.`nidn`
          WHERE mahasiswa.npm = $data->Username AND kaprodi.status='AKTIF'");
            if ($resultMahasiswa->num_rows()) {
                return $resultMahasiswa->result_array();
            } else {
                return 0;
            }
        } else {
            $resultPegawai = $this->db->query("
            SELECT
            `pegawai`.*,
            `dosen`.`noskstimik`,
            `dosen`.`spekdsn`,
            `dosen`.`pendakhir`,
            `dosen`.`jaddsn`,
            `dosen`.`tgskstimik`,
            `dosen`.`stdsn`,
            `dosen`.`nidn`
            FROM
            `pegawai`
            LEFT JOIN `user` ON `user`.`Id` = `pegawai`.`IdUser`
            LEFT JOIN `dosen` ON `dosen`.`idpegawai` = `pegawai`.`idpegawai`
            Where `user`.`Id`=$data->id;");
            if ($resultPegawai->num_rows()) {
                return $resultPegawai->result_array();
            } else {
                return 0;
            }
        }
    }
}
