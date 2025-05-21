<?php

namespace App\Feature\Admin\Auth\View\LoginForm;

use Tools\CommandBus\CommandInterface;

class AdminAuthLoginFormViewCommand implements CommandInterface
{
    public function __construct(
        public string $redirectPath
    ) {}
}
