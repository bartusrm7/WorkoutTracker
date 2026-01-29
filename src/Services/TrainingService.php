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
}
