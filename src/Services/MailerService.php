<?php

declare(strict_types=1);

namespace App\Services;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class MailerService
{
    private PHPMailer $mail;

    public function __construct(PHPMailer $mail)
    {
        $this->mail = $mail;

        try {
            $this->mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $this->mail->isSMTP();
            $this->mail->Host       = $_ENV['SMTP_HOST'];
            $this->mail->SMTPAuth   = true;
            $this->mail->Username   = $_ENV['SMTP_NAME'];
            $this->mail->Password   = $_ENV['SMTP_PASS'];
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->Port       = $_ENV['SMTP_PORT'];
        } catch (Exception $e) {
            die('Message could not be sent. Mailer Error:' . $e->getMessage());
        }
    }

    public function sendRegistrationMailToUser($email)
    {
        $this->mail->setFrom($_ENV['SMTP_NAME'], 'WorkoutTracker');
        $this->mail->addAddress($email);
        $this->mail->isHTML(true);
        $this->mail->Subject = 'Potwierde swoja rejestracje';
        $this->mail->Body = '<div><div style="font-weight: bold;">Dziękujemy za rejestrację!</div><br/>  Aby rozpocząć korzystanie z aplikacji, potwierdź swoją rejestrację. Możesz się zalogować, klikając <a style="font-weight: bold;" href="http://workouttracker.local:8000/signin-form">tutaj</a></div>';
        $this->mail->send();
    }

    public function sendResetPasswordMailToUser($email)
    {
        $this->mail->setFrom($_ENV['SMTP_NAME'], 'WorkoutTracker');
        $this->mail->addAddress($email);
        $this->mail->isHTML(true);
        $this->mail->Subject = 'Zresetuj swoje haslo';
        $this->mail->Body = '<div><a style="font-weight: bold;" href="http://workouttracker.local:8000/reset-password-form">Kliknij tutaj</a>, aby zresetować hasło..</div>';
        $this->mail->send();
    }
}
