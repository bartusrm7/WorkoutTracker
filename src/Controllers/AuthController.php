<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\AuthService;
use App\Services\MailerService;
use App\Sessions\Session;
use DateTime;

class AuthController
{
    private AuthService $service;
    private Session $session;
    private MailerService $mailer;

    public function __construct(AuthService $service, Session $session, MailerService $mailer)
    {
        $this->service = $service;
        $this->session = $session;
        $this->mailer = $mailer;
    }

    public function userRegistration()
    {
        session_start();
        $token = $_POST['token'];
        if (!isset($token) || $token !== $this->session->getSession('token')) {
            die('CSRF token nieprawidłowy');
        }

        $name = $_POST['name'] ?? null;
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $pass = $_POST['password'] ?? null;

        $user = $this->service->createNewUser($name, $email, $pass);
        if (is_array($user)) {
            $errors = $user;
            include __DIR__ . '/../../templates/auth/signup.html.twig';
            exit();
        }

        header('Location: /signin-form');
        exit();
    }

    public function userLogin()
    {
        $rememberMe = isset($_POST['remember-me']);
        if ($rememberMe) {
            session_set_cookie_params([
                'lifetime' => 60 * 60 * 24 * 30,
                'httponly' => true,
                'samesite' => 'Strict'
            ]);
        }

        session_start();
        $token = $_POST['token'];
        if (!isset($token) || $token !== $this->session->getSession('token')) {
            die('CSRF token nieprawidłowy');
        }

        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $pass = $_POST['password'] ?? null;

        $user = $this->service->loginUser($email, $pass);

        if (is_array($user)) {
            $errors = $user;
            include __DIR__ .  '/../../templates/auth/signin.html.twig';
            exit();
        }
        session_regenerate_id(true);
        $this->session->setSession('id', $user->getId());
        $this->session->setSession('name', $user->getName());
        if ($user->getIsProfileComplete()) {
            header('Location: /dashboard');
        } else {
            header('Location: /user-data-form');
        }
        exit();
    }

    public function userLogout()
    {
        session_start();
        session_unset();
        setcookie('PHPSESSID', '', time() - 3600, '/');
        session_destroy();
        header('Location: /signin-form');
        exit();
    }

    public function forgetPasswordEmail($email)
    {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $this->session->setSession('userEmail', $email);

            $result = $this->service->forgetPasswordEmail($email);

            header('Location: /signin-form');
            exit;
        }
    }

    public function resetPassword()
    {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $this->session->getSession('userEmail');
            $pass = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);
            $repeatPass = filter_input(INPUT_POST, 'repeatPass', FILTER_UNSAFE_RAW);

            $result = $this->service->resetPassword($email, $pass, $repeatPass);
            if (is_array($result)) {
                $errors = $result;
                include __DIR__ . '/../../templates/auth/resetpasswordform.html.twig';
                exit;
            } else {
                session_unset();
                header('Location: /signin-form');
                exit;
            }
        }
    }

    public function signInForm()
    {
        session_start();
        if ($this->session->getSession('id')) {
            header('Location: /dashboard');
        }
        if (!$this->session->getSession('token')) {
            $this->session->setSession('token', bin2hex(random_bytes(32)));
        }
        require '../templates/auth/signin.html.twig';
    }

    public function signUpForm()
    {
        session_start();
        if (!$this->session->getSession('token')) {
            $this->session->setSession('token', bin2hex(random_bytes(32)));
        }
        require '../templates/auth/signup.html.twig';
    }

    public function forgetPasswordEmailForm()
    {
        session_start();
        if (!$this->session->getSession('token')) {
            $this->session->setSession('token', bin2hex(random_bytes(32)));
        }
        require '../templates/auth/forgetpasswordform.html.twig';
    }

    public function resetPasswordForm()
    {
        session_start();
        if (!$this->session->getSession('token')) {
            $this->session->setSession('token', bin2hex(random_bytes(32)));
        }
        require '../templates/auth/resetpasswordform.html.twig';
    }

    public function userDataForm()
    {
        require '../templates/auth/userdataform.html.twig';
    }

    public function userData()
    {
        session_start();
        $userId = $this->session->getSession('id');
        $data = json_decode(file_get_contents('php://input'), true);

        $sex = $data['sex'];
        $age = $data['age'];
        $height = $data['height'];
        $weight = $data['weight'];
        $goalWeight = $data['goalWeight'];
        $goal = $data['goal'];
        $updatedDate = (new DateTime())->format('Y-m-d H:i:s');

        $result = $this->service->insertUserData($sex, $age, $height, $weight, $goalWeight, $goal, $userId, $updatedDate);
        if ($result) {
            $completedProfile = $this->service->markIsProfileComplete($userId);
            echo json_encode($completedProfile);
            exit;
        }

        echo json_encode($result);
        exit;
    }
}
