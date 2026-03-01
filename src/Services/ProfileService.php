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

    public function changeUserProfileData($id, $sex, $age, $height, $weight, $goalWeight, $goal)
    {
        if (!$id) {
            return ['success' => false, 'error' => 'Brak ID ostatniego wiersza'];
        }
        $result = $this->repository->changeUserProfileDataQuery($id, $sex, $age, $height, $weight, $goalWeight, $goal);

        return [
            'success' => true,
            'data' => $result
        ];
    }
}
