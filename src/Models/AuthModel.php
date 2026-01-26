<?php

namespace App\Models;

class AuthModel
{
    public function __construct(private string $name, private string $email, private string $pass) {}

    public function getName()
    {
        return $this->name;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getPass()
    {
        return $this->pass;
    }
}
