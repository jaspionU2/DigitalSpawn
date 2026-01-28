<?php

declare(strict_types=1);

namespace App\Models;

use function in_array;

use ReflectionClass;
use ReflectionException;

use function ucwords;

/**
 * Classe base para todas as Models do sistema.
 *
 * Fornece funcionalidades comuns como atribuição em massa,
 * métodos mágicos para getters/setters e proteção de atributos.
 */
class BaseModel
{
    /**
     * Lista de atributos que podem ser preenchidos via atribuição em massa.
     *
     * Defina os nomes dos atributos que são seguros para serem
     * preenchidos automaticamente pelo método create().
     *
     * @var array<int, string>
     */
    protected array $fillable = [];

    /**
     * Lista de atributos protegidos contra atribuição em massa.
     *
     * Atributos listados aqui não poderão ser preenchidos
     * automaticamente, mesmo que estejam em $fillable.
     *
     * @var array<int, string>
     */
    protected array $guarded = ['*'];

    /**
     * Armazena os valores dos atributos da model.
     *
     * Array associativo onde a chave é o nome do atributo
     * e o valor é o dado armazenado.
     *
     * @var array<string, mixed>
     */
    protected array $attributes = [];

    /**
     * Lista de atributos que devem ser ocultos ao converter para array.
     *
     * Atributos listados aqui não aparecerão no resultado de toArray(),
     * mesmo que sejam propriedades públicas ou protegidas da classe filha.
     * Útil para ocultar dados sensíveis como senhas, tokens, etc.
     *
     * @var array<int, string>
     */
    protected array $hidden = [];

    /**
     * Método mágico para definir o valor de um atributo.
     *
     * Quando um atributo é definido (ex: $model->nome = 'valor'),
     * este método verifica se existe um setter personalizado (setNome)
     * e o executa. O valor é sempre armazenado em $attributes.
     *
     * @param string $name  nome do atributo a ser definido
     * @param mixed  $value valor a ser atribuído
     */
    public function __set($name, $value): void
    {
        $reflectionClass = new ReflectionClass($this::class);

        $methodName = 'set' . ucwords($name);
        if ($reflectionClass->hasMethod($methodName)) {
            try {
                $method = $reflectionClass->getMethod($methodName);
                $method->invoke(
                    $reflectionClass->newInstance(),
                    $value,
                );
            } catch (ReflectionException) {
            }
        }

        $this->attributes[$name] = $value;
    }

    /**
     * Método mágico para obter o valor de um atributo.
     *
     * Quando um atributo é acessado (ex: $model->nome),
     * este método verifica se existe um getter personalizado (getNome)
     * e o executa. Caso contrário, retorna o valor de $attributes.
     *
     * @param string $name nome do atributo a ser obtido
     *
     * @return mixed valor do atributo ou null se não existir
     */
    public function __get($name): mixed
    {
        $reflectionClass = new ReflectionClass($this::class);
        $methodName = 'get' . ucwords($name);
        if ($reflectionClass->hasMethod($methodName)) {
            try {
                $method = $reflectionClass->getMethod($methodName);

                return $method->invoke(
                    $reflectionClass->newInstance(),
                );
            } catch (ReflectionException) {
            }
        }

        return $this->attributes[$name] ?? null;
    }

    /**
     * Cria uma nova instância da model com os dados fornecidos.
     *
     * Percorre o array de dados e preenche apenas os atributos
     * que estão definidos em $fillable (atribuição em massa segura).
     * Utiliza late static binding para criar a instância correta
     * quando chamado a partir de classes filhas.
     *
     * @param array<string, mixed> $dados array associativo com os dados a serem preenchidos
     *
     * @return static nova instância da model preenchida com os dados permitidos
     */
    public static function create(array $dados): static
    {
        $instance = new static();
        foreach ($dados as $key => $value) {
            if ($instance->isFillable($key)) {
                $instance->{$key} = $value;
            }
        }

        return $instance;
    }

    /**
     * Converte a model em um array associativo com seus dados.
     *
     * Utiliza Reflection para descobrir todas as propriedades da classe filha
     * (excluindo as propriedades da classe pai Model) e retorna apenas aquelas
     * que não estão marcadas como ocultas em $hidden.
     *
     * Este método é útil para serialização JSON, envio em respostas HTTP
     * e comparação de dados.
     *
     * @return array<string, mixed> array associativo contendo as propriedades da model
     */
    public function toArray(): array
    {
        $reflectionClass = new ReflectionClass($this);
        $properties = $reflectionClass->getProperties();

        $parentClassReflection = $reflectionClass->getParentClass();
        $parentProperties = $parentClassReflection->getProperties();

        $parentPropertieName = [];
        foreach ($parentProperties as $propertie) {
            $parentPropertieName[] = $propertie->getName();
        }

        $result = [];
        foreach ($properties as $propertie) {
            $propertieName = $propertie->getName();
            if (!in_array($propertieName, $parentPropertieName) && !$this->isHidden($propertieName)) {
                $result[$propertieName] = $propertie->getValue($this);
            }
        }

        return $result;
    }

    /**
     * Verifica se um atributo pode ser preenchido via atribuição em massa.
     *
     * Consulta o array $fillable para determinar se o atributo
     * informado está na lista de atributos permitidos.
     *
     * @param string $value nome do atributo a ser verificado
     *
     * @return bool true se o atributo pode ser preenchido, false caso contrário
     */
    protected function isFillable(string $value): bool
    {
        if (!in_array('*', $this->guarded) && empty($this->fillable)) {
            $this->guarded[] = '*';
        }

        if (in_array('*', $this->guarded)) {
            return in_array($value, $this->fillable);
        }

        if (in_array($value, $this->fillable)) {
            return true;
        }

        return !in_array($value, $this->guarded);
    }

    /**
     * Verifica se um atributo está na lista de propriedades ocultas.
     *
     * Consulta o array $hidden para determinar se o atributo
     * informado deve ser excluído da serialização.
     *
     * @param string $value nome do atributo a ser verificado
     *
     * @return bool true se o atributo deve ser oculto, false caso contrário
     */
    protected function isHidden(string $value): bool
    {
        if (in_array($value, $this->hidden)) {
            return true;
        }

        return false;
    }
}
