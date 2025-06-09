<?php

namespace App\Feature\Admin\View\LoginForm;

use Tools\CommandBus\CommandInterface;

class AdminLoginFormViewCommand implements CommandInterface
{
    public function __construct(
        public string $redirectPath
    ) {}
}
