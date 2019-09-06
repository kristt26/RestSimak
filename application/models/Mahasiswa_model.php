<?php
class Mahasiswa_Model extends CI_Model
{
    public function AmbilMahasiswa($npm)
    {
        $ResultMahasiswa = $this->db->query("
            SELECT
                `mahasiswa`.`npm`,
                `mahasiswa`.`kdps`,
                `mahasiswa`.`jenjang`,
                `mahasiswa`.`kelas`,
                `mahasiswa`.`nmmhs`,
                `mahasiswa`.`tmlhr`,
                `mahasiswa`.`tglhr`,
                `mahasiswa`.`jk`,
                `mahasiswa`.`agama`,
                `mahasiswa`.`kewarga`,
                `mahasiswa`.`pendidikan`,
                `mahasiswa`.`nmsmu`,
                `mahasiswa`.`jursmu`,
                `mahasiswa`.`kotasmu`,
                `mahasiswa`.`kabsmu`,
                `mahasiswa`.`provsmu`,
                `mahasiswa`.`pekerjaan`,
                `mahasiswa`.`almt`,
                `mahasiswa`.`notlp`,
                `mahasiswa`.`status`,
                `mahasiswa`.`jmsaudara`,
                `mahasiswa`.`nmayah`,
                `mahasiswa`.`almtayah`,
                `mahasiswa`.`nmibu`,
                `mahasiswa`.`sumbiaya`,
                `mahasiswa`.`statuskul`,
                `mahasiswa`.`tgdaftar`,
                `mahasiswa`.`kurikulum`
            FROM
                `mahasiswa` WHERE npm='$npm'");
        if ($ResultMahasiswa->num_rows()) {
            $data = [
                'status' => true,
                'data' => $ResultMahasiswa->result(),
                'message' => "Success",
            ];
            return $data;
        } else {
            $data = [
                'status' => true,
                'data' => $ResultMahasiswa->result(),
                'message' => "Tidak Data Mahasiswa",
            ];
            return $data;
        }
    }
}
