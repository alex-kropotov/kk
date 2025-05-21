<?php

namespace Tools\Template;

use RuntimeException;

class TplLoader
{
    private static array $cache = [];
    private static array $templateMap = [];
    private static bool $mapLoaded = false;

    public static function get(string $templateName): Template8
    {
        if (!self::$mapLoaded) {
            self::loadTemplateMap();
        }

        if (isset(self::$cache[$templateName])) {
            return self::$cache[$templateName];
        }

        if (!isset(self::$templateMap[$templateName])) {
            throw new RuntimeException("Template '$templateName' not found");
        }

        $templatePath = self::normalizePath(self::$templateMap[$templateName]);
        self::$cache[$templateName] = new Template8(dirname($_SERVER['DOCUMENT_ROOT']).'/'.$templatePath);

        return self::$cache[$templateName];
    }

    private static function loadTemplateMap(): void
    {
        $mapFile = self::normalizePath(dirname(__DIR__, 2) . '/config/template-map.php');

        if (!file_exists($mapFile)) {
            throw new RuntimeException("Template map file not found: $mapFile");
        }

        self::$templateMap = require $mapFile;
        self::$mapLoaded = true;
    }

    private static function normalizePath(string $path): string
    {
        // Заменяем слеши и дублирующиеся разделители
        return str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $path);
    }

    public static function clearCache(): void
    {
        self::$cache = [];
    }
}
