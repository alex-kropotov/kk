<?php

namespace Tools\CommandBus;

interface CommandHandlerInterface
{
    public function handle(CommandInterface $command): CommandHandlerResultInterface;
}
