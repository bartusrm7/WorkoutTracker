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
        if (!password_verify($pass, $user->getPass())) {
            return ['Hasło niepoprawne'];
        }
        return $user;
    }

    public function insertUserData($sex, $age, $height, $weight, $goalWeight, $goal, $userId, $updatedDate)
    {
        if (empty($sex) || empty($age) || empty($height) || empty($weight) || empty($goalWeight) || empty($goal)) {
            return ['success' => false, 'error' => 'Wszystkie pola muszą być uzupełnione'];
        }
        if (!$userId) {
            return ['success' => false, 'error' => 'Brak ID użytkownika'];
        }
        $result = $this->repository->insertUserDataQuery($sex, $age, $height, $weight, $goalWeight, $goal, $userId, $updatedDate);

        return [
            'success' => true,
            'data' => $result
        ];
    }

    public function markIsProfileComplete($id)
    {
        if (!$id) {
            return ['success' => false, 'error' => 'Brak ID użytkownika'];
        }
        $result = $this->repository->markIsProfileCompleteQuery($id);

        return [
            'success' => true,
            'data' => $result
        ];
    }
}
