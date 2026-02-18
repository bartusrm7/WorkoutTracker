<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\TrainingRepository;

class TrainingService
{
    private TrainingRepository $repository;

    public function __construct()
    {
        $this->repository = new TrainingRepository();
    }

    public function newTraining($trainingName, $exercisesName, $userId)
    {
        if (empty($trainingName)) {
            return ['success' => false, 'error' => 'Nazwa treningu musi być podana'];
        }

        if (empty($exercisesName) || !is_array($exercisesName)) {
            return ['success' => false, 'error' => 'Musisz dodać przynajmniej jedno ćwiczenie'];
        }
        $training = $this->repository->createNewTrainingQuery($trainingName, $userId);
        $trainingId = $training->getId();

        foreach ($exercisesName as $exercise) {
            if (!empty($exercise)) {
                $this->repository->createNewExercisesQuery($exercise, $trainingId);
            }
        }

        return [
            'success' => true,
            'data' => [
                'id' => $training->getId(),
                'name' => $training->getName(),
                'exercises' => $exercisesName
            ]
        ];
    }

    public function startTraining($id, $start)
    {
        if (!$id) {
            return ['success' => false, 'error' => 'Brak ID ćwiczenia'];
        }
        if (empty($start)) {
            return ['success' => false, 'error' => 'Nie podano rozpoczęcia czasu treningu'];
        }
        $result = $this->repository->startTrainingTimeQuery($id, $start);

        return [
            'success' => true,
            'data' => $result
        ];
    }

    public function endTraining($id, $end)
    {
        if (!$id) {
            return ['success' => false, 'error' => 'Brak ID ćwiczenia'];
        }
        if (empty($end)) {
            return ['success' => false, 'error' => 'Nie podano rozpoczęcia czasu treningu'];
        }
        $result = $this->repository->endTrainingTimeQuery($id, $end);

        return [
            'success' => true,
            'data' => $result
        ];
    }

    public function editTrainingName($id, $name)
    {
        if (!$id) {
            return ['success' => false, 'error' => 'Brak ID ćwiczenia'];
        }
        if (empty($name)) {
            return ['success' => false, 'error' => 'Nazwa treningu musi być podana'];
        }
        $training = $this->repository->editTrainingNameQuery($id, $name);

        return [
            'success' => true,
            'data' => $training
        ];
    }

    public function deleteTraining($id)
    {
        if (!$id) {
            return ['success' => false, 'error' => 'Brak ID ćwiczenia'];
        }
        $result = $this->repository->deleteTrainingQuery($id);

        return [
            'success' => true,
            'data' => $result
        ];
    }

    public function createExerciseDataSet($sets, $weight, $reps, $rir, $exerciseId, $createdAt)
    {
        if (!$sets) {
            return [
                'success' => false,
                'error' => ['sets' => 'Liczba serii musi być wybrana']
            ];
        }
        if (empty($reps)) {
            return [
                'success' => false,
                'error' => ['setData' => 'Pola muszą być uzupełnione']
            ];
        }
        if (!$exerciseId) {
            return [
                'success' => false,
                'error' => ['exerciseId' => 'ID ćwiczenia musi być wybrane']
            ];
        }
        $sets = $this->repository->getSetsDataByExerciseIdQuery($exerciseId);
        $setNum = count($sets) + 1;
        $exercisesData = $this->repository->createNewExercisesDataQuery($setNum,  $weight, $reps, $rir, $exerciseId, $createdAt);

        return [
            'success' => true,
            'data' => $exercisesData
        ];
    }

    public function getSetsDataByExerciseId($exerciseId)
    {
        if (empty($exerciseId)) {
            return ['success' => false, 'error' => 'Brak ID ćwiczenia'];
        }
        $exercises = $this->repository->getSetsDataByExerciseIdQuery($exerciseId);

        return [
            'success' => true,
            'data' => $exercises
        ];
    }

