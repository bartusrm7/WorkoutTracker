<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\ProfileRepository;

class ProfileService
{
    private ProfileRepository $repository;

    public function __construct()
    {
        $this->repository = new ProfileRepository();
    }

    public function getUserProfileData($userId)
    {
        if (!$userId) {
            return ['success' => false, 'error' => 'Brak ID użytkownika'];
        }
        $result = $this->repository->getUserProfileDataQuery($userId);

        return [
            'success' => true,
            'data' => $result
        ];
    }

    public function changeUserProfileData($sex, $age, $height, $weight, $goalWeight, $goal, $userId)
    {
        if (!$userId) {
            return ['success' => false, 'error' => 'Brak ID użytkownika'];
        }
        $result = $this->repository->changeUserProfileDataQuery($sex, $age, $height, $weight, $goalWeight, $goal, $userId);

        return [
            'success' => true,
            'data' => $result
        ];
    }
}
