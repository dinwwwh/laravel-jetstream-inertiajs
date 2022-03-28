<?php

namespace App\TypeScriptTransformers;

use ReflectionClass;
use Spatie\TypeScriptTransformer\Structures\TransformedType;

class EnumTransformer extends BaseTransformer
{
    public function transform(ReflectionClass $class, string $name): ?TransformedType
    {
        if (!$class->isEnum()) {
            return null;
        }

        $instance = $class->name;

        $transformed = '';
        foreach ($instance::cases() as $case) {
            $value = $case->value;

            if (\is_string($value)) {
                $transformed .= "'{$value}' | ";
                continue;
            }

            if (is_numeric($value)) {
                $transformed .= "{$value} | ";
                continue;
            }

            return null;
        }

        return TransformedType::create(
            $class,
            $name,
            rtrim($transformed, ' |'),
        );
    }
}
