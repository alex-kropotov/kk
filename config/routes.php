<?php

use API\users\ApiUserHandler;
use App\Bootstrap\Middleware\Route\AdminAuthMiddleware;
use App\Feature\Admin\Auth\Api\LoginCheck\AdminAuthLoginCheckApiController;
use App\Feature\Admin\Auth\Api\Logout\AdminAuthLogoutApiController;
use App\Feature\Admin\Auth\View\LoginForm\AdminAuthLoginFormViewController;
use App\Feature\Admin\Layout\View\HomePage\AdminLayoutHomePageViewController;
use Slim\App;
use Tools\Services\SessionService;

return function (App $app) {
    $app->get('/test-session', function ($request, $response) {
        $sessionService = $this->get(SessionService::class);
        $value = $sessionService->get('test_value', 'No session value');

        $response->getBody()->write("Session value: " . $value);
        return $response;
    });

    $app->group('/admin', function ($group) {
        $group->get('[/]', AdminLayoutHomePageViewController::class);
        $group->get('/adminLogin[/]', AdminAuthLoginFormViewController::class);
        $group->get('/adminLogout[/]', AdminAuthLogoutApiController::class);
    })->add(AdminAuthMiddleware::class);

    $app->group('/api', function ($group) {
        $group->group('/admin', function ($group) {
            $group->post('/login', AdminAuthLoginCheckApiController::class);
        });
        $group->group('/users', function ($group) {
            $group->post('/login', ApiUserHandler::class.':login');
            $group->post('/logout', ApiUserHandler::class.':logout');
        });
    });

};
