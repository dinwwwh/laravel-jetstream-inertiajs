<?php

namespace App\TypeScriptTransformers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Casts\AsEncryptedArrayObject;
use Illuminate\Database\Eloquent\Casts\AsEncryptedCollection;
use ReflectionClass;
use ReflectionMethod;
use ReflectionUnionType;
use Spatie\TypeScriptTransformer\Structures\TransformedType;
use Spatie\TypeScriptTransformer\Transformers\Transformer;
use Str;

abstract class BaseTransformer implements Transformer
{
    /**
     * Use for determine whether a field should transform or not.
     *
     * @var string[]
     */
    protected array $transformedFields = [];

    /**
     * Handle transform a class.
     */
    abstract public function transform(ReflectionClass $class, string $name): ?TransformedType;

    /**
     * get return typescript of a php method.
     */
    public static function getReturnedTypeScript(ReflectionMethod $method): string
    {
        $types = $method->getReturnType() instanceof ReflectionUnionType
            ? $method->getReturnType()->getTypes()
            : (string) $method->getReturnType();

        if (\is_string($types) && str_contains($types, '?')) {
            $types = [
                str_replace('?', '', $types),
                'null',
            ];
        }

        return collect($types)
            ->map(fn (string $type) => static::getTypeScript($type))
            ->join(' | ')
        ;
    }

    /**
     * Transform a php type to TypeScript.
     */
    public static function getTypeScript(string $phpType): string
    {
        // For laravel caster types to php type
        $phpType = match ($phpType) {
            'json', AsArrayObject::class, AsCollection::class, 'encrypted:object' => 'object',
            AsEncryptedCollection::class, AsEncryptedArrayObject::class => 'object',
            'date', 'datetime', 'timestamp' => Carbon::class,
            'double' => 'float',
            'encrypted' => 'string',
            'collection', 'encrypted:array', 'encrypted:collection' => 'array',
            default => $phpType,
        };

        $typescript = match ($phpType) {
            'int' => 'number',
            'float' => 'number',
            'string' => 'string',
            'array' => 'Array<any>',
            'object' => 'any',
            'null' => 'null',
            'bool' => 'boolean',
            default => null,
        };

        if (null === $typescript) {
            $typescript = static::getDefaultTypeScript($phpType);

            if (Str::startsWith($phpType, 'App\\')) {
                return Str::replace('\\', '.', $phpType);
            }
        }

        return '' === $typescript ? 'any' : $typescript;
    }

    /**
     * Get default alternative typescript in config.
     */
    public static function getDefaultTypeScript(string $phpType): string
    {
        return config('typescript-transformer.default_type_replacements')[$phpType] ?? $phpType;
    }

    /**
     * Determine whether a filed of typescript should be transformed or not.
     */
    public function shouldTransformField(string $field): bool
    {
        if (\in_array($field, $this->transformedFields, true)) {
            $should = false;
        } else {
            $this->transformedFields[] = $field;
            $should = true;
        }

        return $should;
    }
}
