<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\StatisticsService;

class StatisticsController
{
    private StatisticsService $service;

    public function __construct()
    {
        $this->service = new StatisticsService();
    }

    public function statistics()
    {
        $userId = $_SESSION['id'];
        $weightCharts = $this->service->getUserWeights($userId);
        $trainingCharts = $this->service->getUserTrainings($userId);

        require '../templates/dashboard/statistics.php';
    }
}
