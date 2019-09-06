<?php defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Tokyo');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class Perwalian extends \Restserver\Libraries\REST_Controller
{
    public function __construct($config = 'rest')
    {
        parent::__construct($config);
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        $this->load->model('Perwalian_model', 'PerwalianModel');
    }
    public function MahasiswaWali_get()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        $Status =json_decode($this->security->xss_clean($this->input->raw_input_stream), true);
        if ($is_valid_token['status'] === true) {
            $Output = $this->PerwalianModel->GetMahasiswa($is_valid_token['data']);
            if ($Output > 0 && !empty($Output)) {
                $message = [
                    'status' => true,
                    'data' => json_encode($Output),
                    'message' => "Success!",
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            } else {
                $message = [
                    'status' => false,
                    'data'=> json_encode(array()),
                    'message' => "Data Kosong",
                ];
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }
}