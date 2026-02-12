<?php

declare(strict_types=1);

namespace App\Models;

class TrainingHistoryModel
{
    public function __construct(private $id, private $name, private $start, private $end, private $duration, private $userId) {}

    public function getId()
    {
        return $this->id;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getStart()
    {
        return $this->start;
    }
    public function getEnd()
    {
        return $this->end;
    }
    public function getDuration()
    {
        return $this->duration;
    }
    public function getUserId()
    {
        return $this->userId;
    }
}
