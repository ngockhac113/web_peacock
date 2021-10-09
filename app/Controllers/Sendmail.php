<?php 
namespace App\Controllers;
require_once('mail/PHPMailerAutoload.php');
use PHPMailer;

class Sendmail extends BaseController
{


    public function index()
    {
      echo view('contact');

    }
     public function do_send()
    {
        $ten = $this->request->getVar('name');
        $sdt = $this->request->getVar('phone');
        $nguoinhan = $this->request->getVar('email');
        $noidung = $this->request->getVar('message');
        $mail = new PHPMailer;

    //$mail->SMTPDebug = 3;                               // Enable verbose debug output

    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'trinhthiyen12120@gmail.com';                 // SMTP username
    $mail->Password = 'xdkdtryuzuyhinkw';                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to

    $mail->setFrom('trinhdinhtieu@gmail.com', 'Trịnh Thị Yến làm gửi mail');
    $mail->addAddress($nguoinhan);     // Add a recipient
    // $mail->addAddress('ellen@example.com');               // Name is optional
    // $mail->addReplyTo('info@example.com', 'Information');
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
    // $mail->isHTML(true);                                  // Set email format to HTML

    $mail->Subject = $ten;
    $mail->Body    = $noidung;
    // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    if(!$mail->send()) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        echo 'thư đã được gửi rồi';
    }
    }
}