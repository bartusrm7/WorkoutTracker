<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\SettingsService;

class SettingsController
{
    private SettingsService $service;

    public function __construct()
    {
        $this->service = new SettingsService();
    }
}
