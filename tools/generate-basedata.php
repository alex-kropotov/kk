<?php

declare(strict_types=1);

if ($argc < 2) {
    echo "Usage: php generator.php <filename_without_extension>\n";
    echo "Example: php generator.php places\n";
    exit(1);
}

$fileName = $argv[1];
$rootFolder = 'back/Domain';

// Пути к файлам
$sqlFilePath = $rootFolder . '/Tables/' . $fileName . '.sql';
$entityName = ucfirst(snakeToPascal(toSingular($fileName)));
$phpFilePath = $rootFolder . '/Entities/' . $entityName . 'Data.php';

// Генерируем имя класса (используем toSingular для правильного преобразования)
$className = $entityName . 'Data';

// Проверяем существование SQL файла
if (!file_exists($sqlFilePath)) {
    echo "Error: SQL file not found: $sqlFilePath\n";
    exit(1);
}

// Создаем директорию для Entity если не существует
$entitiesDir = dirname($phpFilePath);
if (!is_dir($entitiesDir)) {
    mkdir($entitiesDir, 0755, true);
}

// Читаем SQL файл
$sqlContent = file_get_contents($sqlFilePath);

// Парсим SQL для извлечения информации о таблице
function parseSqlTable($sqlContent): array
{
    $result = [
        'tableName' => '',
        'columns' => [],
        'primaryKey' => '',
        'defaults' => [],
        'nullable' => [],
        'foreignKeys' => []
    ];

    // Извлекаем имя таблицы
    if (preg_match('/create\s+table\s+(?:\w+\.)?(\w+)\s*\(/i', $sqlContent, $matches)) {
        $result['tableName'] = $matches[1];
    }

    // Извлекаем колонки
    preg_match('/create\s+table[^(]*\((.*?)\);/is', $sqlContent, $tableMatches);
    if (isset($tableMatches[1])) {
        $tableContent = $tableMatches[1];

        // Разбиваем на строки и обрабатываем каждую колонку
        $lines = explode(',', $tableContent);

        foreach ($lines as $line) {
            $line = trim($line);

            // Обрабатываем foreign key constraints
            if (preg_match('/constraint\s+\w+\s+foreign\s+key\s*\(\s*(\w+)\s*\)\s+references\s+(?:\w+\.)?(\w+)\s*\(\s*(\w+)\s*\)/i', $line, $fkMatches)) {
                $result['foreignKeys'][] = [
                    'column' => $fkMatches[1],
                    'referencedTable' => $fkMatches[2],
                    'referencedColumn' => $fkMatches[3]
                ];
                continue;
            }

            // Проверяем primary key
            if (preg_match('/primary\s+key\s*\(\s*(\w+)\s*\)/i', $line, $pkMatches)) {
                $result['primaryKey'] = $pkMatches[1];
                // Первичный ключ всегда NOT NULL
                $result['nullable'][$pkMatches[1]] = false;
                continue;
            }

            // Парсим колонку
            if (preg_match('/^\s*(\w+)\s+(\w+)(?:\([^)]+\))?\s*(.*?)$/i', $line, $colMatches)) {
                $columnName = $colMatches[1];
                $dataType = strtolower($colMatches[2]);
                $attributes = $colMatches[3];

                // Проверяем primary key
                if (str_contains($attributes, 'primary key')) {
                    $result['primaryKey'] = $columnName;
                    // Первичный ключ всегда NOT NULL
                    $result['nullable'][$columnName] = false;
                }

                // Определяем nullable (первичный ключ уже обработан)
                if ($result['primaryKey'] !== $columnName) {
                    $nullable = !str_contains($attributes, 'not null');
                    $result['nullable'][$columnName] = $nullable;
                }

                // Извлекаем default значение
                if (preg_match('/default\s+(\d+|\'[^\']*\'|null|current_timestamp)/i', $attributes, $defaultMatches)) {
                    $defaultValue = $defaultMatches[1];
                    if ($defaultValue === 'null') {
                        $defaultValue = null;
                    } elseif ($defaultValue === 'current_timestamp') {
                        $defaultValue = 'CURRENT_TIMESTAMP';
                    } elseif (!is_numeric($defaultValue)) {
                        $defaultValue = trim($defaultValue, "'");
                    }

                    if ($defaultValue !== null) {
                        $result['defaults'][$columnName] = $defaultValue;
                    }
                }

                // Маппинг типов данных
                $phpType = mapSqlTypeToPhp($dataType);

                $result['columns'][$columnName] = [
                    'type' => $phpType,
                    'nullable' => $result['nullable'][$columnName],
                    'sqlType' => $dataType
                ];
            }
        }
    }

    return $result;
}

function mapSqlTypeToPhp($sqlType): string
{
    $typeMapping = [
        'int' => 'int',
        'integer' => 'int',
        'smallint' => 'int',
        'tinyint' => 'int',
        'bigint' => 'int',
        'double' => 'float',
        'float' => 'float',
        'decimal' => 'float',
        'varchar' => 'string',
        'char' => 'string',
        'text' => 'string',
        'longtext' => 'string',
        'date' => 'datetime',
        'datetime' => 'datetime',
        'timestamp' => 'datetime',
        'time' => 'datetime',
        'boolean' => 'bool',
        'bool' => 'bool'
    ];

    return $typeMapping[$sqlType] ?? 'string';
}

