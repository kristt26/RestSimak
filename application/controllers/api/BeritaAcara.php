<?php defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Tokyo');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class BeritaAcara extends \Restserver\Libraries\REST_Controller
{
    public function __construct($config = 'rest')
    {
        parent::__construct($config);
        
        $this->load->model('BeritaAcara_model', 'BeritaAcaraModel');
    }

    public function AddBaMengajar_post()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        header("Access-Control-Allow-Methods: POST");
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        $data =json_decode($this->security->xss_clean($this->input->raw_input_stream), true);
        if ($is_valid_token['status'] === true) {
            $Output = $this->BeritaAcaraModel->insert(json_decode($data));
            if($data){
                $message= [
                    "status" => $Output
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            }else{
                $message= [
                    "status" => $Output
                ];
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        }

    }
    
}