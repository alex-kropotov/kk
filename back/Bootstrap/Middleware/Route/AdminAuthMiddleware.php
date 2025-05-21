<?php

declare(strict_types=1);

namespace App\Bootstrap\Middleware\Route;

use App\Domain\Service\SessionKeyEnum;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Factory\ResponseFactory;
use Tools\Services\SessionService;
use Tools\Utils\NamedLog;

readonly class AdminAuthMiddleware implements Middleware
{

    public function __construct(
        private SessionService $session
    ) {}

    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        $idUser = $this->session->get(SessionKeyEnum::IdUser, -1);
        $currentPath = $request->getUri()->getPath();
        $queryParams = $request->getQueryParams();
        $hasQueryParams = !empty($queryParams);
        $isPostRequest = $request->getMethod() === 'POST';
        $redirectUrl = $this->session->get(SessionKeyEnum::RedirectFromLogin);
//        $this->session->ensureSessionActive();

        // Удаляем финальный слеш, если он присутствует (кроме корневого пути)
        if (strlen($currentPath) > 1 && str_ends_with($currentPath, '/')) {
            $currentPath = rtrim($currentPath, '/');
        }

        $isLoginPage = ($currentPath === '/admin/adminLogin');

        // Нормализуем путь /admin/adminLogin[/*] → /admin
//        if (preg_match('~^/admin/adminLogin(?:/|$)~', $currentPath)) {
//            $currentPath = '/admin';
//        }

//        echo 'Начало, $redirectUrl из сессии: '.$redirectUrl.', $currentPath: ' . $currentPath. '; $idUser: ' . $idUser.'<br>';


        NamedLog::write('AuthMid',
            '$redirectUrl из сессии: '.$redirectUrl.', $currentPath: ' . $currentPath. '; $idUser: ' . $idUser);
        if ($idUser == -1) {

            if (!$isLoginPage) {
                NamedLog::write('AuthMid', $hasQueryParams, $isPostRequest);
                if (!$hasQueryParams && !$isPostRequest) {
                    NamedLog::write('AuthMid', 'SET');
                    $this->session->set(SessionKeyEnum::RedirectFromLogin, $currentPath);
                }
                else {
                    NamedLog::write('AuthMid', 'UNSETSET');
                    $this->session->unset(SessionKeyEnum::RedirectFromLogin);
                }
                $response = (new ResponseFactory())->createResponse();
                return $response
                    ->withHeader('Location', '/admin/adminLogin')
                    ->withStatus(302);
            }
        }
        else {

            if ($isLoginPage) {
                $redirectUrl = '/admin';
            }
            NamedLog::write('AuthMid', 'USER IS LOGIN', $redirectUrl);
            $this->session->unset(SessionKeyEnum::RedirectFromLogin);
            if ($redirectUrl > '') {
                $response = (new ResponseFactory())->createResponse();
                return $response
                    ->withHeader('Location', $redirectUrl)
                    ->withStatus(301); // Постоянный редирект
            }


        }
        return $handler->handle($request);
    }
}
