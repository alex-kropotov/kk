<?php

namespace App\Feature\Admin\Api\CitySearch;

use Tools\CommandBus\CommandInterface;

class AdminCitySearchApiCommand implements CommandInterface
{
    public function __construct(
        public string $searchTemplate,
    ) {}
}
