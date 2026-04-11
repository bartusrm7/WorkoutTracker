<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\AuthRepository;
use App\Validators\AuthValidation;

class AuthService
{
    private AuthRepository $repository;
    private AuthValidation $validator;
    private MailerService $mailer;

    public function __construct(AuthRepository $repository, AuthValidation $validator, MailerService $mailer)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->mailer = $mailer;
    }

    public function createNewUser($name, $email, $pass)
    {
        $errors = [];
        if ($error = $this->validator->nameValidation($name)) {
            $errors[] = $error;
        }
        if ($error = $this->validator->passwordValidation($pass)) {
            $errors[] = $error;
        }
        if ($error = $this->validator->nameToShort($name)) {
            $errors[] = $error;
        }
        if ($error = $this->validator->passwordToShort($pass)) {
            $errors[] = $error;
        }

        $emailExists = $this->repository->isUserEmailsExistsQuery($email);
        if ($emailExists) {
            $errors[] =  'Użytkownik z takim adresem email już istnieje';
        }
        if (!empty($errors)) {
            return $errors;
        } else {
            $hashPass = password_hash($pass, PASSWORD_DEFAULT);
            return $this->repository->registerUserQuery($name, $email, $hashPass);
        }
    }

    public function loginUser($email, $pass)
    {
        $errors = [];
        if ($error = $this->validator->passwordValidation($pass)) {
            $errors[] = $error;
        }
        if ($error = $this->validator->passwordToShort($pass)) {
            $errors[] = $error;
        }

        $userExists = $this->repository->isUserEmailsExistsQuery($email);
        if (!$userExists) {
            $errors[] = 'Użytkownik z takim adresem email nie istnieje';
        }
        if (!empty($errors)) {
            return $errors;
        } else {
            $user = $this->repository->loginUserQuery($email);
            if (!password_verify($pass, $user->getPass())) {
                return ['Hasło niepoprawne'];
            }
            return $user;
        }
    }

    public function forgetPasswordEmail($email)
    {
        if (!$email) {
            return ['success' => false, 'error' => 'Email jest wymagany'];
        }
        $result = $this->mailer->sendResetPasswordMailToUser($email);

        return [
            'success' => true,
            'data' => $result
        ];
    }

    public function resetPassword($email, $pass, $repeatPass)
    {
        $errors = [];
        if (empty($email) || empty($pass) || empty($repeatPass)) {
            $errors[] = 'Wszystkie pola muszą być uzupełnione';
        }
        if (strlen($pass) < 6) {
            $errors[] =  'Hasło musi posiadać co najmniej 6 znaków';
        }
        if ($pass !== $repeatPass) {
            $errors[] =  'Oba hasła muszą się zgadzać';
        }
        if (!empty($errors)) {
            return $errors;
        } else {
            $hashPass = password_hash($pass, PASSWORD_DEFAULT);
            $result = $this->repository->resetPasswordQuery($email, $hashPass);
            return $result;
        }
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
