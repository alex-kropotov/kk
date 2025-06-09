<?php

namespace App\Feature\Common\Api\HealthCheck;

use App\Render\Services\Renderers\ViewRenderer;
use Tools\CommandBus\AsCommandHandler;
use Tools\CommandBus\CommandHandlerInterface;
use Tools\CommandBus\CommandHandlerResultInterface;
use Tools\CommandBus\CommandInterface;

#[AsCommandHandler(CommonHealthCheckApiCommand::class)]
readonly class CommonHealthCheckApiHandler implements CommandHandlerInterface
{
    public function __construct(
        private ViewRenderer $renderer
    ) {}

    public function handle(CommandInterface $command): CommandHandlerResultInterface
    {
        // TODO: implement handler logic
    }
}
