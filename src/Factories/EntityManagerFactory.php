<?php

declare(strict_types=1);

namespace App\Factories;

use Doctrine\DBAL\Tools\DsnParser;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\EntityManagerInterface;
use App\Factories\DbalConnectionFactory;

class EntityManagerFactory
{
    /**
     * Cria e retorna uma instância configurada do EntityManager do Doctrine.
     *
     * Este método é responsável por configurar o ORM utilizando metadados baseados
     * em atributos PHP 8+ e estabelecer a conexão com o banco de dados através
     * do DBAL. O EntityManager retornado pode ser utilizado para gerenciar
     * operações de persistência de entidades.
     *
     * @param DbalConnectionFactory $dbalConn  Factory responsável por criar a conexão DBAL.
     * @param DsnParser             $dsnParser Parser para converter a URL de conexão em parâmetros.
     * @param string                $dbUrl     URL de conexão com o banco de dados.
     *                                         Formato: 'mysql://user:password@host:port/database'
     *
     * @return EntityManagerInterface Instância do EntityManager configurada e pronta para uso.
     *
     * @throws \Doctrine\ORM\Exception\ORMException Caso ocorra erro na configuração do ORM.
     * @throws \Doctrine\DBAL\Exception             Caso ocorra erro na conexão com o banco.
     */
    public function getEntityManager(
        DbalConnectionFactory $dbalConn,
        DsnParser $dsnParser,
        string $dbUrl
    ): EntityManagerInterface {
        $config = ORMSetup::createAttributeMetadataConfig(
            paths: [__DIR__ . '/../Models'],
            isDevMode: true
        );
        $config->enableNativeLazyObjects(true);

        $conn = $dbalConn->getDbalConnection(
            dbUrl: $dbUrl,
            dsnParser: $dsnParser
        );

        return new EntityManager($conn, $config);
    }
}
