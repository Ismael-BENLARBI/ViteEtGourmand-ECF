<?php
require_once __DIR__ . '/../libs/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../libs/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../libs/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer {
    
    public static function send($to, $subject, $body) {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp-vite-et-gourmand-projet.alwaysdata.net';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'vite-et-gourmand-projet@alwaysdata.net';
            $mail->Password   = 'ViteEtGourmand2026!';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';
            $mail->setFrom('vite-et-gourmand-projet@vite-et-gourmand-projet.alwaysdata.net', 'Vite & Gourmand');
            $mail->addAddress($to);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = strip_tags($body);

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}