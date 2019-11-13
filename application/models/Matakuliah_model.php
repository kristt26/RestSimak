<?php

class Matakuliah_Model extends CI_Model
{
    public function AmbilMatakuliah()
    {
        $result = $this->db->query("
            SELECT
                `program_studi`.`kdps`,
                `program_studi`.`nmps`,
                `program_studi`.`jenjang`,
                `matakuliah`.*
            FROM
                `program_studi`
                LEFT JOIN `matakuliah` ON `matakuliah`.`kdps` = `program_studi`.`kdps`
            WHERE 
                program_studi.status = 'true' and
                matakuliah.kurikulum in(2011,2018,2019)
        ");
        return $result->result_array();
    }
}
