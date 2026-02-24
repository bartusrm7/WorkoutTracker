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


    public function getUserTrainingsQuery($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM training WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
