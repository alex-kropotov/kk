<?php

namespace App\Bootstrap\Middleware\Command;

use Tools\CommandBus\CommandInterface;
use Tools\CommandBus\CommandHandlerInterface;
use Tools\CommandBus\CommandMiddlewareInterface;
use Tools\Utils\NamedLog;

class CommandLoggingMiddleware implements CommandMiddlewareInterface
{
    public function process(
        CommandInterface $command,
        CommandHandlerInterface $handler,
        callable $next
    ): mixed {
        NamedLog::write('CommandLogging', 'Dispatching: ' . get_class($command) . ' → ' . get_class($handler));
        $result = $next($command);
        NamedLog::write('CommandLogging','Handled: ' . get_class($command));

        return $result;
    }
}

