<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pegawai_model extends CI_Model {
    public function select($idpegawai=null)
    {
        if($idpegawai==null){
            return $this->db->get('pegawai')->result();
        }else
            return $this->db->get_where('pegawai', ['IdUser'=>$idpegawai])->row_array();
    }
}

/* End of file ModelName.php */
