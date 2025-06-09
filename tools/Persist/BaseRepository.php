<?php

declare(strict_types=1);

namespace Tools\Persist;

use PDO;
use PDOException;
use RuntimeException;
use InvalidArgumentException;

abstract class BaseRepository
{
    protected PDO $pdo;
    protected string $table;
    protected string $dataClass;

    protected array $conditions = [];
    protected array $bindings = [];
    protected string $logic = 'AND';
    protected int $paramIndex = 0;
    protected ?string $orderBy = null;
    protected ?int $limit = null;
    protected ?int $offset = null;
    protected bool $debug = false;
    protected array $selectFields = ['*'];

    protected array $with = [];
    protected array $withEager = [];

    public function __construct(PDO $pdo, string $table, string $dataClass)
    {
        if (!is_subclass_of($dataClass, BaseClassData::class)) {
            throw new InvalidArgumentException("Data class must extend BaseClassData");
        }

        $this->pdo = $pdo;
        $this->table = $table;
        $this->dataClass = $dataClass;
    }

    protected function toDbField(string $property): string
    {
        if (is_subclass_of($this->dataClass, BaseClassData::class)) {
            return $this->dataClass::toDbField($property);
        }

        // Простое преобразование, если класс не наследует BaseClassData
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $property));
    }

    public function enableDebug(): static
    {
        $this->debug = true;
        return $this;
    }

    public function select(array $fields): static
    {
        $this->selectFields = array_map(function($field) {
            return $field === '*' ? '*' : $this->toDbField($field);
        }, $fields);
        return $this;
    }

    public function where(
        string|callable $field,
        mixed $operator = null,
        mixed $value = null,
        string $logic = 'AND'
    ): static {
        if (is_callable($field)) {
            $groupRepo = clone $this;
            $groupRepo->clearWhere();
            $field($groupRepo);
            $group = $groupRepo->conditions;
            if (!empty($group)) {
                $this->conditions[] = ['group' => $group, 'logic' => $logic];
                $this->bindings = array_merge($this->bindings, $groupRepo->bindings);
                $this->paramIndex = max($this->paramIndex, $groupRepo->paramIndex);
            }
        } else {
            // Обработка случая, когда передано только поле и значение
            if (func_num_args() === 2) {
                $value = $operator;
                $operator = '=';
            }
            $this->addCondition($field, $operator, $value, $logic);
        }

        return $this;
    }

    public function orWhere(string|callable $field, mixed $operator = null, mixed $value = null): static
    {
        return $this->where($field, $operator, $value, 'OR');
    }

    public function whereBetween(string $field, array $values, string $logic = 'AND'): static
    {
        if (count($values) !== 2) {
            throw new InvalidArgumentException("whereBetween требует массив из двух значений");
        }

        $key1 = ':p' . ++$this->paramIndex;
        $key2 = ':p' . ++$this->paramIndex;

        $this->bindings[$key1] = $values[0];
        $this->bindings[$key2] = $values[1];

        $this->conditions[] = [
            'field' => $field,
            'operator' => 'BETWEEN',
            'placeholder' => "$key1 AND $key2",
            'logic' => $logic,
        ];

        return $this;
    }

    public function whereIn(string $field, array $values, string $logic = 'AND'): static
    {
        if (empty($values)) {
            throw new InvalidArgumentException("whereIn требует непустой массив значений");
        }

        return $this->where($field, 'IN', $values, $logic);
    }

    public function whereNotIn(string $field, array $values, string $logic = 'AND'): static
    {
        if (empty($values)) {
            throw new InvalidArgumentException("whereNotIn требует непустой массив значений");
        }

        return $this->where($field, 'NOT IN', $values, $logic);
    }

    public function whereNull(string $field, string $logic = 'AND'): static
    {
        return $this->where($field, 'IS NULL', null, $logic);
    }

    public function whereNotNull(string $field, string $logic = 'AND'): static
    {
        return $this->where($field, 'IS NOT NULL', null, $logic);
    }

    public function limit(int $limit): static
    {
        if ($limit <= 0) {
            throw new InvalidArgumentException("Limit должен быть положительным числом");
        }
        $this->limit = $limit;
        return $this;
    }

    public function offset(int $offset): static
    {
        if ($offset < 0) {
            throw new InvalidArgumentException("Offset не может быть отрицательным");
        }
        $this->offset = $offset;
        return $this;
    }

    public function orderBy(string $field, string $direction = 'ASC'): static
    {
        $direction = strtoupper($direction);
        if (!in_array($direction, ['ASC', 'DESC'])) {
            throw new InvalidArgumentException("Направление сортировки должно быть ASC или DESC");
        }

        $this->orderBy = $this->escapeField($this->toDbField($field)) . ' ' . $direction;
        return $this;
    }

    public function with(string|array $relations, bool $eager = false): static
    {
        if ($eager) {
            $this->withEager = array_merge($this->withEager, (array)$relations);
        } else {
            $this->with = array_merge($this->with, (array)$relations);
        }
        return $this;
    }

    public function get(): array
    {
        $results = $this->executeEagerLoading() ? $this->getWithEagerLoading() : $this->executeQuery(
            $this->buildSelectQuery()
        );

        // Ленивая загрузка оставшихся отношений
        if (!empty($this->with)) {
            foreach ($results as $result) {
                foreach ($this->with as $relation) {
                    $result->loadRelation($relation);
                }
            }
        }

        $this->with = [];
        $this->withEager = [];
        return $results;
    }

    public function first(): ?object
    {
        $originalLimit = $this->limit;
        $this->limit = 1;

        $results = $this->get();
        $this->limit = $originalLimit;

        return $results[0] ?? null;
    }

    public function count(): int
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->escapeField($this->table)}";
        $where = $this->buildWhere();
        if ($where) {
            $sql .= ' WHERE ' . $where;
        }

        if ($this->debug) {
            echo $this->interpolateQuery($sql, $this->bindings) . PHP_EOL;
        }

        $stmt = $this->pdo->prepare($sql);
        foreach ($this->bindings as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();

        $this->clearWhere();
        return (int)$stmt->fetchColumn();
    }

    public function exists(): bool
    {
        return $this->count() > 0;
    }

    protected function addCondition(string $field, mixed $operator, mixed $value, string $logic): void
    {
        $operator = strtoupper((string)$operator);
        $dbField = $this->toDbField($field);

        if (in_array($operator, ['IS NULL', 'IS NOT NULL'])) {
            $placeholder = '';
        } elseif (in_array($operator, ['IN', 'NOT IN']) && is_array($value)) {
            if (empty($value)) {
                throw new InvalidArgumentException("IN/NOT IN операторы требуют непустой массив значений");
            }
            $placeholder = $this->createInPlaceholder($value);
        } else {
            $key = ':p' . ++$this->paramIndex;
            $this->bindings[$key] = $value;
            $placeholder = $key;
        }

        $this->conditions[] = [
            'field' => $dbField,
            'operator' => $operator,
            'placeholder' => $placeholder,
            'logic' => $logic,
        ];
    }

    protected function createInPlaceholder(array $values): string
    {
        $placeholders = [];
        foreach ($values as $v) {
            $key = ':p' . ++$this->paramIndex;
            $placeholders[] = $key;
            $this->bindings[$key] = $v;
        }
        return '(' . implode(', ', $placeholders) . ')';
    }

    protected function buildSelectQuery(): string
    {
        $fields = implode(', ', array_map([$this, 'escapeField'], $this->selectFields));
        $sql = "SELECT {$fields} FROM {$this->escapeField($this->table)}";

        $where = $this->buildWhere();
        if ($where) {
            $sql .= ' WHERE ' . $where;
        }

        if ($this->orderBy) {
            $sql .= ' ORDER BY ' . $this->orderBy;
        }

        if ($this->limit !== null) {
            $sql .= ' LIMIT ' . $this->limit;
        }

        if ($this->offset !== null) {
            $sql .= ' OFFSET ' . $this->offset;
        }

        return $sql;
    }

    protected function executeQuery(string $sql): array
    {
        if ($this->debug) {
            echo $this->interpolateQuery($sql, $this->bindings) . PHP_EOL;
        }

        try {
            $stmt = $this->pdo->prepare($sql);
            foreach ($this->bindings as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $class = $this->dataClass;
            $this->clearWhere();

            return array_map(fn($row) => $class::fromArray($row), $rows);
        } catch (PDOException $e) {
            $this->clearWhere();
            throw new RuntimeException("Ошибка выполнения запроса: " . $e->getMessage(), 0, $e);
        }
    }

    protected function buildWhere(): string
    {
        if (empty($this->conditions)) {
            return '';
        }

        $parts = [];
        $isFirst = true;

        foreach ($this->conditions as $cond) {
            if (isset($cond['group'])) {
                $groupParts = [];
                $isFirstInGroup = true;

                foreach ($cond['group'] as $g) {
                    $formatted = $this->formatCondition($g, $isFirstInGroup);
                    if ($formatted) {
                        $groupParts[] = $formatted;
                        $isFirstInGroup = false;
                    }
                }

                if (!empty($groupParts)) {
                    $groupStr = '(' . implode(' ', $groupParts) . ')';
                    if (!$isFirst) {
                        $groupStr = strtoupper($cond['logic']) . ' ' . $groupStr;
                    }
                    $parts[] = $groupStr;
                    $isFirst = false;
                }
            } else {
                $formatted = $this->formatCondition($cond, $isFirst);
                if ($formatted) {
                    $parts[] = $formatted;
                    $isFirst = false;
                }
            }
        }

        return implode(' ', $parts);
    }

    protected function formatCondition(array $cond, bool $isFirst = false): string
    {
        if (!isset($cond['field']) || !isset($cond['operator'])) {
            return '';
        }

        $logic = !$isFirst ? strtoupper($cond['logic'] ?? 'AND') . ' ' : '';
        $field = $this->escapeField($cond['field']);
        $operator = $cond['operator'];
        $placeholder = $cond['placeholder'] ?? '';

        if (in_array($operator, ['IS NULL', 'IS NOT NULL'])) {
            return "{$logic}{$field} {$operator}";
        }

        if ($operator === 'BETWEEN') {
            return "{$logic}{$field} BETWEEN {$placeholder}";
        }

        // Добавлено условие для IN/NOT IN
        if (in_array($operator, ['IN', 'NOT IN'])) {
            return "{$logic}{$field} {$operator} {$placeholder}";
        }

        return "{$logic}{$field} {$operator} {$placeholder}";
    }

    protected function escapeField(string $field): string
    {
        // Оставляем * без изменений
        if ($field === '*') {
            return $field;
        }

        // Если поле содержит точку (например, table.field), экранируем части отдельно
        if (str_contains($field, '.')) {
            $parts = explode('.', $field);
            $fieldPart = $parts[1] === '*' ? '*' : "`{$this->toDbField($parts[1])}`";
            return "`{$parts[0]}`.{$fieldPart}";
        }

        return "`{$this->toDbField($field)}`";
    }

    protected function interpolateQuery(string $query, array $params): string
    {
        $keys = array_map(fn($k) => '/' . preg_quote($k, '/') . '\b/', array_keys($params));
        $values = array_map(fn($v) => is_numeric($v) ? $v : "'{$v}'", array_values($params));
        return preg_replace($keys, $values, $query);
    }

    protected function clearWhere(): void
    {
        $this->conditions = [];
        $this->bindings = [];
        $this->paramIndex = 0;
        $this->orderBy = null;
        $this->limit = null;
        $this->offset = null;
        $this->selectFields = ['*'];
    }

    private function executeEagerLoading(): bool
    {
        return !empty($this->withEager);
    }

    private function getWithEagerLoading(): array
    {
        $mainResults = $this->executeQuery($this->buildSelectQuery());
        if (empty($mainResults)) {
            return [];
        }

        foreach ($this->withEager as $relation) {
            $this->loadRelationEagerly($mainResults, $relation);
        }
        return $mainResults;
    }

    protected function loadRelationEagerly(array &$mainResults, string $relationName): void
    {
        if (!method_exists($this->dataClass, 'relations')) {
            throw new RuntimeException("Data class does not support relations");
        }

        $relationConfig = $this->dataClass::relations()[$relationName] ?? null;
        if (!$relationConfig) {
            throw new RuntimeException("Relation {$relationName} not defined");
        }

        switch ($relationConfig['type']) {
            case 'one':
                $this->loadOneToOneEagerly($mainResults, $relationConfig);
                break;
            case 'many':
                $this->loadOneToManyEagerly($mainResults, $relationConfig);
                break;
            case 'many-to-many':
                $this->loadManyToManyEagerly($mainResults, $relationConfig);
                break;
        }
    }

    private function loadOneToOneEagerly(array &$mainResults, array $relationConfig): void
    {
        $foreignKey = $this->toDbField($relationConfig['foreignKey']);

        $ids = array_unique(array_column($mainResults, $relationConfig['localKey']));
        $relatedRepo = new $relationConfig['relatedRepo']($this->pdo);

        $relatedItems = $relatedRepo
            ->whereIn($foreignKey, $ids)
            ->get();
        $map = [];
        foreach ($relatedItems as $item) {
            $map[$item->{$relationConfig['foreignKey']}] = $item;
        }
        foreach ($mainResults as &$mainItem) {
            $mainItem->{$relationConfig['name']} = $map[$mainItem->{$relationConfig['localKey']}] ?? null;
            $mainItem->loadedRelations[$relationConfig['name']] = true;
        }
    }

    private function loadOneToManyEagerly(array &$mainResults, array $relationConfig): void
    {
        $foreignKey = $this->toDbField($relationConfig['foreignKey']);

        $ids = array_unique(array_column($mainResults, $relationConfig['localKey']));
        $relatedRepo = new $relationConfig['relatedRepo']($this->pdo);

        $relatedItems = $relatedRepo
            ->whereIn($foreignKey, $ids)
            ->get();

        $map = [];
        foreach ($relatedItems as $item) {
            $map[$item->{$foreignKey}][] = $item;
        }

        foreach ($mainResults as &$mainItem) {
            $mainItem->{$relationConfig['name']} = $map[$mainItem->{$relationConfig['localKey']}] ?? [];
            $mainItem->loadedRelations[$relationConfig['name']] = true;
        }
    }


    private function loadManyToManyEagerly(array &$mainResults, array $relationConfig): void
    {
        // Преобразуем имена полей в формат базы данных
        $localKey = $this->toDbField($relationConfig['localKey']);
        $pivotLocalKey = $this->toDbField($relationConfig['pivotLocalKey']);
        $pivotForeignKey = $this->toDbField($relationConfig['pivotForeignKey']);
        $relatedKey = $this->toDbField($relationConfig['relatedKey']);

        // Получаем ID основных объектов
        $ids = array_column($mainResults, $localKey);

        // Получаем все связи из промежуточной таблицы
        $pivotRepo = new $relationConfig['pivotRepo']($this->pdo);
        $pivotItems = $pivotRepo
            ->whereIn($pivotLocalKey, $ids)
            ->get();

        if (empty($pivotItems)) {
            foreach ($mainResults as &$mainItem) {
                $mainItem->{$relationConfig['name']} = [];
                $mainItem->loadedRelations[$relationConfig['name']] = true;
            }
            return;
        }

        // Получаем ID связанных объектов
        $relatedIds = array_column($pivotItems, $pivotForeignKey);

        // Получаем связанные записи
        $relatedRepo = new $relationConfig['relatedRepo']($this->pdo);
        $relatedItems = $relatedRepo
            ->whereIn($relatedKey, $relatedIds)
            ->get();

        // Создаем маппинг relatedId => relatedItem
        $relatedMap = [];
        foreach ($relatedItems as $item) {
            $relatedMap[$item->{$relatedKey}] = $item;
        }

        // Группируем связи по mainId
        $pivotMap = [];
        foreach ($pivotItems as $pivot) {
            $pivotMap[$pivot->{$pivotLocalKey}][] = $pivot->{$pivotForeignKey};
        }

        // Связываем результаты
        foreach ($mainResults as &$mainItem) {
            $mainItem->{$relationConfig['name']} = array_map(
                fn($id) => $relatedMap[$id] ?? null,
                $pivotMap[$mainItem->{$localKey}] ?? []
            );
            $mainItem->loadedRelations[$relationConfig['name']] = true;
        }
    }
}


