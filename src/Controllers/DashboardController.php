<?php

namespace App\Controllers;

use App\Services\AuthService;

class DashboardController
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function dashboard()
    {
        require '../templates/dashboard/dashboard.php';
    }
}
