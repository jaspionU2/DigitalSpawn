<?php

declare(strict_types=1);

namespace App\Models;

class Model
{
    /**
     * @var array<int, string>
     */
    protected array $fillable = [];
    protected array $attributes = [];


    protected function isFillable(string $key) : bool
    {
        if (array_key_exists($key, $this->fillable)) {
            return true;
        }

        return false;
    }

    protected static function filter(array $dados) : array
    {
        return array_filter(
            array: $dados,
            callback: fn($key) => $this->isFillable($key),
            mode: ARRAY_FILTER_USE_KEY
        );
    }
}
