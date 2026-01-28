<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\UserModel;
use App\Repository\UserRepository;

class UserService
{
    public function createUser(UserModel $user): void
    {
        $userRepository = new UserRepository();
        $userRepository->save($user);
    }
}
