<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;
use App\Models\ExercisesDataModel;
use App\Models\ExercisesModel;
use App\Models\TrainingModel;
use PDO;

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

    public function startTrainingTimeQuery($id, $start)
    {
        $stmt = $this->pdo->prepare('UPDATE training SET start = :start WHERE id = :id');
        $result = $stmt->execute([':id' => $id, ':start' => $start]);
        return $result;
    }

    public function endTrainingTimeQuery($id, $end)
    {
        $stmt = $this->pdo->prepare('UPDATE training SET end = :end WHERE id = :id');
        $result = $stmt->execute([':id' => $id, ':end' => $end]);
        return $result;
    }

    public function editTrainingNameQuery($id, $name)
    {
        $stmt = $this->pdo->prepare('UPDATE training SET name = :name WHERE id = :id');
        $stmt->execute([':id' => $id, ':name' => $name]);

        return new TrainingModel(
            $id,
            $name,
            null
        );
    }

    public function deleteTrainingQuery($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM training WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount();
    }

    public function createNewExercisesQuery($name, $trainingId)
    {
        $stmt = $this->pdo->prepare('INSERT INTO exercises (name, training_id) VALUES (:name, :training_id)');
        $stmt->execute([':name' => $name, ':training_id' => $trainingId]);
        $id = (int) $this->pdo->lastInsertId();

        return new ExercisesModel(
            $id,
            $name,
            '',
            $trainingId,
        );
    }

    public function createNewExercisesDataQuery($sets, $weight, $reps, $rir, $exerciseId, $createdAt)
    {
        $stmt = $this->pdo->prepare('INSERT INTO exercises_data (sets, weight, reps, rir, created_at, exercise_id) VALUES (:sets, :weight, :reps, :rir, :created_at, :exercise_id)');
        $stmt->execute([':sets' => $sets, ':weight' => $weight, ':reps' => $reps, ':rir' => $rir, ':exercise_id' => $exerciseId, ':created_at' => $createdAt]);
        $id = (int) $this->pdo->lastInsertId();

        return new ExercisesDataModel(
            $id,
            $sets,
            $weight,
            $reps,
            $rir,
            $createdAt,
            $exerciseId
        );
    }

    public function getSetsDataByExerciseIdQuery($exerciseId)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM exercises_data WHERE exercise_id = :exercise_id');
        $stmt->execute([':exercise_id' => $exerciseId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function displayAllTrainingPlansQuery($userId)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM training WHERE user_id = :user_id');
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function displayTrainingPlanQuery($userId, $trainingId)
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM training
        INNER JOIN exercises ON training.id = exercises.training_id
        WHERE training.user_id = :user_id
        AND training.id = :training_id'
        );
        $stmt->execute([':user_id' => $userId, ':training_id' => $trainingId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addNoteSetQuery($id, $note)
    {
        $stmt = $this->pdo->prepare('UPDATE exercises SET note = :note WHERE id = :id');
        $result = $stmt->execute([':id' => $id, ':note' => $note]);
        return $result;
    }

    public function editExerciseSetQuery($id, $sets, $weight, $reps, $rir, $exerciseId)
    {
        $stmt = $this->pdo->prepare('UPDATE exercises_data SET sets = :sets, weight = :weight, reps = :reps, rir = :rir WHERE id = :id AND exercise_id = :exercise_id');
        $stmt->execute([':id' => $id, ':sets' => $sets, ':weight' => $weight, ':reps' => $reps, ':rir' => $rir, ':exercise_id' => $exerciseId]);

        return new ExercisesDataModel(
            $id,
            $sets,
            $weight,
            $reps,
            $rir,
            null,
            $exerciseId
        );
    }

    public function deleteExerciseSetQuery($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM exercises_data WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount();
    }

    public function getEditSetDataQuery($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM exercises_data WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function countSetsVolumeQuery($trainingId)
    {
        $stmt = $this->pdo->prepare(
            'SELECT exercises_data.* FROM exercises_data
            INNER JOIN exercises ON exercises_data.exercise_id = exercises.id
            WHERE exercises.training_id = :training_id'
        );
        $stmt->execute([':training_id' => $trainingId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function editExerciseQuery($id, $name)
    {
        $stmt = $this->pdo->prepare('UPDATE exercises SET name = :name WHERE id = :id');
        $stmt->execute([':id' => $id, ':name' => $name]);

        return new ExercisesModel(
            $id,
            $name,
            '',
            null
        );
    }

    public function deleteExerciseQuery($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM exercises WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount();
    }
}
