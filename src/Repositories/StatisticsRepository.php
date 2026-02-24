<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;
use PDO;

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
        $stmt = $this->pdo->prepare('SELECT weight FROM user_data WHERE user_id = :user_id');
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function filterExercisesStatisticsByDateQuery($userId, $start, $end, $exercise)
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM training_history
            INNER JOIN exercises_history ON exercises_history.training_id = training_history.id
            INNER JOIN exercises_history_data ON exercises_history_data.exercise_id = exercises_history.id
            WHERE training_history.user_id = :user_id
            AND training_history.start >= :start
            AND training_history.end <= :end
            AND exercises_history.name = :name'
        );
        $stmt->execute([':user_id' => $userId, ':start' => $start, ':end' => $end, 'name' => $exercise]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserTrainingsQuery($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM training WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
