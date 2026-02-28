<?php

declare(strict_types=1);

namespace App\Models;

class ProfileDataModel
{
    public function __construct(private $id, private $sex, private $age, private $height, private $weight, private $goalWeight, private $goal, private $userId, private $updatedDate) {}

    public function getId()
    {
        return $this->id;
    }
    public function getSex()
    {
        return $this->sex;
    }
    public function getAge()
    {
        return $this->age;
    }
    public function getHeight()
    {
        return $this->height;
    }
    public function getWeight()
    {
        return $this->weight;
    }
    public function getGoalWeight()
    {
        return $this->goalWeight;
    }
    public function getGoal()
    {
        return $this->goal;
    }
    public function getUserId()
    {
        return $this->userId;
    }
    public function getUpdatedDate()
    {
        return $this->updatedDate;
    }
}
