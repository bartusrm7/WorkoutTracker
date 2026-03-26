<?php

declare(strict_types=1);

namespace App\Services;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class MailerService
{
    private PHPMailer $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);

        try {
            //Server settings
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
        $this->mail->Subject = 'Confirm your registration';
        $this->mail->Body = '<div><div style="font-weight: bold;">Thank you for signing up!</div><br/> Please confirm your registration to get started, <a style="font-weight: bold;" href="http://workouttracker.local:8000/signin-form">sign in here</a></div>';
        $this->mail->send();
    }

    public function sendResetPasswordMailToUser($email)
    {
        $this->mail->setFrom($_ENV['SMTP_NAME'], 'WorkoutTracker');
        $this->mail->addAddress($email);
        $this->mail->isHTML(true);
        $this->mail->Subject = 'Reset password';
        $this->mail->Body = '<div><a style="font-weight: bold;" href="http://workouttracker.local:8000/reset-password-form">Click the link</a> to change password.</div>';
        $this->mail->send();
    }
}
