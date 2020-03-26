<?php

class BeritaAcara_Model extends CI_Model
{
    public function insert($data, $akses)
    {
        if ($akses) {
            $this->db->where('idjadwal', $data['idjadwal']);
            $result_jadwal = $this->db->get('jadwal_kuliah');
            $jamjadwal = strtotime($result_jadwal->row('ws'));

            $jamsistem = strtotime(date('H:i:s'));
            $selisih = $jamsistem - $jamjadwal;
            $menit = floor($selisih / 60);
            if ($menit <= 30 && $menit >= -15) {
                $this->db->where('nidn', $data['nidn']);
                $this->db->where('idjadwal', $data['idjadwal']);
                $this->db->where('kmk', $data['kmk']);
                $this->db->where('tanggal', $data['tanggal']);
                $num = $this->db->get('bamengajardosen');
                if ($num->num_rows() === 0) {
                    $result = $this->db->insert("bamengajardosen", $data);
                    $message = [
                        'status' => true,
                        'id' => $this->db->insert_id(),
                    ];
                    return $message;
                } else {
                    $message = [
                        'status' => true,
                        'id' => $num->row('idbamengajardosen'),
                    ];
                    return $message;
                }
            } else {
                $message = [
                    'status' => false,
                ];
                return $message;
            }
        } else {
            $this->db->where('nidn', $data['nidn']);
            $this->db->where('idjadwal', $data['idjadwal']);
            $this->db->where('kmk', $data['kmk']);
            $this->db->where('tanggal', $data['tanggal']);
            $num = $this->db->get('bamengajardosen');
            if ($num->num_rows() === 0) {
                $result = $this->db->insert("bamengajardosen", $data);
                $message = [
                    'status' => true,
                    'id' => $this->db->insert_id(),
                ];
                return $message;
            } else {
                $message = [
                    'status' => true,
                    'id' => $num->row('idbamengajardosen'),
                ];
                return $message;
            }
        }

    }

    public function get($idjadwal, $nidn)
    {
        $this->db->where("idjadwal", $idjadwal);
        $this->db->where("nidn", $nidn);
        $result = $this->db->get("bamengajardosen");
        return $result->result_array();
    }

