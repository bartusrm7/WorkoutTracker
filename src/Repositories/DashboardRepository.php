<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;
use PDO;

class DashboardRepository
{
    private $pdo;

    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getConnection();
    }

    public function getLastTrainingNameQuery($userId)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM training_history WHERE user_id = :user_id ORDER BY id DESC LIMIT 1');
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function countVolumeLast7DaysQuery($userId)
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM training_history
            INNER JOIN exercises_history ON exercises_history.training_id = training_history.id
            INNER JOIN exercises_history_data ON exercises_history_data.exercise_id = exercises_history.id
            WHERE training_history.user_id = :user_id
            AND training_history.start >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)'
        );
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
