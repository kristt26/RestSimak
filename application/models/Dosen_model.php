<?php

class Dosen_Model extends CI_Model
{
    public function get()
    {
        $result = $this->db->query("
            SELECT
                *
            FROM
                `dosen`
                RIGHT JOIN `pegawai` ON `dosen`.`idpegawai` = `pegawai`.`idpegawai`
            WHERE
                `dosen`.nidn!='null' AND
                `dosen`.scholarId!='null'
        ");
        $Data = $result->result_array();
        $b = array(
            "Data" => array(),
        );
        foreach ($Data as $ke => $value) {
            $a = "http://cse.bth.se/~fer/googlescholar-api/googlescholar.php?user=" . $value['scholarId'];
            $response = $this->callAPI("GET", $a, false);
            if (strpos($response, '"total_citations": ,')) {
                $response = str_replace('"total_citations":', '"total_citations": 0', $response);
            }
            $data = array(
                "nidn" => $value["nidn"],
                "nmsdn" => $value["nmdsn"],
                "pendidikan" => $value['pendakhir'],
                "scholarId" => $value['scholarId'],
                "Publikasi" => $response,
            );
            array_push($b["Data"], $data);
        }
        return $b["Data"];
    }
    public function callAPI($method, $url, $data)
    {
        $curl = curl_init();

        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }

                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }

                break;
            default:
                if ($data) {
                    $url = sprintf("%s?%s", $url, http_build_query($data));
                }

        }

        // OPTIONS:
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'APIKEY: 111111111111111111111',
            'Content-Type: application/json',
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        // EXECUTE:
        $result = curl_exec($curl);
        if (!$result) {die("Connection Failure");}
        curl_close($curl);
        return $result;
    }
    public function select()
    {
        $tahunakademik = $this->db->get_where('tahun_akademik', ['status' => 'AKTIF'])->result()[0];
        $gg = $tahunakademik->gg == "GANJIL" ? 1 : 0;
        $prodi = $this->db->get_where('program_studi', ['status' => 'true'])->result();
        foreach ($prodi as $key => $itemprodi) {
            $itemprodi->thakademik = $tahunakademik->thakademik;
            $itemprodi->gg = $tahunakademik->gg;
            $itemprodi->idtahunakademik = $tahunakademik->idtahunakademik;
            $itemprodi->kurikulum = $this->db->query("SELECT kdps, kurikulum FROM `matakuliah` WHERE kurikulum is not null AND kdps = '$itemprodi->kdps' GROUP BY kurikulum")->result();
            foreach ($itemprodi->kurikulum as $key => $itemkurikulum) {
                $itemkurikulum->matakuliah = $this->db->query("SELECT
                    matakuliah.*
                FROM
                    `matakuliah`
                WHERE matakuliah.kurikulum ='$itemkurikulum->kurikulum' AND matakuliah.kdps='$itemprodi->kdps' AND (matakuliah.smt%2)=$gg
                GROUP BY matakuliah.kmk")->result();
            }
        }
        $dosen = $this->db->get('dosen')->result();
        $pengampu = $this->db->query("SELECT
            `dosen_pengampu`.*,
            `dosen`.`nmdsn`,
            `matakuliah`.`nmmk`
        FROM
            `dosen_pengampu`
            LEFT JOIN `matakuliah` ON `matakuliah`.`idmatakuliah` =
            `dosen_pengampu`.`idmatakuliah`
            LEFT JOIN `tahun_akademik` ON `dosen_pengampu`.`idtahunakademik` =
            `tahun_akademik`.`idtahunakademik`
            LEFT JOIN `dosen` ON `dosen`.`iddosen` = `dosen_pengampu`.`iddosen`
        WHERE `tahun_akademik`.`status`='AKTIF'")->result();
        return ['prodi' => $prodi, 'dosen' => $dosen, 'pengampu' => $pengampu];
    }
    
    public function bymk($kmk)
    {
        $tahunakademik = $this->db->get_where('tahun_akademik', ['status' => 'AKTIF'])->result()[0];
        $pengampu = $this->db->query("SELECT
            `dosen_pengampu`.*,
            `dosen`.`nmdsn`
        FROM
            `dosen_pengampu`
            LEFT JOIN `tahun_akademik` ON `dosen_pengampu`.`idtahunakademik` =
            `tahun_akademik`.`idtahunakademik`
            LEFT JOIN `dosen` ON `dosen`.`iddosen` = `dosen_pengampu`.`iddosen`
        WHERE `tahun_akademik`.`status`='AKTIF' AND kmk='$kmk'")->result();
        return $pengampu;
    }

    public function insertpengampu($data = null)
    {
        $item = [
            'nidn' => $data['nidn'],
            'kdps' => $data['kdps'],
            'kmk' => $data['kmk'],
            'thakademik' => $data['thakademik'],
            'gg' => $data['gg'],
            'iddosen' => $data['iddosen'],
            'idmatakuliah' => $data['idmatakuliah'],
            'jenis' => $data['jenis'],
            'mengajar' => $data['mengajar'],
            'idtahunakademik' => $data['idtahunakademik'],
        ];
        $this->db->insert('dosen_pengampu', $item);
        $data['idpengampu'] = $this->db->insert_id();
        return $data;
    }
    public function updatepengampu($data = null)
    {
        $item = [
            'nidn' => $data['nidn'],
            'kdps' => $data['kdps'],
            'kmk' => $data['kmk'],
            'thakademik' => $data['thakademik'],
            'gg' => $data['gg'],
            'iddosen' => $data['iddosen'],
            'idmatakuliah' => $data['idmatakuliah'],
            'jenis' => $data['jenis'],
            'mengajar' => $data['mengajar'],
            'idtahunakademik' => $data['idtahunakademik'],
        ];
        $this->db->where('idpengampu', $data['idpengampu']);
        $this->db->update('dosen_pengampu', $item);
        return $data;
    }
}