<?php

declare(strict_types=1);

namespace Tools\Persist;

use DateTimeInterface;
use JsonSerializable;
use PDO;
use PDOException;
use RuntimeException;
use InvalidArgumentException;
use Psr\Container\ContainerInterface;


abstract class BaseClassData implements JsonSerializable
{
    /** @var array<string, string> Поля: имя поля объекта => тип поля базы данных */
    protected static array $map = [];

    /** @var array<string, bool> Nullable поля: имя поля => nullable */
    protected static array $nullable = [];

    /** @var array<string, mixed> Значения по умолчанию */
    protected static array $defaults = [];

    /** @var bool Флаг новой записи */
    protected bool $_isNew = true;

    /** @var ContainerInterface|null Контейнер зависимостей */
    protected static ?ContainerInterface $container = null;

    /** @var array Загруженные отношения */
    public array $loadedRelations = [];

    abstract protected static function table(): string;
    abstract protected static function primaryKey(): string;
    abstract protected function primaryKeyValue(): ?int;

    /**
     * Устанавливает контейнер зависимостей
     */
    public static function setContainer(ContainerInterface $container): void
    {
        static::$container = $container;
    }

    /**
     * Получает контейнер зависимостей
     */
    protected static function getContainer(): ContainerInterface
    {
        if (static::$container === null) {
            throw new RuntimeException('Container not set. Call BaseClassData::setContainer() first.');
        }
        return static::$container;
    }

    /**
     * Определяет отношения класса
     */
    public static function relations(): array
    {
        return [];
    }

    /**
     * Загружает отношение для объекта
     */
    public function loadRelation(string $relationName): void
    {
        $relations = static::relations();
        if (!isset($relations[$relationName])) {
            throw new RuntimeException("Relation {$relationName} not defined");
        }

        $relation = $relations[$relationName];
        $repoClass = $relation['relatedRepo'];
        $repo = new $repoClass(static::getPdo());

        switch ($relation['type']) {
            case 'one':
                $this->{$relationName} = $repo
                    ->where($relation['foreignKey'], $this->{$relation['localKey']})
                    ->first();
                break;

            case 'many':
                $this->{$relationName} = $repo
                    ->where($relation['foreignKey'], $this->{$relation['localKey']})
                    ->get();
                break;

            case 'many-to-many':
                $pivotRepoClass = $relation['pivotRepo'];
                $pivotRepo = new $pivotRepoClass(static::getPdo());

                $pivotItems = $pivotRepo
                    ->where($relation['pivotLocalKey'], $this->{$relation['localKey']})
                    ->get();

                if (empty($pivotItems)) {
                    $this->{$relationName} = [];
                    break;
                }

                $relatedIds = array_column($pivotItems, $relation['pivotForeignKey']);
                $this->{$relationName} = $repo
                    ->whereIn($relation['relatedKey'], $relatedIds)
                    ->get();
                break;

            default:
                throw new RuntimeException("Unknown relation type: {$relation['type']}");
        }

        $this->loadedRelations[$relationName] = true;
    }

    protected static function dbPrimaryKey(): string
    {
        return static::toDbField(static::primaryKey());
    }

    public static function fromArray(array $data): static
    {
        $args = [];

        foreach (static::$map as $property => $type) {
            $nullable = static::$nullable[$property] ?? true;
            $default = static::$defaults[$property] ?? null;

            // Получаем имя поля в БД
            $dbField = static::toDbField($property);
            // Ищем значение сначала по имени поля БД, затем по имени свойства
            $value = $data[$dbField] ?? $data[$property] ?? $default;

            $args[] = static::castInputValue($value, $type, $nullable);
        }

        return new static(...$args);
    }

    /**
     * Создает объект из записи таблицы
     */
    public static function loadFromRec(array $rec): ?static
    {
        if (!static::validateRecord($rec)) {
            return null;
        }

        $args = [];
        foreach (static::$map as $property => $type) {
            $dbField = static::toDbField($property);
            $args[] = static::castFromDb($rec[$dbField] ?? null, $type);
        }

        $obj = new static(...$args);
        $obj->_isNew = false;
        return $obj;
    }

