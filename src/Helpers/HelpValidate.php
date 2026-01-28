<?php

declare(strict_types=1);

namespace App\Helpers;

use Respect\Validation\Exceptions\NestedValidationException;

class HelpValidate
{
    public static function validate(
        array $data,
        array $rules,
        array $templates = [],
    ): array {
        $erros = [];

        foreach ($rules as $field => $rule) {
            try {
                $rule->assert($data[$field] ?? null);
            } catch (NestedValidationException $e) {
                $erros[$field] = $e->getFullMessage();
            }
        }

        return $erros;
    }
}
