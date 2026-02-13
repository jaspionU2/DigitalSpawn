<?php

declare(strict_types=1);

namespace App\Helpers;

use Respect\Validation\Exceptions\NestedValidationException;

/**
 * Classe utilitária para validação de dados utilizando Respect\Validation.
 */
class HelpValidate
{
    /**
     * Valida dados conforme regras especificadas.
     *
     * @param array $data Dados a serem validados.
     * @param array $rules Regras de validação (Respect\Validation).
     * @param array $templates Templates de mensagens de erro.
     * @param bool $safeMode Se false, lança exceção ao encontrar erro.
     * @return array Lista de erros encontrados, indexados pelo campo.
     * @throws NestedValidationException Caso safeMode seja false e haja erro de validação.
     */
    public static function validate(
        array $data,
        array $rules,
        array $templates = [],
        bool $safeMode = true,
    ): array {
        $erros = [];

        foreach ($rules as $field => $rule) {
            try {
                $rule->assert($data[$field] ?? null);
            } catch (NestedValidationException $e) {
                $erros[$field] = $e->getFullMessage();

                if (!$safeMode) {
                    throw $e;
                }
            }
        }

        return $erros;
    }
}
