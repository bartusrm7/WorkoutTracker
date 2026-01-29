<?php

declare(strict_types=1);

namespace App\Models;

class ExercisesModel
{
    public function __construct(private $id, private $name, private $trainingId) {}

    public function getId()
    {
        return $this->id;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getTrainingId()
    {
        return $this->trainingId;
    }
}
