<?php

class Approved_model extends CI_Model
{
    public function GetHistory($data, $Role)
    {
        if ($Role == "Keuangan") {
            $resultTahunAktif = $this->db->query("SELECT * FROM tahun_akademik WHERE status='AKTIF'");

            if ($resultTahunAktif->num_rows()) {
                $DataTahunAktif = $resultTahunAktif->row();
                $resultTem = $this->db->query("
                SELECT
                    `tem_krsm`.*,
                    `mahasiswa`.`kdps`,
                    `mahasiswa`.`jenjang`,
                    `mahasiswa`.`nmmhs`,
                    `mahasiswa`.`kelas`
                FROM
                    `tem_krsm`
                    LEFT JOIN `mahasiswa` ON `mahasiswa`.`npm` = `tem_krsm`.`npm`
                    WHERE thakademik = '$DataTahunAktif->thakademik' AND gg = '$DataTahunAktif->gg'"
                );
                $resultKrsm = $this->db->query("
                    SELECT
                    `krsm`.*,
                    `mahasiswa`.`kdps`,
                    `mahasiswa`.`jenjang`,
                    `mahasiswa`.`nmmhs`,
                    `mahasiswa`.`kelas`
                FROM
                    `krsm`
                    LEFT JOIN `mahasiswa` ON `mahasiswa`.`npm` = `krsm`.`npm`
                    WHERE thakademik = '$DataTahunAktif->thakademik' AND gg = '$DataTahunAktif->gg'"
                );
                $Message = [
                    'Data' => array(),
                ];

                if ($resultTem->num_rows()) {
                    foreach ($resultTem->result_array() as $key => $value) {
                        if ($value['status'] == 'Keuangan') {
                            $Datas = [
                                'thakademik' => $value['thakademik'],
                                'gg' => $value['gg'],
                                'npm' => $value['npm'],
                                'sms' => $value['sms'],
                                'dsn_wali' => $value['dsn_wali'],
                                'ketjur' => $value['ketjur'],
                                'admakademik' => $value['admakademik'],
                                'jmsks' => $value['jmsks'],
                            ];
                            array_push($Message['Data'], $Datas);
                        }
                    }
                }
                if ($resultKrsm->num_rows()) {
                    foreach ($resultKrsm->result_array() as $key => $value) {
                        $Datas = [
                            'thakademik' => $value['thakademik'],
                            'gg' => $value['gg'],
                            'npm' => $value['npm'],
                            'sms' => $value['sms'],
                            'dsn_wali' => $value['dsn_wali'],
                            'ketjur' => $value['ketjur'],
                            'admakademik' => $value['admakademik'],
                            'jmsks' => $value['jmsks'],
                        ];
                        array_push($Message['Data'], $Datas);
                    }
                }
                return $Message['Data'];
            }
        }
    }
}
