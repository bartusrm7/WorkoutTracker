<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;

class HistoryRepository
{
    private $pdo;

    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getConnection();
    }

    public function getSavedTrainingQuery($id, $userId)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM training WHERE id = :id AND user_id = :user_id');
        $stmt->execute([':id' => $id, ':user_id' => $userId]);
        return $stmt->fetch();
    }

    public function deleteAllSetsAfterFinishedTrainingQuery($trainingId)
    {
        $stmt = $this->pdo->prepare(
            'DELETE ed FROM exercises_data AS ed
            INNER JOIN exercises AS e ON ed.exercise_id = e.id
            WHERE e.training_id = :training_id'
        );
        $stmt->execute([':training_id' => $trainingId]);
        return $stmt->rowCount();
    }

    public function deleteAllNotesFromSavedTrainingQuery($trainingId) {
        $stmt = $this->pdo->prepare('UPDATE exercises SET note = NULL WHERE training_id = :training_id');
        $stmt->execute([':training_id' => $trainingId]);
        return $stmt->rowCount();
    }

    public function getSavedExercisesQuery($trainingId)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM exercises WHERE training_id = :training_id');
        $stmt->execute([':training_id' => $trainingId]);
        return $stmt->fetchAll();
    }

    public function getSavedExercisesDataQuery($exerciseId)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM exercises_data WHERE exercise_id = :exercise_id');
        $stmt->execute([':exercise_id' => $exerciseId]);
        return $stmt->fetchAll();
    }

    public function saveTrainingToHistoryQuery($name, $start, $end, $duration, $userId)
    {
        $stmt = $this->pdo->prepare('INSERT INTO training_history (name, start, end, duration, user_id) VALUES (:name, :start, :end, :duration, :user_id)');
        $stmt->execute([':name' => $name, ':start' => $start, ':end' => $end, ':duration' => $duration, ':user_id' => $userId]);
        return (int) $this->pdo->lastInsertId();
    }

    public function saveExercisesToHistoryQuery($name, $note, $trainingId, $trainingName)
    {
        $stmt = $this->pdo->prepare('INSERT INTO exercises_history (name, note, training_id, training_name) VALUES (:name, :note, :training_id, :training_name)');
        $stmt->execute([':name' => $name, ':note' => $note, ':training_id' => $trainingId, ':training_name' => $trainingName]);
        return (int) $this->pdo->lastInsertId();
    }

    public function saveExercisesDataToHistoryQuery($sets, $weight, $reps, $rir, $exerciseId, $exerciseName, $createdAt)
    {
        $stmt = $this->pdo->prepare('INSERT INTO exercises_history_data (sets, weight, reps, rir, created_at, exercise_id, exercise_name) VALUES (:sets, :weight, :reps, :rir, :created_at, :exercise_id, :exercise_name)');
        $stmt->execute([':sets' => $sets, ':weight' => $weight, ':reps' => $reps, ':rir' => $rir, ':exercise_id' => $exerciseId, ':exercise_name' => $exerciseName, ':created_at' => $createdAt]);
        return (int) $this->pdo->lastInsertId();
    }

    public function filterTrainingByDateQuery($start, $end, $userId)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM training_history WHERE start >= :start AND end <= :end AND user_id = :user_id');
        $stmt->execute([':start' => $start, ':end' => $end, ':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function findExercisesByTrainingQuery($trainingId)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM exercises_history WHERE training_id = :training_id');
        $stmt->execute([':training_id' => $trainingId]);
        return $stmt->fetchAll();
    }

    public function findExercisesDataByExercisesQuery($exerciseId)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM exercises_history_data WHERE exercise_id = :exercise_id');
        $stmt->execute([':exercise_id' => $exerciseId]);
        return $stmt->fetchAll();
    }
}
