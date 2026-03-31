<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;

class StatisticsRepository
{
    private $pdo;

    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getConnection();
    }


    public function getUserWeightsQuery($userId)
    {
        $stmt = $this->pdo->prepare('SELECT weight, updated_date FROM user_data WHERE user_id = :user_id');
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function getUserTrainingsQuery($userId)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM training_history WHERE user_id = :user_id');
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function filterExercisesStatisticsByDateQuery($userId, $start, $end, $exercise)
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM training_history AS th
            INNER JOIN exercises_history AS eh ON eh.training_id = th.id
            INNER JOIN exercises_history_data AS ehd ON ehd.exercise_id = eh.id
            WHERE th.user_id = :user_id
            AND th.start >= :start
            AND th.end <= :end
            AND eh.name = :name'
        );
        $stmt->execute([':user_id' => $userId, ':start' => $start, ':end' => $end, 'name' => $exercise]);
        return $stmt->fetchAll();
    }

    public function getAllExercisesBelongForLoggedUserQuery($userId)
    {
        $stmt = $this->pdo->prepare(
            'SELECT DISTINCT eh.name FROM exercises_history AS eh
            INNER JOIN training_history AS th ON eh.training_id = th.id
            WHERE th.user_id = :user_id'
        );
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }
}
