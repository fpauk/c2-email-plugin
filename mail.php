<?php
require 'phpmailer/PHPMailerAutoload.php';

header('Access-Control-Allow-Origin: *');

$json = file_get_contents('php://input');
$obj = json_decode($json);

$mail = new PHPMailer;

$mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = $obj->smtp_server;  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = $obj->from_mail;                 // SMTP username
$mail->Password = $obj->password;                           // SMTP password
$mail->SMTPSecure = $obj->security;                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = $obj->port;                                    // TCP port to connect to

$mail->setFrom($obj->from_mail, $obj->name_from);
$mail->addAddress($obj->recipient, $obj->recipient);     // Add a recipient
//$mail->addAddress('ellen@example.com');               // Name is optional
//$mail->addReplyTo('info@example.com', 'Information');
//$mail->addCC('cc@example.com');
//$mail->addBCC('bcc@example.com');

$contact_image_data=$obj->file;
$data = substr($contact_image_data, strpos($contact_image_data, ","));
$filename=$obj->attachment_name;
$encoding = "base64";
$type = "image/png";
$mail->AddStringAttachment(base64_decode($data), $filename, $encoding, $type);

$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = $obj->subject;
$mail->Body    = $obj->body;
$mail->AltBody = $obj->body;

var_dump($obj);

if (!$mail->send()) {
	if ($obj->debug == true)
	{
		echo 'Message could not be sent.';
		echo 'Error: ' . $mail->ErrorInfo;
	}
} else {
    echo 'OK';
}
