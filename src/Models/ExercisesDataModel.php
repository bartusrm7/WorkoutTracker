<?php

namespace App\Models;


class ExercisesDataModel
{
    public function __construct(private $id, private $sets, private $reps, private $weight, private $exerciseId) {}

    public function getId()
    {
        return $this->id;
    }
    public function getSets()
    {
        return $this->sets;
    }
    public function getReps()
    {
        return $this->reps;
    }
    public function getWeight()
    {
        return $this->weight;
    }
    public function getExerciseId()
    {
        return $this->exerciseId;
    }
}
