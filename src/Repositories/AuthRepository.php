<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;
use App\Models\AuthModel;

class AuthRepository
{
    private $pdo;

    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getConnection();
    }

    public function isUserEmailsExistsQuery($email)
    {
        $stmt = $this->pdo->prepare('SELECT email FROM users WHERE email = :email');
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch();
        $id = (int) $this->pdo->lastInsertId();

        if (!$row) {
            return null;
        }
        return new AuthModel(
            $id,
            '',
            $row['email'],
            '',
            0
        );
    }

    public function registerUserQuery($name, $email, $pass)
    {
        $stmt = $this->pdo->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
        $stmt->execute([':name' => $name, ':email' => $email, ':password' => $pass]);
        $id = (int) $this->pdo->lastInsertId();

        return new AuthModel($id, $name, $email, $pass, 0);
    }

    public function loginUserQuery($email)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }
        return new AuthModel(
            (int) $row['id'],
            $row['name'],
            $row['email'],
            $row['password'],
            $row['is_profile_complete']
        );
    }

    public function insertUserDataQuery($sex, $age, $height, $weight, $goalWeight, $goal, $userId, $updatedDate)
    {
        $stmt = $this->pdo->prepare('INSERT INTO user_data (sex, age, height, weight, goal_weight, goal, user_id, updated_date) VALUES (:sex, :age, :height, :weight, :goal_weight, :goal, :user_id, :updated_date)');
        $stmt->execute([':sex' => $sex, ':age' => $age, ':height' => $height, ':weight' => $weight, ':goal_weight' => $goalWeight, ':goal' => $goal, ':user_id' => $userId, ':updated_date' => $updatedDate]);
        return (int) $this->pdo->lastInsertId();
    }

    public function markIsProfileCompleteQuery($id)
    {
        $stmt = $this->pdo->prepare('UPDATE users SET is_profile_complete = 1 WHERE id = :id');
        $result = $stmt->execute([':id' => $id]);
        return $result;
    }
}