    public function GetLaporan($data)
    {
        $message = [
            "data" => array(),
        ];
        $this->db->where("status", "true");
        $result = $this->db->get("program_studi");
        $DataProdi = $result->result_array();
        $result = $this->db->query("
            SELECT
                `matakuliah`.`kmk`,
                `matakuliah`.`nmmk`,
                `matakuliah`.`kdps`,
                `matakuliah`.`kurikulum`,
                `matakuliah`.`idmatakuliah`,
                `jadwal_kuliah`.`idjadwal`,
                `jadwal_kuliah`.`kelas`,
                `jadwal_kuliah`.`sks`,
                `pegawai`.`Nama`,
                `tahun_akademik`.`thakademik`,
                `tahun_akademik`.`gg`,
                `program_studi`.`nmps`
            FROM
                `jadwal_kuliah`
                RIGHT JOIN `tahun_akademik` ON `tahun_akademik`.`thakademik` =
                `jadwal_kuliah`.`thakademik` AND `tahun_akademik`.`gg` =
                `jadwal_kuliah`.`gg`
                LEFT JOIN `dosen_pengampu` ON `dosen_pengampu`.`thakademik` =
                `tahun_akademik`.`thakademik` AND `dosen_pengampu`.`gg` =
                `tahun_akademik`.`gg` AND `jadwal_kuliah`.`idpengampu` =
                `dosen_pengampu`.`idpengampu`
                LEFT JOIN `dosen` ON `dosen`.`iddosen` = `dosen_pengampu`.`iddosen`
                RIGHT JOIN `pegawai` ON `pegawai`.`idpegawai` = `dosen`.`idpegawai`
                LEFT JOIN `matakuliah` ON `matakuliah`.`idmatakuliah` =
                `dosen_pengampu`.`idmatakuliah` AND `matakuliah`.`kdps` =
                `jadwal_kuliah`.`kdps`
                LEFT JOIN `program_studi` ON `jadwal_kuliah`.`kdps` = `program_studi`.`kdps`
            WHERE
                `tahun_akademik`.`status` = 'AKTIF' AND
                `dosen_pengampu`.`mengajar` = 'Y'
            GROUP BY
                `jadwal_kuliah`.`thakademik`,
                `jadwal_kuliah`.`gg`,
                `jadwal_kuliah`.`kelas`,
                `jadwal_kuliah`.`kmk`,
                `program_studi`.`nmps`

        ");
        $DataMatakuliah = $result->result_array();
        $result = $this->db->query("
            SELECT
                *
            FROM
                `bamengajardosen` WHERE status='rekap'
        ");
        $DataBa = $result->result_array();
        foreach ($DataMatakuliah as $key1 => $value1) {
            $DatasMatakuliah = [
                "Matakuliah" => $value1['nmmk'],
                "dosen" => $value1['Nama'],
                "kmk" => $value1['kmk'],
                "kelas" => $value1['kelas'],
                "sks" => $value1['sks'],
                "jurusan" => $value1['nmps'],
                "beritaacara" => array(),
            ];
            foreach ($DataBa as $key => $value2) {
                if ($value1['idjadwal'] == $value2['idjadwal'] && $value2['status'] == 'rekap') {
                    array_push($DatasMatakuliah['beritaacara'], $value2);
                }
            }
            array_push($message['data'], $DatasMatakuliah);
        }
        return $message;
    }

    public function update($data)
    {
        $this->db->set("persetujuan1", $data["persetujuan1"]);
        $this->db->where("idbamengajardosen", $data["idbamengajardosen"]);
        $result = $this->db->update("bamengajardosen");
        return $result;
    }
    public function Persetujuan()
    {
        $result = $this->db->query("
            SELECT
                `bamengajardosen`.*,
                `matakuliah`.`nmmk`,
                `matakuliah`.`sks`,
                `matakuliah`.`smt`,
                `matakuliah`.`kdps`,
                `jadwal_kuliah`.`kelas`,
                `jadwal_kuliah`.`dsn_saji`
            FROM
                `bamengajardosen`
                LEFT JOIN `matakuliah` ON `bamengajardosen`.`kmk` = `matakuliah`.`kmk`
                LEFT JOIN `jadwal_kuliah` ON `jadwal_kuliah`.`idjadwal` =
                `bamengajardosen`.`idjadwal`
            WHERE
                `bamengajardosen`.`status` = 'non'
            ORDER BY
                `matakuliah`.`nmmk`,
                `jadwal_kuliah`.`kelas`,
                `bamengajardosen`.`tanggal` DESC

        ");
        return $result->result_array();
    }
    public function rekap()
    {
        $this->db->trans_begin();

        $this->db->set("status", "rekap");
        $this->db->set('tanggalrekap');
        $this->db->where("status", "non");
        $date = date('Y-m-d');

        $this->db->query("UPDATE bamengajardosen SET status='finish' WHERE status='rekap'");
        $result = $this->db->query("UPDATE bamengajardosen SET status='rekap', tanggalrekap='$date' WHERE persetujuan1 IS NOT NULL AND tanggalrekap IS NULL");
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return $result;
        } else {
            $this->db->trans_commit();
            return $result;
        }
    }
    public function hapus($id)
    {
        $this->db->where("idbamengajardosen", $id);
        $result = $this->db->delete("bamengajardosen");
        return $result;
    }
    public function laporan()
    {
        $resultTa = $this->db->query("call taAktif()");
        $taAktif = $resultTa->result_object();
        $ta = $taAktif[0]->thakademik;
        $gg = $taAktif[0]->gg;
        $resultTa->next_result(); 
        // $resultTa->free_result(); 
        $result = $this->db->query("call rekap_ba('$ta', '$gg')");
        $a = $result->result_array();
        return $a;
    }
}
