<?php

namespace Tools\CommandBus;

interface CommandMiddlewareInterface
{
    public function process(
        CommandInterface $command,
        CommandHandlerInterface $handler,
        callable $next
    ): mixed;
}
