<?php

class Profile_Model extends CI_Model
{
    public function getProfilee($data, $role)
    {
        if ($role == "Mahasiswa") {
            $this->db->where('IdUser', $data->id);
            $result = $this->db->get('mahasiswa');
            return $result->result_array();
        } else {
            $this->db->where('IdUser', $data->id);
            $result = $this->db->get('pegawai');
            return $result->result_array();
        }
    }
    public function UpdateProfile($data)
    {
        if ($data['Role'] == "Pegawai") {
            $this->db->set('Nama', $data['Nama']);
            $this->db->set('Alamat', $data['Alamat']);
            $this->db->set('Kontak', $data['Kontak']);
            $this->db->set('TempatLahir', $data['TempatLahir']);
            $this->db->set('TanggalLahir', $data['TanggalLahir']);
            $this->db->set('NoSK', $data['NoSK']);
            $this->db->set('NIK', $data['NIK']);
            $this->db->set('Nama', $data['Nama']);
            $this->db->where('idpegawai', $data['idpegawai']);
            $result = $this->db->update('pegawai');
            return $result;
        }else{
            $this->db->set('nmmhs', $data['nmmhs']);
            $this->db->set('tmlhr', $data['tmlhr']);
            $this->db->set('tglhr', $data['tglhr']);
            $this->db->set('agama', $data['agama']);
            $this->db->set('kewarga', $data['kewarga']);
            $this->db->set('pendidikan', $data['pendidikan']);
            $this->db->set('nmsmu', $data['nmsmu']);
            $this->db->set('jursmu', $data['jursmu']);
            $this->db->set('kotasmu', $data['kotasmu']);
            $this->db->set('provsmu', $data['provsmu']);
            $this->db->set('pekerjaan', $data['pekerjaan']);
            $this->db->set('almt', $data['almt']);
            $this->db->set('notlp', $data['notlp']);
            $this->db->set('status', $data['status']);
            $this->db->set('jmsaudara', $data['jmsaudara']);
            $this->db->set('nmayah', $data['nmayah']);
            $this->db->set('nmibu', $data['nmibu']);
            $this->db->set('sumbiaya', $data['sumbiaya']);
            $this->db->where('npm', $data['npm']);
            $result = $this->db->update('mahasiswa');
            return $result;
        }
    }

}
