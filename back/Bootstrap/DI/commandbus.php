<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Tools\CommandBus\CommandBus;

return [
    CommandBus::class => function (ContainerInterface $container) {
        // Получаем карту "команда => хендлер"
        $handlerMap = require dirname($_SERVER['DOCUMENT_ROOT']) . '/config/command-map.php';

        // Преобразуем карту имен классов в карту объектов
        // Создаем экземпляр хендлера через контейнер
        $handlers = array_map(function ($handlerClass) use ($container) {
            return $container->get($handlerClass);
        }, $handlerMap);

        // Получаем middleware через фабричную функцию
        $middlewareFactory = $container->get('command.middleware');
        $middleware = $middlewareFactory();

        return new CommandBus($handlers, $middleware);
    },


];
