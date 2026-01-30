<?php

declare(strict_types=1);

namespace App\Repository;

use App\Factories\EntityManagerFactory;
use App\Models\UserModel;
use Doctrine\ORM\EntityManager;

class UserRepository
{
    private EntityManager $entityManager;

    public function __construct()
    {
        $this->entityManager = EntityManagerFactory::getInstance();
    }

    public function getUser(int $userId): UserModel
    {
        return $this->entityManager->find(
            className: UserModel::class,
            id: $userId,
        );
    }

    public function saveUser(UserModel $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function updateUser(int $id, array $data)
    {
        $user = $this->getUser($id);

        foreach ($data as $propertie => $value) {
            $user->$propertie = $value;
            // dd($propertie);
        }
        // dd($user);

        $this->entityManager->flush();
    }
}
