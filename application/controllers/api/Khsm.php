<?php defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Tokyo');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class Khsm extends \Restserver\Libraries\REST_Controller

{
    public function __construct($config = 'rest')
    {
        parent::__construct($config);
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        $this->load->model('Khsm_model', 'KhsmModel');
    }

    public function GetKhsm_get()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        $npm = $this->get('npm');
        if ($is_valid_token['status'] === true) {
            $Output = $this->KhsmModel->AmbilKhs($npm);
            if (!empty($Output && $Output != false)) {
                $message = [
                    'status' => true,
                    'data' => (object) $Output,
                    'message' => "Success",
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            }
        }
    }

    public function AmbilIPK_get()
    {
        $npm = $this->get('npm');
        $Output = $this->KhsmModel->AmbilIPS($npm);
        if (!empty($Output && $Output != false)) {
            $Datas = array(
                'Data' => []
            );
            for ($i = 1; $i <= 8; $i++) {
                $item = [
                    'Semester' => '',
                    'IPS' => '',
                    'SKS' => '',
                    'NSKS' => '',
                    'ListMatakuliah' => array(),
                ];
                $nilai = 0;
                $sks = 0;
                foreach ($Output['Datas'] as $key => $value) {
                    if ($value['smt'] == $i) {
                        $nilai += (int) $value['nxsks'];
                        $sks += (int) $value['sks'];
                        array_push($item['ListMatakuliah'], $value);
                    }
                }
                $IPS = $nilai / $sks;
                $item['Semester'] = $i;
                $item['IPS'] = $IPS;
                $item['SKS'] = $sks;
                $item['NSKS'] = $nilai;
                array_push($Datas['Data'], $item);
            }
            $message = [
                'status' => true,
                'data' => $Datas,
                'message' => "Success",
            ];
            $this->response($message, REST_Controller::HTTP_OK);
        }
    }
    public function CreateKHS_post()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if ($is_valid_token['status'] === true) {
            $item = json_decode($this->security->xss_clean($this->input->raw_input_stream), true);
            $Output = $this->KhsmModel->insert($item);
            if ($Output) {
                $message = [
                    'status' => true,
                    'data' => (object) $Output,
                    'message' => "Success",
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            } else {
                $message = [
                    'status' => false,
                    'message' => "Gagal Simpan",
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            }
        } else {
            $message = [
                'status' => false,
                'message' => "Session Anda Habis",
            ];
            $this->response($message, Rest_Controller::HTTP_OK);
        }
    }
    public function GetlistKHS_get()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if ($is_valid_token['status'] === true) {
            $ta = $this->db->query("SELECT * FROM tahun_akademik WHERE status = 'AKTIF'")->result();
            if (!is_null($ta->tgl_nilai)) {
                if (strtotime(date("Y/m/d") . " 23:59:59") <= strtotime(str_replace("-","/",$ta->tgl_nilai))) {
                    $Output = $this->KhsmModel->getNilai($is_valid_token['data']);
                    if (!empty($Output)) {
                        $message = [
                            'status' => true,
                            'data' => (object) $Output,
                            'message' => "Success",
                        ];
                        $this->response($message, REST_Controller::HTTP_OK);
                    } else {
                        $message = [
                            'status' => false,
                            'message' => "Kosong",
                        ];
                        $this->response($message, REST_Controller::HTTP_OK);
                    }
                } else {
                    $message = [
                        'status' => true,
                        'message' => "Batas pengisian nilai telah ditutup",
                    ];
                    $this->response($message, REST_Controller::HTTP_BAD_REQUEST);
                }
            } else {
                $Output = $this->KhsmModel->getNilai($is_valid_token['data']);
                if (!empty($Output)) {
                    $message = [
                        'status' => true,
                        'data' => (object) $Output,
                        'message' => "Success",
                    ];
                    $this->response($message, REST_Controller::HTTP_OK);
                } else {
                    $message = [
                        'status' => false,
                        'message' => "Kosong",
                    ];
                    $this->response($message, REST_Controller::HTTP_OK);
                }
            }
        } else {
            $message = [
                'status' => false,
                'message' => "Session Anda Habis",
            ];
            $this->response($message, Rest_Controller::HTTP_OK);
        }
    }

    public function GetProgress_get()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if ($is_valid_token['status'] === true) {
            $Output = $this->KhsmModel->getProgress($is_valid_token['data']);
            $this->response($Output, REST_Controller::HTTP_OK);
        } else {
            $message = [
                'status' => false,
                'message' => "Session Anda Habis",
            ];
            $this->response($message, Rest_Controller::HTTP_OK);
        }
    }

    public function GetAllListKHS_get()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if ($is_valid_token['status'] === true) {
            $Output = $this->KhsmModel->getAllNilai($is_valid_token['data']);
            if (!empty($Output)) {
                $message = [
                    'status' => true,
                    'data' => (object) $Output,
                    'message' => "Success",
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            } else {
                $message = [
                    'status' => false,
                    'message' => "Kosong",
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            }
        } else {
            $message = [
                'status' => false,
                'message' => "Session Anda Habis",
            ];
            $this->response($message, Rest_Controller::HTTP_OK);
        }
    }

    public function PutDetaiKHS_put()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if ($is_valid_token['status'] === true) {
            $item = json_decode($this->security->xss_clean($this->input->raw_input_stream), true);
            $Output = $this->KhsmModel->putNilai($item);
            if ($Output) {
                $message = [
                    'status' => true,
                    'message' => "Success",
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            } else {
                $message = [
                    'status' => false,
                    'message' => "Gagal",
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            }
        } else {
            $message = [
                'status' => false,
                'message' => "Session Anda Habis",
            ];
            $this->response($message, Rest_Controller::HTTP_OK);
        }
    }
}
