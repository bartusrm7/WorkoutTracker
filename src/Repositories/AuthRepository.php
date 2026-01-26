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

        if (!$row) {
            return null;
        }
        return new AuthModel(
            '',
            $row['email'],
            ''
        );
    }

    public function registerUserQuery($name, $email, $pass)
    {
        $stmt = $this->pdo->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
        $stmt->execute([':name' => $name, ':email' => $email, ':password' => $pass]);

        return new AuthModel($name, $email, $pass);
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
            '',
            $row['email'],
            $row['password'],
        );
    }
}
