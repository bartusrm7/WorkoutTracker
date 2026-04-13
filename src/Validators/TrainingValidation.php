<?php

declare(strict_types=1);

namespace App\Validators;

class TrainingValidation
{
    public function emptyTrainingNameValidation(string $trainingName)
    {
        if (empty($trainingName)) {
            return 'Nazwa treningu musi zostać podana';
        }
    }
    public function emptyExercisesValidation(array $exercisesName)
    {
        if (empty($exercisesName)) {
            return 'Nazwa ćwiczenia musi zostać podana';
        }
    }
    public function emptyExercisesNameValidation(array $exercisesName)
    {
        if (!is_array($exercisesName)) {
            return 'Musisz dodać przynajmniej jedno ćwiczenie';
        }
    }
}
