<?php
class Penelitian_Model extends CI_Model
{
    public function select()
    {
        $message = [
            'riset'=> '',
            'kp' => ''
        ];
        $result = $this->db->query("
        SELECT
        `mahasiswa`.`nmmhs`,
        `riset`.*
        FROM
        `riset`
        INNER JOIN `mahasiswa` ON `mahasiswa`.`npm` = `riset`.`npm` order by tgseminar desc
        ");
        $message['riset']= $result->result_object();
        $result = $this->db->query("
        SELECT
        `mahasiswa`.`nmmhs`,
        `riset_kp`.*
        FROM
        `riset_kp`
        INNER JOIN `mahasiswa` ON `mahasiswa`.`npm` = `riset_kp`.`npm` order by tgseminar desc
        ");
        $message['kp']= $result->result_object();
        return $message;
    }
}