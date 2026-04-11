<?php

declare(strict_types=1);

namespace App\Auth;

use App\Database\Database;
use App\Repositories\AuthRepository;
use App\Services\AuthService;
use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase
{
    private AuthService $service;
    private AuthRepository $repository;

    public function setUp(): void
    {
        $this->repository = $this->createStub(AuthRepository::class);
        $this->service = $this->createStub(AuthService::class);
    }
}
