<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\UserModel;
use App\Repository\UserRepository;

class UserService
{
    protected UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function createUser(UserModel $user): void
    {
        $this->userRepository->saveUser($user);
    }

    public function updateUser(int $id, array $data): void
    {
        $this->userRepository->updateUser($id, $data);
    }
}
