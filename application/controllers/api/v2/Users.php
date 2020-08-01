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
        header("Access-Control-Allow-Methods: POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        $this->load->model('User_model', 'UserModel');
    }

    public function Login_post()
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
            $Output = $this->UserModel->loginpenggunalain($this->input->post('Username'), $this->input->post('Password'));
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
                    'Token' => $UserToken
                ];
                $message = [
                    'status' => true,
                    'data' => $return_data,
                    'message' => "Login Berhasil",
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            } else {
                $message = [
                    'status' => false,
                    'message' => "Username anda tidak ditemukan Periksa Username dan Password anda",
                ];
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }
}
