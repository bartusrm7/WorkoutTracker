<?php

namespace App\Repositories;

use App\Database\Database;
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
}
