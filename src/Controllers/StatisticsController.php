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
        $exercises = $this->service->getAllExercisesBelongForLoggedUser($userId);

        require '../templates/dashboard/statistics.php';
    }

    public function filterExercisesStatistics()
    {
        session_start();
        $userId = $_SESSION['id'];
        $data = json_decode(file_get_contents('php://input'), true);
        $start = $data['start'];
        $end = $data['end'];
        $exercise = $data['exercise'];

        $result = $this->service->filterExercisesStatisticsByDate($userId, $start, $end, $exercise);
        echo json_encode($result);
    }
}
