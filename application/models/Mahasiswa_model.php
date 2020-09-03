<?php
class Mahasiswa_Model extends CI_Model
{
    public function detailmahasiswa($npm)
    {
        $result = $this->db->query("SELECT
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
            `program_studi`.`nmps`,
            (SELECT
                    SUM(CASE 
                        WHEN ket= 'L' THEN 1*nxsks
                        ELSE
                        0*nxsks
                        END)/SUM(CASE 
                        WHEN ket = 'L' THEN 1*sks
                        ELSE
                        0*sks
                        END) AS IPK
                    FROM
                        `transkip`
                    WHERE
                        mahasiswa.Id=`transkip`.`Idmahasiswa`)AS IPK,
            (SELECT count(*) FROM `daftar_ulang` WHERE mahasiswa.Id=`daftar_ulang`.`idmahasiswa`) AS semester
        FROM
            `mahasiswa`
            LEFT JOIN `program_studi` ON `program_studi`.`kdps` = `mahasiswa`.`kdps`
            LEFT JOIN `transkip` ON `transkip`.`Idmahasiswa` = `mahasiswa`.`Id`
            LEFT JOIN `daftar_ulang` ON `mahasiswa`.`Id` = `daftar_ulang`.`idmahasiswa`
        WHERE
            `mahasiswa`.`npm` = '$npm' AND
            `mahasiswa`.`statuskul` IN ('AKTIF', 'CUTI', 'TIDAK AKTIF', 'TRANSFER')
        GROUP BY mahasiswa.Id;")->row_array();
        return $result;
    }
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
    public function mahasiswaonly($npm=null)
    {
        if($npm!=null)
            $string = "npm='$npm' AND";
        else
            $string ="";
        $result = $this->db->query("
            SELECT 
            `mahasiswa`.*,
            `user`.`Email`
            FROM
                `mahasiswa`
                INNER JOIN `user` ON `mahasiswa`.`IdUser` = `user`.`Id`
            WHERE $string statuskul in('AKTIF', 'CUTI', 'TIDAK AKTIF', 'TRANSFER')")->result();
        return $result;
            
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
