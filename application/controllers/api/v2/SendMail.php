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
		$mail = new PHPMailer();

		//Tell PHPMailer to use SMTP
		$mail->isSMTP();

		//Enable SMTP debugging
		//SMTP::DEBUG_OFF = off (for production use)
		//SMTP::DEBUG_CLIENT = client messages
		//SMTP::DEBUG_SERVER = client and server messages
		$mail->SMTPDebug = SMTP::DEBUG_SERVER;

		//Set the hostname of the mail server
		$mail->Host = 'smtp.gmail.com';
		//Use `$mail->Host = gethostbyname('smtp.gmail.com');`
		//if your network does not support SMTP over IPv6,
		//though this may cause issues with TLS

		//Set the SMTP port number:
		// - 465 for SMTP with implicit TLS, a.k.a. RFC8314 SMTPS or
		// - 587 for SMTP+STARTTLS
		$mail->Port = 465;

		//Set the encryption mechanism to use:
		// - SMTPS (implicit TLS on port 465) or
		// - STARTTLS (explicit TLS on port 587)
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

		//Whether to use SMTP authentication
		$mail->SMTPAuth = true;

		//Username to use for SMTP authentication - use full email address for gmail
		$mail->Username = 'emailfortesting1011@gmail.com';

		//Password to use for SMTP authentication
		$mail->Password = '26031988@Aj';

		//Set who the message is to be sent from
		//Note that with gmail you can only use your account address (same as `Username`)
		//or predefined aliases that you have configured within your account.
		//Do not use user-submitted addresses in here
		$mail->setFrom('emailfortesting1011@gmail.com', 'First Last');

		//Set an alternative reply-to address
		//This is a good place to put user-submitted addresses
		// $mail->addReplyTo('replyto@example.com', 'First Last');

		//Set who the message is to be sent to
		$mail->addAddress('kristt26@gmail.com', 'Ajenkris Yanto Kungkung');

		//Set the subject line
		$mail->Subject = 'PHPMailer GMail SMTP test';

		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		$mail->msgHTML('Testing');

		//Replace the plain text body with one created manually
		$mail->AltBody = 'This is a plain-text message body';

		//Attach an image file
		$mail->addAttachment('images/phpmailer_mini.png');

		//send the message, check for errors
		if (!$mail->send()) {
			echo 'Mailer Error: ' . $mail->ErrorInfo;
		} else {
			echo 'Message sent!';
			//Section 2: IMAP
			//Uncomment these to save your message in the 'Sent Mail' folder.
			#if (save_mail($mail)) {
			#    echo "Message saved!";
			#}
		}
		// $mail = new PHPMailer(true);
		// try {
		// 	$mail->SMTPDebug = SMTP::DEBUG_SERVER; //Enable verbose debug output
		// 	$mail->isSMTP(); //Send using SMTP
		// 	$mail->Host = 'smtp.gmail.com'; //Set the SMTP server to send through
		// 	$mail->SMTPAuth = true; //Enable SMTP authentication
		// 	$mail->Username = 'emailfortesting1011@gmail.com'; //SMTP username
		// 	$mail->Password = '26031988@Aj'; //SMTP password
		// 	$mail->SMTPSecure = 'ssl'; //Enable implicit TLS encryption
		// 	$mail->Port = 465; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
		// 	$mail->setFrom('emailfortesting1011@gmail.com', 'Testing');
		// 	$mail->addAddress('kristt26@gmail.com', "Ajenkris"); //Add a recipient
		// 	$mail->isHTML(true); //Set email format to HTML
		// 	$mail->Subject = 'Undangan';
		// 	// $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
		// 	// $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
		// 	$mail->msgHTML("Testing");
		// 	$result = $mail->send();
		// 	// return true;
		// 	$this->response($result, REST_Controller::HTTP_OK);
		// } catch (Exception $e) {
		// 	$mail->ErrorInfo;
		// 	$this->response($mail->ErrorInfo, REST_Controller::HTTP_BAD_REQUEST);
		// }
	}
}
