<?php

namespace App\Controllers;

use App\Services\DashboardService;

class DashboardController
{
    private DashboardService $service;

    public function __construct()
    {
        $this->service = new DashboardService();
    }

    public function dashboard()
    {
        $userId = $_SESSION['id'];
        $lastTraining = $this->service->getLastTrainingNameQuery($userId);

        require '../templates/dashboard/dashboard.php';
    }
}
