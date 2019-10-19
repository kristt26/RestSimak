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
            $Output = $this->BeritaAcaraModel->insert($data);
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
        }else{
            $message= [
                "result" => "Session anda telah habis"
            ];
            $this->response($message, REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function GetBaMengajar_get()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        header("Access-Control-Allow-Methods: GET");
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        $data =json_decode($this->security->xss_clean($this->input->raw_input_stream), true);
        if ($is_valid_token['status'] === true) {
            $idjadwal = $this->get('idjadwal');
            $nidn = $this->get('nidn');
            $Output = $this->BeritaAcaraModel->get($idjadwal, $nidn);
            if(!empty($Output)){
                $message= [
                    "status" => true,
                    "result" => $Output
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            }else{
                $message= [
                    "status" => false
                ];
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        }else{
            $message= [
                "result" => "Session anda telah habis"
            ];
            $this->response($message, REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function updateBaMengajar_put()
    {
        header("Access-Control-Allow-Methods: PUT");
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        // $data =json_decode($this->security->xss_clean($this->input->raw_input_stream), true);
        if ($is_valid_token['status'] === true) {
            $decoded_input = json_decode(file_get_contents("php://input"), true);
            $Output = $this->BeritaAcaraModel->update($decoded_input);
            if($Output){
                $message = [
                    "result" => $Output
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            }else{
                $message = [
                    "result" => $Output
                ];
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
            
        }else{
            $message = [
                "result" => "Session Habis"
            ];
            $this->response($message, REST_Controller::HTTP_NOT_FOUND);
        }
    }
    
}