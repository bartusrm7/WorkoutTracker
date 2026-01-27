<?php

namespace App\Models;

class TrainingModel
{
    public function __construct(private $id, private $name, private $userId) {}

    public function getId()
    {
        $this->id;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getUserId()
    {
        return $this->userId;
    }
}
