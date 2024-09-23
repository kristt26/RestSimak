<?php defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Tokyo');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class Users extends \Restserver\Libraries\REST_Controller
{
    public function __construct($config = 'rest')
    {
        parent::__construct($config);
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        $this->load->model('User_model', 'UserModel');
    }

    public function changepassword_post()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if ($is_valid_token['status'] === true) {
            $_POST = json_decode($this->security->xss_clean($this->input->raw_input_stream), true);
            $Output = $this->UserModel->ChangesPassword($_POST, $is_valid_token['data']->id);
            if (!empty($Output) && $Output != false) {
                $message = [
                    'status' => true,
                    'message' => "Changes Password Success",
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            } else {
                $message = [
                    'status' => false,
                    'message' => "Password Lama Anda Tidak Sesuai",
                ];
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $message = [
                'status' => false,
                'message' => "Anda tidak memiliki Akses",
            ];
            $this->response($message, REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function changeuser_post()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if ($is_valid_token['status'] === true) {
            $_POST = json_decode($this->security->xss_clean($this->input->raw_input_stream), true);
            $Output = $this->UserModel->ChangesUsername($_POST, $is_valid_token['data']->id);
            if (!empty($Output) && $Output != false) {
                $message = [
                    'status' => true,
                    'message' => "Changes Username Success",
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            } else {
                $message = [
                    'status' => false,
                    'message' => "Gagal, Hubungi Admin",
                ];
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $message = [
                'status' => false,
                'message' => "Anda tidak memiliki Akses",
            ];
            $this->response($message, REST_Controller::HTTP_NOT_FOUND);
        }
    }

    /**
     *  Add New User
     * @method : POST
     */
    public function register_post()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if ($is_valid_token['status'] === true) {
            $data = json_decode($this->security->xss_clean($this->input->raw_input_stream), true);
            $Output = $this->UserModel->insert_user($data);
            if ($Output['status']) {
                $message = [
                    'message' => "User Berhasil di Simpan",
                    'data' => $Output['id'],
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            } else {
                $message = [
                    'message' => "User Gagal di Simpan",
                    'data' => $Output['id'],
                ];
                $this->response($message, REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $message = [
                'message' => "User Berhasil di Simpan",
                'data' => $Output['id'],
            ];
            $this->response($message, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    public function login_post()
    {
        $_POST = json_decode($this->security->xss_clean($this->input->raw_input_stream), true);
        $this->form_validation->set_rules('Username', 'Username', 'trim|required');
        $this->form_validation->set_rules('Password', 'trim|required|max_length[100]');
        if ($this->form_validation->run() == false) {
            $message = array(
                'status' => false,
                'error' => $this->form_validation->error_array(),
                'message' => validation_errors(),
            );
            $this->response($message, REST_Controller::HTTP_NOT_FOUND);
        } else {
            $Output = $this->UserModel->user_login($this->input->post('Username'), $this->input->post('Password'));
            if (!empty($Output && $Output != false)) {
                $this->load->library('Authorization_Token');
                $token_data['id'] = $Output->Id;
                $token_data['Username'] = $Output->Username;
                $token_data['Email'] = $Output->Email;
                $token_data['NamaUser'] = $Output->NamaUser;
                $token_data['RoleUser'] = $Output->role->Role;
                $token_data['time'] = time();
                $UserToken = $this->authorization_token->generateToken($token_data);
                $return_data = [
                    'IdUser' => $Output->Id,
                    'Username' => $Output->Username,
                    'Email' => $Output->Email,
                    'NamaUser' => $Output->NamaUser,
                    'RoleUser' => $Output->role,
                    'DataRole' => $Output->dataRole,
                    'Token' => $UserToken,
                ];
                $message = [
                    'status' => true,
                    'data' => $return_data,
                    'message' => "Login Berhasil",
                ];
                $this->response($Output, REST_Controller::HTTP_OK);
            } else {
                $message = [
                    'status' => false,
                    'message' => "Username anda tidak ditemukan Periksa Username dan Password anda",
                ];
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    public function GetBiodata_get()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if ($is_valid_token['status'] === true) {
            $Output = $this->UserModel->GetBiodata($is_valid_token['data']);
            if (!empty($Output && $Output != false)) {
                $message = [
                    'status' => true,
                    'data' => $Output,
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            }
        }else{
            $this->response($message, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }
    public function AmbilUser_get()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if ($is_valid_token['status'] === true) {
            $Output = $this->UserModel->Select();
            if (!empty($Output && $Output != false)) {
                $message = [
                    'status' => true,
                    'data' => $Output,
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            }
        }
    }
    public function ResetPassword_put()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if ($is_valid_token['status'] === true) {
            $_POST = json_decode($this->security->xss_clean($this->input->raw_input_stream), true);
            $Output = $this->UserModel->Reset($_POST);
            if ($Output) {
                $message = [
                    'status' => true,
                    'message' => "anda berhasil melakukan reset password",
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            } else {
                $message = [
                    'status' => false,
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            }
        }
    }
    public function getRole_get()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if ($is_valid_token['status'] === true) {
            $Output = $this->UserModel->getRole();
            if ($Output) {
                $message = [
                    'status' => true,
                    'data' => $Output,
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            } else {
                $message = [
                    'status' => false,
                    'data' => [],
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            }
        }
    }
    public function changeUserInRole_post()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if ($is_valid_token['status'] === true) {
            $_POST = json_decode($this->security->xss_clean($this->input->raw_input_stream), true);
            $Output = $this->UserModel->ChangeUserRole($_POST);
            if ($Output) {
                $message = [
                    'status' => true,
                    'message' => "Proses Berhasil",
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            } else {
                $message = [
                    'status' => false,
                    'message' => "Proses Gagal",
                ];
                $this->response($message, REST_Controller::HTTP_BAD_REQUEST);
            }
        }
    }
    public function userUpdate_put()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if ($is_valid_token['status'] === true) {
            $_POST = json_decode($this->security->xss_clean($this->input->raw_input_stream), true);
            $Output = $this->UserModel->userUpdate($_POST);
            if ($Output) {
                $message = [
                    'status' => true,
                    'message' => "Proses Berhasil",
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            } else {
                $message = [
                    'status' => false,
                    'message' => "Proses Gagal",
                ];
                $this->response($message, REST_Controller::HTTP_BAD_REQUEST);
            }
        }
    }
}
