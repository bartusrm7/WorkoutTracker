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
}
