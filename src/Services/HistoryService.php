<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\HistoryRepository;

class HistoryService
{
    private HistoryRepository $repository;

    public function __construct()
    {
        $this->repository = new HistoryRepository();
    }

    public function saveTrainingToHistory($id)
    {
        $saveTraining = [];
        $saveExercises = [];
        $saveExercisesData = [];

        if (empty($id)) {
            return ['success' => false, 'error' => 'Brak ID treningu'];
        }

        $training = $this->repository->getSavedTrainingQuery($id);
        $saveTraining = $this->repository->saveTrainingToHistoryQuery(
            $training['name'],
            $training['start'],
            $training['end'],
            $training['duration'],
            $training['user_id']
        );

        $exercises = $this->repository->getSavedExercisesQuery($id);
        foreach ($exercises as $exercise) {
            $exerciseId = $this->repository->saveExercisesToHistoryQuery(
                $exercise['name'],
                $exercise['note'],
                $saveTraining
            );
            $saveExercises[] = $exerciseId;

            $exercisesData = $this->repository->getSavedExercisesDataQuery($exercise['id']);
            foreach ($exercisesData as $set) {
                $saveExercisesData[] = $this->repository->saveExercisesDataToHistoryQuery(
                    $set['sets'],
                    $set['weight'],
                    $set['reps'],
                    $set['rir'],
                    $exerciseId,
                    $set['created_at']
                );
            }
        }

        return [
            'success' => true,
            'data' => [
                'training' => $saveTraining,
                'exercises' => $saveExercises,
                'exercisesData' => $saveExercisesData

            ]
        ];
    }
}
