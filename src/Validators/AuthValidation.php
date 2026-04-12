<?php

declare(strict_types=1);

namespace App\Validators;

class AuthValidation
{
    public function nameValidation(string $name)
    {
        if (empty($name)) {
            return 'Pole nazwy użytkownika jest puste.';
        }
    }
    public function passwordValidation(string $pass)
    {
        if (empty($pass)) {
            return 'Pole hasła jest puste.';
        }
    }
    public function nameToShort(string $name)
    {
        if (strlen($name) < 6) {
            return 'Nazwa użytkownika jest za krótka.';
        }
    }
    public function passwordToShort(string $pass)
    {
        if (strlen($pass) < 6) {
            return 'Hasło jest za krótkie.';
        }
    }
}
