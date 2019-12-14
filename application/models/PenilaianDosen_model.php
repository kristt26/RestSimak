<?php

class PenilaianDosen_Model extends CI_Model
{
    public function Cek($id)
    {
        $npm = $id['npm'];
        $this->db->where("st_period", "Y");
        $resultPeriode = $this->db->get("periode_ev");
        if ($resultPeriode->num_rows() == 0) {
            $message = 'Periode Evaluasi Telah Berakhir';
            return $message;
        } else {
            $resultKrs = $this->db->query("
            SELECT
                COUNT(npm) as Jumlah
            FROM
                `krsm_detail`
            LEFT JOIN `tahun_akademik` ON `tahun_akademik`.`thakademik` =
                `krsm_detail`.`thakademik` AND `tahun_akademik`.`gg` = `krsm_detail`.`gg`
            WHERE
                `tahun_akademik`.status = 'AKTIF' AND
                `krsm_detail`.npm = '$npm'
        ");
            $Jkrs = 0;
            $JMenilai = 0;
            $resultMenilai = $this->db->query("
            SELECT
                COUNT(npm) as Jumlah
            FROM
                `penilai_evaluasi`
            LEFT JOIN `tahun_akademik` ON `tahun_akademik`.`thakademik` =
                `penilai_evaluasi`.`thakademik` AND `tahun_akademik`.`gg` =
                `penilai_evaluasi`.`gg`
            LEFT JOIN `periode_ev` ON `periode_ev`.`nm_period` =
                `penilai_evaluasi`.`period`
            WHERE
                `tahun_akademik`.status = 'AKTIF' AND
                `penilai_evaluasi`.npm = '$npm' AND
                `periode_ev`.`st_period` = 'Y'
        ");
            if ($resultKrs->num_rows() > 0) {
                $Jkrs = (int) $resultKrs->row('Jumlah');
                if ($resultKrs->num_rows() > 0) {
                    $JMenilai = (int) $resultMenilai->row('Jumlah');
                    if ($Jkrs == $JMenilai) {
                        $message = "Anda Sudah Melakukan Penilaian Dosen";
                        return $message;
                    } else {
                        $message = 'Anda belum menyelesaikan Penilaian Dosen';
                        return $message;
                    }
                } else {
                    $message = 'Anda Belum Melakukan Penilaian Dosen';
                    return $message;
                }
            } else {
                $message = 'Anda Belum Melakukan Kontrak KRS';
                return $message;
            }

        }
    }
}
