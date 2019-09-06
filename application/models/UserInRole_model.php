<?php

class User_Model extends CI_Model
{
    protected $UserTable = 'user';
    protected $MahasiswaTable = 'mahasiswa';
    protected $UserinRoleTable = 'userinrole';
    protected $RoleTable = 'role';
    protected $PegawaiTable = 'pegawai';
    public function insert_user(array $UserData)
    {
        $this->db->insert($this->UserTable, $UserData);
        return $this->db->insert_id();
    }

    public function fetch_all_users()
    {
        $query = $this->db->get('user');
        foreach ($query->result() as $row) {
            $user_data[] = [
                'Username' => $row->Username,
                'Email' => $row->Email,
                'Insert' => $row->Insert,
                'Update' => $row->Update,
            ];
        }
        return $user_data;
    }

    public function GetUserRole($IdUser)
    {
        $this->db->where('Email', $Username);
        $this->db->or_where('Username', $Username);
        $q = $this->db->get($this->UserTable);

        $a = $q->row();
        $this->db->where('IdUser', $q->row('IdUser'));
        $roleinuser = $this->db->get($this->UserinRoleTable);
        if ($roleinuser->num_rows() > 0) {
            $this->db->where('Id', $roleinuser->row('role_Id'));
            $role = $this->db->get($this->RoleTable);
            if ($role->row('Nama') == 'Mahasiswa') {
                $IdUser = $q->row('IdUser');
                $this->db->where('IdUser', $q->row('IdUser'));
                $Biodata = $this->db->get($this->MahasiswaTable);
                if ($Biodata->num_rows()) {
                    $roleitem = array('Role' => array());
                    $item = array('Nama' => $role->row('Nama'));
                    array_push($roleitem['Role'], $item);
                    $Nama = "NamaUser";
                    $Role = "role";
                    $a->$Nama = $Biodata->row('nmmhs');
                    $a->$Role = (object) $roleitem;
                }
            } else {
                $IdUser = $q->row('IdUser');
                $this->db->where('IdUser', $q->row('IdUser'));
                $Biodata = $this->db->get($this->PegawaiTable);
                if ($Biodata->num_rows()) {
                    $roleitem = array('Role' => array());
                    foreach ($roleinuser->row() as &$value) {
                        $item = array('Nama' => $value->Nama);
                        array_push($roleitem['Role'], $item);
                    }
                    $Nama = "NamaUser";
                    $Role = "role";
                    $a->$Nama = $Biodata->row('nmmhs');
                    $a->$Role = (object) $roleitem;
                }

            }
        }

        return $a;
    }
}
