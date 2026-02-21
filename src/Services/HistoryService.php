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

    public function saveTrainingToHistory($id, $userId)
    {
        $saveTraining = [];
        $saveExercises = [];
        $saveExercisesData = [];

        if (empty($id)) {
            return ['success' => false, 'error' => 'Brak ID treningu'];
        }
        if (empty($userId)) {
            return ['success' => false, 'error' => 'Brak ID użytkownika'];
        }

        $training = $this->repository->getSavedTrainingQuery($id, $userId);
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

    public function deleteAllSetsAfterFinishedTraining($trainingId)
    {
        if (!$trainingId) {
            return ['success' => false, 'error' => 'Brak ID treningu'];
        }
        $result = $this->repository->deleteAllSetsAfterFinishedTrainingQuery($trainingId);

        return [
            'success' => true,
            'data' => $result
        ];
    }

    public function filterTrainingByDate($start, $end, $userId)
    {
        if (empty($start) || empty($end)) {
            return ['success' => false, 'error' => 'Brak podanej daty początkowej i końcowej'];
        }
        if (!$userId) {
            return ['success' => false, 'error' => 'Brak ID użytkownika'];
        }

        $trainings = $this->repository->filterTrainingByDateQuery($start, $end, $userId);
        $exercises = [];
        $exercisesData = [];

        foreach ($trainings as $i => $training) {
            $exercises = $this->repository->findExercisesByTrainingQuery($training['id']);
            $trainings[$i]['exercises'] = $exercises;
            $exerciseId[$training['id']] = $exercises;

            foreach ($exercises as $j => $exercise) {
                $exercisesData = $this->repository->findExercisesDataByExercisesQuery($exercise['id']);
                $trainings[$i]['exercises'][$j]['sets'] = $exercisesData;
            }
        }

        return [
            'success' => true,
            'data' => [
                'trainings' => $trainings
            ]
        ];
    }
}