function snakeToCamel($string): string
{
    return lcfirst(str_replace('_', '', ucwords($string, '_')));
}

function snakeToPascal($string): array|string
{
    return str_replace('_', '', ucwords($string, '_'));
}

function toSingular(string $tableName): string
{
    // Специальные случаи
    $irregular = [
        'people' => 'person',
        'children' => 'child',
        'men' => 'man',
        'women' => 'woman',
        'teeth' => 'tooth',
        'feet' => 'foot',
        'mice' => 'mouse',
        'geese' => 'goose',
    ];

    if (isset($irregular[$tableName])) {
        return $irregular[$tableName];
    }

    // Правила для регулярных форм
    $patterns = [
        '/(quiz)zes$/i' => '$1',
        '/(matr)ices$/i' => '$1ix',
        '/(vert|ind)ices$/i' => '$1ex',
        '/^(ox)en/i' => '$1',
        '/(alias|status)es$/i' => '$1',
        '/([octop|vir])i$/i' => '$1us',
        '/(cris|ax|test)es$/i' => '$1is',
        '/(shoe)s$/i' => '$1',
        '/(o)es$/i' => '$1',
        '/(bus)es$/i' => '$1',
        '/([m|l])ice$/i' => '$1ouse',
        '/(x|ch|ss|sh)es$/i' => '$1',
        '/(m)ovies$/i' => '$1ovie',
        '/(s)eries$/i' => '$1eries',
        '/([^aeiouy]|qu)ies$/i' => '$1y',
        '/([lr])ves$/i' => '$1f',
        '/(tive)s$/i' => '$1',
        '/(hive)s$/i' => '$1',
        '/([^f])ves$/i' => '$1fe',
        '/(^analy)ses$/i' => '$1sis',
        '/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i' => '$1$2sis',
        '/([ti])a$/i' => '$1um',
        '/(n)ews$/i' => '$1ews',
        '/(.*)s$/i' => '$1',
    ];

    foreach ($patterns as $pattern => $replacement) {
        if (preg_match($pattern, $tableName)) {
            return preg_replace($pattern, $replacement, $tableName);
        }
    }

    return $tableName;
}

function getRelationPropertyName(string $tableName): string
{
    $singular = toSingular($tableName);
    return snakeToCamel($singular);
}

function getPhpTypeForProperty($type, $nullable): string
{
    $typeMapping = [
        'int' => 'int',
        'float' => 'float',
        'string' => 'string',
        'datetime' => 'int',
        'bool' => 'bool'
    ];

    $phpType = $typeMapping[$type] ?? 'string';
    return $nullable ? "?$phpType" : $phpType;
}

// Получаем дефолтное значение для типа
function getDefaultValueForType($type, $nullable)
{
    if ($nullable) {
        return 'null';
    }

    switch ($type) {
        case 'int':
        case 'float':
            return '0';
        case 'string':
            return "''";
        case 'datetime':
            return 'time()';
        case 'bool':
            return 'false';
        default:
            return 'null';
    }
}

// Парсим SQL
$tableInfo = parseSqlTable($sqlContent);

if (empty($tableInfo['tableName'])) {
    echo "Error: Could not parse table name from SQL file\n";
    exit(1);
}

// Генерируем PHP код
$phpCode = "<?php\n\n";
$phpCode .= "declare(strict_types=1);\n\n";
$phpCode .= "namespace App\\Domain\\Entities;\n\n";
$phpCode .= "use Tools\\Persist\\BaseClassData;\n";

// Добавляем use statements для связанных классов
if (!empty($tableInfo['foreignKeys'])) {
    $phpCode .= "\n";
    foreach ($tableInfo['foreignKeys'] as $fk) {
        $relatedClassName = ucfirst(snakeToPascal(toSingular($fk['referencedTable']))) . 'Data';
        $phpCode .= "use App\\Domain\\Entities\\$relatedClassName;\n";
    }
}
$phpCode .= "\n";
$phpCode .= "class $className extends BaseClassData\n";
$phpCode .= "{\n";

// Генерируем массив map
$phpCode .= "    protected static array \$map = [\n";
foreach ($tableInfo['columns'] as $columnName => $columnInfo) {
    $camelName = snakeToCamel($columnName);
    $phpCode .= "        '$camelName' => '{$columnInfo['type']}',\n";
}
$phpCode .= "    ];\n\n";

// Генерируем массив nullable
$phpCode .= "    protected static array \$nullable = [\n";
foreach ($tableInfo['nullable'] as $columnName => $nullable) {
    $camelName = snakeToCamel($columnName);
    $phpCode .= "        '$camelName' => " . ($nullable ? 'true' : 'false') . ",\n";
}
$phpCode .= "    ];\n\n";

