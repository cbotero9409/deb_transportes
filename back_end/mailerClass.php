<?php

include_once '../config/dataBaseConection.php';
require_once '../views/mailForm.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class mailerClass {

    function sendMails($email, $asunt, $bodyMail, $photoImag,$idlot) {

        $message = view($idlot);
        $mail = new PHPMailer;

//Create an instance; passing `true` enables exceptions

        //$mail->SMTPDebug  = 2;
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'mail.altsys.com.co';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'debt@altsys.com.co';                 // SMTP username
        $mail->Password = 'DebTransportes321';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        $mail->setFrom('debt@altsys.com.co');
        
        
        $mail->addAddress($email);
        $mail->AddAddress('gtmoreno@sura.com.co');
        $mail->AddAddress('oocampo@sura.com.co');
        $mail->AddAddress('decheverrib@gmail.com');
        $i=0;
        foreach ($photoImag as $photo) {
            $mail->AddAttachment('../assets/img/'.$photo, $photo);
            $i++;
        }

        $mail->WordWrap = 100;                                 // Set word wrap to 50 characters
//content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $asunt;
        $mail->Body = $bodyMail . $message;

        if (!$mail->send()) {
            echo 'Mailer Error: ' . $mail->ErrorInfo;
            die();
            return 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            return true;
        }
    }

}
