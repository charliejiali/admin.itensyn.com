<?php 

$public_page=true;
include("../function.php");
include("../include/MailPush.class.php");
$re=MailPush::push_mail();
print_r($re);

// include("mail_template.php");
// echo $body;
exit();

require_once('../include/PHPMailer/PHPMailerAutoload.php');
$mail = new PHPMailer;
// $mail->SMTPDebug = 3;                                // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->CharSet='UTF-8'; 
$mail->Host = 'mail.tensynchina.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'marketing@tensynchina.com';                 // SMTP username
$mail->Password = 'Tensyn2016';                            // SMTP password
// $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;  

$mail->setFrom('marketing@tensynchina.com','OWL猫头鹰');
// $mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
$mail->addAddress("charliejiali@hotmail.com");               // Name is optional
// $mail->addReplyTo('charliejiali@hotmail.com', '测试');
// $mail->addCC('cc@example.com');
// $mail->addBCC('bcc@example.com');

// $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
// $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = "test";
// $mail->AddEmbeddedImage('images/mail_logo.png', 1, 'attachment', 'base64', 'image/png');
$mail->Body    = $mail_body;
// $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
//$mail->send();
// if(!$mail->send()){
// 	return $mail->ErrorInfo;
// }else{
// 	return true;
// }
if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}