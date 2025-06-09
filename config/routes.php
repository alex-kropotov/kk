<?php

use App\Bootstrap\Middleware\Route\AdminAuthMiddleware;
use App\Feature\Admin\Api\CitySearch\AdminCitySearchApiController;
use App\Feature\Admin\Api\LoginCheck\AdminLoginCheckApiController;
use App\Feature\Admin\Api\Logout\AdminLogoutApiController;
use App\Feature\Admin\View\LoginForm\AdminLoginFormViewController;
use App\Feature\Admin\View\Home\AdminHomeViewController;
use App\Feature\Admin\View\Properties\AdminPropertiesViewController;
use App\Feature\Admin\View\PropertyEdit\AdminPropertyEditViewController;
use App\Feature\User\View\Home\UserHomeViewController;
use Slim\App;
use Tools\Services\SessionService;
use Tools\Database\EloquentManager;
use Illuminate\Database\Capsule\Manager as Capsule;

return function (App $app) {
    $app->get('/debug/db', function ( $request, $response)  use ($app) {
        try {
            $container = $app->getContainer();
            $container->get(EloquentManager::class);

            // Теперь Capsule уже настроен
            $user = Capsule::table('users')->first();

            if ($user === null) {
                $data = ['status' => 'connected', 'message' => 'No users found.'];
            } else {
                $data = ['status' => 'connected', 'user' => $user];
            }
        } catch (\Throwable $e) {
            // Ловим любые ошибки (например, соединение не установлено)
            $data = [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }

        $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    });

    $app->get('/test-session', function ($request, $response) {
        $sessionService = $this->get(SessionService::class);
        $value = $sessionService->get('test_value', 'No session value');

        $response->getBody()->write("Session value: " . $value);
        return $response;
    });

    $app->group('/admin', function ($group) {
        $group->get('[/]', AdminHomeViewController::class);
        $group->get('/adminLogin[/]', AdminLoginFormViewController::class);
        $group->get('/adminLogout[/]', AdminLogoutApiController::class);
        $group->get('/properties[/]', AdminPropertiesViewController::class);
        $group->get('/property/{id}', AdminPropertyEditViewController::class);
    })->add(AdminAuthMiddleware::class);

    $app->group('/api', function ($group) {
        $group->group('/admin', function ($group) {
            $group->post('/login', AdminLoginCheckApiController::class);
            $group->post('/citySearch', AdminCitySearchApiController::class);
        });
    });

    $app->get('/', UserHomeViewController::class);
};
