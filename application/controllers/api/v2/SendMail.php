<?php defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Tokyo');

use Restserver\Libraries\REST_Controller;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require APPPATH . '/libraries/REST_Controller.php';

class SendMail extends \Restserver\Libraries\REST_Controller
{

	public function __construct($config = 'rest')
	{
		parent::__construct($config);
		header("Access-Control-Allow-Methods:  GET, POST, OPTIONS, PUT, DELETE");
		header("Access-Control-Allow-Origin: *");
		// header("Content-Type: application/json; charset=UTF-8");
		header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	}

	public function send_get()
	{
		$mail = new PHPMailer(true);
		try {
			$mail->SMTPDebug = SMTP::DEBUG_SERVER;
			$mail->isSMTP();
			$mail->Host = 'smtp-relay.gmail.com';
			$mail->SMTPAuth = true;
			$mail->Username = 'kristt26@stimiksepnop.ac.id';
			$mail->Password = '26031988@Aj';
			$mail->SMTPSecure = 'SSL';
			$mail->Port = 456; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
			$mail->setFrom('kristt26@stimiksepnop.ac.id', 'Testing');
			$mail->addAddress('kristt26@gmail.com', "Ajenkris");
			$mail->isHTML(true);
			$mail->Subject = 'Undangan';
			$mail->msgHTML("Testing");
			$result = $mail->send();
			$this->response($result, REST_Controller::HTTP_OK);
		} catch (Exception $e) {
			$mail->ErrorInfo;
			$this->response($mail->ErrorInfo, REST_Controller::HTTP_BAD_REQUEST);
		}
	}
}
