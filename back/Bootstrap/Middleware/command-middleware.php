<?php

use App\Bootstrap\Middleware\Command\CommandLoggingMiddleware;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;


return [
    // Фабрика для middleware, которая возвращает функцию
    'command.middleware' => function (ContainerInterface $container) {
        return function () use ($container) {
            // Важно получать запрос ЗДЕСЬ, в момент вызова, чтобы получить самую актуальную версию
            $request = $container->get(ServerRequestInterface::class);

            return [
                new CommandLoggingMiddleware(),
                // другие middleware
            ];
        };
    },
];