    public function displayAllTrainingPlans($userId)
    {
        if (empty($userId)) {
            return ['success' => false, 'error' => 'Brak ID użytkownika'];
        }
        $trainings = $this->repository->displayAllTrainingPlansQuery($userId);

        return [
            'success' => true,
            'data' => $trainings
        ];
    }

    public function getTrainingName($id)
    {
        if (!$id) {
            return ['success' => false, 'error' => 'Brak ID treningu'];
        }
        $trainingId = $this->repository->getTrainingNameQuery($id);

        return [
            'success' => true,
            'data' => $trainingId
        ];
    }

    public function displayTrainingPlan($userId, $trainingId)
    {
        if (empty($userId)) {
            return ['success' => false, 'error' => 'Brak ID użytkownika'];
        }
        if (empty($trainingId)) {
            return ['success' => false, 'error' => 'ID treningu jest niepoprawne'];
        }
        $training = $this->repository->displayTrainingPlanQuery($userId, $trainingId);

        return [
            'success' => true,
            'data' => $training
        ];
    }

    public function editExerciseSet($id, $sets, $weight, $reps, $rir, $exerciseId)
    {
        if (!$id) {
            return ['success' => false, 'error' => 'Brak ID serii'];
        }
        if (empty($reps)) {
            return ['success' => false, 'error' => 'Pole powtórzeń musi być uzupełnione'];
        }
        if (!$exerciseId) {
            return ['success' => false, 'error' => 'Brak ID ćwiczenia'];
        }
        $exerciseSet = $this->repository->editExerciseSetQuery($id, $sets, $weight, $reps, $rir, $exerciseId);

        return [
            'success' => true,
            'data' => $exerciseSet
        ];
    }

    public function deleteExerciseSet($id)
    {
        if (!$id) {
            return ['success' => false, 'error' => 'Brak ID serii'];
        }
        $exerciseSet = $this->repository->deleteExerciseSetQuery($id);

        return [
            'success' => true,
            'data' => $exerciseSet
        ];
    }

    public function addNoteSet($id, $note)
    {
        if (!$id) {
            return ['success' => false, 'error' => 'Brak ID serii'];
        }
        $result = $this->repository->addNoteSetQuery($id, $note);

        return [
            'success' => true,
            'data' => $result
        ];
    }

    public function getEditSetData($id)
    {
        if (!$id) {
            return ['success' => false, 'error' => 'Brak ID serii'];
        }
        $exerciseSet = $this->repository->getEditSetDataQuery($id);

        return [
            'success' => true,
            'data' => $exerciseSet
        ];
    }

    public function countSetsVolume($trainingId)
    {
        if (empty($trainingId)) {
            return ['success' => false, 'error' => 'ID treningu jest niepoprawne'];
        }
        $exercises = $this->repository->countSetsVolumeQuery($trainingId);

        return [
            'success' => true,
            'data' => $exercises
        ];
    }

    public function createNewExercises($name, $trainingId)
    {
        if (empty($trainingId)) {
            return ['success' => false, 'error' => 'ID treningu jest niepoprawne'];
        }
        if (empty($name)) {
            return ['success' => false, 'error' => 'Nazwa ćwiczenia musi być podana'];
        }
        $exercise = $this->repository->createNewExercisesQuery($name, $trainingId);

        return [
            'success' => true,
            'data' => $exercise
        ];
    }

    public function editExercise($id, $name)
    {
        if (!$id) {
            return ['success' => false, 'error' => 'Brak ID ćwiczenia'];
        }
        if (empty($name)) {
            return ['success' => false, 'error' => 'Nazwa ćwiczenia musi być podana'];
        }
        $result = $this->repository->editExerciseQuery($id, $name);

        return [
            'success' => true,
            'data' => $result
        ];
    }

    public function deleteExercise($id)
    {
        if (!$id) {
            return ['success' => false, 'error' => 'Brak ID ćwiczenia'];
        }
        $result = $this->repository->deleteExerciseQuery($id);

        return [
            'success' => true,
            'data' => $result
        ];
    }
}
