<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;

class SettingsRepository
{
    private $pdo;

    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getConnection();
    }
}
