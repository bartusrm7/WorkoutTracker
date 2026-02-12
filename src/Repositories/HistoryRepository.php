<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;
use App\Models\ExercisesHistoryDataModel;
use App\Models\ExercisesHistoryModel;
use App\Models\TrainingHistoryModel;
use DateTime;
use PDO;

class HistoryRepository
{
    private $pdo;

    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getConnection();
    }

    public function getSavedTrainingQuery($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM training WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getSavedExercisesQuery($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM exercises WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSavedExercisesDataQuery($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM exercises_data WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function saveTrainingToHistoryQuery($name, $start, $end, $duration, $userId)
    {
        $stmt = $this->pdo->prepare('INSERT INTO training_history (name, start, end, duration, user_id) VALUES (:name, :start, :end, :duration, :user_id)');
        $stmt->execute([':name' => $name, ':start' => $start, ':end' => $end, ':duration' => $duration, ':user_id' => $userId]);
        $id = (int) $this->pdo->lastInsertId();

        return new TrainingHistoryModel(
            $id,
            $name,
            $start,
            $end,
            $duration,
            $userId
        );
    }

    public function saveExercisesToHistoryQuery($name, $note, $trainingId)
    {
        $stmt = $this->pdo->prepare('INSERT INTO exercises (name, note, training_id) VALUES (:name, :note, :training_id)');
        $stmt->execute([':name' => $name, ':note' => $note, ':training_id' => $trainingId]);
        $id = (int) $this->pdo->lastInsertId();

        return new ExercisesHistoryModel(
            $id,
            $name,
            $note,
            $trainingId,
        );
    }

    public function saveExercisesDataToHistoryQuery($sets, $weight, $reps, $rir, $exerciseId, $createdAt)
    {
        $stmt = $this->pdo->prepare('INSERT INTO exercises_data (sets, weight, reps, rir, created_at, exercise_id) VALUES (:sets, :weight, :reps, :rir, :created_at, :exercise_id)');
        $stmt->execute([':sets' => $sets, ':weight' => $weight, ':reps' => $reps, ':rir' => $rir, ':exercise_id' => $exerciseId, ':created_at' => $createdAt]);
        $id = (int) $this->pdo->lastInsertId();

        return new ExercisesHistoryDataModel(
            $id,
            $sets,
            $weight,
            $reps,
            $rir,
            $createdAt,
            $exerciseId
        );
    }
}
