<?php declare(strict_types=1);

namespace App\Factories;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\DsnParser;

class DbalConnectionFactory
{
    /**
     * @param string|array $params Recebe uma url de conexão com banco de dados. ex.: 'mysql://username:password@host:$port/$db_name'
     * @param DsnParser|null $dsnParser Recebe uma nova instancia de DsnParser. A função esperar receber uma URL de conexão, portanto no construtor da nova instancia, deve ser passado um array associativo, onde chave é o DSN scheme e valor é o DBAL driver.
     * 
     * @return \Doctrine\DBAL\Connection
     * @throws \Doctrine\DBAL\Exception\MalformedDsnException
     */
    public function getDbalConnection(
        string|array $params, 
        DsnParser|null $dsnParser
    ) : Connection {
        $connectionParams = $params;
        if (is_string($params) && !is_null($dsnParser)) { 
            $connectionParams = $dsnParser->parse(dsn: $params);
        }
        return DriverManager::getConnection($connectionParams);
    }
}
?>