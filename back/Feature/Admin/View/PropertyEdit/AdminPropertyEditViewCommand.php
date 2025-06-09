<?php

namespace App\Feature\Admin\View\PropertyEdit;

use Tools\CommandBus\CommandInterface;

readonly class AdminPropertyEditViewCommand implements CommandInterface
{
    public function __construct(
        public int $id
    ){}
}
