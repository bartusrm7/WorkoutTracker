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
        require '../templates/dashboard/statistics.php';
    }
}
