<?php

namespace App\Middlewares;

class AuthMiddleware
{
    public function userAccess()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /signin-form');
            exit();
        }
    }
}
