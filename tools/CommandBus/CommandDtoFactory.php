<?php

namespace Tools\CommandBus;

use BackedEnum;
use DateTime;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use ReflectionParameter;
use RuntimeException;

use ValueError;

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

                if (enum_exists($typeName)) {
                    if (is_subclass_of($typeName, BackedEnum::class)) {
                        try {
                            $args[] = $typeName::from($value); // string/int → enum
                        } catch (ValueError $e) {
                            throw new RuntimeException("Invalid value for enum '{$name}': '{$value}'");
                        }
                        continue;
                    }

                    throw new RuntimeException("Unsupported enum type for '{$name}'");
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

//                if (!self::validatePrimitiveType($value, $typeName)) {
//                    throw new RuntimeException("Invalid type for parameter '{$name}': expected {$typeName}, got " . gettype($value));
//                }
//
//                $args[] = $value;

                $args[] = self::castPrimitiveType($value, $typeName);
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

    private static function castPrimitiveType(mixed $value, string $expectedType): mixed
    {
        return match ($expectedType) {
            'int' => is_numeric($value) ? (int)$value : throw new RuntimeException("Expected int, got " . gettype($value)),
            'float' => is_numeric($value) ? (float)$value : throw new RuntimeException("Expected float, got " . gettype($value)),
            'string' => is_scalar($value) || $value === null ? (string)$value : throw new RuntimeException("Expected string, got " . gettype($value)),
            'bool' => match (true) {
                is_bool($value) => $value,
                is_numeric($value) => (bool)$value,
                is_string($value) => match (strtolower($value)) {
                    '1', 'true', 'yes', 'on' => true,
                    '0', 'false', 'no', 'off' => false,
                    default => throw new RuntimeException("Cannot cast '{$value}' to bool")
                },
                default => throw new RuntimeException("Expected bool, got " . gettype($value))
            },
            default => throw new RuntimeException("Unknown primitive type: {$expectedType}"),
        };
    }
}
