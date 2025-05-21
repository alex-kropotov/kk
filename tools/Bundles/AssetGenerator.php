<?php

namespace Tools\Bundles;

/**
 * Класс для генерации HTML-тегов ресурсов (JS, CSS)
 */
class AssetGenerator
{
    /**
     * Генерирует HTML-теги скриптов только для существующих файлов
     *
     * @param array $scripts Массив скриптов в формате [name => path]
     * @param bool $useCrossOrigin Использовать ли атрибут crossorigin для первого скрипта
     * @return string HTML-код тегов script
     */
    public static function generateScriptTags(array $scripts, bool $useCrossOrigin = false): string
    {
        $tags = '';
        $isFirst = true;

        foreach ($scripts as $name => $path) {
            if (empty($path)) {
                continue;
            }

            $crossOrigin = ($isFirst && $useCrossOrigin) ? ' crossorigin' : '';
            $tags .= '<script type="module"' . $crossOrigin . ' src="/' . $path . '"></script>' . PHP_EOL;
            $isFirst = false;
        }

        return $tags;
    }

    /**
     * Генерирует HTML-теги стилей только для существующих файлов
     *
     * @param array $styles Массив стилей в формате [name => path]
     * @return string HTML-код тегов link
     */
    public static function generateStyleTags(array $styles): string
    {
        $tags = '';

        foreach ($styles as $name => $path) {
            if (empty($path)) {
                continue;
            }

            $tags .= '<link rel="stylesheet" href="/' . $path . '">' . PHP_EOL;
        }

        return $tags;
    }

    /**
     * Генерирует все необходимые теги ресурсов
     *
     * @param array $assets Массив путей к ресурсам
     * @param bool $useCrossOrigin Использовать ли атрибут crossorigin для первого скрипта
     * @return array Массив с HTML-кодом для скриптов и стилей
     */
    public static function generateAssetTags(array $assets, bool $useCrossOrigin = false): array
    {
        // Фильтруем скрипты и стили
        $scripts = array_filter($assets, function($key) {
            return str_contains($key, '_JS');
        }, ARRAY_FILTER_USE_KEY);

        $styles = array_filter($assets, function($key) {
            return str_contains($key, '_CSS');
        }, ARRAY_FILTER_USE_KEY);

        return [
            'SCRIPT_TAGS' => self::generateScriptTags($scripts, $useCrossOrigin),
            'STYLE_TAGS' => self::generateStyleTags($styles)
        ];
    }
}
