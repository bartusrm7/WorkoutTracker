<?php

declare(strict_types=1);

namespace App\Auth;

use App\Repositories\AuthRepository;
use App\Services\AuthService;
use App\Validators\AuthValidation;
use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase
{
    private AuthRepository $repository;
    private AuthValidation $validation;
    private AuthService $service;

    public function setUp(): void
    {
        $this->repository = $this->createStub(AuthRepository::class);
        $this->validation = $this->createStub(AuthValidation::class);
        $this->service = new AuthService($this->repository, $this->validation);
    }

    public function testUserNameExists()
    {
        $this->validation->method('nameValidation')->willReturn('Pole nazwy użytkownika jest puste.');
        $this->repository->method('registerUserQuery')->willReturn(null);

        $result = $this->service->createNewUser('', 'example@email.com', 'pass123');
        $this->assertEquals(['Pole nazwy użytkownika jest puste.'], $result);
    }

    public function testUserPasswordExists()
    {
        $this->validation->method('passwordValidation')->willReturn('Pole hasła jest puste.');
        $this->repository->method('registerUserQuery')->willReturn(null);

        $result = $this->service->createNewUser('user123', 'example@email.com', '');
        $this->assertEquals(['Pole hasła jest puste.'], $result);
    }

    public function testUserNameToShort()
    {
        $this->validation->method('nameToShort')->willReturn('Nazwa użytkownika jest za krótka.');
        $this->repository->method('registerUserQuery')->willReturn(null);

        $result = $this->service->createNewUser('user', 'example@email.com', 'pass123');
        $this->assertEquals(['Nazwa użytkownika jest za krótka.'], $result);
    }

    public function testUserPasswordToShort()
    {
        $this->validation->method('passwordToShort')->willReturn('Hasło jest za krótkie.');
        $this->repository->method('registerUserQuery')->willReturn(null);

        $result = $this->service->createNewUser('user', 'example@email.com', 'pass123');
        $this->assertEquals(['Hasło jest za krótkie.'], $result);
    }

    public function testUserRegistration()
    {
        $repo = $this->createMock(AuthRepository::class);

        $this->validation->method('nameValidation')->willReturn(null);
        $this->validation->method('passwordValidation')->willReturn(null);
        $this->validation->method('nameToShort')->willReturn(null);
        $this->validation->method('passwordToShort')->willReturn(null);
        $repo->expects($this->once())
            ->method('registerUserQuery')
            ->with('user123', 'example@email.com', $this->anything())
            ->willReturn(['success' => true]);

        $this->service = new AuthService($repo, $this->validation);
        $result = $this->service->createNewUser('user123', 'example@email.com', 'pass123');
        $this->assertEquals(['success' => true], $result);
    }
}
