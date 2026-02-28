<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\ProfileService;

class ProfileController
{
    private ProfileService $service;

    public function __construct()
    {
        $this->service = new ProfileService();
    }

    public function profile()
    {
        $userId = $_SESSION['id'];
        $userData = $this->service->getUserProfileData($userId);

        require '../templates/dashboard/profile.php';
    }

    public function editProfile()
    {
        session_start();

        $userId = $_SESSION['id'];
        $sex = $_POST['sex'];
        $age = $_POST['age'];
        $height = $_POST['height'];
        $weight = $_POST['weight'];
        $goalWeight = $_POST['goal_weight'];
        $goal = $_POST['goal'];

        $userData = $this->service->changeUserProfileData($sex, $age, $height, $weight, $goalWeight, $goal, $userId);
        header('Location: /profile');
    }
}
