<?php

declare(strict_types=1);

namespace App\Sessions;

class Session
{
    public function setSession(string $key, mixed $value)
    {
        $_SESSION[$key] = $value;
    }

    public function getSession(string $key)
    {
        return $_SESSION[$key] ?? null;
    }

    public function removeSession(string $key)
    {
        unset($_SESSION[$key]);
    }
}
