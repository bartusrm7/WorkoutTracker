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

        require '../templates/dashboard/training.php';
    }

    public function displayTraining()
    {
        session_start();

        $userId = (int)($_SESSION['id'] ?? 0);
        $trainingId = $_GET['trainingId'];
        $training = $this->service->displayTrainingPlan($userId, $trainingId);

        return $training;
    }
}
