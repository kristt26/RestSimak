<?php defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Tokyo');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class Home extends \Restserver\Libraries\REST_Controller
{
    public function __construct($config = 'rest')
    {
        parent::__construct($config);
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        $this->load->model('Home_model', 'HomeModel');
    }
    public function ambilinfo_get()
    {
        $role = $this->get('role');
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if ($is_valid_token['status'] === true) {
            $Output = $this->HomeModel->GetBiodata($is_valid_token['data'], $role);
            if ($Output != 0) {
                $message = [
                    'status' => true,
                    'message' => 'Success!!!',
                    'data' => $Output,
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            } else {
                $message = [
                    'status' => false,
                    'message' => 'empty!!!',
                    'data' => [],
                ];
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }

        }
    }

}
