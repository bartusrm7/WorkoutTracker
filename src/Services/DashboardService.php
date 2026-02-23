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

    public function amountOfTrainingsThisWeek($userId)
    {
        if (!$userId) {
            return ['success' => false, 'error' => 'Brak ID użytkownika'];
        }
        $result = $this->repository->amountOfTrainingsThisWeekQuery($userId);

        return [
            'success' => true,
            'data' => $result
        ];
    }

    public function countVolumeLast7Days($userId)
    {
        if (!$userId) {
            return ['success' => false, 'error' => 'Brak ID użytkownika'];
        }
        $trainings = $this->repository->countVolumeLast7DaysQuery($userId);
        $volume = 0;

        foreach ($trainings as $row) {
            $weight = $row['weight'];
            $reps = $row['reps'];

            $volume += $weight * $reps;
        }

        return [
            'success' => true,
            'data' => [
                'volume' => $volume
            ]
        ];
    }

    public function sumOfTrainigDurationsThisWeek($userId)
    {
        if (!$userId) {
            return ['success' => false, 'error' => 'Brak ID użytkownika'];
        }
        $result = $this->repository->sumOfTrainigDurationsThisWeekQuery($userId);
        $durationSum = 0;

        foreach ($result as $row) {
            $durationSum += $row['duration'];
            $minutes = intdiv($durationSum, 60);
            $hours = intdiv($minutes, 60);
            $rest = $durationSum % 60;
            $time = $rest . 's';
            if ($minutes > 0) {
                $time = $minutes . ':' . $rest . 'm';
            } else if ($hours > 0) {
                $time = $hours . ':' . $minutes . ':' . $rest . 'h';
            }
        }

        return [
            'success' => true,
            'data' => $time
        ];
    }
}
