<?php

declare(strict_types=1);

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidFileException;
use Dotenv\Exception\InvalidPathException;
use Doctrine\DBAL\Tools\DsnParser;
use App\Factories\DbalConnectionFactory;
use App\Factories\EntityManagerFactory;

$manualConfig = [
    'driver'   => 'pdo_sqlite',
    'path' => dirname(__DIR__) . '/teste.db'
];

$dbParams = null;

$dotEnv = Dotenv::createImmutable(dirname(__DIR__));
// var_dump($dotEnv);
try {
    $dotEnv->load();
    $dbParams = $_ENV['DB_URL'] ?? null;
} catch (InvalidFileException | InvalidPathException $e) {

}

if (empty($dbParams)) {
    $dbParams = $manualConfig;
}
var_dump($dbParams);

$dsnParser = new DsnParser();

$entityManagerFactory = new EntityManagerFactory;
$dbalConnectionFactory = new DbalConnectionFactory;

$entityManager = $entityManagerFactory->getEntityManager(
    dbalConn: $dbalConnectionFactory,
    dsnParser: $dsnParser,
    params: $dbParams
);
