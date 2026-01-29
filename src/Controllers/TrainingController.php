<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\TrainingService;

class TrainingController
{
    private TrainingService $service;

    public function __construct()
    {
        $this->service = new TrainingService();
    }

    public function addTraining()
    {
        session_start();
        header('Content-Type: application/json');

        $trainingName = $_POST['trainingName'] ?? '';
        $exercises = $_POST['exercisesName'] ?? '[]';
        $exercisesName = json_decode($exercises, true);
        $userId = (int)($_SESSION['id'] ?? 0);

        if (!$userId) {
            echo json_encode(['success' => false, 'error' => 'Użytkownik niezalogowany']);
            return;
        }
        $result = $this->service->newTraining($trainingName, $exercisesName, $userId);
        echo json_encode($result);
    }

    public function training()
    {
        require '../templates/dashboard/training.php';
    }
}
