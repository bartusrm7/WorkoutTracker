<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\StatisticsRepository;

class StatisticsService
{
    private StatisticsRepository $repository;

    public function __construct()
    {
        $this->repository = new StatisticsRepository();
    }
}
