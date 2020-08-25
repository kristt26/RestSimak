<?php
class Mahasiswa_Model extends CI_Model
{
    public function AmbilMahasiswa($npm)
    {
        if ($npm != null && $npm !== "undefined") {
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
                `mahasiswa` WHERE npm='$npm' AND statuskul in('AKTIF', 'CUTI', 'TIDAK AKTIF', 'TRANSFER')");
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
        } else {
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
                `mahasiswa`.`kurikulum`,
                `user`.`Status` AS `userStatus`,
                `user`.`Email`,
                `user`.`Password`,
                `user`.`Username`
            FROM
            `mahasiswa`
            LEFT JOIN `user` ON `user`.`Id` = `mahasiswa`.`IdUser`
            WHERE statuskul in('AKTIF', 'CUTI', 'TIDAK AKTIF', 'TRANSFER')");
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
    public function MahasiswaPublick($npm)
    {
        if ($npm != null && $npm !== "undefined") {
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
                `mahasiswa` WHERE npm='$npm' AND statuskul in('AKTIF', 'CUTI', 'TIDAK AKTIF', 'TRANSFER')");
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
        } else {
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
                `mahasiswa` WHERE statuskul in ('AKTIF', 'CUTI', 'TIDAK AKTIF', 'TRANSFER')");
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
}
