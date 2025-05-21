<?php

namespace Tools\CommandBus;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class AsCommandHandler
{
    public function __construct(
        public string $commandClass
    ) {}
}
