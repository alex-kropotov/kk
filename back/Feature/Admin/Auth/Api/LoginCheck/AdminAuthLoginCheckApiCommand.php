<?php

namespace App\Feature\Admin\Auth\Api\LoginCheck;

use Tools\CommandBus\CommandInterface;

class AdminAuthLoginCheckApiCommand implements CommandInterface
{
    public function __construct(
        public string $name,
        public string $pass
    ) {}
}
