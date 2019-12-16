<?php defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Tokyo');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class PenilaianDosen extends \Restserver\Libraries\REST_Controller
{
    public function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->model('PenilaianDosen_model', 'PenilaianDosenModel');
    }
    public function CekPenilaianDosen_get()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        $npm = $_GET;
        $Output = $this->PenilaianDosenModel->Cek($npm);
        $this->response($Output, REST_Controller::HTTP_OK);
    }
    public function GetPenilaiEvaluasi_get()
    {
        $id = $_GET;
        $Output = $this->PenilaianDosenModel->get($id);
        if(!empty($Output)){
            $message = [
                'status' => $Output
            ];
            $this->response($message, REST_Controller::HTTP_OK);
        }else{
            $message = [
                'status' => $Output
            ];
            $this->response($message, REST_Controller::HTTP_OK);
        }
    }

    public function InsertPenilaiEvaluasi_post()
    {
        $data = json_decode($this->input->raw_input_stream);
        $Output = $this->PenilaianDosenModel->insert($data);
        if($Output){
            $message = [
                'status' => true
            ];
            $this->response($message, REST_Controller::HTTP_OK);
        }else{
            $message = [
                'status' => false
            ];
            $this->response($message, REST_Controller::HTTP_OK);
        }
    }

    public function UpdatePenilaiEvaluasi_put()
    {
        $data = json_decode($this->input->raw_input_stream);
        $result = $this->PenilaianDosenModel->update($data);
        if ($result){
            $message = [
                'status' => true
            ];
            $this->response($message, REST_Controller::HTTP_OK);
        }else{
            $message = [
                'status' => false
            ];
            $this->response($message, REST_Controller::HTTP_OK);
        }
    }

        

    public function DeletePenilaiEvaluasi()
    {
        $id = $_GET;
        $result = $this->PenilaianDosenModel->delete($id);
        if ($result){
            $message = [
                'status' => true
            ];
            $this->response($message, REST_Controller::HTTP_OK);
        }else{
            $message = [
                'status' => true
            ];
            $this->response($message, REST_Controller::HTTP_OK);
        }
    }
}