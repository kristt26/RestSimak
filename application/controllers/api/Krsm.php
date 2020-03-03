<?php defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Tokyo');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class Krsm extends \Restserver\Libraries\REST_Controller
{
    public function __construct($config = 'rest')
    {
        parent::__construct($config);
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        $this->load->model('Krsm_model', 'KrsmModel');
    }
    public function ambilTemkrsm_post()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        $Status =json_decode($this->security->xss_clean($this->input->raw_input_stream), true);
        if ($is_valid_token['status'] === true) {
            $Output = $this->KrsmModel->GetTem($is_valid_token['data'], $Status['status']);
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

    public function approvedKrsm_put()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        $_PUT =json_decode($this->security->xss_clean($this->input->raw_input_stream), true);
        if ($is_valid_token['status'] === true) {
            $Output = $this->KrsmModel->UpdateTemKrsm($_PUT);
            if (!empty($Output && $Output != false)) {
                $message = [
                    'status' => true,
                    'message' => "Berhasil melakukan Approved"
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            }
        }
    }

    public function InsertItem_post()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if ($is_valid_token['status'] === true) {
            $item = json_decode($this->security->xss_clean($this->input->raw_input_stream), true);
            
            $Output = $this->KrsmModel->InsertItemKrsm($item);
            if (!empty($Output && $Output != false)) {

            }
        }
    }

    public function HapusItem_delete()
    {
        $this->load->library('Authorization_Token');

        $is_valid_token = $this->authorization_token->validateToken();
        if ($is_valid_token['status'] === true) {
            $item = json_decode($this->security->xss_clean($this->input->raw_input_stream), true);
            
            $Output = $this->KrsmModel->DeleteItemKRSM($item['Id']);
            if (!empty($Output && $Output != false)) {
                $message = [
                    'status' => true,
                    'message' => "Pengajuan KRS Anda Berhasil",
                ];
            }
        }
    }

    public function pengajuanKRS_post()
    {
        $this->load->library('Authorization_Token');

        $is_valid_token = $this->authorization_token->validateToken();
        if ($is_valid_token['status'] === true) {
            $_POST = json_decode($this->security->xss_clean($this->input->raw_input_stream), true);
            
            $Output = $this->KrsmModel->Inser_Pengajuan((object) $this->input->post('krsm'), (object) $this->input->post('DetailKrsm'));
            if ($Output) {
                $message = [
                    'status' => true,
                    'message' => "Pengajuan KRS Anda Berhasil"
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            } else {
                $message = [
                    'status' => false,
                    'message' => "Pengajuan KRS Anda Gagal"
                ];
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    public function AmbilKrsm_get()
    {
        $data = $_GET;
        $Output = $this->KrsmModel->get($data);
        $message = [
            'status' => true,
            'data' => $Output
        ];
        $this->response($message, REST_Controller::HTTP_OK);
    }

    // /**
    //  *  Fetch All User Data
    //  * @method : GET
    //  */
    // public function fetch_all_users_get()
    // {
    //     header("Access-Control-Allow-Origin: *");
    //     $data = $this->User_model->fetch_all_users();
    //     $this->response($data);
    // }
}
