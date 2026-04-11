<?php

namespace App\Controllers;

use App\Services\DashboardService;
use App\Sessions\Session;

class DashboardController
{
    private DashboardService $service;
    private Session $session;

    public function __construct(DashboardService $service, Session $session)
    {
        $this->service = $service;
        $this->session = $session;
    }

    public function dashboard()
    {
        $userId = $this->session->getSession('id');

        $lastTraining = $this->service->getLastTrainingNameQuery($userId);
        $trainingsThisWeek = $this->service->amountOfTrainingsThisWeek($userId);
        $last7TrainingsVolume = $this->service->countVolumeLast7Days($userId);
        $sumTrainingDuration = $this->service->sumOfTrainigDurationsThisWeek($userId);
        $trainingsLast30Days = $this->service->sumOfTrainingsLast30Days($userId);

        require '../templates/dashboard/dashboard.php';
    }
}
