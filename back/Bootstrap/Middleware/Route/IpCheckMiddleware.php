<?php

namespace App\Bootstrap\Middleware\Route;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class IpCheckMiddleware implements MiddlewareInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(Request $request, RequestHandler $handler): Response
    {
        // Список доверенных IP-адресов
        $trustedIps = [
            '127.0.0.1', // Пример локального IP
            '203.0.113.42' // Пример публичного IP
        ];

        // Получение IP-адреса клиента
        $clientIp = $request->getServerParams()['REMOTE_ADDR'] ?? '';

        // Если запрос прошел через прокси, получаем реальный IP из заголовка X-Forwarded-For
        if ($request->hasHeader('X-Forwarded-For')) {
            $clientIp = explode(',', $request->getHeaderLine('X-Forwarded-For'))[0];
        }

        // Проверка, находится ли IP в списке доверенных
//        if (!in_array($clientIp, $trustedIps)) {
//            // Возвращаем ошибку 403, если IP не доверенный
//            $response = new \Slim\Psr7\Response(); // Инициализация Response
//            $response = $response->withStatus(403);
//            $response->getBody()->write(json_encode([
//                'error' => 'Access denied: Unauthorized IP address.'
//            ]));
//            return $response->withHeader('Content-Type', 'application/json');
//        }

        // Если IP доверенный, продолжаем обработку запроса
        return $handler->handle($request);
    }
}

