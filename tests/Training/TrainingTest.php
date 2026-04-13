<?php

declare(strict_types=1);

namespace App\Training;

use App\Models\TrainingModel;
use App\Repositories\TrainingRepository;
use App\Services\TrainingService;
use App\Validators\TrainingValidation;
use PHPUnit\Framework\TestCase;

class TrainingTest extends TestCase
{
    private TrainingService $service;
    private TrainingRepository $repository;
    private TrainingValidation $validation;

    public function setUp(): void
    {
        $this->repository = $this->createStub(TrainingRepository::class);
        $this->validation = $this->createStub(TrainingValidation::class);
        $this->service = new TrainingService($this->repository, $this->validation);
    }

    public function testTrainingNameEmpty()
    {
        $this->validation->method('emptyTrainingNameValidation')->willReturn('Nazwa treningu musi zostać podana');
        $this->repository->method('createNewTrainingQuery')->willReturn(null);

        $result = $this->service->newTraining('', ['pull ups', 'dips'], 1);
        $this->assertEquals(
            [
                'success' => false,
                'errors'  => ['Nazwa treningu musi zostać podana']
            ],
            $result
        );
    }

    public function testExercisesNameArrayEmpty()
    {
        $this->validation->method('emptyExercisesValidation')->willReturn('Nazwa ćwiczenia musi zostać podana');
        $this->repository->method('createNewTrainingQuery')->willReturn(null);

        $result = $this->service->newTraining('workout a', [], 1);
        $this->assertEquals(
            [
                'success' => false,
                'errors'  => ['Nazwa ćwiczenia musi zostać podana']
            ],
            $result
        );
    }

    public function testNewTrainingCreatedSuccessful()
    {
        $repo = $this->createMock(TrainingRepository::class);
        $this->validation->method('emptyTrainingNameValidation')->willReturn(null);
        $this->validation->method('emptyExercisesValidation')->willReturn(null);

        $repo->expects($this->once())
            ->method('createNewTrainingQuery')
            ->with('workout a', 1)
            ->willReturn(new TrainingModel(1, 'workout a', 1));

        $this->service = new TrainingService($repo, $this->validation);
        $result = $this->service->newTraining('workout a', ['pull ups', 'dips'], 1);
        $this->assertEquals(
            [
                'success'       => true,
                'data'          => [
                    'id'        => 1,
                    'name'      => 'workout a',
                    'exercises' => ['pull ups', 'dips'],
                ]
            ],
            $result
        );
    }
}
