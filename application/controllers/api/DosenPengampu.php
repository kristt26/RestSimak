<?php defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Tokyo');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class DosenPengampu extends \Restserver\Libraries\REST_Controller

{
    public function __construct($config = 'rest')
    {
        parent::__construct($config);
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        $this->load->model('Dosen_model', 'DosenModel');
    }

    public function GetData_get()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if ($is_valid_token['status'] === true) {
            $result = $this->DosenModel->select();
            $this->response($result, REST_Controller::HTTP_OK);
        }
    }
    
    public function bymk_get()
    {
        
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if ($is_valid_token['status'] === true) {
            $kmk = $this->uri->segment(0);
            $th = $this->uri->segment(4);
            $gg = $this->uri->segment(5);
            $this->response($kmk, REST_Controller::HTTP_OK);
            // $result = $this->DosenModel->select();
        }
    }
    
    public function Tambah_post()
    {
        $data = json_decode($this->security->xss_clean($this->input->raw_input_stream), true);
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if ($is_valid_token['status'] === true) {
            $Output = $this->DosenModel->insertpengampu($data);
            $this->response($Output, REST_Controller::HTTP_OK);
        }
    }
    public function Ubah_put()
    {
        $data = json_decode($this->security->xss_clean($this->input->raw_input_stream), true);
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if ($is_valid_token['status'] === true) {
            $Output = $this->DosenModel->updatepengampu($data);
            $this->response($Output, REST_Controller::HTTP_OK);
        }
    }
}