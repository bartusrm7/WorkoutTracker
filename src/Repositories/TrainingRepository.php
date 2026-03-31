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
    private PDO $pdo;

    public function __construct(Database $db)
    {
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

    public function endTrainingTimeQuery($id, $end, $duration)
    {
        $stmt = $this->pdo->prepare('UPDATE training SET end = :end, duration = :duration WHERE id = :id');
        $result = $stmt->execute([':id' => $id, ':end' => $end, ':duration' => $duration]);
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

    public function createNewExercisesQuery($name, $trainingId, $trainingName)
    {
        $stmt = $this->pdo->prepare('INSERT INTO exercises (name, training_id, training_name) VALUES (:name, :training_id, :training_name)');
        $stmt->execute([':name' => $name, ':training_id' => $trainingId, ':training_name' => $trainingName]);
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
        return $stmt->fetchAll();
    }

    public function displayAllTrainingPlansQuery($userId)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM training WHERE user_id = :user_id');
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function getTrainingNameQuery($id)
    {
        $stmt = $this->pdo->prepare('SELECT name FROM training WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function displayTrainingPlanQuery($userId, $trainingId)
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM training AS t
            INNER JOIN exercises AS e ON t.id = e.training_id
            WHERE t.user_id = :user_id
            AND t.id = :training_id'
        );
        $stmt->execute([':user_id' => $userId, ':training_id' => $trainingId]);
        return $stmt->fetchAll();
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
        return $stmt->fetch();
    }

    public function countSetsVolumeQuery($trainingId)
    {
        $stmt = $this->pdo->prepare(
            'SELECT exercises_data.* FROM exercises_data AS ed
            INNER JOIN exercises AS e ON ed.exercise_id = e.id
            WHERE e.training_id = :training_id'
        );
        $stmt->execute([':training_id' => $trainingId]);
        return $stmt->fetchAll();
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

    public function getLastExercisesWithNameQuery($userId, $trainingName)
    {
        $stmt = $this->pdo->prepare('SELECT name FROM training_history WHERE user_id = :user_id AND name = :name');
        $stmt->execute([':user_id' => $userId, ':name' => $trainingName]);
        return $stmt->fetch();
    }

    public function findLastTrainingIdQuery($userId, $trainingName)
    {
        $stmt = $this->pdo->prepare('SELECT id FROM training_history WHERE user_id = :user_id AND name = :name ORDER BY id DESC LIMIT 1');
        $stmt->execute([':user_id' => $userId, ':name' => $trainingName]);
        return $stmt->fetchColumn();
    }

    public function displayLastTrainingExercisesQuery($id, $userId, $exerciseName)
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM exercises_history_data AS ehd
            INNER JOIN exercises_history AS eh ON ehd.exercise_id = eh.id
            INNER JOIN training_history AS th ON eh.training_id = th.id
            WHERE th.id = :id
            AND th.user_id = :user_id
            AND eh.name = :exercise_name'
        );
        $stmt->execute([':id' => $id, ':user_id' => $userId, ':exercise_name' => $exerciseName]);
        return $stmt->fetchAll();
    }

    public function getLastExercisePRQuery($exerciseName)
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM exercises_history_data AS ehd
            INNER JOIN exercises_history AS eh ON ehd.exercise_name = eh.name
            WHERE eh.name = :name'
        );
        $stmt->execute([':name' => $exerciseName]);
        return $stmt->fetchAll();
    }
}
