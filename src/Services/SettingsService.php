<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\SettingsRepository;

class SettingsService
{
    private SettingsRepository $repository;

    public function __construct()
    {
        $this->repository = new SettingsRepository();
    }
}
