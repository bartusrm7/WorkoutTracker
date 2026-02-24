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

    public function getUserWeights($userId)
    {
        if (!$userId) {
            return ['success' => false, 'error' => 'Brak ID użytkownika'];
        }
        $result = $this->repository->getUserWeightsQuery($userId);

        return [
            'success' => false,
            'data' => $result
        ];
    }

    public function getUserTrainings($id)
    {
        if (!$id) {
            return ['success' => false, 'error' => 'Brak ID użytkownika'];
        }
        $result = $this->repository->getUserTrainingsQuery($id);

        return [
            'success' => false,
            'data' => $result
        ];
    }
}
