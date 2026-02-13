<?php

declare(strict_types=1);

namespace App\Services;

use App\Exception\DatabaseException;
use App\Models\UserModel;
use App\Repository\UserRepository;

class UserService
{
    protected UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function getUser(string $filter, mixed $value) : UserModel
    {
        return $this->userRepository->getUser($filter, $value);
    }

    public function getUserById(int $id) : ?UserModel
    {
        try {
            return $this->userRepository->getUserById($id);
        } catch (DatabaseException $e) {
            throw $e;
        }
    }

    public function createUser(UserModel $user): void
    {
        try {
            $this->userRepository->saveUser($user);
        } catch (DatabaseException $e) {
            throw $e;
        }
    }

    public function updateUser(int $id, array $data): void
    {
        try {
            $this->userRepository->updateUser($id, $data);
        } catch (DatabaseException $e) {
            throw $e;
        }
    }
}