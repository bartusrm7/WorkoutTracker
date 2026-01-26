<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\AuthRepository;

class AuthService
{
    private AuthRepository $repository;

    public function __construct()
    {
        $this->repository = new AuthRepository();
    }

    public function createNewUser($name, $email, $pass)
    {
        $errors = [];
        if (empty($name) || empty($email) || empty($pass)) {
            $errors[] = 'Wszystkie pola muszą być uzupełnione';
        }
        if (strlen($name) < 4) {
            $errors[] = 'Nazwa użytkownika musi posiadać co najmniej 4 znaki';
        }
        $emailExists = $this->repository->isUserEmailsExistsQuery($email);
        if ($emailExists) {
            $errors[] =  'Użytkownik z takim adresem email już istnieje';
        }
        if (strlen($pass) < 6) {
            $errors[] =  'Hasło musi posiadać co najmniej 6 znaków';
        }
        if (!empty($errors)) {
            return $errors;
        }

        $hashPass = password_hash($pass, PASSWORD_DEFAULT);
        return $this->repository->registerUserQuery($name, $email, $hashPass);
    }

    public function loginUser($email, $pass)
    {
        $errors = [];
        if (empty($email) || empty($pass)) {
            $errors[] = 'Wszystkie pola muszą być uzupełnione';
        }
        $userExists = $this->repository->isUserEmailsExistsQuery($email);
        if (!$userExists) {
            $errors[] = 'Użytkownik z takim adresem email nie istnieje';
        }
        if (strlen($pass) < 6) {
            $errors[] =  'Hasło musi posiadać co najmniej 6 znaków';
        }
        if (!empty($errors)) {
            return $errors;
        }
        $user = $this->repository->loginUserQuery($email);
        $verifiedPassword = password_verify($pass, $user->getPass());
        if (!$verifiedPassword) {
            $errors[] =  'Hasło niepoprawne';
        }

        return [
            'success' => true,
            'email' => $user->getEmail()
        ];
    }
}
