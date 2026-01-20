<?php declare(strict_types=1);

namespace App\Factories;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\DsnParser;
use Dotenv\Dotenv;
use Dotenv\Exception\InvalidFileException;
use Dotenv\Exception\InvalidPathException;

class DbalConnectionFactory
{
    /**
     * @param string|array $params Recebe uma url de conexão com banco de dados. ex.: 'mysql://username:password@host:$port/$db_name'
     * @param DsnParser|null $dsnParser Recebe uma nova instancia de DsnParser. A função esperar receber uma URL de conexão, portanto no construtor da nova instancia, deve ser passado um array associativo, onde chave é o DSN scheme e valor é o DBAL driver.
     * 
     * @return \Doctrine\DBAL\Connection
     * @throws \Doctrine\DBAL\Exception\MalformedDsnException
     */
    public static function getDbalConnection(
        string|array $params, 
        DsnParser|null $dsnParser
    ) : Connection {
        $connectionParams = $params;
        if (is_string($params) && !is_null($dsnParser)) { 
            $connectionParams = $dsnParser->parse(dsn: $params);
        }
        return DriverManager::getConnection($connectionParams);
    }

    /**
     * Carrega as configurações de conexão com o banco de dados.
     * 
     * Prioridade de carregamento:
     * 1. Configurações definidas via método configure()
     * 2. Variável de ambiente DB_URL do arquivo .env
     * 3. Configuração padrão SQLite (fallback)
     *
     * @return array|string Array com parâmetros de conexão ou string DSN
     */
    public static function loadDatabaseConfig(?array $config = null): array|string
    {
        $pathDir = dirname(__DIR__, 2);
        try {
            $dotenv = Dotenv::createImmutable(paths: $pathDir);
            $dotenv->load();

            if (!empty($_ENV['DB_URL'])) {
                return $_ENV['DB_URL'];
            }
        } catch (InvalidFileException | InvalidPathException) {
            // Fall through to default SQLite configuration
        }

        return [
            'driver' => 'pdo_sqlite',
            'path'   => $pathDir . '/database.db'
        ];
    }
}
?>