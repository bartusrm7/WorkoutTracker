<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\HistoryService;

class HistoryController
{
    private HistoryService $service;

    public function __construct()
    {
        $this->service = new HistoryService();
    }

    public function history()
    {
        session_start();
        require '../templates/dashboard/history.php';
    }

    public function saveTrainingHistory()
    {
        session_start();
        header('Content-Type: application/json');

        $trainingId = $_SESSION['trainingId'];
        $userId = $_SESSION['id'];
        $training = $this->service->saveTrainingToHistory($trainingId, $userId);

        $setsData = $this->service->deleteAllSetsAfterFinishedTraining($trainingId);

        echo json_encode($training);
    }

    public function filterTrainings()
    {
        session_start();
        $start = $_POST['startDate'];
        $end = $_POST['endDate'];
        $userId = $_SESSION['id'];

        $trainings = $this->service->filterTrainingByDate($start, $end, $userId);

        foreach ($trainings['data']['trainings'] as &$training) {
            $totalWeight = 0;
            $totalSets = 0;

            foreach ($training['exercises'] as $exercise) {

                foreach ($exercise['sets'] as $set) {
                    $totalWeight += $set['weight'] * $set['reps'];
                    $totalSets++;
                }
            }

            $training['weightVolume'] = $totalWeight;
            $training['setsVolume'] = $totalSets;
        }
        unset($training);

        require '../templates/dashboard/history.php';
    }
}
