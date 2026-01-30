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
        $training = $this->service->newTraining($trainingName, $exercisesName, $userId);
        echo json_encode($training);
    }

    public function displayAllTrainings()
    {
        $userId = (int)($_SESSION['id'] ?? 0);
        $result = $this->service->displayAllTrainingPlans($userId);
        $trainings = $result['data'] ?? [];
        foreach ($trainings as $training) {
            $_SESSION['trainingName'] = $training['name'];
        }

        require '../templates/dashboard/trainings.php';
    }

    public function displayTraining()
    {
        $userId = (int)($_SESSION['id'] ?? 0);
        $trainingId = $_GET['id'];
        $training = $this->service->displayTrainingPlan($userId, $trainingId);
        $trainingName = $_SESSION['trainingName'];

        require '../templates/dashboard/training.php';
    }
}
