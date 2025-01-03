<?php // emailSender.php
// create the PHPMailer class
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
// require the autoload file of composer
require_once "../helperFiles/vendor/autoload.php";

// create a function to send mails
function sendMail($email_address, $email_subject, $email_message, $success_message, $error_message) {
    // create the PHPMailer object
    $mail = new PHPMailer();

    // setup the smtp
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPDebug = 0;
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->Username = 'orjiphils@gmail.com';
    $mail->Password = "tdkw axuq nfzt wqff";
    $mail->SMTPSecure = 'tls';

    // setup the mail headers
    $mail->setFrom('orjiphils@gmail.com');
    $mail->addReplyTo('orjiphils@gmail.com');
    $mail->addAddress($email_address);

    // email subject and message
    $mail->Subject = $email_subject;
    $mail->isHTML(true);
    $mail->Body = $email_message;

    echo ($mail->send()) ? $success_message : ($error_message);
}