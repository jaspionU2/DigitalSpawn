<?php

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Dotenv\Dotenv;
use Doctrine\DBAL\Tools\DsnParser;

require_once "vendor/autoload.php";

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$dsnParser = new DsnParser([
    'mysql' => 'pdo_mysql'
]);

$connectionParams = $dsnParser->parse($_ENV['DB_URL']);

$conn = DriverManager::getConnection($connectionParams);