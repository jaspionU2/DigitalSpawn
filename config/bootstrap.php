<?php

use Dotenv\Dotenv;

$dotenv = Dotenv::createMutable(dirname(path: __DIR__));
$dotenv->load();

define('ROOT_DIR_PATH', dirname(__DIR__));
define('APP_DIR_PATH', ROOT_DIR_PATH . '/src');
define('VAR_DIR_PATH', ROOT_DIR_PATH . '/var');
define('CONF_DIR_PATH', ROOT_DIR_PATH . '/config');
define('PUBLIC_DIR_PATH', ROOT_DIR_PATH . '/public');
define('CACHE_DIR_PATH', VAR_DIR_PATH . '/cache');
define('LOG_DIR_PATH', VAR_DIR_PATH . '/log');

// Configurações do ambiente da aplicação
define('APP_ENV', $_ENV['APP_ENV'] ?? 'prod');
define('APP_DEBUG', strtolower(APP_ENV) === 'dev');

// Configurações de timezone e locale
date_default_timezone_set($_ENV['APP_TIMEZONE'] ?? 'UTC');
setlocale(LC_ALL, $_ENV['APP_LOCALE'] ?? 'pt_BR.UTF-8');

// Configurações de erro baseadas no ambiente
// if (APP_DEBUG) {
//     error_reporting(E_ALL);
//     ini_set('display_errors', '1');
//     ini_set('log_errors', '1');
//     ini_set('error_log', LOG_DIR_PATH . '/php_errors.log');
// } else {
//     error_reporting(E_ERROR | E_WARNING | E_PARSE);
//     ini_set('display_errors', '0');
//     ini_set('log_errors', '1');
//     ini_set('error_log', LOG_DIR_PATH . '/php_errors.log');
// }

// Configurações de sessão
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_secure', $_ENV['APP_SECURE'] ?? '0');
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.use_strict_mode', '0');
ini_set('session.gc_maxlifetime', $_ENV['SESSION_LIFETIME'] ?? '604800');
ini_set('session.gc_probability', 10);
ini_set('session.gc_divisor', 100);
ini_set('session.save_path', VAR_DIR_PATH . '/session');


// Verificação de diretórios necessários
$requiredDirs = [
    VAR_DIR_PATH,
    CACHE_DIR_PATH,
    LOG_DIR_PATH,
    CACHE_DIR_PATH . '/twig',
    VAR_DIR_PATH . '/session'
];

foreach ($requiredDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}
