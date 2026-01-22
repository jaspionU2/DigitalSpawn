<?php declare(strict_types=1);

namespace App\Factories;

use Doctrine\DBAL\Tools\DsnParser;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\EntityManagerInterface;
use App\Factories\DbalConnectionFactory;


/**
 * Classe responsável pela criação e gerenciamento do EntityManager do Doctrine ORM.
 * 
 * Implementa o padrão Singleton para garantir uma única instância do EntityManager
 * durante todo o ciclo de vida da aplicação.
 */
class EntityManagerFactory
{
    /** @var EntityManagerInterface|null Instância única do EntityManager */
    protected static ?EntityManagerInterface $entityManager = null;

    /** @var array|null Configurações personalizadas do banco de dados */
    protected static ?array $config = null;

    /**
     * Obtém a instância do EntityManager.
     * 
     * Retorna a instância existente do EntityManager ou cria uma nova
     * caso ainda não exista (padrão Singleton).
     *
     * @return EntityManagerInterface Instância do EntityManager
     */
    public static function getInstance(): EntityManagerInterface
    {
        if (is_null(self::$entityManager)) {
            return self::createEntityManager();
        }

        return self::$entityManager;
    }

    /**
     * Define as configurações personalizadas do banco de dados.
     * 
     * Permite sobrescrever as configurações padrão do banco de dados
     * antes da criação do EntityManager.
     *
     * @param array $params Array com os parâmetros de conexão do banco de dados
     * @return void
     */
    public static function configure(
        array $params
    ): void {
        self::$config = $params;
    }
    
    /**
     * Cria e configura uma nova instância do EntityManager.
     * 
     * Responsável por:
     * - Configurar os metadados das entidades a partir dos atributos PHP
     * - Habilitar objetos lazy nativos
     * - Estabelecer a conexão DBAL com o banco de dados
     * - Instanciar o EntityManager com as configurações definidas
     *
     * @return EntityManagerInterface Nova instância configurada do EntityManager
     */
    protected static function createEntityManager(): EntityManagerInterface
    {
        $pathDir = [dirname(__DIR__, 1) . '/Models'];
        $ormConfig = ORMSetup::createAttributeMetadataConfig(
            paths: $pathDir,
            isDevMode: true
        );
        $ormConfig->enableNativeLazyObjects(true);

        $dsnParser = new DsnParser([
            'mysql' => 'pdo_mysql'
        ]);

        $dbParams = null;

        if (!is_null(self::$config)) {
            $dbParams = self::$config;
        } else {
            $dbParams = DbalConnectionFactory::loadDatabaseConfig();
        }

        $conn = DbalConnectionFactory::getDbalConnection(
            params: $dbParams,
            dsnParser: $dsnParser
        );

        self::$entityManager = new EntityManager(
            conn: $conn,
            config: $ormConfig
        );

        return self::$entityManager;
    }
}
