<?php

declare(strict_types=1);

use App\Bootstrap\Middleware\Route\ContainerMiddleware;
use App\Bootstrap\Middleware\Route\CurrentPageMiddleware;
use App\Bootstrap\Middleware\Route\SessionMiddleware;
use App\Render\NotFound\NotFoundRender;
use Dotenv\Dotenv;
use Slim\Exception\HttpNotFoundException;
use Slim\Factory\AppFactory;
use Slim\Middleware\BodyParsingMiddleware;
use Slim\Psr7\Request;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

require dirname($_SERVER['DOCUMENT_ROOT']) . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(dirname($_SERVER['DOCUMENT_ROOT']) . '/', '.env.dev');
$dotenv->load();

$whoops = new Run();
$whoops->pushHandler(new PrettyPageHandler());
//$whoops->pushHandler(new CallbackHandler(function($error) {
//    file_put_contents('error_log.text', date('Y-m-d H:i') . ':' . $error->getMessage
//        .' File: '.$error->getFile()
//        .' Line:'.$error->getLine()
//        .PHP_EOL,
//        FILE_APPEND | LOCK_EX);
//}));
$whoops->register();


require dirname($_SERVER['DOCUMENT_ROOT']).'/back/Bootstrap/bootstrap.php';

try {
    $container = buildContainer();
} catch (Throwable $e) {
    return;
}


AppFactory::setContainer($container);

$app = AppFactory::create();
// 1. Добавляем ErrorMiddleware
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// Устанавливаем кастомный обработчик для HttpNotFoundException
$errorMiddleware->setErrorHandler(
    HttpNotFoundException::class,
    function (Request $request, Throwable $exception, bool $displayErrorDetails) use ($app) {
        $response = $app->getResponseFactory()->createResponse();
        $response->getBody()->write(NotFoundRender::render()); // Путь к вашей HTML странице
        return $response->withStatus(404);
    }
);

// 2. CORS Middleware (если нужно)
$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

// 3. BodyParsingMiddleware
$app->add(new BodyParsingMiddleware());

// 4. SessionMiddleware (стартует сессию)
$app->add(SessionMiddleware::class);

// 5. CurrentPageMiddleware (расбираем URI->path)
$app->add(CurrentPageMiddleware::class);

// 6. ContainerMiddleware - ВАЖНО: добавляем ПОСЛЕДНИМ, чтобы выполнялся ПЕРВЫМ
$app->add(ContainerMiddleware::class);

$routes = require dirname($_SERVER['DOCUMENT_ROOT']) .'/config/routes.php';
//$routes = require '../config/routes.php';
$routes($app);

$app->run();
