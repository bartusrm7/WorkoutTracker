<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;
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

    public function deleteAllSetsAfterFinishedTrainingQuery($trainingId)
    {
        $stmt = $this->pdo->prepare(
            'DELETE exercises_data FROM exercises_data 
            INNER JOIN exercises ON exercises_data.exercise_id = exercises.id
            WHERE exercises.training_id = :training_id'
        );
        $stmt->execute([':training_id' => $trainingId]);
        return $stmt->rowCount();
    }

    public function getSavedExercisesQuery($trainingId)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM exercises WHERE training_id = :training_id');
        $stmt->execute([':training_id' => $trainingId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSavedExercisesDataQuery($exerciseId)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM exercises_data WHERE exercise_id = :exercise_id');
        $stmt->execute([':exercise_id' => $exerciseId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function saveTrainingToHistoryQuery($name, $start, $end, $duration, $userId)
    {
        $stmt = $this->pdo->prepare('INSERT INTO training_history (name, start, end, duration, user_id) VALUES (:name, :start, :end, :duration, :user_id)');
        $stmt->execute([':name' => $name, ':start' => $start, ':end' => $end, ':duration' => $duration, ':user_id' => $userId]);
        return (int) $this->pdo->lastInsertId();
    }

    public function saveExercisesToHistoryQuery($name, $note, $trainingId)
    {
        $stmt = $this->pdo->prepare('INSERT INTO exercises_history (name, note, training_id) VALUES (:name, :note, :training_id)');
        $stmt->execute([':name' => $name, ':note' => $note, ':training_id' => $trainingId]);
        return (int) $this->pdo->lastInsertId();
    }

    public function saveExercisesDataToHistoryQuery($sets, $weight, $reps, $rir, $exerciseId, $createdAt)
    {
        $stmt = $this->pdo->prepare('INSERT INTO exercises_history_data (sets, weight, reps, rir, created_at, exercise_id) VALUES (:sets, :weight, :reps, :rir, :created_at, :exercise_id)');
        $stmt->execute([':sets' => $sets, ':weight' => $weight, ':reps' => $reps, ':rir' => $rir, ':exercise_id' => $exerciseId, ':created_at' => $createdAt]);
        return (int) $this->pdo->lastInsertId();
    }

    public function filterTrainingByDateQuery($start, $end)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM training_history WHERE start >= :start AND end <= :end');
        $stmt->execute([':start' => $start, ':end' => $end]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findExercisesByTrainingQuery($trainingId)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM exercises_history WHERE training_id = :training_id');
        $stmt->execute([':training_id' => $trainingId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findExercisesDataByExercisesQuery($exerciseId)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM exercises_history_data WHERE exercise_id = :exercise_id');
        $stmt->execute([':exercise_id' => $exerciseId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
