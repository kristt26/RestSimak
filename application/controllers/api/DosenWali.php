<?php defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Tokyo');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class DosenWali extends \Restserver\Libraries\REST_Controller
{
	public function __construct($config = 'rest')
	{
		parent::__construct($config);
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT, OPTIONS");
		header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
		$this->load->model('Dosenwali_model', 'DosenWaliModel');
	}

	public function insert_get()
	{
		$this->load->library('Authorization_Token');
		$is_valid_token = $this->authorization_token->validateToken();
		if ($is_valid_token['status'] === true) {
			$_POST = json_decode($this->security->xss_clean($this->input->raw_input_stream), true);
			// $result = $this->DosenWaliModel->get();
			$message = [
				'status' => true,
				'data' => $_POST,
				'message' => "Success",
			];
			$this->response($message, REST_Controller::HTTP_OK);
		}
	}
}
