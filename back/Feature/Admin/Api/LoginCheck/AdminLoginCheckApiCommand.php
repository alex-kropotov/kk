<?php

namespace App\Feature\Admin\Api\LoginCheck;

use Tools\CommandBus\CommandInterface;

class AdminLoginCheckApiCommand implements CommandInterface
{
    public function __construct(
        public string $name,
        public string $pass
    ) {}
}
