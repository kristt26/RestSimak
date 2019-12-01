<?php defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Tokyo');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class Matakuliah extends \Restserver\Libraries\REST_Controller
{
    public function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->model('Matakuliah_model', 'MatakuliahModel');
    }
    public function GetMatakuliah_get()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        $Output = $this->MatakuliahModel->AmbilMatakuliah();
        $message = [
            'status' => true,
            'data' => $Output
        ];
        $this->response($message, REST_Controller::HTTP_OK);
    }
    public function GetKrsm_get()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if ($is_valid_token['status'] === true) {
            $Output = $this->MatakuliahModel->ambilkrsm($is_valid_token['data']);
            $message = [
                'status' => true,
                'data' => $Output
            ];
            $this->response($message, REST_Controller::HTTP_OK);
        }else{
            $message = [
                'status' => true,
                'data' => [],
                'message' => "Session Anda telah kadaluarsa"
            ];
            $this->response($message, REST_Controller::HTTP_OK);
        }
        
    }
}