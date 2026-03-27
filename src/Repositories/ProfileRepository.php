<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;
use App\Models\ProfileDataModel;
use DateTime;
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
        return $stmt->fetch();
    }

    public function changeUserProfileDataQuery($sex, $age, $height, $weight, $goalWeight, $goal, $userId)
    {
        $stmt = $this->pdo->prepare('INSERT INTO user_data (sex, age, height, weight, goal_weight, goal, user_id, updated_date) VALUES (:sex, :age, :height, :weight, :goal_weight, :goal, :user_id, :updated_date)');
        $stmt->execute([':sex' => $sex, ':age' => $age, ':height' => $height, ':weight' => $weight, ':goal_weight' => $goalWeight, ':goal' => $goal, ':user_id' => $userId, 'updated_date' => date('Y-m-d')]);

        return new ProfileDataModel(
            null,
            $sex,
            $age,
            $height,
            $weight,
            $goalWeight,
            $goal,
            $userId,
            date('Y-m-d')
        );
    }
}
