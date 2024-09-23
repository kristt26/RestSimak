<?php

class User_Model extends CI_Model
{
    protected $UserTable = 'user';
    protected $MahasiswaTable = 'mahasiswa';
    protected $UserinRoleTable = 'userinrole';
    protected $RoleTable = 'role';
    protected $PegawaiTable = 'pegawai';
    public function insert_user($UserData)
    {
        $username = $UserData['Username'];
        $result = $this->db->query(
            "SELECT * FROM user WHERE Username = '$username'"
        );
        if ($result->num_rows() > 0) {
            $message = [
                'status' => false,
                'id' => $result->row('Id'),
            ];
            return $message;
        } else {
            $UserData['Password'] = md5($UserData['Password']);
            $this->db->insert($this->UserTable, $UserData);
            $message = [
                'status' => true,
                'id' => $result->row('Id'),
            ];
            return $message;
        }
    }

    public function ChangesPassword($data, $Id)
    {
        $this->db->where('Id', $Id);
        $this->db->where('Password', md5($data['OldPassword']));
        $resultCek = $this->db->get('user');
        if ($resultCek->num_rows()) {
            $this->db->set('Password', md5($data['NewPassword']));
            $this->db->where('Id', $Id);
            $result = $this->db->update('user');
            return true;
        } else {
            return false;
        }
    }

