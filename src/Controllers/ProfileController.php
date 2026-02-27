<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\ProfileService;

class ProfileController
{
    private ProfileService $service;

    public function __construct()
    {
        $this->service = new ProfileService();
    }

    public function Profile()
    {
        session_start();
        require '../templates/dashboard/profile.php';
    }
}
