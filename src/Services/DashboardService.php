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
        $time = 0;

        foreach ($result as $row) {
            $durationSum += $row['duration'];
            $totalSeconds = $durationSum;

            $hours = intdiv($totalSeconds, 3600);
            $minutes = intdiv($totalSeconds % 3600, 60);
            $seconds = $totalSeconds % 60;

            $hoursStr = str_pad((string)$hours, 2, '0', STR_PAD_LEFT);
            $minutesStr = str_pad((string)$minutes, 2, '0', STR_PAD_LEFT);
            $secondsStr = str_pad((string)$seconds, 2, '0', STR_PAD_LEFT);

            if ($hours > 0) {
                $time = $hoursStr . ':' . $minutesStr . ':' . $secondsStr . 'h';
            } elseif ($minutes > 0) {
                $time = $minutesStr . ':' . $secondsStr . 'min';
            } else {
                $time = $secondsStr . 's';
            }
        }

        return [
            'success' => true,
            'data' => $time
        ];
    }
}
