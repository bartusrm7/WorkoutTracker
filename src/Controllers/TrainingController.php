<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\TrainingService;
use DateTime;

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

    public function exerciseSet()
    {
        session_start();
        $sets = $_POST['sets'];
        $weight = $_POST['weight'];
        $reps = $_POST['reps'];
        $rir = $_POST['rir'];
        $exerciseId = $_POST['exerciseId'];
        $createdAt = (new DateTime())->format('Y-m-d');

        $exercisesData = $this->service->createExerciseDataSet($sets, $weight, $reps, $rir, $exerciseId, $createdAt);
        $trainingId = $_SESSION['trainingId'];

        header("Location: /training?id=$trainingId");
    }

    // public function displayExercisesData()
    // {
    //     session_start();
    //     $trainingId = $_SESSION['trainingId'];
    //     $exercises = $this->service->addExercisesDataToExercises($trainingId, $exerciseId);
    //     header("Location: /training?id=$trainingId");
    // }

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
        $_SESSION['trainingId'] = $trainingId;
        if (!$trainingId) {
            return 'Brak ID treiningu';
        }

        $training = $this->service->displayTrainingPlan($userId, $trainingId);
        $trainingName = $_SESSION['trainingName'];

        if (!is_array($training)) {
            return 'Zmienna nie jest pętlą';
        }

        foreach ($training['data'] as $k => $exercise) {
            $training['data'][$k]['sets'] = $this->service->getSetsDataByExerciseId($exercise['id'])['data'];
        };

        require '../templates/dashboard/training.php';
    }
}
