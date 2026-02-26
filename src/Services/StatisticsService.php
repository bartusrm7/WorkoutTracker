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
            'success' => true,
            'data' => $result
        ];
    }

    public function filterExercisesStatisticsByDate($userId, $start, $end, $exercise)
    {
        if (!$userId) {
            return ['success' => false, 'error' => 'Brak ID użytkownika'];
        }
        if (empty($start) || empty($end)) {
            return ['success' => false, 'error' => 'Zakres dat musi być wybrany'];
        }
        if (empty($exercise)) {
            return ['success' => false, 'error' => 'Ćwiczenie musi zostać wybrane'];
        }
        $result = $this->repository->filterExercisesStatisticsByDateQuery($userId, $start, $end, $exercise);

        return [
            'success' => true,
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
            'success' => true,
            'data' => $result
        ];
    }

    public function getAllExercisesBelongForLoggedUser($userId)
    {
        if (!$userId) {
            return ['success' => false, 'error' => 'Brak ID użytkownika'];
        }
        $result = $this->repository->getAllExercisesBelongForLoggedUserQuery($userId);

        return [
            'success' => true,
            'data' => $result
        ];
    }
}
