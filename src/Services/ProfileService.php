<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ProfileRepository;

class ProfileService
{
    private ProfileRepository $repository;

    public function __construct()
    {
        $this->repository = new ProfileRepository();
    }
}