// Генерируем массив defaults
if (!empty($tableInfo['defaults'])) {
    $phpCode .= "    protected static array \$defaults = [\n";
    foreach ($tableInfo['defaults'] as $columnName => $defaultValue) {
        $camelName = snakeToCamel($columnName);
        if (is_numeric($defaultValue)) {
            $phpCode .= "        '$camelName' => $defaultValue,\n";
        } elseif ($defaultValue === 'CURRENT_TIMESTAMP') {
            $phpCode .= "        '$camelName' => time(),\n";
        } elseif ($defaultValue === 'null') {
            $phpCode .= "        '$camelName' => null,\n";
        } else {
            $phpCode .= "        '$camelName' => '$defaultValue',\n";
        }
    }
    $phpCode .= "    ];\n\n";
}

// Генерируем свойства класса для колонок таблицы
foreach ($tableInfo['columns'] as $columnName => $columnInfo) {
    $camelName = snakeToCamel($columnName);
    $phpType = getPhpTypeForProperty($columnInfo['type'], $columnInfo['nullable']);
    $phpCode .= "    public $phpType \$$camelName;\n";
}

// Генерируем свойства для связанных объектов
foreach ($tableInfo['foreignKeys'] as $fk) {
    $relatedClassName = ucfirst(snakeToPascal(toSingular($fk['referencedTable']))) . 'Data';
    $propertyName = getRelationPropertyName($fk['referencedTable']);
    $phpCode .= "    public ?$relatedClassName \$$propertyName = null;\n";
}
$phpCode .= "\n";

// Генерируем конструктор
$phpCode .= "    public function __construct(\n";
$constructorParams = [];
foreach ($tableInfo['columns'] as $columnName => $columnInfo) {
    $camelName = snakeToCamel($columnName);
    $phpType = getPhpTypeForProperty($columnInfo['type'], $columnInfo['nullable']);

    // Определяем значение по умолчанию
    if (isset($tableInfo['defaults'][$columnName])) {
        $defaultValue = $tableInfo['defaults'][$columnName];
        if ($defaultValue === 'CURRENT_TIMESTAMP') {
            $defaultValue = 'time()';
        } elseif (is_numeric($defaultValue)) {
            $defaultValue = $defaultValue;
        } elseif ($defaultValue === 'null') {
            $defaultValue = 'null';
        } else {
            $defaultValue = "'$defaultValue'";
        }
    } else {
        $defaultValue = getDefaultValueForType($columnInfo['type'], $columnInfo['nullable']);
    }

    $constructorParams[] = "        $phpType \$$camelName = $defaultValue";
}
$phpCode .= implode(",\n", $constructorParams) . "\n";
$phpCode .= "    ) {\n";

// Присваивание в конструкторе
foreach ($tableInfo['columns'] as $columnName => $columnInfo) {
    $camelName = snakeToCamel($columnName);
    $phpCode .= "        \$this->$camelName = \$$camelName;\n";
}
$phpCode .= "    }\n\n";

// Генерируем методы table(), primaryKey(), primaryKeyValue()
$phpCode .= "    protected static function table(): string\n";
$phpCode .= "    {\n";
$phpCode .= "        return '{$tableInfo['tableName']}';\n";
$phpCode .= "    }\n\n";

$primaryKeyCamel = snakeToCamel($tableInfo['primaryKey']);
$phpCode .= "    protected static function primaryKey(): string\n";
$phpCode .= "    {\n";
$phpCode .= "        return '$primaryKeyCamel';\n";
$phpCode .= "    }\n\n";

$phpCode .= "    protected function primaryKeyValue(): ?int\n";
$phpCode .= "    {\n";
$phpCode .= "        return \$this->$primaryKeyCamel;\n";
$phpCode .= "    }\n\n";

// Генерируем статический метод createObj
$phpCode .= "    public static function createObj(\n";
$phpCode .= implode(",\n", $constructorParams) . "\n";
$phpCode .= "    ): self {\n";
$phpCode .= "        return new self(\n";

$createObjParams = [];
foreach ($tableInfo['columns'] as $columnName => $columnInfo) {
    $camelName = snakeToCamel($columnName);
    $createObjParams[] = "            \$$camelName";
}
$phpCode .= implode(",\n", $createObjParams) . "\n";
$phpCode .= "        );\n";
$phpCode .= "    }\n";
$phpCode .= "}\n";

// Сохраняем файл
if (file_put_contents($phpFilePath, $phpCode)) {
    echo "Entity class generated successfully: $phpFilePath\n";
    echo "Class name: $className\n";
    echo "Table name: {$tableInfo['tableName']}\n";
    echo "Primary key: {$tableInfo['primaryKey']}\n";

    if (!empty($tableInfo['foreignKeys'])) {
        echo "Foreign keys:\n";
        foreach ($tableInfo['foreignKeys'] as $fk) {
            $relatedClassName = ucfirst(snakeToPascal(toSingular($fk['referencedTable']))) . 'Data';
            echo "  - {$fk['column']} references {$fk['referencedTable']}.{$fk['referencedColumn']} ($relatedClassName)\n";
        }
    }
} else {
    echo "Error: Could not write to file: $phpFilePath\n";
    exit(1);
}
