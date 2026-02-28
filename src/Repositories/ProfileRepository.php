<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;
use App\Models\ProfileDataModel;
use PDO;

class ProfileRepository
{
    private $pdo;

    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getConnection();
    }

    public function getUserProfileDataQuery($userId)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM user_data WHERE user_id = :user_id ORDER BY id DESC LIMIT 1');
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function changeUserProfileDataQuery($sex, $age, $height, $weight, $goalWeight, $goal, $userId)
    {
        $stmt = $this->pdo->prepare('UPDATE user_data SET sex = :sex, age = :age, height = :height, weight = :weight, goal_weight = :goal_weight, goal = :goal WHERE user_id = :user_id');
        $stmt->execute([':sex' => $sex, ':age' => $age, ':height' => $height, ':weight' => $weight, ':goal_weight' => $goalWeight, ':goal' => $goal, ':user_id' => $userId]);
        $id = (int)$this->pdo->lastInsertId();

        return new ProfileDataModel(
            $id,
            $sex,
            $age,
            $height,
            $weight,
            $goalWeight,
            $goal,
            $userId,
            null
        );
    }
}
