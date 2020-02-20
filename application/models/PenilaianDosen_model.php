<?php

class PenilaianDosen_Model extends CI_Model
{
    public function Cek($id)
    {
        $npm = $id['npm'];
        $resultMenilai = $this->db->query("
            SELECT
                COUNT(npm) as Jumlah, (select photo FROM mahasiswa where npm = '$npm') AS photo
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
        if ((int) $resultMenilai->row('NumPeriode') == 0) {
            $message=[
                'message' => "Periode Evaluasi Telah Berakhir",
                'photo' => $resultMenilai->row('photo')
            ];
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
            
            if ($resultKrs->num_rows() > 0) {
                $Jkrs = (int) $resultKrs->row('Jumlah');
                if ($resultKrs->num_rows() > 0) {
                    $JMenilai = (int) $resultMenilai->row('Jumlah');
                    if ($Jkrs == $JMenilai) {
                        $message=[
                            'message' => "Anda Sudah Melakukan Penilaian Dosen",
                            'photo' => $resultMenilai->row('photo')
                        ];
                        return $message;
                    } else {
                        $message=[
                            'message' => "Anda belum menyelesaikan Penilaian Dosen",
                            'photo' => $resultMenilai->row('photo')
                        ];
                        return $message;
                    }
                } else {
                    $message=[
                        'message' => "Anda Belum Melakukan Penilaian Dosen",
                        'photo' => $resultMenilai->row('photo')
                    ];
                    return $message;
                }
            } else {
                $message=[
                    'message' => "Anda Belum Melakukan Kontrak KRS",
                    'photo' => $resultMenilai->row('photo')
                ];
                return $message;
            }

        }
    }
    public function insert($data)
    {
        $this->db->trans_start();
        $datapenilai = [
            'thakademik' => $data->thakademik,
            'gg'=> $data->gg,
            'period'=>$data->period,
            'npm'=> $data->npm,
            'kmk'=>$data->kmk
        ];
        $this->db->insert('penilai_evaluasi', $datapenilai);
        $idPenilai = $this->db->insert_id();
        foreach ($data->Pertanyaan as $key => $value) {
            foreach ($value->Pertanyaan as $key1 => $value1) {
                $nilai = [
                    'thakademik' => $data->thakademik,
                    'gg'=> $data->gg,
                    'periode'=>$data->period,
                    'kmk'=>$data->kmk,
                    'nidn'=>$data->nidn,
                    'kelas'=>$data->kelas,
                    'idkom'=>$value1->idkom,
                    'idnilai'=>$value1->idnilai,
                    'nilai_ev'=>$value1->nilai_ev,
                    'IdPenilai'=>$idPenilai
                ];
                $this->db->insert("nilai_evaluasi", $nilai);
            }
        }
        if($this->db->trans_status()===false){
            $this->db->trans_rollback();
            return false;
        }else{
            $this->db->trans_complete();
            return true;
        }
        // $result = $this->db->insert("penilai_evaluasi", $data);
        return $result;
    }

    public function get($data)
    {
        $this->db->where("thakademik", $data['thakademik']);
        $this->db->where("gg", $data['gg']);
        $this->db->where("period", $data['period']);
        $this->db->where("npm", $data['npm']);
        $this->db->where("kmk", $data['kmk']);
        $result =  $this->db->get("penilai_evaluasi");
        if($result->num_rows()>0){
            return true;
        }else{
            return false;
        }
    }

    public function update($data)
    {
        $this->db->where("id", $data->id);
        $result = $this->db->update("penilai_evaluasi", $data); 
        return $result;
    }

    public function delete($id)
    {
        $this->db->where("id", $id['id']);
            $result =  $this->db->delete("penilai_evaluasi");
            return $result;
    }
}
