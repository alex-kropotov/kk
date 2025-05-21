<?php

declare(strict_types=1);

namespace App\Bootstrap\Middleware\Route;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Tools\Services\SessionService;
use Tools\Utils\NamedLog;

readonly class SessionMiddleware implements Middleware
{
    public function __construct(private SessionService $sessionService)
    {
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        // Стартуем сессию
        session_start();

        // Инициализация тестового значения (если нужно)
        if (!isset($_SESSION['test_value'])) {
            $_SESSION['test_value'] = 'Hello, Session! ' . date('Y-m-d H:i:s');
        }

        // Загружаем текущую сессию в сервис
        $this->sessionService->setSession($_SESSION);
        NamedLog::write('SessionMid', 'BEFORE', $_SESSION);

        // Пропускаем запрос дальше по цепочке middleware
        $response = $handler->handle($request);

        NamedLog::write('SessionMid', '$sessionService', $this->sessionService->getSession());
        // !!! ВАЖНО: Сохраняем изменения из сервиса обратно в сессию !!!
        $this->sessionService->persist();
        NamedLog::write('SessionMid', 'AFTER', $_SESSION);

        return $response;
    }
}
