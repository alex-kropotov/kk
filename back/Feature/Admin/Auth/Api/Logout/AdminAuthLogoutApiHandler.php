<?php

namespace App\Feature\Admin\Auth\Api\Logout;

use Tools\CommandBus\AsCommandHandler;
use Tools\CommandBus\CommandHandlerInterface;
use Tools\CommandBus\CommandHandlerResult;
use Tools\CommandBus\CommandHandlerResultInterface;
use Tools\CommandBus\CommandInterface;
use Tools\Services\SessionService;

#[AsCommandHandler(AdminAuthLogoutApiCommand::class)]
readonly class AdminAuthLogoutApiHandler implements CommandHandlerInterface
{
    public function __construct(
        private SessionService $session
    ) {}

    public function handle(CommandInterface $command): CommandHandlerResultInterface
    {
        $this->session->reset();

        return new CommandHandlerResult();

    }
}
