<?php

namespace Tools\CommandBus;

use RuntimeException;

class CommandBus
{
    /**
     * @var array<class-string<CommandInterface>, CommandHandlerInterface>
     */
    private array $handlers;

    /**
     * @var callable
     */
    private $middlewareFactory;

    /**
     * @param array<class-string<CommandInterface>, CommandHandlerInterface> $handlers
     * @param array<CommandMiddlewareInterface>|callable $middleware Массив middleware или функция, возвращающая массив middleware
     */
    public function __construct(array $handlers = [], array $middleware = [])
    {
        // Сохраняем middleware как массив или как фабричную функцию
        if (is_callable($middleware)) {
            $this->middlewareFactory = $middleware;
        } else {
            $this->middlewareFactory = function() use ($middleware) {
                return $middleware;
            };
        }

        $this->handlers = [];
        foreach ($handlers as $commandClass => $handler) {
            if (!($handler instanceof CommandHandlerInterface)) {
                throw new RuntimeException("Handler for $commandClass must implement CommandHandlerInterface");
            }

            $this->handlers[$commandClass] = $handler;
        }
    }

    public function dispatch(CommandInterface $command): CommandHandlerResult
    {
        $commandClass = get_class($command);

        if (!isset($this->handlers[$commandClass])) {
            throw new RuntimeException("No handler for command $commandClass");
        }

        $handler = $this->handlers[$commandClass];

        // Получаем актуальные middleware в момент выполнения
        $middleware = ($this->middlewareFactory)();

        // Создаем ядро пайплайна
        $core = function(CommandInterface $cmd) use ($handler) {
            return $handler->handle($cmd);
        };

        // Строим пайплайн middleware
        $pipeline = array_reduce(
            array_reverse($middleware),
            function($next, $middleware) use ($handler) {
                return function($cmd) use ($middleware, $handler, $next) {
                    return $middleware->process($cmd, $handler, $next);
                };
            },
            $core
        );

        return $pipeline($command);
    }
}
