<?php defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Tokyo');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class Prodi extends \Restserver\Libraries\REST_Controller
{
	public function __construct($config = 'rest')
	{
		parent::__construct($config);
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT, OPTIONS");
		header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
		$this->load->model('Prodi_model', 'ProdiModel');
	}

	public function read_get()
	{
		$this->load->library('Authorization_Token');
		$is_valid_token = $this->authorization_token->validateToken();
		if ($is_valid_token['status'] === true) {
			$result = $this->ProdiModel->get();
			$message = [
				'status' => true,
				'data' => $result,
				'message' => "Success",
			];
			$this->response($message, REST_Controller::HTTP_OK);
		}
	}

	public function readbydosen_get($kdps, $iddosen)
	{
		$this->load->library('Authorization_Token');
		$is_valid_token = $this->authorization_token->validateToken();
		if ($is_valid_token['status'] === true) {
			$result = $this->DosenWaliModel->read($kdps, $iddosen);
			$message = [
				'status' => true,
				'data' => $result,
				'message' => "Success",
			];
			$this->response($message, REST_Controller::HTTP_OK);
		}
	}
}
