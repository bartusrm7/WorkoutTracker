<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;
use App\Models\ExercisesDataModel;
use App\Models\ExercisesModel;
use App\Models\TrainingModel;

class TrainingRepository
{
    private $pdo;

    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getConnection();
    }

    public function createNewTrainingQuery($name, $userId)
    {
        $stmt = $this->pdo->prepare('INSERT INTO training (name, user_id) VALUES (:name, :user_id)');
        $stmt->execute([':name' => $name, ':user_id' => $userId]);
        $id = (int) $this->pdo->lastInsertId();

        return new TrainingModel(
            $id,
            $name,
            $userId
        );
    }

    public function createNewExercisesQuery($name, $trainingId)
    {
        $stmt = $this->pdo->prepare('INSERT INTO exercises (name, training_id) VALUES (:name, :training_id)');
        $stmt->execute([':name' => $name, ':training_id' => $trainingId]);
        $id = (int) $this->pdo->lastInsertId();

        return new ExercisesModel(
            $id,
            $name,
            $trainingId,
        );
    }

    public function createNewExercisesDataQuery($sets, $reps, $weight, $exerciseId)
    {
        $stmt = $this->pdo->prepare('INSERT INTO exercises_data (sets, reps, weight, training_id) VALUES (:sets, :reps, :weight, :exercise_id)');
        $stmt->execute([':sets' => $sets, ':reps' => $reps, ':weight' => $weight, ':exercise_id' => $exerciseId]);
        $id = (int) $this->pdo->lastInsertId();

        return new ExercisesDataModel(
            $id,
            $sets,
            $reps,
            $weight,
            $exerciseId
        );
    }
}
