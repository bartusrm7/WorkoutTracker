<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\HistoryService;
use DateTime;

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
        $training = $this->service->saveTrainingToHistory($trainingId);

        echo json_encode($training);
    }

    public function filterTrainings()
    {
        $start = $_GET['startDate'];
        $end = $_GET['endDate'];

        $training = $this->service->filterTrainingByDate($start, $end);
        // echo '<pre>';
        // var_dump($training['data']);
        // exit;
        header('Location: /history');
        return $training;
    }
}
