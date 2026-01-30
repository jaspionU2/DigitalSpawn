<?php

declare(strict_types=1);

namespace App\Repository;

use App\Factories\EntityManagerFactory;
use App\Models\EmailTokenModel;
use Doctrine\ORM\EntityManager;

class EmailTokenRepository
{
    protected EntityManager $entityManager;

    public function __construct()
    {
        $this->entityManager = EntityManagerFactory::getInstance();
    }

    public function getToken(string $token)
    {
        $tokenEntity = $this->entityManager->getRepository(EmailTokenModel::class);

        return $tokenEntity->findOneBy([
            'token' => $token,
        ]);
    }

    public function saveToken(EmailTokenModel $token): void
    {
        $this->entityManager->persist($token);
        $this->entityManager->flush();
    }

    public function updateToken(): void {}
}
