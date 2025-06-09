<?php

declare(strict_types=1);

use App\Domain\Service\URI\UriManager;
use App\Render\Services\Bundles\Bundles;
use App\Render\Services\Renderers\ViewRenderer;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Factory\ServerRequestCreatorFactory;
use Tools\Database\Db;
use Tools\Database\EloquentManager;
use Tools\Persist\RepositoryFactory;
use Tools\Services\SessionService;
use Tools\Template\TplLoader;


use function DI\autowire;
use function DI\create;

function get(string $class) {}


return [
    'server.params' => $_SERVER,
    ServerRequestInterface::class => function () {
        return ServerRequestCreatorFactory::create()->createServerRequestFromGlobals();
    },
    'curPage' => create()->constructor(''),
    Db::class => function () {
        $config = [
            'driver' => $_ENV['DB_DRIVER'] ?? 'mysql',
            'host' => $_ENV['DB_HOST'],
            'port' => (int)($_ENV['DB_PORT'] ?? 3306),
            'database' => $_ENV['DB_NAME'],
            'username' => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PWD'],
            'charset' => $_ENV['DB_CHARSET'] ?? 'utf8mb4',
            'collation' => $_ENV['DB_COLLATION'] ?? 'utf8mb4_unicode_ci',
            'prefix' => $_ENV['DB_PREFIX'] ?? '',
        ];

        return new Db($config);
    },

    PDO::class => function (ContainerInterface $container) {
        return $container->get(Db::class)->getPdo();
    },

    RepositoryFactory::class => autowire(),

//    TplLoader::class => autowire()->constructor(),
    UriManager::class => autowire(),
    Bundles::class => autowire(),
    ViewRenderer::class => autowire(),
    SessionService::class => autowire(),
//    EloquentManager::class => function () {
//        $config = [
//
//            'driver'    => $_ENV['DB_DRIVER'] ?? 'mysql',
//            'host'      => $_ENV['DB_HOST'],
//            'port'      => (int)($_ENV['DB_PORT'] ?? 3312), // <--- добавляем порт
//            'database'  => $_ENV['DB_NAME'],
//            'username'  => $_ENV['DB_USER'],
//            'password'  => $_ENV['DB_PWD'],
//            'charset'   => $_ENV['DB_CHARSET'] ?? 'utf8mb4',
//            'collation' => $_ENV['DB_COLLATION'] ?? 'utf8mb4_unicode_ci',
//            'prefix'    => '',
//
//        ];
//        $manager = new EloquentManager();
//        $manager->initialize($config);
//        return $manager;
//    },
];
