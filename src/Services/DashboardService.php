<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\DashboardRepository;

class DashboardService
{
    private DashboardRepository $repository;

    public function __construct()
    {
        $this->repository = new DashboardRepository();
    }

    public function getLastTrainingNameQuery($userId)
    {
        if (!$userId) {
            return ['success' => false, 'error' => 'Brak ID użytkownika'];
        }
        $result = $this->repository->getLastTrainingNameQuery($userId);

        return [
            'success' => true,
            'data' => $result
        ];
    }
}
