<?php declare(strict_types=1);

namespace App\Repository;

use App\Factories\EntityManagerFactory;
use App\Models\UserModel;
use Doctrine\ORM\EntityManager;

class UserRepository
{
    private EntityManager $entityManager;

    public function __construct(){
        $this->entityManager = EntityManagerFactory::getInstance();
    }

    public function save(UserModel $user) : void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        echo "Created Product with ID " . $user->getId() . "\n";
    }
}