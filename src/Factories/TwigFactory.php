<?php

declare(strict_types=1);

namespace App\Factories;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidFileException;
use Dotenv\Exception\InvalidPathException;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\Loader\LoaderInterface;
use Twig\Extension\ExtensionInterface;

/**
 * Factory para criar e configurar instâncias do ambiente Twig.
 *
 * Responsável por inicializar o Twig com as configurações adequadas
 * baseadas nas variáveis de ambiente do projeto.
 */
class TwigFactory
{
    /**
     * @var array<array-key, string> Opções de configuração do ambiente Twig
     */
    protected static array $environmentOptions = [];

    private function __construct() {}

    /**
     * Cria um carregador de templates do Twig para os caminhos especificados.
     *
     * @param array $paths Array contendo os caminhos dos diretórios de templates
     * @return LoaderInterface Instância do carregador de templates
     */
    protected static function getLoader(
        array $paths
    ): LoaderInterface {
        return new FilesystemLoader($paths);
    }

    /**
     * Carrega as opções de configuração do ambiente Twig a partir de variáveis de ambiente, ou a partir de configurações ja setadas através do metodo 'setOption'. As 'option', setadas na classe tem prioridade sobre as variáveis de ambiente.
     *
     * @param string $pathToCache Caminho para o diretório de cache do Twig (padrão: '')
     * @return array Array contendo as opções de configuração (debug, cache, autoreload)
     */
    protected static function loadOption(string $pathToCache = ''): array
    {
        if (!empty(self::$environmentOptions)) return self::$environmentOptions;

        $pathRoot = dirname(__DIR__, 2);

        try {
            $dotenv = Dotenv::createImmutable($pathRoot);
            $dotenv->load();
        } catch (InvalidFileException | InvalidPathException) {
        }

        return self::$environmentOptions = [
            'debug' => strtolower($_ENV['APP_ENV'] ?? '') === 'dev',
            'cache' => $pathRoot . '/' . $_ENV['TWIG_CACHE_PATH'] ?? $pathToCache,
            'autoreload' => true
        ];
    }

    /**
     * Define manualmente as opções de configuração do ambiente Twig.
     *
     * @param array $options Array contendo as opções de configuração a serem aplicadas
     * @return void
     */
    public static function setOption(array $options): void
    {
        self::$environmentOptions = $options;
    }

    /**
     * Cria e retorna uma instância configurada do ambiente Twig.
     *
     * @param array $paths Array de caminhos para os diretórios de templates (padrão: '/src/Views')
     * @param ?array $options Opções de configuração customizadas (opcional)
     * @param ?string $pathToCache Caminho para o diretório de cache (opcional)
     * @param ?ExtensionInterface $extension Extensão customizada do Twig a ser adicionada (opcional)
     * @return Environment Instância configurada do ambiente Twig
     */
    public static function createTwigEnvironment(
        array $paths = [],
        ?array $options = null,
        ?string $pathToCache = null,
        ?ExtensionInterface $extension = null
    ): Environment {
        if (empty($paths)) {
            $projectRoot = dirname(__DIR__, 2);
            $paths = [
                $projectRoot . '/src/Views'
            ];
        }

        $loader = self::getLoader($paths);

        if (empty($pathToCache)) {
            $projectRoot = dirname(__DIR__, 2);
            $pathToCache = $projectRoot . '/var/cache/twig';
        }

        $twig = new Environment($loader, $options ?? self::loadOption($pathToCache));

        if (strtolower($_ENV['APP_ENV'] ?? '') === 'dev') {
            $twig->addExtension(new DebugExtension());
        }

        if (!is_null($extension)) {
            $twig->addExtension($extension);
        }

        return $twig;
    }
}
