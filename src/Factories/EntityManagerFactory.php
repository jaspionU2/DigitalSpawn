<?php

declare(strict_types=1);

namespace App\Factories;

use Doctrine\DBAL\Tools\DsnParser;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\EntityManagerInterface;
use App\Factories\DbalConnectionFactory;
use Dotenv\Dotenv;
use Dotenv\Exception\InvalidFileException;
use Dotenv\Exception\InvalidPathException;

class EntityManagerFactory
{
    protected static ?EntityManagerInterface $entityManager = null;
    protected static ?array $config = null;

    public static function configure(
        array $params
    ) : void {
        self::$config = $params;
    }

    public function loadDatabaseConfig() : array|null
    {
        if (!is_null(self::$config)) {
            return self::$config;
        }

        $pathDir = dirname(__DIR__, 2);
        try {
            $dotenv = Dotenv::createImmutable(paths: $pathDir);
            $dotenv->load();
            
            if (!empty($_ENV['DB_URL'])) {
                return $_ENV['DB_URL'];
            }
        } catch (InvalidFileException | InvalidPathException $e) {

        }

        return [
            'driver' => 'pdo_sqlite',
            'path'   => $pathDir . '/database.db'
        ];

    }
}
