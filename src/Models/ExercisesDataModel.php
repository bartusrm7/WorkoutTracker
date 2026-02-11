<?php

declare(strict_types=1);

namespace App\Models;

class ExercisesDataModel
{
    public function __construct(private $id, private $sets, private $weight, private $reps, private $rir, private $createdAt, private $exerciseId) {}

    public function getId()
    {
        return $this->id;
    }
    public function getSets()
    {
        return $this->sets;
    }
    public function getWeight()
    {
        return $this->weight;
    }
    public function getReps()
    {
        return $this->reps;
    }
    public function getRir()
    {
        return $this->rir;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    public function getExerciseId()
    {
        return $this->exerciseId;
    }
}
