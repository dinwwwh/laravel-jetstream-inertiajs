<?php

namespace App\TypeScriptTransformers;

use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Types\Types;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use ReflectionClass;
use ReflectionFunction;
use ReflectionMethod;
use Spatie\TypeScriptTransformer\Structures\TransformedType;
use Str;
use Throwable;

class ModelTransformer extends BaseTransformer
{
    protected Model $model;
    protected Collection $columns;
    protected Collection $methods;

    public function transform(ReflectionClass $class, string $name): ?TransformedType
    {
        try {
            $this->model = new $class->name();
        } catch (\Throwable $e) {
            return null;
        }

        $this->columns = collect(
            $this->model->getConnection()
                ->getDoctrineSchemaManager()
                ->listTableColumns($this->model->getConnection()->getTablePrefix().$this->model->getTable())
        );

        $this->methods = collect($class->getMethods(ReflectionMethod::IS_PUBLIC))
            ->reject(fn (ReflectionMethod $method) => $method->isStatic())
            ->reject(fn (ReflectionMethod $method) => $method->getNumberOfParameters())
        ;

        return TransformedType::create(
            $class,
            $name,
            '{'
            .collect([ // Order is important
                $this->getAccessors(),
                $this->getNewAccessors(),
                $this->getProperties(),
                $this->getRelations(),
                $this->getManyRelations(),
            ])
                ->filter(fn (string $part) => !empty($part))
                ->join(\PHP_EOL.'        ')
            .'}',
        );
    }

    /** Transform database properties to typescript */
    protected function getProperties(): string
    {
        return $this->columns
            ->filter(fn (Column $column) => $this->shouldTransformField($column->getName()))
            ->map(function (Column $column): string {
                $propertyType = $this->model->getCasts()[$column->getName()] ?? null;

                if (\in_array($column->getName(), ['created_at', 'updated_at'], true)) {
                    $propertyType = 'datetime';
                }

                $propertyType = null === $propertyType
                ? $this->getPropertyType($column->getType()->getName())
                : $this->getTypeScript($propertyType);

                $definition = "'{$column->getName()}' : {$propertyType}";

                if (!$column->getNotnull()) {
                    $definition .= ' | null';
                }

                return $definition;
            })
            ->join(\PHP_EOL.'        ')
        ;
    }

    /** Transform accessors to typescript */
    protected function getAccessors(): string
    {
        return $this->methods
            ->filter(fn (ReflectionMethod $method) => Str::startsWith($method->getName(), 'get') && Str::endsWith($method->getName(), 'Attribute'))
            ->mapWithKeys(function (ReflectionMethod $method) {
                $property = (string) Str::of($method->getName())
                    ->between('get', 'Attribute')
                    ->snake()
                ;

                return [$property => $method];
            })
            ->filter(fn (ReflectionMethod $method, string $property) => $this->shouldTransformField($property))
            ->map(function (ReflectionMethod $method, string $property) {
                if ($this->isReadonlyAccessor($method)) {
                    return "readonly '{$property}' : {$this->getReturnedTypeScript($method)}";
                }

                return "'{$property}' : {$this->getReturnedTypeScript($method)}";
            })
            ->join(\PHP_EOL.'        ')
        ;
    }

    /** Transform new accessors to typescript */
    protected function getNewAccessors(): string
    {
        return $this->methods
            ->filter(fn (ReflectionMethod $method) => Attribute::class === $method->getReturnType()?->getName())
            ->mapWithKeys(fn (ReflectionMethod $method) => [$method->getName() => $method])
            ->filter(fn (ReflectionMethod $method, string $property) => $this->shouldTransformField($property))

            // Filter out methods that are set only
            ->filter(fn (ReflectionMethod $method, string $property) => $method->invoke($this->model)->get)
            ->map(function (ReflectionMethod $method, string $property) {
                $property = Str::snake($property);
                $setClosure = new ReflectionFunction($method->invoke($this->model)->get);
                if ($method->invoke($this->model)->set) {
                    return "'{$property}' : {$this->getReturnedTypeScript($setClosure)}";
                }

                return "readonly '{$property}' : {$this->getReturnedTypeScript($setClosure)}";
            })
            ->join(\PHP_EOL.'        ')
        ;
    }

