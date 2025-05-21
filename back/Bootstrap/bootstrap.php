<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

require dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

/**
 * @throws Throwable
 */
function buildContainer(): ContainerInterface
{
    $builder = new ContainerBuilder();

    // Настройка окружения (если нужно)
    // $builder->enableCompilation(__DIR__ . '/../../var/cache');

    // Добавляем определения DI
    $builder->addDefinitions(__DIR__.'/DI/dependencies.php');
    $builder->addDefinitions(__DIR__.'/DI/middleware.php');
    $builder->addDefinitions(__DIR__.'/Middleware/command-middleware.php');

    $builder->addDefinitions(__DIR__.'/DI/commandbus.php');

    // и другие по вкусу

    try {
        return $builder->build();
    } catch (Throwable $e) {
        // логировать, отправить на мониторинг и т.п.
        error_log($e->getMessage());
        throw $e;
    }
}
