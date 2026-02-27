<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;
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
}
