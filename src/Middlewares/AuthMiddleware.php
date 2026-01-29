<?php

namespace App\Middlewares;

class AuthMiddleware
{
    public function userAccess()
    {
        session_start();
        
        if (!isset($_SESSION['id'])) {
            header('Location: /signin-form');
            exit();
        }
    }
}
