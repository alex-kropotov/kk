<?php

declare(strict_types=1);

use App\Domain\Service\URI\UriManager;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Factory\ServerRequestCreatorFactory;
use Tools\Bundles\Bundles;
use Tools\Database\Db;
use Tools\Services\SessionService;
use Tools\Template\TplLoader;

use Tools\Template\ViewRenderer;

use function DI\autowire;
use function DI\create;

function get(string $class) {}


return [
    'server.params' => $_SERVER,
    ServerRequestInterface::class => function () {
        return ServerRequestCreatorFactory::create()->createServerRequestFromGlobals();
    },
    'curPage' => create()->constructor(''),
    Db::class => autowire()
        ->constructor(
            host: getenv('DB_HOST'),
            dbName: getenv('DB_NAME'),
            dbUser: getenv('DB_USER'),
            dbPwd: getenv('DB_PWD'),
            dbCharSet: getenv('DB_CHARSET')
        ),
//    TplLoader::class => autowire()->constructor(),
    UriManager::class => autowire(),
    Bundles::class => autowire(),
    ViewRenderer::class => autowire(),
    SessionService::class => autowire(),
];
