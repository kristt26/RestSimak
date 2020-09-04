<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pegawai_model extends CI_Model
{
    public function select($idpegawai = null)
    {
        if ($idpegawai == null) {
            return $this->db->query("SELECT
                `pegawai`.*,
                `user`.`Email`
            FROM
                `pegawai`
                LEFT JOIN `user` ON `pegawai`.`IdUser` = `user`.`Id`")->result();
        } else {
            return $this->db->query("SELECT
                `pegawai`.*,
                `user`.`Email`
            FROM
                `pegawai`
                LEFT JOIN `user` ON `pegawai`.`IdUser` = `user`.`Id`
            WHERE pegawai.idpegawai='$idpegawai'")->result();
        }

    }
}

/* End of file ModelName.php */
