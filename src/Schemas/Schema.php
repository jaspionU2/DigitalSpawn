<?php

declare(strict_types=1);

namespace App\Schemas;

use App\Helpers\HelpValidate;
use ReflectionClass;

class Schema
{
    public function validate(array $data, array $template = [], $safeMode = true): array
    {
        $rules = $this->toArray();

        return HelpValidate::validate($data, $rules, $template, $safeMode);
    }

    protected function toArray(): array
    {
        $reflectionClass = new ReflectionClass($this);
        $reflectionProperties = $reflectionClass->getProperties();

        $objectDeserialazed = [];
        foreach ($reflectionProperties as $propertie) {
            $propertieName = $propertie->getName();
            $objectDeserialazed[$propertieName] = $propertie->getValue($this);
        }

        return $objectDeserialazed;
    }
}