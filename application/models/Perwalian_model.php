<?php
class Perwalian_Model extends CI_Model
{
    public function GetMahasiswa($data)
    {
        $resultMahasiswa = $this->db->query("
            SELECT
                `mahasiswa`.*
            FROM
                `dosen_wali`
                RIGHT JOIN `dosen` ON `dosen_wali`.`iddosen` = `dosen`.`iddosen`
                RIGHT JOIN `mahasiswa` ON `mahasiswa`.`npm` = `dosen_wali`.`npm`
                RIGHT JOIN `pegawai` ON `dosen`.`idpegawai` = `pegawai`.`idpegawai`
            WHERE 
                pegawai.IdUser = '1121' and (mahasiswa.statuskul<>'DO' AND mahasiswa.statuskul<>'LULUS' AND mahasiswa.statuskul<>'UNDUR DIRI' AND mahasiswa.statuskul<>'PINDAH')
        ");
        if($resultMahasiswa->num_rows()){
            foreach ($resultMahasiswa->result_object() as $key => $value) {
                $a = $value;
            }            
        }
    }
}