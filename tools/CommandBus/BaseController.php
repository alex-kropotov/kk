<?php

namespace Tools\CommandBus;

use Tools\Services\SessionService;

abstract class BaseController
{
    protected CommandBus $commandBus;
    protected CommandDtoFactory $dtoFactory;
    protected SessionService $session;

    public function __construct(
        CommandBus $commandBus,
        CommandDtoFactory $dtoFactory,
        SessionService $session
    ) {
        $this->commandBus = $commandBus;
        $this->dtoFactory = $dtoFactory;
        $this->session = $session;
    }
}
