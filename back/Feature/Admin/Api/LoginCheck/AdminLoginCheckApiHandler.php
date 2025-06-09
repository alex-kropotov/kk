<?php

namespace App\Feature\Admin\Api\LoginCheck;

use App\Domain\Service\Security\AdminLoginChecker;
use App\Domain\Service\SessionKeyEnum;
use Tools\CommandBus\AsCommandHandler;
use Tools\CommandBus\CommandHandlerInterface;
use Tools\CommandBus\CommandHandlerResult;
use Tools\CommandBus\CommandHandlerResultInterface;
use Tools\CommandBus\CommandInterface;
use Tools\Services\SessionService;

#[AsCommandHandler(AdminLoginCheckApiCommand::class)]
readonly class AdminLoginCheckApiHandler implements CommandHandlerInterface
{
    public function __construct(
        private SessionService $session
    ) {}

    public function handle(CommandInterface $command): CommandHandlerResultInterface
    {
        $idUser = AdminLoginChecker::checkUser($command->name, $command->pass);
        if ($idUser > 0) {
            $this->session->set(SessionKeyEnum::IdUser, $idUser);
        }
        else {
            $this->session->unset(SessionKeyEnum::IdUser);
        }
        return (new CommandHandlerResult())
            ->addDataArray([
                    'idUser' => $idUser
                ]
            );
    }
}