    /** Transform relations to typescript */
    protected function getRelations(): string
    {
        return $this->getRelationMethods()
            ->filter(fn (ReflectionMethod $method) => $this->shouldTransformField($method->getName()))
            ->map(fn (ReflectionMethod $method) => "'{$method->getName()}' : {$this->getRelationType($method)}")
            ->join(\PHP_EOL.'        ')
        ;
    }

    /** Transform special relation properties to typescript */
    protected function getManyRelations(): string
    {
        return $this->getRelationMethods()
            ->filter(fn (ReflectionMethod $method) => $this->isManyRelation($method))
            ->filter(fn (ReflectionMethod $method) => $this->shouldTransformField($method->getName().'_count'))
            ->map(function (ReflectionMethod $method) {
                $snackMethodName = Str::snake($method->getName());

                return "readonly '{$snackMethodName}_count' : number";
            })
            ->join(\PHP_EOL.'        ')
        ;
    }

    /** Transform database type to typescript */
    protected function getPropertyType(string $type): string
    {
        return match ($type) {
            Types::ARRAY => 'Array<any>',
            Types::ASCII_STRING => 'string',
            Types::BIGINT => 'number',
            Types::BINARY => 'string',
            Types::BLOB => 'string',
            Types::BOOLEAN => 'boolean',
            Types::DATE_MUTABLE => 'string',
            Types::DATE_IMMUTABLE => 'string',
            Types::DATEINTERVAL => 'string',
            Types::DATETIME_MUTABLE => 'string',
            Types::DATETIME_IMMUTABLE => 'string',
            Types::DATETIMETZ_MUTABLE => 'string',
            Types::DATETIMETZ_IMMUTABLE => 'string',
            Types::DECIMAL => 'number',
            Types::FLOAT => 'number',
            Types::GUID => 'string',
            Types::INTEGER => 'number',
            Types::JSON => 'Array<any>',
            Types::OBJECT => 'any',
            Types::SIMPLE_ARRAY => 'Array<any>',
            Types::SMALLINT => 'number',
            Types::STRING => 'string',
            Types::TEXT => 'string',
            Types::TIME_MUTABLE => 'number',
            Types::TIME_IMMUTABLE => 'number',
            default => 'any',
        };
    }

    protected function getRelationMethods(): Collection
    {
        return $this->methods
            ->filter(function (ReflectionMethod $method) {
                try {
                    return $method->invoke($this->model) instanceof Relation;
                } catch (Throwable) {
                    return false;
                }
            })
        ;
    }

    protected function getRelationType(ReflectionMethod $method): string
    {
        $relationReturn = $method->invoke($this->model);
        $related = str_replace('\\', '.', \get_class($relationReturn->getRelated()));

        if (!Str::startsWith($related, 'App\\')) {
            return 'any';
        }

        if ($this->isManyRelation($method)) {
            return "Array<{$related}>";
        }

        if ($this->isOneRelation($method)) {
            return $related;
        }

        return 'any';
    }

    protected function isManyRelation(ReflectionMethod $method): bool
    {
        $relationType = \get_class($method->invoke($this->model));

        return \in_array(
            $relationType,
            [
                HasMany::class,
                BelongsToMany::class,
                HasManyThrough::class,
                MorphMany::class,
                MorphToMany::class,
            ], true
        );
    }

    protected function isOneRelation(ReflectionMethod $method): bool
    {
        $relationType = \get_class($method->invoke($this->model));

        return \in_array(
            $relationType,
            [
                HasOne::class,
                BelongsTo::class,
                MorphOne::class,
                HasOneThrough::class,
            ], true
        );
    }

    protected function isReadonlyAccessor(ReflectionMethod $method): bool
    {
        $field = (string) Str::of($method->getName())
            ->between('get', 'Attribute')
            ->snake()
        ;

        if ($this->columns->contains(fn (Column $column) => $column->getName() === $field)) {
            return false;
        }

        $setMethodName = (string) Str::of($method->getName())
            ->replaceFirst('get', 'set')
        ;

        return !$this->methods->contains(fn (ReflectionMethod $method) => $method->getName() === $setMethodName);
    }
}
