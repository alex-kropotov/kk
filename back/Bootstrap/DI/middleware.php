<?php

use App\Bootstrap\Middleware\Route\AdminAuthMiddleware;
use App\Bootstrap\Middleware\Route\ContainerMiddleware;
use App\Bootstrap\Middleware\Route\CurrentPageMiddleware;
use App\Bootstrap\Middleware\Route\IpCheckMiddleware;
use App\Bootstrap\Middleware\Route\SessionMiddleware;

use function DI\autowire;

return [
    IpCheckMiddleware::class => autowire(),
    SessionMiddleware::class => autowire(),
    AdminAuthMiddleware::class => autowire(),
    CurrentPageMiddleware::class => autowire(),
    ContainerMiddleware::class => autowire(),
];
