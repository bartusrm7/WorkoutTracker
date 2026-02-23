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
        $trainingsThisWeek = $this->service->amountOfTrainingsThisWeek($userId);
        $last7TrainingsVolume = $this->service->countVolumeLast7Days($userId);
        $sumTrainingDuration = $this->service->sumOfTrainigDurationsThisWeek($userId);

        require '../templates/dashboard/dashboard.php';
    }
}
