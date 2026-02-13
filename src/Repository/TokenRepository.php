<?php

declare(strict_types=1);

namespace App\Repository;

use App\Exception\DatabaseException;
use App\Factories\EntityManagerFactory;
use App\Models\EmailTokenModel;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;

class TokenRepository
{
    protected EntityManager $entityManager;

    public function __construct()
    {
        $this->entityManager = EntityManagerFactory::getInstance();
    }

    public function getToken(string $token): ?EmailTokenModel
    {
        try {
            $tokenEntity = $this->entityManager->getRepository(EmailTokenModel::class);

            return $tokenEntity->findOneBy([
                'token' => $token,
            ]);
        } catch (ORMException $e) {
            $this->entityManager->close();
            throw new DatabaseException(
                message: "Error: não foi possivel obter o token informado: {$token}",
                statusCode: 500,
                previous: $e,
            );
        }
    }

    public function saveToken(EmailTokenModel $token): void
    {
        try {
            $this->entityManager->persist($token);
            $this->entityManager->flush();
        } catch (ORMException $e) {
            $this->entityManager->close();
            throw new DatabaseException(
                message: "Error: não foi possivel persistir o token no database: {$token->getToken()}",
                statusCode: 500,
                previous: $e,
            );
        }
    }

    public function updateToken(string $token, array $data): void
    {
        try {
            $token = $this->getToken($token);

            foreach ($data as $propertie => $value) {
                $token->$propertie = $value;
            }

            $this->entityManager->flush();
        } catch (ORMException $e) {
            $this->entityManager->close();
            throw new DatabaseException(
                message: "Error: não foi possivel atualizar o token, {$token->getToken()}",
                statusCode: 500,
                previous: $e,
            );
        }
    }
}