    /**
     * Получает запись из базы по ID
     */
    public static function getById(int $id): ?static
    {
        try {
            $pdo = static::getPdo();
            $stmt = $pdo->prepare(sprintf(
                "SELECT * FROM %s WHERE %s = :id LIMIT 1",
                static::quoteIdentifier(static::table()),
                static::quoteIdentifier(static::dbPrimaryKey())
            ));
            $stmt->execute(['id' => $id]);
            $rec = $stmt->fetch(PDO::FETCH_ASSOC);

            return $rec ? static::loadFromRec($rec) : null;
        } catch (PDOException $e) {
            throw new RuntimeException("Database error in getById: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Получает несколько записей по ID
     */
    public static function getByIds(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        try {
            $pdo = static::getPdo();
            $placeholders = str_repeat('?,', count($ids) - 1) . '?';
            $stmt = $pdo->prepare(sprintf(
                "SELECT * FROM %s WHERE %s IN (%s)",
                static::quoteIdentifier(static::table()),
                static::quoteIdentifier(static::dbPrimaryKey()),
                $placeholders
            ));
            $stmt->execute($ids);

            $objects = [];
            while ($rec = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($obj = static::loadFromRec($rec)) {
                    $objects[] = $obj;
                }
            }

            return $objects;
        } catch (PDOException $e) {
            throw new RuntimeException("Database error in getByIds: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Проверяет наличие всех нужных полей в записи
     */
    protected static function validateRecord(array $rec): bool
    {
        foreach (static::$map as $property => $_) {
            if (!array_key_exists(static::toDbField($property), $rec)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Сохраняет объект (insert или update)
     */
    public function save(): static
    {
        $this->validate();
        $pdo = static::getPdo();

        try {
            $pdo->beginTransaction();

            if ($this->isSaved()) {
                $this->update();
            } else {
                $this->create();
            }

            $pdo->commit();
            return $this;
        } catch (PDOException $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Проверяет, сохранен ли объект в БД
     */
    public function isSaved(): bool
    {
        return $this->primaryKeyValue() > 0;
    }

    /**
     * Создает объект с пустыми/дефолтными значениями
     */
    public static function createEmpty(): static
    {
        $args = [];
        foreach (static::$map as $property => $type) {
            if (isset(static::$defaults[$property])) {
                $args[] = static::$defaults[$property];
            } else {
                $args[] = match ($type) {
                    'int', 'bigint' => 0,
                    'float', 'double' => 0.0,
                    'tinyint' => false,
                    default => null,
                };
            }
        }
        return new static(...$args);
    }

    /**
     * Вставляет новую запись
     */
    protected function create(): static
    {
        try {
            $pdo = static::getPdo();
            $fields = $placeholders = $values = [];

            foreach (static::$map as $property => $type) {
                if ($property === static::primaryKey()) {
                    continue;
                }

                $dbField = static::toDbField($property);
                $fields[] = static::quoteIdentifier($dbField);
                $placeholders[] = ":$property";
                $values[":$property"] = static::castToDb($this->$property, $type);
            }

            $sql = sprintf(
                "INSERT INTO %s (%s) VALUES (%s)",
                static::quoteIdentifier(static::table()),
                implode(", ", $fields),
                implode(", ", $placeholders)
            );

            $stmt = $pdo->prepare($sql);
            $stmt->execute($values);

            // Обновляем ID текущего объекта
            $idProperty = static::primaryKey();
            $this->$idProperty = (int)$pdo->lastInsertId();
            $this->_isNew = false;

            return $this;
        } catch (PDOException $e) {
            throw new RuntimeException("Database error in create: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Обновляет существующую запись
     */
    protected function update(): static
    {
        try {
            $pdo = static::getPdo();
            $assignments = $values = [];

            foreach (static::$map as $property => $type) {
                if ($property === static::primaryKey()) {
                    continue;
                }

                $dbField = static::toDbField($property);
                $assignments[] = static::quoteIdentifier($dbField) . " = :$property";
                $values[":$property"] = static::castToDb($this->$property, $type);
            }

            $values[":id"] = $this->primaryKeyValue();

            $sql = sprintf(
                "UPDATE %s SET %s WHERE %s = :id",
                static::quoteIdentifier(static::table()),
                implode(", ", $assignments),
                static::quoteIdentifier(static::dbPrimaryKey())
            );

            $stmt = $pdo->prepare($sql);
            $stmt->execute($values);

            return $this;
        } catch (PDOException $e) {
            throw new RuntimeException("Database error in update: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Удаляет запись из БД
     */
    public function delete(): void
    {
        if (!$this->isSaved()) {
            throw new RuntimeException("Cannot delete unsaved object");
        }

        try {
            $pdo = static::getPdo();
            $sql = sprintf(
                "DELETE FROM %s WHERE %s = :id",
                static::quoteIdentifier(static::table()),
                static::quoteIdentifier(static::dbPrimaryKey())
            );

            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $this->primaryKeyValue()]);

            // Сбрасываем ID
            $idProperty = static::primaryKey();
            $this->$idProperty = 0;
            $this->_isNew = true;
        } catch (PDOException $e) {
            throw new RuntimeException("Database error in delete: " . $e->getMessage(), 0, $e);
        }
    }

    protected static function castInputValue(mixed $value, string $type, bool $nullable): mixed
    {
        if ($value === null) {
            if ($nullable) {
                return null;
            }
            throw new InvalidArgumentException("Field of type $type cannot be null");
        }

        return match ($type) {
            'int' => (int)$value,
            'float', 'double' => (float)$value,
            'bool' => (bool)$value,
            'string' => (string)$value,
            'array' => (array)$value,
            'date', 'datetime' => static::convertToTimestamp($value),
            default => throw new InvalidArgumentException("Unsupported type: $type"),
        };
    }

    protected static function convertToTimestamp(mixed $value): int
    {
        if (is_int($value)) {
            return $value;
        }

        if ($value instanceof DateTimeInterface) {
            return $value->getTimestamp();
        }

        if (is_string($value)) {
            try {
                return (new \DateTimeImmutable($value))->getTimestamp();
            } catch (\Exception) {
                throw new InvalidArgumentException("Invalid date string: $value");
            }
        }

        throw new InvalidArgumentException("Cannot convert value to timestamp: " . var_export($value, true));
    }

    /**
     * Реализация JsonSerializable
     */
    public function jsonSerialize(): array
    {
        $data = [];
        foreach (static::$map as $property => $type) {
            $value = $this->{$property};

            // Преобразуем для JSON
            $data[$property] = match ($type) {
                'datetime', 'date' => $value ? date('Y-m-d H:i:s', (int)$value) : null,
                default => $value,
            };
        }
        return $data;
    }

    /**
     * Создает объект из JSON
     */
    public static function fromJson(string $json): ?static
    {
        $data = json_decode($json, true);
        if (!is_array($data)) {
            return null;
        }

        return static::fromArray($data);
    }

    /**
     * Валидация данных объекта
     */
    protected function validate(): void
    {
        foreach (static::$map as $property => $type) {
            $value = $this->{$property};

            if ($value === null && !$this->isNullable($property)) {
                throw new RuntimeException("Property {$property} cannot be null");
            }

            $this->validateProperty($property, $value, $type);
        }

        // Пользовательская валидация
        if (method_exists($this, 'customValidate')) {
            $this->customValidate();
        }
    }

    /**
     * Проверяет, может ли поле быть null
     */
    protected function isNullable(string $property): bool
    {
        return static::$nullable[$property] ?? false;
    }

    /**
     * Валидация отдельного свойства
     */
    protected function validateProperty(string $property, mixed $value, string $type): void
    {
        if ($value === null) {
            return;
        }

        $isValid = match ($type) {
            'int', 'bigint' => is_int($value),
            'float', 'double' => is_float($value) || is_int($value),
            'tinyint' => is_bool($value),
            'datetime', 'date' => is_int($value) || ($value instanceof DateTimeInterface),
            'string', 'text' => is_string($value),
            default => true,
        };

        if (!$isValid) {
            throw new RuntimeException(sprintf(
                "Invalid type for property %s: expected %s, got %s",
                $property,
                $type,
                gettype($value),
            ));
        }
    }

    /**
     * Преобразует имя свойства в имя поля БД
     */
    public static function toDbField(string $property): string
    {
        // Преобразуем camelCase в snake_case
        $snake = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $property));

        // Убираем возможные двойные подчеркивания
        return preg_replace('/_+/', '_', $snake);
    }

    /**
     * Преобразует значение из БД в PHP-тип
     */
    protected static function castFromDb(mixed $value, string $type): mixed
    {
        if ($value === null) {
            return null;
        }

        return match ($type) {
            'datetime', 'date' => $value ? strtotime($value) : null,
            'int', 'bigint' => (int)$value,
            'float', 'double' => (float)$value,
            'tinyint' => (bool)$value,
            default => $value,
        };
    }

    /**
     * Преобразует значение из PHP в тип БД
     */
    protected static function castToDb(mixed $value, string $type): mixed
    {
        if ($value === null) {
            return null;
        }

        return match ($type) {
            'datetime', 'date' => date('Y-m-d H:i:s', (int)$value),
            'int', 'bigint' => (int)$value,
            'float', 'double' => (float)$value,
            'tinyint' => (int)(bool)$value,
            default => $value,
        };
    }

    /**
     * Возвращает подключение к БД из контейнера
     */
    protected static function getPdo(): PDO
    {
        return static::getContainer()->get(PDO::class);
    }

    /**
     * Экранирует идентификатор для SQL-запроса
     */
    protected static function quoteIdentifier(string $identifier): string
    {
        return '`' . str_replace('`', '``', $identifier) . '`';
    }

    /**
     * Возвращает массив всех свойств объекта
     */
    public function toArray(): array
    {
        $result = [];
        foreach (static::$map as $property => $_) {
            $result[$property] = $this->{$property};
        }
        return $result;
    }

    /**
     * Клонирует объект (сбрасывает ID)
     */
    public function __clone()
    {
        $idProperty = static::primaryKey();
        $this->$idProperty = null;
    }

    /**
     * Получает записи по значению поля с дополнительными параметрами
     */
    public static function getByField(
        string $field,
        mixed $value,
        ?int $limit = null,
        ?string $orderBy = null,
        string $orderDirection = 'ASC'
    ): array {
        if (!array_key_exists($field, static::$map)) {
            throw new RuntimeException("Field {$field} is not defined in mapping");
        }

        return static::getByFields(
            [$field => $value],
            'AND',
            $limit,
            $orderBy,
            $orderDirection
        );
    }

    /**
     * Получает записи по нескольким условиям
     */
    public static function getByFields(
        array $conditions,
        string $operator = 'AND',
        ?int $limit = null,
        ?string $orderBy = null,
        string $orderDirection = 'ASC'
    ): array {
        try {
            $pdo = static::getPdo();
            $whereClauses = [];
            $values = [];

            foreach ($conditions as $field => $value) {
                if (!array_key_exists($field, static::$map)) {
                    throw new RuntimeException("Field {$field} is not defined in mapping");
                }

                $dbField = static::toDbField($field);
                $placeholder = ":{$field}";
                $whereClauses[] = static::quoteIdentifier($dbField) . " = {$placeholder}";
                $values[$placeholder] = $value;
            }

            $operator = strtoupper($operator);
            if (!in_array($operator, ['AND', 'OR'])) {
                throw new InvalidArgumentException("Invalid operator: {$operator}");
            }

            $sql = sprintf(
                "SELECT * FROM %s WHERE %s",
                static::quoteIdentifier(static::table()),
                implode(" {$operator} ", $whereClauses)
            );

            if ($orderBy) {
                if (!array_key_exists($orderBy, static::$map)) {
                    throw new RuntimeException("Field {$orderBy} is not defined in mapping");
                }
                $sql .= " ORDER BY " . static::quoteIdentifier(static::toDbField($orderBy)) . " " . $orderDirection;
            }

            if ($limit) {
                $sql .= " LIMIT " . (int)$limit;
            }

            $stmt = $pdo->prepare($sql);
            $stmt->execute($values);

            $objects = [];
            while ($rec = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($obj = static::loadFromRec($rec)) {
                    $objects[] = $obj;
                }
            }

            return $objects;
        } catch (PDOException $e) {
            throw new RuntimeException("Database error in getByFields: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Получает одну запись по условиям
     */
    public static function getOneByFields(array $conditions, string $operator = 'AND'): ?static
    {
        $items = static::getByFields($conditions, $operator, 1);
        return $items[0] ?? null;
    }
}
