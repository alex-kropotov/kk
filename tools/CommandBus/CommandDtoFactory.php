<?php

namespace Tools\CommandBus;

use DateTime;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use ReflectionParameter;
use RuntimeException;

use function PHPUnit\Framework\isNull;

class CommandDtoFactory
{
    /** @var array<class-string, ReflectionClass> */
    private static array $classCache = [];

    /**
     * @template T of CommandInterface
     * @param class-string<T> $commandClass
     * @param array $data
     * @return CommandInterface
     * @throws ReflectionException
     */
    public static function create(string $commandClass, ?array $data): CommandInterface
    {
        $data = $data ?? [];
        if (!is_subclass_of($commandClass, CommandInterface::class)) {
            throw new RuntimeException("{$commandClass} must implement CommandInterface");
        }

        $reflection = self::$classCache[$commandClass] ??= new ReflectionClass($commandClass);
        $constructor = $reflection->getConstructor();

        if (!$constructor) {
            return new $commandClass();
        }

        $args = [];

        foreach ($constructor->getParameters() as $param) {
            $name = $param->getName();
            $type = $param->getType();

            if (!array_key_exists($name, $data)) {
                if ($param->isDefaultValueAvailable()) {
                    $args[] = $param->getDefaultValue();
                    continue;
                }
                throw new RuntimeException("Missing value for parameter '{$name}'");
            }

            $value = $data[$name];

            if ($type instanceof ReflectionNamedType) {
                $typeName = $type->getName();
                $isNullable = $type->allowsNull();

                if ($value === null) {
                    if (!$isNullable) {
                        throw new RuntimeException("Parameter '{$name}' cannot be null");
                    }
                    $args[] = null;
                    continue;
                }

                if ($typeName === DateTime::class) {
                    try {
                        $args[] = new DateTime($value);
                    } catch (\Exception $e) {
                        throw new RuntimeException("Invalid date format for '{$name}': {$value}");
                    }
                    continue;
                }

                if (class_exists($typeName)) {
                    if (is_array($value)) {
                        if (self::isAssoc($value)) {
                            $args[] = self::create($typeName, $value);
                        } else {
                            $args[] = array_map(fn($item) => self::create($typeName, $item), $value);
                        }
                        continue;
                    } else {
                        throw new RuntimeException("Expected array for object parameter '{$name}'");
                    }
                }

                if (!self::validatePrimitiveType($value, $typeName)) {
                    throw new RuntimeException("Invalid type for parameter '{$name}': expected {$typeName}, got " . gettype($value));
                }

                $args[] = $value;
            } else {
                $args[] = $value;
            }
        }

        return $reflection->newInstanceArgs($args);
    }

    private static function isAssoc(array $array): bool
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }

    private static function validatePrimitiveType(mixed $value, string $expectedType): bool
    {
        return match ($expectedType) {
            'int' => is_int($value),
            'float' => is_float($value) || is_int($value),
            'string' => is_string($value),
            'bool' => is_bool($value),
            default => false,
        };
    }
}
