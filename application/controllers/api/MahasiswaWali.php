<?php defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Tokyo');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class MahasiswaWali extends \Restserver\Libraries\REST_Controller
{
    public function __construct($config = 'rest')
    {
        parent::__construct($config);
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        $this->load->model('MahasiswaWali_model', 'MahasiswaWaliModel');
    }
    public function GetMahasiswaWali_get()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if ($is_valid_token['status'] === true) {
            $Output = $this->MahasiswaWaliModel->Select($is_valid_token['data']->id);
            if (!empty($Output)) {
                $message = [
                    'status' => true,
                    'data' => $Output,
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            }
        }
    }
}