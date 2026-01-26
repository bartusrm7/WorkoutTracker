<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\AuthService;

class AuthController
{
    private AuthService $service;

    public function __construct()
    {
        $this->service = new AuthService();
    }

    public function userRegistration()
    {
        session_start();
        if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
            die('CSRF token nieprawidłowy');
        }

        $name = $_POST['name'] ?? null;
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $pass = $_POST['password'] ?? null;

        $user = $this->service->createNewUser($name, $email, $pass);
        if (is_array($user)) {
            $errors = $user;
            include __DIR__ . '/../../templates/auth/signup.php';
            exit();
        }

        header('Location: /signin-form');
        exit();
    }

    public function userLogin()
    {
        session_start();
        if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
            die('CSRF token nieprawidłowy');
        }

        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $pass = $_POST['password'] ?? null;

        $user = $this->service->loginUser($email, $pass);
        var_dump($user->getId());

        if (is_array($user)) {
            $errors = $user;
            include __DIR__ .  '/../../templates/auth/signin.php';
            exit();
        }

        session_regenerate_id(true);
        $_SESSION['user'] = $user->getId();

        header('Location: /dashboard');
        exit();
    }

    public function userLogout()
    {
        session_start();
        session_unset();
        session_destroy();
        header('Location: /signin-form');
        exit();
    }

    public function signInForm()
    {
        session_start();
        if (!isset($_SESSION['token'])) {
            $_SESSION['token'] = bin2hex(random_bytes(32));
        }
        require '../templates/auth/signin.php';
    }

    public function signUpForm()
    {
        session_start();
        if (!isset($_SESSION['token'])) {
            $_SESSION['token'] = bin2hex(random_bytes(32));
        }
        require '../templates/auth/signup.php';
    }
}