    public function ChangesUsername($data, $Id)
    {
        $this->load->library('mylib');

        $this->db->set('Username', $data['Username']);
        $this->db->set('Email', $data['Email']);
        $this->db->where('Id', $Id);
        $result = $this->db->update('user');
        if ($result) {
            $item = [
                'Email' => $data['Username'],
                'chatid' => $data['chatid'],
            ];
            $this->mylib->updatedatasurat($item, $data['jenis'] == 'Pegawai' ? "pegawai/edit/" . $Id : "mahasiswa/edit/" . $Id);
            return true;
        } else {
            return false;
        }
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

    public function user_login($Username, $Password)
    {
        $Pass = md5($Password);
        $this->db->select("user.Id, user.Username, user.Password, user.Email, role.Nama as RoleName");
        $this->db->join('userinrole', 'userinrole.IdUser = user.Id', 'left');
        $this->db->join('role', 'role.Id = userinrole.RoleId', 'left');
        $this->db->where('Email', $Username);
        $this->db->or_where('Username', $Username);
        $this->db->where('Password', $Pass);
        $this->db->where('Status', true);
        $q = $this->db->get($this->UserTable);

        if ($q->num_rows()) {
            $a = $q->row();
            $Id = $q->row('Id');
            $this->db->select('*');
            $this->db->join('role', 'role.Id=userinrole.RoleId', 'left');
            $this->db->where('IdUser', $q->row('Id'));
            $roleinuser = $this->db->get($this->UserinRoleTable);
            $Tampung = $roleinuser->result_array();
            if ($roleinuser->num_rows()) {
                $this->db->where('Id', $roleinuser->row('RoleId'));
                $role = $this->db->get($this->RoleTable);
                $datarole = $role->row('Nama');
                if ($datarole == 'Mahasiswa') {
                    $this->db->where('IdUser', $q->row('Id'));
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
                } else if ($datarole == 'AdminPenggunaLain') {
                    $this->db->where('IdUser', $q->row('Id'));
                    $Biodata = $this->db->get("PenggunaLain");
                    if ($Biodata->num_rows()) {
                        $roleitem = array('Role' => array());
                        foreach ($Tampung as &$value) {
                            $item = array('Nama' => $value['Nama']);
                            array_push($roleitem['Role'], $item);
                        }
                        $Nama = "NamaUser";
                        $Role = "role";
                        $a->$Nama = $Biodata->row('Nama');
                        $a->$Role = (object) $roleitem;
                    }
                } else {
                    $this->db->where('IdUser', $q->row('Id'));
                    $Biodata = $this->db->get($this->PegawaiTable);
                    if ($Biodata->num_rows()) {
                        $roleitem = array('Role' => array());
                        foreach ($Tampung as &$value) {
                            $item = array('Nama' => $value['Nama']);
                            array_push($roleitem['Role'], $item);
                        }
                        $Nama = "NamaUser";
                        $Role = "role";
                        $a->$Nama = $Biodata->row('Nama');
                        $a->role = $datarole;
						$a->dataRole = $datarole;
                    }
                }
            }
            return $a;
        } else {
            return false;
        }
    }

    public function loginpenggunalain($Username, $Password)
    {
        $Pass = md5($Password);
        $this->db->select("user.Id, user.Username, user.Password, user.Email, role.Nama as RoleName");
        $this->db->join('userinrole', 'userinrole.IdUser = user.Id', 'left');
        $this->db->join('role', 'role.Id = userinrole.RoleId', 'left');
        $this->db->where('Email', $Username);
        $this->db->or_where('Username', $Username);
        $this->db->where('Password', $Pass);
        $this->db->where('Status', true);
        $q = $this->db->get($this->UserTable);
        $a = $q->row();
        $Id = $q->row('Id');
        $this->db->select('*');
        $this->db->join('role', 'role.Id=userinrole.RoleId', 'left');
        $this->db->where('IdUser', $q->row('Id'));
        $roleinuser = $this->db->get($this->UserinRoleTable);
        $Tampung = $roleinuser->result_array();
        if ($roleinuser->num_rows()) {
            $this->db->where('Id', $roleinuser->row('RoleId'));
            $role = $this->db->get($this->RoleTable);
            $datarole = $role->row('Nama');
        }if ($datarole == 'AdminPenggunaLain') {
            $this->db->where('IdUser', $q->row('Id'));
            $Biodata = $this->db->get("PenggunaLain");
            if ($Biodata->num_rows()) {
                $roleitem = array('Role' => array());
                foreach ($Tampung as &$value) {
                    $item = array('Nama' => $value['Nama']);
                    array_push($roleitem['Role'], $item);
                }
                $Nama = "NamaUser";
                $Role = "role";
                $a->$Nama = $Biodata->row('Nama');
                $a->$Role = (object) $roleitem;
            }
        }
        return $a;
    }
    public function GetBiodata($data)
    {
        $result = $this->db->query("
            SELECT
                `role`.`Nama`
            FROM
                `user`
            LEFT JOIN `userinrole` ON `user`.`Id` = `userinrole`.`IdUser`
            LEFT JOIN `role` ON `role`.`Id` = `userinrole`.`RoleId`
            WHERE
                `user`.`Id` = '$data->id'
        ");
        if ($result->num_rows() > 0) {
            $dataresult = $result->result_object();
            $a = $dataresult[0];
            if ($a->Nama == "Mahasiswa") {
                $resultMahasiswa = $this->db->query("
                    SELECT
                        *
                    FROM
                        `mahasiswa`
                    WHERE IdUser = '$data->id'
                ");
                return $resultMahasiswa->result_array();
            } else if ($a->Nama == "AdminPenggunaLain") {
                $resultMahasiswa = $this->db->query("
                    SELECT
                        *
                    FROM
                        `PenggunaLain`
                    WHERE IdUser = '$data->id'
                ");
                return $resultMahasiswa->result_array();
            } else {
                $resultMahasiswa = $this->db->query("
                    SELECT
                        *
                    FROM
                        `pegawai`
                    WHERE IdUser = '$data->id'
                ");
                return $resultMahasiswa->result_array();
            }
        } else {
            return false;
        }
    }
    public function Select()
    {
        $result = $this->db->query(
            "SELECT
            `pegawai`.*,
            `user`.`Status` AS `userStatus`,
            `user`.`Username`
          FROM
            `pegawai`
            LEFT JOIN `user` ON `user`.`Id` = `pegawai`.`IdUser`"
        );
        $dataPegawai = $result->result_object();
        foreach ($dataPegawai as $key => $value) {
            $resultUser = $this->db->query(
                "SELECT
                    `userinrole`.*,
                    `role`.*
              FROM
                `user`
                RIGHT JOIN `userinrole` ON `userinrole`.`IdUser` = `user`.`Id`
                LEFT JOIN `role` ON `role`.`Id` = `userinrole`.`RoleId`
              WHERE user.Id='$value->IdUser'"
            );
            $value->Role = $resultUser->result_object();
        }
        return $dataPegawai;
    }
    public function Reset($data)
    {
        $IdUser = $data['IdUser'];
        $pass = md5("stimik1011");
        $this->db->set("Password", $pass);
        $this->db->where("Id", $IdUser);
        $result = $this->db->update("user");
        return $result;
    }
    public function userUpdate($data)
    {
        $IdUser = $data['IdUser'];
        $Status;
        if ($data['Status'] == true) {
            $Status = "true";
        } else {
            $Status = "false";
        }
        $this->db->set("Status", $Status);
        $this->db->where("Id", $IdUser);
        $result = $this->db->update("user");
        return $result;
    }
    public function getRole()
    {
        $result = $this->db->get("role");
        return $result->result_array();
    }
    public function ChangeUserRole($data)
    {
        if ($data['status'] == true) {
            $this->db->where("IdUser", $data['IdUser']);
            $this->db->where("RoleId", $data['Id']);
            $num = $this->db->get("userinrole");
            if ($num->num_rows() == 0) {
                $item =
                    [
                    'RoleId' => $data['Id'],
                    'IdUser' => $data['IdUser'],
                ];
                $result = $this->db->insert("userinrole", $item);
                return $result;
            }
        } else {
            $this->db->where("IdUser", $data['IdUser']);
            $this->db->where("RoleId", $data['Id']);
            $result = $this->db->delete('userinrole');
            return $result;
        }
    }
}
