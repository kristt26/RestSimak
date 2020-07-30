<?php
class Mahasiswa_Model extends CI_Model
{
    public function AmbilMahasiswa($npm)
    {
        if ($npm != null && $npm!=="undefined") {
            $ResultMahasiswa = $this->db->query("SELECT
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
                (SELECT SUM(CASE 
                    WHEN ket= 'L' THEN 1*nxsks
                    ELSE
                    0*nxsks
                    END)/SUM(CASE 
                    WHEN ket = 'L' THEN 1*sks
                    ELSE
                    0*sks
                    END)
                    AS IPK 
                    FROM transkip WHERE npm=`mahasiswa`.`npm`) AS IPK
            FROM
                `mahasiswa`
            LEFT JOIN `user` ON `user`.`Id` = `mahasiswa`.`IdUser`
            LEFT JOIN `daftar_ulang` ON `mahasiswa`.`npm` = `daftar_ulang`.`npm`
            LEFT JOIN `tahun_akademik` ON `daftar_ulang`.`thakademik` =
                `tahun_akademik`.`thakademik` AND `daftar_ulang`.`gg` =
                `tahun_akademik`.`gg`
            LEFT JOIN `transkip` ON `mahasiswa`.`npm` = `transkip`.`npm`
            WHERE
                `mahasiswa`.`npm`='$npm' AND `mahasiswa`.`statuskul` IN ('AKTIF') AND
                `tahun_akademik`.`status` = 'Aktif'
            GROUP BY `mahasiswa`.`npm`");
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
            (SELECT SUM(CASE 
                WHEN ket= 'L' THEN 1*nxsks
                ELSE
                0*nxsks
                END)/SUM(CASE 
                WHEN ket = 'L' THEN 1*sks
                ELSE
                0*sks
                END)
                AS IPK 
                FROM transkip WHERE npm=`mahasiswa`.`npm`) AS IPK
            FROM
            `mahasiswa`
            LEFT JOIN `user` ON `user`.`Id` = `mahasiswa`.`IdUser`
            LEFT JOIN `daftar_ulang` ON `mahasiswa`.`npm` = `daftar_ulang`.`npm`
            LEFT JOIN `tahun_akademik` ON `daftar_ulang`.`thakademik` =
                `tahun_akademik`.`thakademik` AND `daftar_ulang`.`gg` =
                `tahun_akademik`.`gg`
            LEFT JOIN `transkip` ON `mahasiswa`.`npm` = `transkip`.`npm`
            WHERE
            `mahasiswa`.`statuskul` IN ('AKTIF') AND
            `tahun_akademik`.`status` = 'Aktif'
            GROUP BY `mahasiswa`.`npm`");
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
        if ($npm != null && $npm!=="undefined") {
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
            `mahasiswa` WHERE statuskul in('AKTIF', 'CUTI', 'TIDAK AKTIF', 'TRANSFER')");
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
