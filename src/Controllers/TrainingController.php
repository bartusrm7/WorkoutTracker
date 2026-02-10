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

    public function editTraining()
    {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'];
        $name = $data['name'];

        $result = $this->service->editTrainingName($id, $name);
        echo json_encode($result);
    }

    public function deleteTraining()
    {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'];

        $result = $this->service->deleteTraining($id);
        echo json_encode($result);
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

        $setsVolume = $this->service->countSetsVolume($trainingId);
        $setsVolumeWeight = strval(array_sum(array_column($setsVolume['data'], 'weight')));
        $setsVolumeAmount = strval(count($setsVolume['data']));

        foreach ($training['data'] as $k => $exercise) {
            $training['data'][$k]['sets'] = $this->service->getSetsDataByExerciseId($exercise['id'])['data'];

            foreach ($training['data'][$k]['sets'] as $i => $set) {
                $training['data'][$k]['sets'][$i]['setNum'] = $i + 1;
            }
        };

        require '../templates/dashboard/training.php';
    }

    public function editSet()
    {
        session_start();
        $id = $_POST['id'];
        $sets = $_POST['sets'];
        $weight = $_POST['weight'];
        $reps = $_POST['reps'];
        $rir = $_POST['rir'];
        $exerciseId = $_POST['exerciseId'];

        $exerciseSet = $this->service->editExerciseSet($id, $sets, $weight, $reps, $rir, $exerciseId);

        $trainingId = $_SESSION['trainingId'];
        header("Location: /training?id=$trainingId");
    }

    public function getEditSet()
    {
        header('Content-Type: application/json');

        $id = $_GET['id'];
        $set = $this->service->getEditSetData($id);

        echo json_encode($set);
    }

    public function newExercise()
    {
        session_start();
        $data = json_decode(file_get_contents('php://input'), true);
        $name = $data['exercisesName'];
        $trainingId = $_SESSION['trainingId'];

        $exercise = $this->service->createNewExercises($name, $trainingId);
        echo json_encode($exercise);
    }

    public function deleteExercise()
    {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'];
        $result = $this->service->deleteExercise($id);

        echo json_encode($result);
    }
}
