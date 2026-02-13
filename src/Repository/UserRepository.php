<?php

declare(strict_types=1);

namespace App\Repository;

use App\Exception\DatabaseException;
use App\Factories\EntityManagerFactory;
use App\Models\UserModel;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;

class UserRepository
{
    private EntityManager $entityManager;

    public function __construct()
    {
        $this->entityManager = EntityManagerFactory::getInstance();
    }

    public function getUser(string $filter, mixed $value) : UserModel
    {
        $dql = "SELECT u FROM App\Models\UserModel u WHERE u.{$filter} = :value";

        $query = $this->entityManager->createQuery($dql);
        $query->setParameter(key: 'value', value: $value);

        return $query->getOneOrNullResult();
    }

    public function getUserById(int $userId): UserModel|null
    {
        try {
            $user = $this->entityManager->find(
                className: UserModel::class,
                id: $userId,
            );

            return $user ?: null;
        } catch (ORMException $e) {
            throw new DatabaseException(
                message: "Error: não foi possivel buscar o usuario de id {$userId}",
                statusCode: 500,
                previous: $e,
            );
        }
    }

    public function saveUser(UserModel $user): void
    {
        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (ORMException $e) {
            throw new DatabaseException(
                message: "Error: não foi persistir o usuario no database, {$user->getEmail()}",
                statusCode: 500,
                previous: $e,
            );
        }

    }

    public function updateUser(int $id, array $data)
    {
        try {
            $user = $this->getUserById($id);

            foreach ($data as $propertie => $value) {
                $user->$propertie = $value;
            }

            $this->entityManager->flush();
        } catch (ORMException $e) {
            throw new DatabaseException(
                message: "Error: não foi possivel atualizar o usuario com id, {$user->getId()}",
                statusCode: 500,
                previous: $e,
            );
        }
    }
}