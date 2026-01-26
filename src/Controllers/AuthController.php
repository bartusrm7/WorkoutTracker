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
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $pass = $_POST['password'];

        $user = $this->service->loginUser($email, $pass);
        if (is_array($user)) {
            $errors = $user;
            include __DIR__ .  '/../../templates/auth/signin.php';
            exit();
        }
        header('Location: /dashboard');
        exit();
    }

    public function signInForm()
    {
        require '../templates/auth/signin.php';
    }

    public function signUpForm()
    {
        require '../templates/auth/signup.php';
    }
}
